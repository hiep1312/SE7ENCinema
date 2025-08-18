<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\BookingSeat;
use Illuminate\Database\Seeder;

class BookingSeatSeeder extends Seeder
{
    public function run(): void
    {
        $bookings = Booking::with('showtime.room.seats')->get();

        foreach ($bookings as $booking) {
            $room = $booking->showtime->room;

            $seats = $room->seats()->inRandomOrder()->take(rand(1, 4))->get();

            foreach ($seats as $seat) {
                
                $price = $seat->price;

                BookingSeat::create([
                    'booking_id' => $booking->id,
                    'seat_id' => $seat->id,
                    'ticket_price' => $price,
                ]);
            }
        }
    }
}
