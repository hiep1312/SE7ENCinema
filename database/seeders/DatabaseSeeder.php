<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Booking;
use App\Models\PromotionUsage;
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
            PromotionSeeder::class,
            PromotionUsageSeeder::class,
            RatingSeeder::class,
            CommentSeeder::class,
            NotificationSeeder::class,
            UserNotificationSeeder::class,
            BannerSeeder::class,
        ]);


        // Sau khi tất cả xong, cập nhật total_price
        
        $bookings = Booking::all();
        foreach ($bookings as $booking) {
            $ticketPrice = $booking->seats()->sum('ticket_price');
            $foodPrice   = $booking->foodOrderItems()->sum('price');
            $discount    = PromotionUsage::where('booking_id', $booking->id)->sum('discount_amount');

            $booking->update([
                'total_price' => $ticketPrice + $foodPrice - $discount,
            ]);
        }
    }
}
