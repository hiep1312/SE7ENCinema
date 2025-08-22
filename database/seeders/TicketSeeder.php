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
                        if (fake()->boolean(10)) {
                            // 10% đã thanh toán nhưng chưa lấy vé
                            $status = 'active';
                            $taken = 0;
                        } else {
                            // 90% đã lấy vé
                            $taken = 1;
                            $takenAt = $showtime->start_time->copy()
                                ->subMinutes(fake()->numberBetween(15, 60));

                            if (fake()->boolean(30)) {
                                // 30% trong số này đã check-in
                                $status = 'used';
                                $checkinAt = $showtime->start_time->copy()
                                    ->subMinutes(fake()->numberBetween(0, 10));
                            } else {
                                $status = 'active';
                            }
                        }
                        break;

                    case 'pending':
                        $status = 'active';
                        $taken = fake()->boolean(40); // 40% đã in vé nhưng chưa check-in
                        $takenAt = $taken
                            ? $showtime->start_time->copy()
                                ->subMinutes(fake()->numberBetween(20, 90))
                            : null;
                        $checkinAt = null;
                        break;

                    case 'expired':
                    case 'failed':
                        $status = 'canceled';
                        $taken = 0;
                        $takenAt = null;
                        $checkinAt = null;
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
