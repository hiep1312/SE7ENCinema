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

        Room::all()->each(function ($room) {
            $rows = range('A', 'E');

            foreach ($rows as $row) {
                for ($num = 1; $num <= 10; $num++) {
                    $seatType = 'standard';
                    $price = 80000;

                    if (in_array($row, ['C', 'D'])) {
                        $seatType = 'vip';
                        $price = 120000;
                    } elseif ($row === 'E' && in_array($num, [4, 5, 6, 7])) {
                        $seatType = 'couple';
                        $price = 150000;
                    }

                    if ($num == 1 || $num == 10) {
                        $seatType = 'disabled';
                        $price = 60000;
                    }

                    Seat::create([
                        'room_id' => $room->id,
                        'seat_row' => $row,
                        'seat_number' => $num,
                        'price' => $price,
                        'seat_type' => $seatType,
                        'status' => 'active',
                    ]);
                }
            }
        });
    }
}
