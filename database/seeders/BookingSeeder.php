<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Showtime;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /* $users = User::all()->pluck('id')->toArray();
        $showtimes = Showtime::all()->pluck('id')->toArray();

        foreach (range(1, 20) as $i) {
            $start = fake()->dateTimeBetween('-7 days', 'now');
            $end = (clone $start)->modify('+15 minutes');

            Booking::create([
                'user_id' => fake()->randomElement($users),
                'showtime_id' => fake()->randomElement($showtimes),
                'booking_code' => strtoupper(fake()->unique()->bothify('BK####??')),
                'total_price' => fake()->numberBetween(100000, 600000),
                'transaction_code' => strtoupper(fake()->unique()->bothify('TX####??')),
                'start_transaction' => $start,
                'end_transaction' => $end,
                'status' => fake()->randomElement(['pending', 'expired', 'paid', 'failed']),
                'payment_method' => fake()->randomElement(['credit_card', 'bank_transfer', 'e_wallet', 'cash']),
            ]);
        } */
    }
}
