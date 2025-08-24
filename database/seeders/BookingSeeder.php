<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Showtime;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::pluck('id')->toArray();

        // Lấy tất cả showtime đã completed
        $showtimes = Showtime::where('status', 'completed')
            ->orderBy('start_time')
            ->get();

        // Nhóm showtime theo ngày (yyyy-mm-dd)
        $showtimesByDate = $showtimes->groupBy(function ($showtime) {
            return Carbon::parse($showtime->start_time)->toDateString();
        });

        foreach ($showtimesByDate as $date => $dailyShowtimes) {
            // Random số đơn hàng trong ngày (3–4)
            $numBookings = rand(3, 4);

            for ($i = 0; $i < $numBookings; $i++) {
                // Random 1 suất chiếu trong ngày
                $showtime = $dailyShowtimes->random();
                $startTime = Carbon::parse($showtime->start_time);

                $startTransaction = $startTime->copy()->subMinutes(30);
                $endTransaction   = $startTime->copy()->addMinutes(15);

                Booking::create([
                    'user_id'           => $users[array_rand($users)],
                    'showtime_id'       => $showtime->id,
                    'booking_code'      => 'BKG' . strtoupper(Str::random(6)),
                    'total_price'       => 0,
                    'transaction_code'  => 'TXN' . strtoupper(Str::random(6)),
                    'start_transaction' => $startTransaction,
                    'end_transaction'   => $endTransaction,
                    'status'            => $i % 9 == 0 ? 'failed' : 'paid',
                    'payment_method'    => 'e_wallet',
                    'created_at'        => $endTransaction,
                    'updated_at'        => $endTransaction,
                ]);
            }
        }
    }
}
