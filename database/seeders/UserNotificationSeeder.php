<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Database\Seeder;

class UserNotificationSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::pluck('id');
        $notifications = Notification::pluck('id');

        $data = [];

        foreach ($users as $userId) {
            foreach ($notifications as $notiId) {
                $data[] = [
                    'user_id' => $userId,
                    'notification_id' => $notiId,
                    'is_read' => fake()->boolean(),
                ];
            }
        }

        UserNotification::truncate();

        UserNotification::insert($data);
    }
}
