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
        $this->variantItem = FoodVariant::with(['FoodItem', 'attributeValues.attribute'])->find($variant->id);
    }

    public function realTimeVariantUpdate()
    {
        $this->variantItem = FoodVariant::with(['FoodItem', 'attributeValues.attribute'])->find($this->variantItem->id);
    }


    #[Title('Chi tiết biến thể món ăn - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        // Lấy đơn hàng liên quan
        $foodOrderItemsQuery = $this->variantItem->foodOrderItems()->with(['booking.user'])
            ->whereHas('booking', function ($q) {
                $q->where('status', 'paid');
            })
            ->orderBy('created_at', 'desc');

        // $foodOrderItemsQuery = $this->variantItem->foodOrderItems()
        //     ->with(['booking.user'])
        //     ->orderBy('created_at', 'desc');

        // Tổng đơn hàng 30 ngày
        $totalOrderItemsIn30Days = (clone $foodOrderItemsQuery)
            ->whereBetween('created_at', [now()->subDays(30), now()])
            ->count();

        // Doanh thu 30 ngày
        $totalPriceIn30Days = (clone $foodOrderItemsQuery)
            ->whereBetween('created_at', [now()->subDays(30), now()])
            ->sum('price');

        // Đơn hàng phân trang
        $foodOrderItems = $foodOrderItemsQuery->paginate(20);

        // Các biến thể cùng loại
        $relatedVariants = FoodVariant::with(['attributeValues.attribute', 'FoodItem'])
            ->where('food_item_id', $this->variantItem->food_item_id)
            ->where('id', '!=', $this->variantItem->id)
            ->paginate(20);


        // Thuộc tính & giá trị của món ăn gốc
        $foodItem = $this->variantItem->FoodItem;
        $attributes = $foodItem->attributes()->with('values')->get();

        return view('livewire.admin.food-variants.food-variant-detail', [
            'foodOrderItems' => $foodOrderItems,
            'totalOrderItemsIn30Days' => $totalOrderItemsIn30Days,
            'totalPriceIn30Days' => $totalPriceIn30Days,
            'relatedVariants' => $relatedVariants,
            'foodItem' => $foodItem,
            'attributes' => $attributes,
        ]);
    }
}
