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
use Illuminate\Support\Facades\Cache;

class SelectSeats extends Component
{
    public $showtime_id;
    public $showtime;
    public $room;
    public $seats;
    public $selectedSeats = [];
    public $lastBookedSeatsHash = '';
    public $sessionId;

    public $listeners = [
        'updateSelectedSeats' => 'updateSelectedSeats',
        'reloadSeats' => 'refreshSeats'
    ];

    public function mount($showtime_id)
    {
        $this->showtime_id = $showtime_id;
        $this->sessionId = session()->getId();
        $this->showtime = Showtime::with('room')->findOrFail($showtime_id);
        $this->room = $this->showtime->room;
        $this->loadSeats();
        $this->generateSeatsLayout();
    }

    public function updateSelectedSeats($seats)
    {
        if (is_string($seats)) {
            $seats = explode(',', $seats);
        }
        $this->selectedSeats = array_filter((array) $seats);

        $this->updateTemporaryHold();

        if ($this->hasBookedSeatsChanged()) {
            $this->loadSeats();
        }
        $this->generateSeatsLayout();
    }

    private function updateTemporaryHold()
    {
        $holdKey = "temp_hold_showtime_{$this->showtime_id}";
        $currentHolds = Cache::get($holdKey, []);

        $now = now();
        $currentHolds = array_filter($currentHolds, function ($hold) use ($now) {
            return $now->diffInMinutes($hold['created_at']) < 15;
        });

        $currentHolds = array_filter($currentHolds, function ($hold) {
            return $hold['session_id'] !== $this->sessionId;
        });

        if (!empty($this->selectedSeats)) {
            $seatIds = $this->getSeatIdsFromCodes($this->selectedSeats);
            foreach ($seatIds as $seatId) {
                $currentHolds[$seatId] = [
                    'session_id' => $this->sessionId,
                    'created_at' => $now,
                    'seat_codes' => $this->selectedSeats
                ];
            }
        }

        Cache::put($holdKey, $currentHolds, 20);
    }

    public function loadSeats()
    {
        $cacheKey = "seats_room_{$this->showtime->room_id}";
        $this->seats = Cache::remember($cacheKey, 30, function () {
            return Seat::where('room_id', $this->showtime->room_id)->get();
        });

        $bookedSeatIds = BookingSeat::whereHas('booking', function ($q) {
            $q->where('showtime_id', $this->showtime_id)
                ->whereIn('status', ['pending', 'paid']);
        })->pluck('seat_id')->toArray();

        $this->lastBookedSeatsHash = md5(serialize($bookedSeatIds));

        $holdKey = "temp_hold_showtime_{$this->showtime_id}";
        $currentHolds = Cache::get($holdKey, []);

        $now = now();
        $currentHolds = array_filter($currentHolds, function ($hold) use ($now) {
            return $now->diffInMinutes($hold['created_at']) < 15;
        });
        Cache::put($holdKey, $currentHolds, 20);

        $heldSeatIds = [];
        foreach ($currentHolds as $seatId => $hold) {
            if ($hold['session_id'] !== $this->sessionId) {
                $heldSeatIds[] = $seatId;
            }
        }

        foreach ($this->seats as $seat) {
            $seat->is_booked = in_array($seat->id, $bookedSeatIds);
            $seat->is_held = in_array($seat->id, $heldSeatIds);
        }
    }

    private function hasBookedSeatsChanged()
    {
        $bookedSeatIds = BookingSeat::whereHas('booking', function ($q) {
            $q->where('showtime_id', $this->showtime_id)
                ->whereIn('status', ['pending', 'paid']);
        })->pluck('seat_id')->toArray();

        $currentHash = md5(serialize($bookedSeatIds));
        return $currentHash !== $this->lastBookedSeatsHash;
    }

    public function refreshSeats()
    {
        Cache::forget("temp_hold_showtime_{$this->showtime_id}");
        $this->loadSeats();
        $this->generateSeatsLayout();
    }

