<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\Seat;
use Illuminate\Database\Seeder;

class SeatSeeder extends Seeder
{
    public function run(): void
    {
        Room::all()->each(function ($room) {
            // Xoá ghế cũ của phòng để seed lại
            Seat::where('room_id', $room->id)->delete();

            // 5 hàng Thường: A–E (mỗi hàng 10 ghế)
            $standardRows = range('A', 'E');
            // 5 hàng VIP: F–J (mỗi hàng 10 ghế)
            $vipRows      = range('F', 'J');
            // 3 hàng Couple: K–M (chỉ ghế 4–5 và 6–7)
            $coupleRows   = range('K', 'M');

            $prices = [
                'standard' => 30000,
                'vip'      => 50000,
                'couple'   => 80000,
            ];

            // Thường
            foreach ($standardRows as $row) {
                for ($num = 1; $num <= 10; $num++) {
                    Seat::create([
                        'room_id'     => $room->id,
                        'seat_row'    => $row,
                        'seat_number' => $num,
                        'price'       => $prices['standard'],
                        'seat_type'   => 'standard',
                        'status'      => 'active',
                    ]);
                }
            }

            // VIP
            foreach ($vipRows as $row) {
                for ($num = 1; $num <= 10; $num++) {
                    Seat::create([
                        'room_id'     => $room->id,
                        'seat_row'    => $row,
                        'seat_number' => $num,
                        'price'       => $prices['vip'],
                        'seat_type'   => 'vip',
                        'status'      => 'active',
                    ]);
                }
            }

            // Couple: chỉ 2 cặp giữa (4–5, 6–7)
            foreach ($coupleRows as $row) {
                foreach ([4, 5, 6, 7] as $num) {
                    Seat::create([
                        'room_id'     => $room->id,
                        'seat_row'    => $row,
                        'seat_number' => $num,
                        'price'       => $prices['couple'],
                        'seat_type'   => 'couple',
                        'status'      => 'active',
                    ]);
                }
            }
        });
    }
}
