<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Promotion;
use App\Models\PromotionUsage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class PromotionUsageSeeder extends Seeder
{
    public function run(): void
    {
        $promotions = Promotion::all();
        $bookings   = Booking::all();

        foreach ($bookings as $i => $booking) {
            if ($promotions->isEmpty()) break;

            $promotion = $promotions[$i % $promotions->count()];

            PromotionUsage::create([
                'promotion_id'    => $promotion->id,
                'booking_id'      => $booking->id,
                'discount_amount' => rand(1, 4) * 5000,
                'used_at'         => Carbon::parse($booking->start_transaction)->subMinutes(rand(5, 20)),
            ]);
        }
    }
}
