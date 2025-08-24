<?php

namespace Database\Seeders;

use App\Models\BookingSeat;
use App\Models\Ticket;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        $bookingSeats = BookingSeat::with(['booking.showtime'])->get();

        foreach ($bookingSeats as $bookingSeat) {
            $booking = $bookingSeat->booking;
            $showtime = $booking?->showtime;

            $status = 'canceled';
            $taken = 0;
            $takenAt = null;
            $checkinAt = null;

            if ($booking && $showtime) {
                switch ($booking->status) {
                    case 'paid':
                        $random = rand(1, 100);
                        if ($random <= 20) {
                            // 20% đã trả tiền nhưng chưa lấy vé
                            $status = 'active';
                            $taken = 0;
                        } elseif ($random <= 70) {
                            // 50% đã lấy vé nhưng chưa check-in
                            $status = 'active';
                            $taken = 1;
                            $takenAt = $showtime->start_time->copy()->subMinutes(rand(15, 60));
                        } else {
                            // 30% đã check-in
                            $status = 'used';
                            $taken = 1;
                            $takenAt = $showtime->start_time->copy()->subMinutes(rand(15, 60));
                            $checkinAt = $showtime->start_time->copy()->subMinutes(rand(0, 10));
                        }
                        break;

                    case 'pending':
                        // Pending => luôn active
                        $status = 'active';
                        $taken = rand(0, 1);
                        if ($taken) {
                            $takenAt = $showtime->start_time->copy()->subMinutes(rand(20, 90));
                        }
                        break;

                    case 'expired':
                    case 'failed':
                        $status = 'canceled';
                        $taken = 0;
                        break;
                }
            }

            Ticket::create([
                'booking_seat_id' => $bookingSeat->id,
                'note'       => fake()->optional()->sentence(),
                'qr_code'    => Str::uuid(),
                'taken'      => $taken,
                'taken_at'   => $takenAt,
                'checkin_at' => $checkinAt,
                'status'     => $status,
            ]);
        }
    }
}
