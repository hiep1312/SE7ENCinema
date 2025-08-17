<?php

namespace Database\Seeders;

use App\Models\Movie;
use App\Models\Room;
use App\Models\Showtime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ShowtimeSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy tất cả phim đã phát hành
        $movies = Movie::whereNotNull('release_date')
            ->where('release_date', '<=', now())
            ->get();

        $rooms  = Room::where('status', 'active')->get();

        if ($movies->isEmpty() || $rooms->isEmpty()) {
            return;
        }

        // Sinh suất chiếu từ 1 tháng trước → 1 tháng sau
        $startWindow = now()->copy()->subMonth()->startOfDay();
        $endWindow   = now()->copy()->addMonth()->endOfDay();

        for ($date = $startWindow->copy(); $date->lte($endWindow); $date->addDay()) {

            foreach ($movies as $movie) {
                $releaseDate = Carbon::parse($movie->release_date);

                if ($releaseDate->gt($date)) {
                    continue; // bỏ qua ngày chiếu trước khi phim ra mắt
                }

                $daysSinceRelease = $releaseDate->diffInDays($date, false);

                // Giảm suất dần theo độ "hot"
                if ($daysSinceRelease <= 7) {
                    $dailyShows = fake()->numberBetween(6, 8); // mới ra mắt → nhiều suất
                } elseif ($daysSinceRelease <= 14) {
                    $dailyShows = fake()->numberBetween(4, 6);
                } elseif ($daysSinceRelease <= 30) {
                    $dailyShows = fake()->numberBetween(2, 4);
                } else {
                    $dailyShows = fake()->numberBetween(0, 2); // phim đã cũ
                }

                if ($dailyShows === 0) continue;

                $duration = $movie->duration ?? 120;
                $cursor = $date->copy()->setTime(8, 0);

                for ($i = 0; $i < $dailyShows; $i++) {
                    $startTime = $cursor->copy();
                    $endTime   = $startTime->copy()->addMinutes($duration);

                    // Tránh trùng giờ chiếu
                    $exists = Showtime::where('movie_id', $movie->id)
                        ->where('start_time', $startTime)
                        ->exists();

                    if ($exists) {
                        $cursor->addMinutes(30);
                        continue;
                    }

                    $room = $rooms->random();

                    Showtime::create([
                        'movie_id'   => $movie->id,
                        'room_id'    => $room->id,
                        'start_time' => $startTime,
                        'end_time'   => $endTime,
                        'status'     => $endTime->isFuture() ? 'active' : 'completed',
                    ]);

                    $cursor = $endTime->copy()->addMinutes(10);

                    if ($cursor->hour >= 23) {
                        break;
                    }
                }
            }
        }
    }
}
