<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            UserSeeder::class,
            GenreSeeder::class,
            RoomSeeder::class,
            MovieSeeder::class,
            SeatSeeder::class,
            ShowtimeSeeder::class,
            BookingSeeder::class,
            BookingSeatSeeder::class,
            TicketSeeder::class,
            FoodItemSeeder::class,
            FoodAttributeSeeder::class,
            FoodAttributeValueSeeder::class,
            FoodVariantSeeder::class,
            FoodOrderItemSeeder::class,
            InventoryTransactionSeeder::class,
            PromotionSeeder::class,
            PromotionUsageSeeder::class,
            RatingSeeder::class,
            CommentSeeder::class,
            NotificationSeeder::class,
            UserNotificationSeeder::class,
            BannerSeeder::class,
        ]);
    }
}
