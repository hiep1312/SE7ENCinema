<?php

namespace Database\Seeders;

use App\Models\BookingSeat;
use App\Models\Ticket;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bookingSeatsId = BookingSeat::pluck('id')->toArray();

        foreach ($bookingSeatsId as $bookingSeatId) {

            Ticket::create([
                'booking_seat_id' => $bookingSeatId,
                'note' => fake()->optional()->sentence(),
                'qr_code'=> fake()->uuid(),
                'taken' => fake()->boolean(),
                'status' => Arr::random(['active', 'used', 'canceled']),
            ]);
        }
    }
}
