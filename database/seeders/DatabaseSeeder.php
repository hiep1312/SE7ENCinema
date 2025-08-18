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

        $bookings = Booking::with(['bookingSeats', 'foodOrderItems', 'promotionUsage'])->get();

        foreach ($bookings as $booking) {
            // Tính tổng giá ghế (lưu ý ticket_price ở pivot)
            $ticketPrice = $booking->bookingSeats->sum('ticket_price');

            // Tính tổng giá đồ ăn
            $foodPrice = $booking->foodOrderItems->sum('price');

            // Lấy giá trị khuyến mãi, nếu không có thì = 0
            $discount = $booking->promotionUsage?->discount_amount ?? 0;

            // Cập nhật total_price
            $booking->update([
                'total_price' => max($ticketPrice + $foodPrice - $discount, 0),
            ]);
        }
    }
}
