<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\Seat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $seatTypes = ['standard', 'vip', 'couple', 'disabled'];

        Room::all()->each(function ($room) use ($seatTypes) {
            $rows = range('A', chr(ord('A') + 4)); // A to E
            foreach ($rows as $row) {
                foreach (range(1, 10) as $num) {
                    Seat::create([
                        'room_id' => $room->id,
                        'seat_row' => $row,
                        'seat_number' => $num,
                        'price' => fake()->numberBetween(50000, 200000),
                        'seat_type' => fake()->randomElement($seatTypes),
                        'status' => fake()->randomElement(['active', 'maintenance', 'inactive']),
                    ]);
                }
            }
        });
    }
}
