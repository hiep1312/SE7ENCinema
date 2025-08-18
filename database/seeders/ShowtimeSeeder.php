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
        $movies = Movie::whereNotNull('release_date')->get();
        $rooms  = Room::where('status', 'active')->get();

        if ($movies->isEmpty() || $rooms->isEmpty()) return;

        // Khung thời gian: 1 tháng trước → 2 tuần tới
        $startWindow = now()->copy()->subMonth()->startOfDay();
        $endWindow   = now()->copy()->addWeeks(2)->endOfDay();

        foreach ($rooms as $room) {
            for ($date = $startWindow->copy(); $date->lte($endWindow); $date->addDay()) {
                foreach ($movies as $movie) {
                    $releaseDate = Carbon::parse($movie->release_date);

                    // Bỏ qua ngày trước khi phim ra mắt
                    if ($releaseDate->gt($date)) continue;

                    $daysSinceRelease = $releaseDate->diffInDays($date, false);

                    // Số suất chiếu/ngày theo độ hot
                    if ($daysSinceRelease <= 7) {
                        $dailyShows = fake()->numberBetween(3, 4); // phim mới → nhiều suất
                    } elseif ($daysSinceRelease <= 14) {
                        $dailyShows = fake()->numberBetween(2, 3);
                    } elseif ($daysSinceRelease <= 30) {
                        $dailyShows = fake()->numberBetween(1, 2);
                    } else {
                        $dailyShows = fake()->numberBetween(0, 1); // phim cũ
                    }

                    if ($dailyShows === 0) continue;

                    $duration = $movie->duration ?? 120;
                    $cursor = $date->copy()->setTime(9, 0); // bắt đầu từ 9h sáng

                    for ($i = 0; $i < $dailyShows; $i++) {
                        $startTime = $cursor->copy();
                        $endTime   = $startTime->copy()->addMinutes($duration);

                        // **Kiểm tra chồng lấn toàn rạp cho cùng phim**
                        $exists = Showtime::where('movie_id', $movie->id)
                            ->where(function($q) use ($startTime, $endTime) {
                                $q->where(function($qq) use ($startTime, $endTime) {
                                    $qq->where('start_time', '<', $endTime)
                                       ->where('end_time', '>', $startTime);
                                });
                            })->exists();

                        if ($exists) {
                            $cursor->addMinutes(15);
                            continue;
                        }

                        // Trạng thái suất chiếu
                        $status = $endTime->isPast() ? 'completed' : 'active';
                        if ($endTime->isFuture() && rand(1, 100) <= 5) {
                            $status = 'canceled';
                        }

                        Showtime::create([
                            'movie_id'   => $movie->id,
                            'room_id'    => $room->id,
                            'start_time' => $startTime,
                            'end_time'   => $endTime,
                            'status'     => $status,
                        ]);

                        $cursor = $endTime->copy()->addMinutes(15); // nghỉ 15 phút giữa suất

                        // Ngừng suất chiếu nếu quá 23h
                        if ($cursor->hour >= 23) break;
                    }
                }
            }
        }
    }
}
