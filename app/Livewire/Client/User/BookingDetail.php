<?php

namespace App\Livewire\Client\User;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Booking;
use App\Models\SeatHold;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\WithPagination;

class BookingDetail extends Component
{
    use WithFileUploads, WithPagination;
    public $user;
    public $bookingInfo;
    public $avatar = null;
    public function mount(int $booking)
    {
        $this->bookingInfo = Booking::with('showtime.movie.ratings', 'showtime.movie.genres', 'showtime.room', 'user', 'seats', 'promotionUsage', 'foodOrderItems.variant.foodItem', 'foodOrderItems.variant.attributeValues.attribute',)->findOrFail($booking);
        $this->user = User::with('bookings.showtime.movie', 'bookings.seats')->findOrFail(Auth::id());
    }

    public function realtimeUpdateOrder(){
        Booking::where('status', 'pending')->where('created_at', '<', now()->subMinutes(20))->delete();
        SeatHold::where('status', 'holding')->where('expires_at', '<', now())->update(['status' => 'expired']);
    }

    #[Title('Chi tiết đơn hàng - SE7ENCinema')]
    #[Layout('components.layouts.client')]
    public function render()
    {
        $this->realtimeUpdateOrder();
        return view('livewire.client.user.booking-detail');
    }
}
