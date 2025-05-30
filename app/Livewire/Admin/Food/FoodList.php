<?php

namespace App\Livewire\Admin\Food;

use Livewire\Component;
use App\Models\FoodItem;

class FoodList extends Component
{
    public $foodItems = [];

    public function render()
    {
        return view('livewire.admin.food.food-list', [
            'foodItems' => $this->foodItems,
        ]);
    }

    public function mount()
    {
        $this->getFoodItems();
    }

    public function getFoodItems()
    {
        $this->foodItems = FoodItem::orderBy('created_at', 'desc')->get();
    }
}
