<?php

namespace App\Livewire\Client;

use Livewire\Component;
use App\Models\Showtime;
use App\Models\Seat;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Booking;
use App\Models\BookingSeat;
use App\Services\SeatHoldService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use SE7ENCinema\scAlert;

class SelectSeats extends Component
{
    use scAlert;
    public $showtime_id;
    public $showtime;
    public $room;
    public $seats;
    public $selectedSeats = [];
    public $userId = null;
    public $holdExpiresAt = null;
    public $remainingSeconds = 0;
    public $isBanned = false;
    public $banInfo = null;

    protected $seatHoldService;

    public $listeners = [
        'updateSelectedSeats' => 'updateSelectedSeats',
        'reloadSeats' => 'refreshSeats',
        'checkHoldStatus' => 'checkHoldStatus'
    ];

    public function mount($showtime_id)
    {
        $this->showtime_id = $showtime_id;
        $this->userId = Auth::id();
        if (!$this->userId) {
            return redirect()->route('login');
        }

        $this->showtime = Showtime::with('room')->findOrFail($showtime_id);
        $this->room = $this->showtime->room;
        $this->checkCurrentHoldStatus();
        $this->loadSeats();
        $this->generateSeatsLayout();
    }

    public function boot(SeatHoldService $seatHoldService)
    {
        $this->seatHoldService = $seatHoldService;
        if ($this->userId && $this->seatHoldService->checkUserBan($this->userId)) {
            $this->isBanned = true;
            $this->banInfo = $this->seatHoldService->getBanInfo($this->userId);
        }
    }

    public function checkCurrentHoldStatus()
    {
        if ($this->isBanned || !$this->userId) return;

        $holdStatus = $this->seatHoldService->getUserHoldStatus($this->showtime_id, $this->userId);

        if ($holdStatus) {
            if ($this->seatHoldService->isHoldExpired($holdStatus['expires_at'])) {
                $this->handleExpiredHold();
                return;
            }

            $this->selectedSeats = $holdStatus['seat_codes'];
            $this->holdExpiresAt = $holdStatus['expires_at'];
            $this->remainingSeconds = max(0, $holdStatus['remaining_seconds']);
        } else {
            $this->clearHoldData();
        }

    }

    private function handleExpiredHold()
    {
        $result = $this->seatHoldService->handleExpiredHolds($this->userId);

        if ($result['banned']) {
            $this->isBanned = true;
            $this->banInfo = $this->seatHoldService->getBanInfo($this->userId);
            $this->dispatch('sc-alert.error', $this->banInfo['reason'], $this->banInfo['details']);
        } else if ($result['warning']) {
            $this->dispatch('sc-alert.warning', 'Cảnh báo vi phạm!', 'Bạn đã vi phạm ' . $result['violation_count'] . ' lần. Lần vi phạm tiếp theo sẽ bị khóa tài khoản.');
        }

        $this->clearHoldData();
    }

    private function clearHoldData()
    {
        $this->selectedSeats = [];
        $this->holdExpiresAt = null;
        $this->remainingSeconds = 0;
    }

    public function updateSelectedSeats($seats)
    {
        if ($this->isBanned) {
            $this->scAlert('sc-alert.error', $this->banInfo['reason'], $this->banInfo['details']);
            return;
        }

        if (!$this->userId) {
            $this->scAlert('sc-alert.error', 'Chưa đăng nhập', 'Vui lòng đăng nhập để chọn ghế.');
            return;
        }

        if (is_string($seats)) {
            $seats = explode(',', $seats);
        }
        $this->selectedSeats = array_filter((array) $seats);

        if (!empty($this->selectedSeats)) {
            $this->updateSeatHolds();
        } else {
            $this->seatHoldService->releaseHolds($this->userId);
            $this->clearHoldData();
        }

        $this->loadSeats();
        $this->generateSeatsLayout();
    }

    private function updateSeatHolds()
    {
        try {
            $seatIds = $this->getSeatIdsFromCodes($this->selectedSeats);
            $expiresAt = $this->seatHoldService->holdSeats(
                $this->showtime_id,
                $seatIds,
                $this->userId
            );

            $this->holdExpiresAt = $expiresAt;
            $this->remainingSeconds = max(0, $expiresAt->diffInSeconds(now()));
        } catch (\Exception $e) {
            if (str_contains($e->getMessage(), 'khóa')) {
                $this->isBanned = true;
                $this->banInfo = $this->seatHoldService->getBanInfo($this->userId);
                $this->dispatch('sc-alert.error', $this->banInfo['reason'], $this->banInfo['details']);
            } else {
                $this->dispatch('sc-alert.error', 'Lỗi chọn ghế', $e->getMessage());
            }
            $this->clearHoldData();
            $this->selectedSeats = [];
        }
    }

