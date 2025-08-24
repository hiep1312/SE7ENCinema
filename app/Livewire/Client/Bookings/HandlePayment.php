<?php

namespace App\Livewire\Client\Bookings;

use App\Livewire\Payment\VnpayPayment;
use App\Models\Booking;
use App\Models\SeatHold;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Str;

class HandlePayment extends Component
{
    public $booking;
    public $seatHold;

    public function mount(string $bookingCode){
        $this->booking = Booking::with('bookingSeats')->where('booking_code', $bookingCode)->first();
        $this->seatHold = SeatHold::where('showtime_id', $this->booking?->showtime_id)->where('user_id', Auth::id())->where('status', 'holding')->where('expires_at', '>', now())->first();

        if(!$this->booking || $this->booking->user_id !== Auth::id() || !$this->seatHold) abort(404);

        $this->booking->update(['status' => 'paid']);
        foreach($this->booking->bookingSeats as $bookingSeat){
            Ticket::create([
                'booking_seat_id' => $bookingSeat->id,
                'qr_code' => Str::uuid(),
                'taken' => false,
                'status' => 'active'
            ]);
        }
        $this->seatHold->delete();
    }

    #[Title('Kết quả thanh toán - SE7ENCinema')]
    #[Layout('livewire.client.bookings.layout-payment')]
    public function render()
    {
        return view('livewire.client.bookings.handle-payment');
    }
}
