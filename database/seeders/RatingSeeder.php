<?php

namespace Database\Seeders;

use App\Models\Movie;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (range(1, 30) as $i) {
            Rating::create([
                'user_id' => User::all()->random()->id,
                'movie_id' => Movie::all()->random()->id,
                'score' => fake()->numberBetween(1, 5),
                'review' => fake()->sentence(),
            ]);
        }
    }
}
