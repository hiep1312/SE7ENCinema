<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\FoodOrderItem;
use App\Models\FoodVariant;
use Illuminate\Database\Seeder;

class FoodOrderItemSeeder extends Seeder
{
    public function run(): void
    {
        $bookings = Booking::all();
        $foodVariants = FoodVariant::all();

        foreach ($bookings as $booking) {
            $count = rand(0, 2);

            for ($i = 0; $i < $count; $i++) {
                $variant = $foodVariants->random();
                $quantity = rand(1, 3);

                FoodOrderItem::create([
                    'booking_id' => $booking->id,
                    'food_variant_id' => $variant->id,
                    'quantity' => $quantity,
                    'price' => $variant->price * $quantity,
                ]);
            }
        }
    }
}
