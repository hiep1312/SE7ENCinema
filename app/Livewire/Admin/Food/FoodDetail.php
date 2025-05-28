<?php

namespace App\Livewire\Admin\Food;

use Livewire\Component;
use App\Models\FoodItem;

class FoodDetail extends Component
{
    public $foodItem;

    public function mount($id)
    {
        $this->foodItem = FoodItem::with('variants')->find($id);

        if (!$this->foodItem) {
            return redirect()->route('admin.food.list')->with('error', 'Food item not found.');
        }
    }

    public function render()
    {
        return view('livewire.admin.food.food-detail');
    }
}
