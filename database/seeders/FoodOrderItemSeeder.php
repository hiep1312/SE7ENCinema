<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\FoodOrderItem;
use App\Models\FoodVariant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class FoodOrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /* Tạo bản ghi tùy theo số lượng booking tạo ra */
        /*  */
        /* $bookings = Booking::all()->toArray();
        $variants = FoodVariant::all();

        foreach (Arr::shuffle($bookings) as $booking) {
            $ordered = $variants->random(rand(1, 4));

            foreach ($ordered as $variant) {
                $qty = fake()->numberBetween(1, 3);
                FoodOrderItem::create([
                    'booking_id' => $booking['id'],
                    'food_variant_id' => $variant->id,
                    'quantity' => $qty,
                    'price' => $variant->price * $qty,
                ]);
            }
        } */
    }
}