    public function toggleSeat($seatCode)
    {
        if (in_array($seatCode, $this->selectedSeats)) {
            $this->selectedSeats = array_values(array_diff($this->selectedSeats, [$seatCode]));
        } else {
            $row = substr($seatCode, 0, 1);
            $number = substr($seatCode, 1);
            $seat = $this->seats->first(fn($s) => $s->seat_row === $row && $s->seat_number == $number);

            if (!$seat) return;

            $alreadyBooked = BookingSeat::where('seat_id', $seat->id)
                ->whereHas('booking', function ($q) {
                    $q->where('showtime_id', $this->showtime_id)
                        ->whereIn('status', ['pending', 'paid']);
                })->exists();

            if ($alreadyBooked) {
                session()->flash('error', 'Ghế đã được đặt.');
                $this->loadSeats();
                $this->generateSeatsLayout();
                return;
            }

            $holdKey = "temp_hold_showtime_{$this->showtime_id}";
            $currentHolds = Cache::get($holdKey, []);

            if (
                isset($currentHolds[$seat->id]) &&
                $currentHolds[$seat->id]['session_id'] !== $this->sessionId
            ) {
                session()->flash('error', 'Ghế đang được giữ bởi người khác.');
                return;
            }

            $this->selectedSeats[] = $seatCode;
        }

        $this->updateTemporaryHold();
        $this->generateSeatsLayout();
    }

    public function getSeatIdsFromCodes($seatCodes)
    {
        return collect($seatCodes)
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
        $seatsData = $this->seats->map(function ($seat) {
            return [
                'id' => $seat->id,
                'seat_row' => $seat->seat_row,
                'seat_number' => $seat->seat_number,
                'seat_type' => $seat->seat_type,
                'price' => $seat->price,
                'is_booked' => $seat->is_booked,
                'is_held' => $seat->is_held ?? false,
            ];
        })->toArray();

        $seats = json_encode($seatsData);
        $selectedSeats = json_encode($this->selectedSeats);
        $sessionId = json_encode($this->sessionId);

        $this->js(<<<JS
            const wrapper = document.getElementById('user-seat-wrapper');
            try {
                wrapper.innerHTML = '';
                const dom = window.generateClientDOMSeats({
                    seats: {$seats},
                    selectedSeats: {$selectedSeats},
                    sessionId: {$sessionId}
                });
                wrapper.appendChild(dom);
                console.log('Seats generated successfully');
            } catch (error) {
                console.error('Error generating seats:', error);
            }
        JS);
    }

    public function noop()
    {
    }

    public function goToSelectFood()
    {
        if (empty($this->selectedSeats)) {
            session()->flash('error', 'Vui lòng chọn ít nhất một ghế.');
            return;
        }

        DB::beginTransaction();
        try {
            $seatIds = $this->getSeatIdsFromCodes($this->selectedSeats);

            $conflict = BookingSeat::whereIn('seat_id', $seatIds)
                ->whereHas('booking', function ($q) {
                    $q->where('showtime_id', $this->showtime_id)
                        ->whereIn('status', ['pending', 'paid']);
                })->exists();

            if ($conflict) {
                session()->flash('error', 'Một số ghế đã bị đặt bởi người khác.');
                DB::rollBack();
                $this->loadSeats();
                $this->generateSeatsLayout();
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

            foreach ($seatIds as $seatId) {
                BookingSeat::create([
                    'booking_id' => $booking->id,
                    'seat_id' => $seatId,
                ]);
            }

            $totalPrice = BookingSeat::where('booking_id', $booking->id)
                ->with('seat')
                ->get()
                ->sum(fn($item) => $item->seat->price ?? 0);

            $booking->update(['total_price' => $totalPrice]);

            $holdKey = "temp_hold_showtime_{$this->showtime_id}";
            $currentHolds = Cache::get($holdKey, []);
            foreach ($seatIds as $seatId) {
                unset($currentHolds[$seatId]);
            }
            Cache::put($holdKey, $currentHolds, 20);

            DB::commit();

            return redirect()->route('client.booking.select_food', [
                'booking_id' => $booking->id,
                'total_price_seats' => $totalPrice,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Lỗi khi tạo booking: ' . $e->getMessage());
            $this->loadSeats();
            $this->generateSeatsLayout();
        }
    }

    #[Title('Chọn ghế - SE7ENCinema')]
    #[Layout('components.layouts.client')]

    public function render()
    {
        if ($this->hasBookedSeatsChanged()) {
            $this->loadSeats();
        }

        return view('livewire.client.select-seats', [
            'room' => $this->room
        ]);
    }
}
