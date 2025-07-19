<?php

namespace App\Livewire\Client;

use Livewire\Component;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ConfirmBooking extends Component
{
    public $booking_id;
    public $booking;

    public function mount($booking_id)
    {
        $this->booking_id = $booking_id;
        $this->booking = Booking::with(['bookingSeats.seat', 'showtime.movie'])->findOrFail($booking_id);

        $expireTime = Carbon::parse($this->booking->start_transaction)->addSeconds(1000);

        if (now()->greaterThan($expireTime) && $this->booking->status !== 'paid') {
            session()->flash('error', 'Bạn đã quá thời gian thanh toán. Vui lòng đặt vé lại.');
            return redirect()->route('booking.select_showtime');
        }
    }


    public function pay($paymentMethod)
    {
        DB::beginTransaction();
        try {
            $this->booking->payment_method = $paymentMethod;
            $this->booking->status = 'paid';
            $this->booking->transaction_code = strtoupper(\Illuminate\Support\Str::random(10));
            $this->booking->end_transaction = now();
            $this->booking->save();

            // Tạo ticket nếu cần

            DB::commit();

            session()->flash('success', 'Thanh toán thành công!');
            return redirect()->route('booking.confirm', ['booking_id' => $this->booking_id]);
        } catch (\Exception $e) {
            DB::rollBack();

            session()->flash('error', 'Thanh toán thất bại: ' . $e->getMessage());

            return;
        }
    }

    public function render()
    {
        return view('livewire.client.confirm-booking')
            ->layout('client');
    }
}
