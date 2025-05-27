<?php

namespace App\Livewire\Client;

use Livewire\Component;
use App\Models\Showtime;
use App\Models\Seat;
use App\Models\Booking;
use App\Models\BookingSeat;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BookingTicket extends Component
{
    public $showtime_id;
    public $selectedSeats = []; // Mảng id ghế user chọn

    public $showtime;
    public $seats;

    public function mount($showtime_id)
    {
        $this->showtime_id = $showtime_id ?? 0;
        $this->loadShowtime();
        $this->loadSeats();
    }

    public function loadShowtime()
    {
        $this->showtime = Showtime::find($this->showtime_id);

        if (!$this->showtime) {
            session()->flash('error', 'Suất chiếu không tồn tại.');
        }
    }

    public function loadSeats()
    {
        if (!$this->showtime) {
            $this->seats = collect();
            return;
        }

        // Lấy tất cả ghế của phòng
        $this->seats = Seat::where('room_id', $this->showtime->room_id)->get();

        // Lấy ghế đã được đặt (pending hoặc paid) cho suất chiếu này
        $bookedSeatIds = BookingSeat::whereHas('booking', function ($q) {
            $q->where('showtime_id', $this->showtime_id)
                ->whereIn('status', ['pending', 'paid']);
        })->pluck('seat_id')->toArray();

        foreach ($this->seats as $seat) {
            $seat->is_booked = in_array($seat->id, $bookedSeatIds);
        }
    }

    public function toggleSeat($seatId)
    {
        if (in_array($seatId, $this->selectedSeats)) {
            $this->selectedSeats = array_values(array_diff($this->selectedSeats, [$seatId]));
        } else {
            $alreadyBooked = BookingSeat::where('seat_id', $seatId)
                ->whereHas('booking', function ($q) {
                    $q->where('showtime_id', $this->showtime_id)
                        ->whereIn('status', ['pending', 'paid']);
                })->exists();

            if ($alreadyBooked) {
                session()->flash('error', 'Ghế đã được đặt.');
                return;
            }

            $this->selectedSeats[] = $seatId;
        }
    }

    public function submit()
    {
        if (empty($this->selectedSeats)) {
            session()->flash('error', 'Bạn chưa chọn ghế.');
            return;
        }

        DB::beginTransaction();

        try {
            $conflict = BookingSeat::whereIn('seat_id', $this->selectedSeats)
                ->whereHas('booking', function ($q) {
                    $q->where('showtime_id', $this->showtime_id)
                        ->whereIn('status', ['pending', 'paid']);
                })->exists();

            if ($conflict) {
                session()->flash('error', 'Một số ghế bạn chọn đã bị đặt bởi người khác.');
                DB::rollBack();
                return;
            }

            // Tạo booking mới với trạng thái pending
            $booking = Booking::create([
                'showtime_id' => $this->showtime_id,
                'booking_code' => strtoupper(Str::random(8)),
                'total_price' => 0, // TODO: tính tổng tiền dựa trên ghế và suất chiếu
                'status' => 'pending',
                'payment_method' => null,
                'start_transaction' => now(),
            ]);

            // Tạo booking_seats
            foreach ($this->selectedSeats as $seatId) {
                BookingSeat::create([
                    'seat_id' => $seatId,
                    'booking_id' => $booking->id,
                ]);
            }

            DB::commit();

            session()->flash('success', 'Đặt vé thành công. Vui lòng thanh toán trong 15 phút.');
            // TODO: Redirect hoặc chuyển sang bước thanh toán

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Lỗi trong quá trình đặt vé.');
        }
    }

    public function render()
    {
        return view('livewire.client.booking-ticket')->layout('client');
    }
}
