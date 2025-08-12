<?php

namespace App\Livewire\Client\Bookings;

use App\Models\FoodItem;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class BookingFood extends Component
{
    public $carts = [];

    #[Title('Chọn đồ ăn - SE7ENCinema')]
    #[Layout('components.layouts.client')]
    public function render()
    {
        $foodItems = FoodItem::with('attributes.values')->where('status', 'activate')->paginate(10);

        $this->js("setDataFoods", $foodItems->each(fn($foodItem) => $foodItem->variants = $foodItem->getAllVariants()));

        return view('livewire.client.bookings.booking-food', compact('foodItems'));
    }
}
