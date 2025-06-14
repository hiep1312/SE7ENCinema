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

    #[Title('Chi tiết biến thể - SE7ENCinema')]
    #[Layout('components.layouts.admin')]

    public function render()
    {
        $variant = $this->variantItem;

        $foodOrderItems = $this->variantItem->foodOrderItems()->with(['booking' => function ($query) {
            $query->with('user');
            $query->where('status', 'paid');
        }])->orderBy('created_at', 'desc');

        $relatedVariants = FoodVariant::where('food_item_id', $this->variantItem->food_item_id)
            ->where('id', '!=', $this->variantItem->id)
            ->paginate(10);

        $totalOrderItems = $foodOrderItems->count();
        $foodOrderItems = $foodOrderItems->paginate(20);

        return view('livewire.admin.food-variants.food-variant-detail', compact(
            'variant',
            'foodOrderItems',
            'totalOrderItems',
            'relatedVariants'
        ));
    }
}
