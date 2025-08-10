<?php

namespace App\Livewire\Client\Bookings;

use App\Models\FoodItem;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class BookingFood extends Component
{
    #[Title('Chọn đồ ăn - SE7ENCinema')]
    #[Layout('components.layouts.client')]
    public function render()
    {
        $foodItems = FoodItem::with('variants.attributeValues.attribute', 'attributes.values')->where('status', 'activate')->get();
        return view('livewire.client.bookings.booking-food', compact('foodItems'));
    }
}
