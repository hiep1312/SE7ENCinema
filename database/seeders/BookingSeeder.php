<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\FoodOrderItem;
use App\Models\Showtime;
use App\Models\PromotionUsage;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $showtimes = Showtime::where('status', 'completed')
            ->orderBy('start_time')
            ->get()
            ->unique(fn($item) => Carbon::parse($item->start_time)->toDateString())
            ->values();

        $users = User::pluck('id')->toArray();

        foreach ($showtimes as $i => $showtime) {

            $startTime = Carbon::parse($showtime->start_time);

            $startTransaction = $startTime->copy()->subMinutes(30);
            $endTransaction   = $startTime->copy()->addMinutes(15);

            Booking::create([
                'user_id' => $users[array_rand($users)],
                'showtime_id' => $showtime->id,
                'booking_code' => 'BKG' . strtoupper(Str::random(6)),
                'total_price' => 0, // tạm thời 0, sẽ update ngay
                'transaction_code' => 'TXN' . strtoupper(Str::random(6)),
                'start_transaction' => $startTransaction,
                'end_transaction'   => $endTransaction,
                'status' => $i % 9 == 0 ? 'failed' : 'paid',
                'payment_method' => 'e_wallet',
                'created_at' => $endTransaction,
                'updated_at' => $endTransaction,
            ]);
        }
    }
}
