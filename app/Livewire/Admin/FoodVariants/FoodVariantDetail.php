<?php

namespace App\Livewire\Admin\FoodVariants;

use Livewire\Component;
use App\Models\FoodVariant;
use App\Models\FoodOrderItem;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class FoodVariantDetail extends Component
{
    use WithPagination;

    public $variantItem;
    public $tabCurrent = 'overview';

    public function mount(FoodVariant $variant)
    {
        $this->variantItem = $variant;
    }

    public function realTimeVariantUpdate()
    {
        $this->variantItem = FoodVariant::with('FoodItem')->find($this->variantItem->id);
    }

    #[Title('Chi tiết biến thể món ăn - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        $foodOrderItems = $this->variantItem->foodOrderItems()->with(['booking' => function ($query) {
            $query->with('user');
            $query->where('status', 'paid');
        }])->orderBy('created_at', 'desc');
        $relatedVariants = FoodVariant::where('food_item_id', $this->variantItem->food_item_id)
            ->where('id', '!=', $this->variantItem->id)
            ->paginate(20);
        $totalOrderItemsIn30Days = (clone $foodOrderItems)->whereBetween('created_at', [now()->subDays(30), now()])->count();
        $totalPriceIn30Days = (clone $foodOrderItems)->whereBetween('created_at', [now()->subDays(30), now()])->sum('price');
        $foodOrderItems = $foodOrderItems->paginate(20);

        return view('livewire.admin.food-variants.food-variant-detail', compact('foodOrderItems', 'totalOrderItemsIn30Days', 'totalPriceIn30Days', 'relatedVariants'));
    }
}
