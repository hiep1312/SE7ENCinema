<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\Seat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookingSeatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /* Tạo bản ghi tùy theo số lượng booking_seats tạo ra */
        /* ticket_price | movie + seat  */

        $seats = Seat::all();

        Booking::all()->each(function ($booking) use ($seats) {
            $selectedSeats = $seats->random(rand(1, 3));

            foreach ($selectedSeats as $seat) {
                BookingSeat::create([
                    'booking_id' => $booking->id,
                    'seat_id' => $seat->id,
                    'ticket_price' => fake()->numberBetween(100000, 600000),
                ]);
            }
        });
    }
}
