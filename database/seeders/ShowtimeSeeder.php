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

        $startWindow = now()->copy()->subMonth(3)->startOfDay();
        $endWindow = now()->copy()->addWeeks(2)->endOfDay();

        for ($date = $startWindow->copy(); $date->lte($endWindow); $date->addDay()) {
            foreach ($movies as $movie) {
                $releaseDate = Carbon::parse($movie->release_date);
                if ($releaseDate->gt($date)) continue;
                $daysSinceRelease = $releaseDate->diffInDays($date, false);

                if ($daysSinceRelease <= 7) {
                    $dailyShows = fake()->numberBetween(3, 4);
                } elseif ($daysSinceRelease <= 14) {
                    $dailyShows = fake()->numberBetween(2, 3);
                } elseif ($daysSinceRelease <= 30) {
                    $dailyShows = fake()->numberBetween(1, 2);
                } else {
                    $dailyShows = fake()->numberBetween(0, 1);
                }

                if ($dailyShows === 0) continue;
                $duration = $movie->duration ?? 120;
                $cursor = $date->copy()->setTime(9, 0);

                for ($i = 0; $i < $dailyShows; $i++) {
                    $startTime = $cursor->copy();
                    $endTime   = $startTime->copy()->addMinutes($duration);

                    $exists = Showtime::where('movie_id', $movie->id)
                        ->where(function ($q) use ($startTime, $endTime) {
                            $q->where('start_time', '<', $endTime)
                              ->where('end_time', '>', $startTime);
                        })
                        ->exists();

                    if ($exists) {
                        $cursor->addMinutes(15);
                        continue;
                    }

                    $room = $rooms->random();

                    $status = $endTime->isPast() ? 'completed' : 'active';
                    if ($endTime->isFuture() && rand(1, 100) <= 5) {
                        $status = 'canceled';
                    }

                    Showtime::create([
                        'movie_id' => $movie->id,
                        'room_id' => $room->id,
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'status' => $status,
                    ]);

                    $cursor = $endTime->copy()->addMinutes(15);

                    if ($cursor->hour >= 23) break;
                }
            }
        }
    }
}
