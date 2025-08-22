<?php

namespace Database\Seeders;

use App\Models\Booking;
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

        $bookings = Booking::with(['bookingSeats', 'foodOrderItems', 'promotionUsage'])->get();

        foreach ($bookings as $booking) {
            $ticketPrice = $booking->bookingSeats->sum('ticket_price');
            $foodPrice = $booking->foodOrderItems->sum(function ($item) {
                return $item->price * $item->quantity;
            });
            $discount = $booking->promotionUsage?->discount_amount ?? 0;

            $booking->update([
                'total_price' => max($ticketPrice + $foodPrice - $discount, 0),
            ]);
        }
    }
}
