<?php

namespace App\Livewire\Client\Bookings;

use App\Models\Booking;
use App\Models\Promotion;
use App\Models\SeatHold;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use SE7ENCinema\scAlert;

class BookingPayment extends Component
{
    use WithPagination, scAlert;

    public $paymentSelected = null;

    public $booking;
    public $seatHold;

    public function mount(string $bookingCode){
        $this->booking = Booking::with('showtime.movie', 'showtime.room', 'user', 'seats', 'bookingSeats', 'foodOrderItems.variant.foodItem')->where('booking_code', $bookingCode)->first();
        $this->seatHold = SeatHold::where('showtime_id', $this->booking?->showtime_id)->where('user_id', Auth::id())->where('status', 'holding')->where('expires_at', '>', now())->first();

        // if(!$this->booking || $this->booking->user_id !== Auth::id() || !$this->seatHold) abort(404);
    }

    #[Title('Thanh toán đơn hàng - SE7ENCinema')]
    #[Layout('components.layouts.client')]
    public function render()
    {
        $promotions = Promotion::where('status', 'active')->paginate(4);

        return view('livewire.client.bookings.booking-payment', compact('promotions'));
    }
}
