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
    public $name = '';
    public $tabCurrent = 'booking-info';
    public $email = '';
    public $phone = '';
    public $birthday = null;
    public $gender = '';
    public $avatar = null;
    public $address = '';
    public $dateFilter = '';
    public $statusFilter = '';
    public $nameFilter = '';
    public $currentPassword = '';
    public $newPassword = '';
    public $confirmPassword = '';
    public function mount(int $booking)
    {
        $this->bookingInfo = Booking::with('showtime.movie.ratings', 'showtime.movie.genres', 'showtime.room', 'user', 'seats', 'promotionUsage', 'foodOrderItems.variant.foodItem', 'foodOrderItems.variant.attributeValues.attribute',)->findOrFail($booking);
        $this->user = User::with('bookings.showtime.movie', 'bookings.seats')->findOrFail(Auth::id());
        $this->fill($this->user->only([
            'name',
            'email',
            'phone',
            'birthday',
            'gender',
            'address',
            'avatar',
        ]));
        $this->birthday = !$this->birthday ?: $this->birthday->format('Y-m-d');
    }
    #[Layout('components.layouts.client')]
    public function render()
    {   
        return view('livewire.client.user.booking-detail');
    }
}
