<?php

namespace App\Livewire\Admin\FoodVariants;

use Livewire\Component;
use App\Models\FoodVariant;
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
        $this->variantItem = $variant->load(['FoodItem', 'attributeValues.attribute']);
    }

    public function realTimeVariantUpdate()
    {
        $this->variantItem = FoodVariant::with(['FoodItem', 'attributeValues.attribute'])->find($this->variantItem->id);
    }

    #[Title('Chi tiết biến thể món ăn - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        $foodOrderItemsQuery = $this->variantItem->foodOrderItems()->with(['booking.user'])
            ->whereHas('booking', function ($q) {
                $q->where('status', 'paid');
            })
            ->orderBy('created_at', 'desc');

        $totalOrderItemsIn30Days = (clone $foodOrderItemsQuery)
            ->whereBetween('created_at', [now()->subDays(30), now()])
            ->count();

        $totalPriceIn30Days = (clone $foodOrderItemsQuery)
            ->whereBetween('created_at', [now()->subDays(30), now()])
            ->sum('price');

        $foodOrderItems = $foodOrderItemsQuery->paginate(20);

        $relatedVariants = FoodVariant::with(['attributeValues.attribute', 'FoodItem'])
            ->where('food_item_id', $this->variantItem->food_item_id)
            ->where('id', '!=', $this->variantItem->id)
            ->paginate(20);

        $foodItem = $this->variantItem->FoodItem;
        $attributes = $foodItem->attributes()->with('values')->get();

        return view('livewire.admin.food-variants.food-variant-detail', compact('foodOrderItems', 'totalOrderItemsIn30Days', 'totalPriceIn30Days', 'relatedVariants', 'foodItem', 'attributes'));
    }
}
