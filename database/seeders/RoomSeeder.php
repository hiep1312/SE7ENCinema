<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (range(1, 5) as $i) {
            Room::create([
                'name' => 'Room ' . $i,
                'capacity' => fake()->numberBetween(50, 150),
                'status' => fake()->randomElement(['active', 'maintenance', 'inactive']),
                'last_maintenance_date' => fake()->dateTimeBetween('-6 months', 'now'),
            ]);
        }
    }
}
