<?php

namespace Database\Seeders;

use App\Models\Movie;
use App\Models\Room;
use App\Models\Showtime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ShowtimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $movies = Movie::all()->pluck('id')->toArray();
        $rooms = Room::all()->pluck('id')->toArray();

        if (empty($movies) || empty($rooms)) {
            return; // Dừng nếu không có phim hoặc phòng
        }

        // Tạo suất chiếu cho 30 ngày qua
        foreach (range(-69, 0) as $dayOffset) {
            $date = Carbon::today()->addDays($dayOffset);

            // Các khung giờ cố định trong ngày: 10:00, 13:00, 16:00, 19:00
            $showtimesPerDay = [
                '10:00',
                '13:00',
                '16:00',
                '19:00',
            ];

            foreach ($showtimesPerDay as $time) {
                $movieId = fake()->randomElement($movies);
                $movie = Movie::find($movieId);
                $duration = $movie->duration ?? fake()->numberBetween(90, 150);

                $start = Carbon::parse($date->format('Y-m-d') . ' ' . $time);
                $end = (clone $start)->addMinutes($duration);

                Showtime::create([
                    'movie_id' => $movieId,
                    'room_id' => fake()->randomElement($rooms),
                    'start_time' => $start,
                    'end_time' => $end,
                    'price' => fake()->numberBetween(60000, 180000),
                    'status' => $end > now() ? 'active' : 'completed',
                ]);
            }
        }
    }
}
