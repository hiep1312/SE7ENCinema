<?php

namespace Database\Seeders;

use App\Models\Promotion;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PromotionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $start_date = Carbon::parse(fake()->dateTimeBetween('-1 year', 'now'));
        $end_date = $start_date->copy()->addDays(10);
        foreach (range(1, 20) as $i) {
            Promotion::create([
                'title' => fake()->words(3, true),
                'description' => fake()->optional()->sentence(12),
                'start_date' => $start_date,
                'end_date' => $end_date,
                'discount_type' => fake()->randomElement(['percentage', 'fixed_amount']),
                'discount_value' => fake()->numberBetween(10000, 100000),
                'code' => strtoupper(fake()->bothify('PROMO###*****')),
                'usage_limit' => fake()->optional()->numberBetween(1, 1000),
                'min_purchase' => fake()->numberBetween(0, 200000),
                'status' => 'active',
            ]);
        }
    }
}
