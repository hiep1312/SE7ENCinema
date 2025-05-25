<?php

namespace Database\Seeders;

use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (range(1, 15) as $i) {
            $movie = Movie::create([
                'title' => fake()->title(),
                'description' => fake()->paragraph(),
                'duration' => fake()->numberBetween(90, 180),
                'release_date' => fake()->dateTimeBetween('-1 year', 'now'),
                'end_date' => fake()->dateTimeBetween('now', '+6 months'),
                'director' => fake()->name(),
                'actors' => implode(', ', fake()->words(5)),
                'age_restriction' => fake()->randomElement(['P', 'K', 'T13', 'T16', 'T18', 'C']),
                'poster' => fake()->imageUrl(300, 450, 'movie'),
                'trailer_url' => fake()->url(),
                'format' => fake()->randomElement(['2D', '3D', '4DX', 'IMAX']),
                'price' => fake()->numberBetween(50000, 150000),
                'status' => fake()->randomElement(['coming_soon', 'showing', 'ended']),
            ]);

            // Gán thể loại (genres)
            $genreIds = Genre::inRandomOrder()->limit(rand(1, 3))->pluck('id');
            $movie->genres()->attach($genreIds);
        }
    }
}
