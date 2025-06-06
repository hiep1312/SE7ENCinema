<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Promotion;
use App\Models\PromotionUsage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class PromotionUsageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $promos = Promotion::all();
        $bookings = Booking::all();

        foreach (Arr::shuffle($bookings->toArray()) as $booking) {
            foreach(range(0, rand(1, 3)) as $i){
                PromotionUsage::create([
                    'promotion_id' => $promos->random()->id,
                    'booking_id' => $booking['id'],
                    'discount_amount' => fake()->numberBetween(10000, 100000),
                    'used_at' => fake()->dateTimeBetween('-1 month', 'now'),
                ]);
            }
        }
    }
}
