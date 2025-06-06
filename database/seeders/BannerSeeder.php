<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (range(1, 10) as $i) {
            Banner::create([
                'title' => fake()->words(5, true),
                'image' => fake()->imageUrl(800, 400, 'banners'),
                'link' => fake()->url(),
                'start_date' => fake()->dateTimeBetween('-1 month', 'now'),
                'end_date' => fake()->dateTimeBetween('now', '+2 months'),
                'status' => fake()->randomElement(['active', 'inactive']),
                'priority' => $i,
            ]);
        }
    }
}
