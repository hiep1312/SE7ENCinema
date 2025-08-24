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

        // Lấy toàn bộ showtime
        $showtimes = Showtime::orderBy('start_time')->get();

        $showtimesByDate = $showtimes->groupBy(function ($showtime) {
            return Carbon::parse($showtime->start_time)->toDateString();
        });

        foreach ($showtimesByDate as $date => $dailyShowtimes) {
            $numBookings = rand(3, 4);

            for ($i = 0; $i < $numBookings; $i++) {
                $showtime = $dailyShowtimes->random();
                $startTime = Carbon::parse($showtime->start_time);

                $startTransaction = $startTime->copy()->subMinutes(30);
                $endTransaction   = $startTime->copy()->addMinutes(15);

                // Xác định status Booking dựa vào trạng thái Showtime
                if ($showtime->status === 'active') {
                    // suất chiếu sắp tới: pending hoặc paid
                    $status = rand(1, 100) <= 60 ? 'paid' : 'pending';
                } elseif ($showtime->status === 'completed') {
                    // suất chiếu đã xong: paid, expired, failed
                    $random = rand(1, 100);
                    if ($random <= 50) {
                        $status = 'paid';
                    } elseif ($random <= 80) {
                        $status = 'expired';
                    } else {
                        $status = 'failed';
                    }
                } else {
                    // showtime bị hủy → booking failed
                    $status = 'failed';
                }

                Booking::create([
                    'user_id'           => $users[array_rand($users)],
                    'showtime_id'       => $showtime->id,
                    'booking_code'      => 'BKG' . strtoupper(Str::random(6)),
                    'total_price'       => 0,
                    'transaction_code'  => 'TXN' . strtoupper(Str::random(6)),
                    'start_transaction' => $startTransaction,
                    'end_transaction'   => $endTransaction,
                    'status'            => $status,
                    'payment_method'    => 'e_wallet',
                    'created_at'        => $endTransaction,
                    'updated_at'        => $endTransaction,
                ]);
            }
        }
    }
}
