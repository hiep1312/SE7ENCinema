<?php

namespace App\Livewire\Client\Bookings;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class BookingFood extends Component
{

    #[Title('Chọn đồ ăn - SE7ENCinema')]
    #[Layout('components.layouts.client')]
    public function render()
    {
        return view('livewire.client.bookings.booking-food');
    }
}
