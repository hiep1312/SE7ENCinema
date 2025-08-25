<?php

namespace App\Livewire\Client\Bookings;

use App\Livewire\Payment\VnpayPayment;
use App\Mail\TicketInvoice;
use App\Models\Booking;
use App\Models\SeatHold;
use App\Models\Ticket;
use App\Services\VNPaymentService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Str;
use Throwable;

class HandlePayment extends Component
{
    public $booking;
    public $seatHold;
    public $statusPayment;

    public function mount(string $bookingCode)
    {
        $this->booking = Booking::with('showtime.movie', 'showtime.room', 'user', 'seats', 'bookingSeats', 'foodOrderItems.variant.foodItem')->where('booking_code', $bookingCode)->first();
        $this->seatHold = SeatHold::where('showtime_id', $this->booking?->showtime_id)->where('user_id', Auth::id())->where('status', 'holding')->where('expires_at', '>=', now());

        $checkSum = true;
        try {
            $analysisPayment = VNPaymentService::parseWithQueryString(null, true);
        } catch (Throwable) {
            $checkSum = false;
        }

        if (!$this->booking || $this->booking->user_id !== Auth::id() || !$this->seatHold || !$checkSum || $analysisPayment['vnp_TxnRef'] !== $this->booking->transaction_code) abort(404);

        if (($this->statusPayment = ($analysisPayment['vnp_TransactionStatus'] === '00'))) {
            $this->booking->update(['status' => 'paid']);
            $this->seatHold->update(['status' => 'released']);
            Mail::to($this->booking->user->email)->send(new TicketInvoice($this->booking));
        } else {
            $this->booking->update(['status' => 'failed']);
            $this->seatHold->delete();
        }

        session()->forget(['__sc-seat__', '__sc-cart__', '__sc-voucher__', '__sc-payment__']);

        foreach ($this->booking->bookingSeats ?? [] as $bookingSeat) Ticket::create([
            'booking_seat_id' => $bookingSeat->id,
            'note' => null,
            'qr_code' => Str::uuid(),
            'taken' => false,
            'taken_at' => null,
            'checkin_at' => null,
            'status' => 'active'
        ]);
    }

    #[Title('Kết quả thanh toán - SE7ENCinema')]
    #[Layout('livewire.client.bookings.layout-payment')]
    public function render()
    {
        return view('livewire.client.bookings.handle-payment');
    }
}