    public function checkHoldStatus()
    {
        if (!$this->userId) return;

        if ($this->seatHoldService->checkUserBan($this->userId)) {
            $this->isBanned = true;
            $this->banInfo = $this->seatHoldService->getBanInfo($this->userId);
            return;
        }

        if ($this->holdExpiresAt) {
            $expiresAt = Carbon::parse($this->holdExpiresAt);
            if ($expiresAt <= now()) {
                $this->handleExpiredHold();
                $this->loadSeats();
                $this->generateSeatsLayout();
                return;
            }
            $this->remainingSeconds = max(0, $expiresAt->diffInSeconds(now()));
        }
    }

    public function loadSeats()
    {
        $this->seats = Seat::where('room_id', $this->showtime->room_id)->get();

        $bookedSeatIds = BookingSeat::whereHas('booking', function ($q) {
            $q->where('showtime_id', $this->showtime_id)
                ->where('status', 'paid');
        })->pluck('seat_id')->toArray();

        $heldSeats = $this->seatHoldService->getHeldSeats($this->showtime_id, $this->userId);
        $heldSeatIds = $heldSeats->pluck('seat_id')->toArray();

        foreach ($this->seats as $seat) {
            $seat->is_booked = in_array($seat->id, $bookedSeatIds);
            $seat->is_held = in_array($seat->id, $heldSeatIds);
        }
    }

    public function refreshSeats()
    {
        $this->loadSeats();
        $this->checkCurrentHoldStatus();
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

    public function refreshSeatStatus()
    {
        if ($this->isBanned) return;

        $this->checkCurrentHoldStatus();
        $this->loadSeats();
        $this->generateSeatsLayout();
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
                'status' => $seat->status,
            ];
        })->toArray();

        $seats = json_encode($seatsData);
        $selectedSeats = json_encode($this->selectedSeats);
        $userId = json_encode($this->userId);
        $holdExpiresAt = $this->holdExpiresAt ? json_encode(Carbon::parse($this->holdExpiresAt)->toIso8601String()) : 'null';
        $remainingSeconds = max(0, $this->remainingSeconds);
        $isBanned = $this->isBanned ? 'true' : 'false';
        $banInfo = $this->banInfo ? json_encode($this->banInfo) : 'null';

        $seatAlgorithms = $this->room->seat_algorithms ?? [];

        $ruleConfig = json_encode([
            'lonely' => $seatAlgorithms['check_lonely'] ?? true,
            'sole' => $seatAlgorithms['check_sole'] ?? true,
            'diagonal' => $seatAlgorithms['check_diagonal'] ?? true,
        ]);

        $this->js(<<<JS
        window.seatRuleConfig = {$ruleConfig};
        const wrapper = document.getElementById('user-seat-wrapper');
        try {
            wrapper.innerHTML = '';
            const dom = window.generateClientDOMSeats({
                seats: {$seats},
                selectedSeats: {$selectedSeats},
                userId: {$userId},
                holdExpiresAt: {$holdExpiresAt},
                remainingSeconds: {$remainingSeconds},
                isBanned: {$isBanned},
                banInfo: {$banInfo}
            });
            wrapper.appendChild(dom);
            console.log('Seats generated successfully');
        } catch (error) {
            console.error('Error generating seats:', error);
        }
    JS);
    }


    public function goToSelectFood()
    {
        DB::beginTransaction();
        try {
            $seatIds = $this->getSeatIdsFromCodes($this->selectedSeats);

            $conflict = BookingSeat::whereIn('seat_id', $seatIds)
                ->whereHas('booking', function ($q) {
                    $q->where('showtime_id', $this->showtime_id)
                        ->where('status', 'paid');
                })->exists();

            if ($conflict) {
                $this->dispatch('sc-alert.error', 'Ghế đã được đặt', 'Một số ghế đã bị đặt bởi người khác.');
                DB::rollBack();
                $this->loadSeats();
                $this->generateSeatsLayout();
                return;
            }

            $moviePrice = $this->showtime->price;

            $booking = Booking::create([
                'user_id' => $this->userId,
                'showtime_id' => $this->showtime_id,
                'booking_code' => strtoupper(Str::random(8)),
                'total_price' => 0,
                'status' => 'pending',
                'start_transaction' => now(),
            ]);

            foreach ($seatIds as $seatId) {
                $seat = Seat::find($seatId);
                $seatPrice = $seat->price;

                $ticketPrice = $seatPrice + $moviePrice;

                BookingSeat::create([
                    'booking_id' => $booking->id,
                    'seat_id' => $seatId,
                    'ticket_price' => $ticketPrice,
                ]);
            }
            $booking->save();
            DB::commit();
            return redirect()->route('client.booking.select_food', [
                'booking_id' => $booking->id,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('sc-alert.error', 'Lỗi tạo đặt chỗ', 'Lỗi khi tạo booking: ' . $e->getMessage());
            $this->loadSeats();
            $this->generateSeatsLayout();
        }
    }


    public function noop() {}

    #[Title('Chọn ghế - SE7ENCinema')]
    #[Layout('components.layouts.client')]

    public function render()
    {
        $this->checkCurrentHoldStatus();

        if ($this->isBanned) {
            return view('livewire.client.select-seats');
        }

        return view('livewire.client.select-seats');
    }
}
