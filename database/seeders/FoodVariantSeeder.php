<?php

namespace Database\Seeders;

use App\Models\FoodItem;
use App\Models\FoodVariant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class FoodVariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = FoodItem::all()->toArray();

        foreach (Arr::shuffle($items) as $item) {
            foreach (range(1, rand(1, 3)) as $i) {
                FoodVariant::create([
                    'food_item_id' => $item['id'],
                    'name' => 'Size ' . fake()->randomElement(['S', 'M', 'L']),
                    'price' => fake()->numberBetween(20000, 100000),
                    'image' => fake()->imageUrl(300, 450, 'food'),
                    'quantity_available' => fake()->numberBetween(10, 100),
                    'limit' => fake()->numberBetween(10, 100),
                    'status' => fake()->randomElement(['available', 'out_of_stock', 'hidden']),
                ]);
            }
        }
    }
}
