<?php

namespace App\Livewire\Client;

use Livewire\Component;
use App\Models\Showtime;
use App\Models\Seat;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Booking;
use App\Models\BookingSeat;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class SelectSeats extends Component
{
    public $showtime_id;
    public $showtime;
    public $seats;
    public $rows = 15;
    public $seatsPerRow = 20;
    public $vipRows = 'A';
    public $coupleRows = 'B';
    public $selectedSeats = [];

    public function mount($showtime_id)
    {
        $this->showtime_id = $showtime_id;
        $this->showtime = Showtime::findOrFail($showtime_id);
        $this->loadSeats();
        $this->generateSeatsLayout();
    }

    public function loadSeats()
    {
        $this->seats = Seat::where('room_id', $this->showtime->room_id)->get();

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

        $this->generateSeatsLayout();
    }

    public function generateSeatCodes($seatIds)
    {
        return collect($seatIds)
            ->map(function ($id) {
                $seat = $this->seats->firstWhere('id', $id);
                return $seat ? ($seat->seat_row . $seat->seat_number) : null;
            })
            ->filter()
            ->values()
            ->toArray();
    }

    public function generateSeatsLayout()
    {
        $this->dispatch(
            'seatuserdetail',
            $this->rows,
            $this->seatsPerRow,
            $this->vipRows,
            $this->coupleRows,
            $this->generateSeatCodes($this->selectedSeats)
        );
    }

    public function toggleSeatByCode($seatCode)
    {
        $seat = $this->seats->firstWhere(fn($s) => $s->seat_row . $s->seat_number == $seatCode);
        if ($seat) {
            $this->toggleSeat($seat->id);
        }
    }


    public function goToSelectFood()
    {
        if (empty($this->selectedSeats)) {
            session()->flash('error', 'Vui lòng chọn ít nhất một ghế.');
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
                session()->flash('error', 'Một số ghế đã bị đặt bởi người khác.');
                DB::rollBack();
                return;
            }

            $booking = Booking::create([
                'user_id' => 1,
                'showtime_id' => $this->showtime_id,
                'booking_code' => strtoupper(Str::random(8)),
                'total_price' => 0,
                'status' => 'pending',
                'start_transaction' => now(),
            ]);

            $seatIds = collect($this->selectedSeats)
                ->map(function ($code) {
                    $row = substr($code, 0, 1);
                    $number = substr($code, 1);
                    $seat = $this->seats->first(fn($s) => $s->seat_row === $row && $s->seat_number == $number);
                    return $seat?->id;
                })
                ->filter()
                ->unique()
                ->values()
                ->toArray();

            foreach ($seatIds as $seatId) {
                BookingSeat::create([
                    'booking_id' => $booking->id,
                    'seat_id' => $seatId,
                ]);
            }

            DB::commit();

            return redirect()->route('booking.select_food', ['booking_id' => $booking->id]);
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Lỗi khi tạo booking: ' . $e->getMessage());
        }
    }

    #[Title('Chọn ghế - SE7ENCinema')]
    #[Layout('components.layouts.client')]
    public function render()
    {
        return view('livewire.client.select-seats');
    }
}
