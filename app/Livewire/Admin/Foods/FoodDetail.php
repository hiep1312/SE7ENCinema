<?php

namespace App\Livewire\Admin\Foods;

use Livewire\Component;
use App\Models\FoodItem;
use App\Models\FoodOrderItem;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class FoodDetail extends Component
{
    public $foodItem;
    public $tabCurrent = 'overview';

    public function mount(FoodItem $food)
    {
        $this->foodItem = $food;
    }

    public function realTimeFoodUpdate(){
        $this->foodItem = FoodItem::with('variants')->find($this->foodItem->id);
    }

    #[Title('Chi tiết món ăn - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        $foodOrderItems = FoodOrderItem::whereIn('food_variant_id', $this->foodItem->variants->pluck('id')->toArray())->with(['variant', 'booking' => function($query){
            $query->with('user');
            $query->where('status', 'paid');
        }])->orderBy('created_at', 'desc');
        $totalOrderItems = $foodOrderItems->count();
        $foodOrderItems = $foodOrderItems->paginate(20);
        return view('livewire.admin.foods.food-detail', compact('foodOrderItems', 'totalOrderItems'));
    }
}
