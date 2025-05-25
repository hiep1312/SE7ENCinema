<?php

namespace Database\Seeders;

use App\Models\FoodItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FoodItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (range(1, 10) as $i) {
            FoodItem::create([
                'name' => fake()->words(2, true),
                'description' => fake()->sentence(),
                'image' => fake()->imageUrl(300, 450, 'food'),
                'status' => fake()->randomElement(['activate', 'discontinued']),
            ]);
        }
    }
}
