<?php

namespace App\Livewire\Client\Bookings;

use App\Models\Promotion;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class BookingPayment extends Component
{

    public $searchVoucher = '';

    public function realtimeUpdateStatus()
    {
        Promotion::where('end_date', '>=', now())->where('status', 'expired')->update(['status' => 'active']);
        Promotion::where('end_date', '<', now())->where('status', '!=', 'expired')->update(['status' => 'expired']);
    }

    #[Title('Thanh toán đơn hàng - SE7ENCinema')]
    #[Layout('components.layouts.client')]
    public function render()
    {
        $this->realtimeUpdateStatus();

        return view('livewire.client.bookings.booking-payment');
    }
}
