<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Ticket;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bookings = Booking::all()->pluck('id')->toArray();
        foreach (Arr::shuffle($bookings) as $booking) {
            foreach (range(1, rand(1, 3)) as $i) {
                Ticket::create([
                    'booking_id' => $booking,
                    'note' => fake()->optional()->sentence(),
                    'qr_code' => fake()->uuid(),
                    'status' => fake()->randomElement(['active', 'used', 'canceled']),
                ]);
            }
        };
    }
}
