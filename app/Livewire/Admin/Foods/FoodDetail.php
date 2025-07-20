<?php

namespace App\Livewire\Admin\Foods;

use Livewire\Component;
use App\Models\FoodItem;
use App\Models\FoodOrderItem;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;

class FoodDetail extends Component
{
    use WithPagination;

    public $foodItem;
    public $tabCurrent = 'overview';
    public $totalOrderItemsIn30Days = 0;

    public $isOriginal = false;

    public $price;
    public $quantity_available;
    public $limit;
    public $status;

    public function mount(FoodItem $food)
    {
        $this->foodItem = $food->load([
            'variants.attributeValues.attribute',
            'variants.foodOrderItems.booking.user',
        ]);

        $variantCount = $this->foodItem->variants->count();

        $firstVariant = $this->foodItem->variants->first();

        if ($firstVariant->attributeValues->count() === 0) {
            $this->isOriginal = true;

            $this->price = $firstVariant->price;
            $this->quantity_available = $firstVariant->quantity_available;
            $this->limit = $firstVariant->limit;
            $this->status = $firstVariant->status;
        } else {
            $this->isOriginal = false;
        }

        //Luôn đếm tổng đơn hàng (cả gốc & con)
        $variantIds = $this->foodItem->variants->pluck('id');

        $this->totalOrderItemsIn30Days = FoodOrderItem::whereIn('food_variant_id', $variantIds)
            ->where('created_at', '>=', now()->subDays(30))
            ->sum('quantity');
    }


    #[Title('Chi tiết món ăn - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        // Luôn lấy tất cả ID biến thể (cả gốc và con)
        $variantIds = $this->foodItem->variants->pluck('id');

        $foodOrderItems = FoodOrderItem::whereIn('food_variant_id', $variantIds)
            ->where('created_at', '>=', now()->subDays(30))
            ->with(['booking.user', 'variant.attributeValues.attribute'])
            ->latest()
            ->paginate(10);

        return view('livewire.admin.foods.food-detail', [
            'foodItem' => $this->foodItem,
            'foodOrderItems' => $foodOrderItems,
            'isOriginal' => $this->isOriginal,
            'price' => $this->price,
            'quantity_available' => $this->quantity_available,
            'limit' => $this->limit,
            'status' => $this->status,
        ]);
    }
}
