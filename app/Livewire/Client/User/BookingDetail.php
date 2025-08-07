<?php

namespace App\Livewire\Client\User;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\BookingSeat;
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
    #[Layout('components.layouts.client')]
    public function render()
    {   
        return view('livewire.client.user.booking-detail');
    }
}
