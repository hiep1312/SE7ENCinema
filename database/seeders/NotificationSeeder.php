<?php

namespace Database\Seeders;

use App\Models\Notification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (range(1, 20) as $i) {
            Notification::create([
                'thumbnail' => fake()->imageUrl(100, 100, 'notifications'),
                'title' => fake()->sentence(),
                'content' => fake()->paragraph(),
                'link' => fake()->url(),
            ]);
        }
    }
}
