<?php

namespace Database\Seeders;

use App\Models\BookingSeat;
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
        $bookingSeatsId = BookingSeat::pluck('id')->toArray();
        foreach (Arr::shuffle($bookingSeatsId) as $bookingSeatId) {
            Ticket::create([
                'booking_seat_id' => $bookingSeatId,
                'note' => fake()->optional()->sentence(),
                'qr_code' => fake()->uuid(),
                'taken' => fake()->boolean(),
                'status' => fake()->randomElement(['active', 'used', 'canceled']),
            ]);
        };
    }
}
