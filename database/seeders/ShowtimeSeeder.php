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

        $movies = Movie::where('status', 'showing')->pluck('id')->toArray();
        $rooms = Room::where('status', 'active')->pluck('id')->toArray();

        foreach (range(1, 60) as $i) {
            $start = fake()->dateTimeBetween('-3 days', '+3 days');
            $duration = $movie->duration ?? fake()->numberBetween(90, 150);
            $end = (clone $start)->modify("+{$duration} minutes");

            Showtime::create([
                'movie_id' => fake()->randomElement($movies),
                'room_id' => fake()->randomElement($rooms),
                'start_time' => $start,
                'end_time' => $end,
                'status' => $end > now() ? 'active' : 'completed',
            ]);
        }
    }
}
