<?php

namespace Database\Seeders;

use App\Models\Movie;
use App\Models\Room;
use App\Models\Showtime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShowtimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /* Rải rác từ 2 tuần cho đến tháng sau | Mỗi suất chiếu không bị phòng và thời gian */
        /* Mỗi suất chiếu mỗi phòng phải cách nhau 10 phút */

        $movies = Movie::where('status', 'showing')->get();
        $rooms  = Room::where('status', 'active')->get();

        // 14 ngày tới 1 tháng
        foreach ($rooms as $room) {
            foreach (range(0, 30) as $dayOffset) {
                $date = now()->addDays(14 + $dayOffset)->startOfDay();

                // Mỗi ngày 4-5 suất chiếu
                $showCount = fake()->numberBetween(4, 5);

                $start = $date->copy()->setTime(8, 0); // bắt đầu từ 8h sáng

                for ($i = 0; $i < $showCount; $i++) {
                    $movie    = $movies->random();
                    $duration = $movie->duration ?? fake()->numberBetween(90, 150);

                    $end = $start->copy()->addMinutes($duration);

                    Showtime::create([
                        'movie_id'   => $movie->id,
                        'room_id'    => $room->id,
                        'start_time' => $start,
                        'end_time'   => $end,
                        'status'     => $end->isFuture() ? 'active' : 'completed',
                    ]);

                    // cách nhau 10p
                    $start = $end->copy()->addMinutes(10);
                }
            }
        }
    }
}
