<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserNotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::pluck('id');
        $notifications = Notification::pluck('id');

        foreach (range(1, 70) as $i) {
            UserNotification::create([
                'user_id' => $users->random(),
                'notification_id' => $notifications->random(),
                'is_read' => fake()->boolean(),
            ]);
        }
    }
}
