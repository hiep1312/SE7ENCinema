<?php

namespace App\Livewire\Client;

use Livewire\Component;
use App\Models\FoodItem;
use App\Models\FoodVariant;
use App\Models\FoodOrderItem;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;


#[Title('Chọn món ăn - SE7ENCinema')]
#[Layout('components.layouts.client')]
class SelectFood extends Component
{
    public $selectedFoodId = null;
    public $selectedAttributes = [];
    public $selectedVariant = null;

    public $cart = [];

    #[\Livewire\Attributes\Computed]
    public function foodItems()
    {
        return FoodItem::with('variants.attributeValues.attribute')
            ->where('status', 'activate')
            ->get();
    }

    public function selectFood($foodId)
    {
        $this->selectedFoodId = $foodId;
        $this->selectedAttributes = [];
        $this->selectedVariant = null;
    }

    public function selectAttribute($attributeName, $value)
    {
        $this->selectedAttributes[$attributeName] = $value;

        $food = FoodItem::with('variants.attributeValues.attribute')
            ->find($this->selectedFoodId);

        $matchingVariant = $food->variants->first(function ($variant) {
            $variantAttributes = $variant->attributeValues->mapWithKeys(fn($val) => [
                $val->attribute->name => $val->value
            ]);

            foreach ($this->selectedAttributes as $name => $value) {
                if (!isset($variantAttributes[$name]) || $variantAttributes[$name] !== $value) {
                    return false;
                }
            }
            return true;
        });

        $this->selectedVariant = $matchingVariant;
    }

    public function addToCart()
    {
        $food = FoodItem::with('variants.attributeValues.attribute')
            ->find($this->selectedFoodId);

        // Đếm tổng số nhóm thuộc tính có thật
        $attributes = $food->variants
            ->flatMap(fn($v) => $v->attributeValues)
            ->pluck('attribute.name')
            ->unique();

        if (count($this->selectedAttributes) < $attributes->count()) {
            $this->addError('variant', 'Vui lòng chọn đủ các thuộc tính!');
            return;
        }

        if (!$this->selectedVariant) {
            $this->addError('variant', 'Tổ hợp thuộc tính không tồn tại!');
            return;
        }

        $sku = $this->selectedVariant->sku;

        if (isset($this->cart[$sku])) {
            $this->cart[$sku]['quantity']++;
        } else {
            $this->cart[$sku] = [
                'variant_id' => $this->selectedVariant->id,
                'name' => $this->selectedVariant->foodItem->name,
                'attributes' => $this->selectedAttributes,
                'price' => $this->selectedVariant->price,
                'quantity' => 1,
            ];
        }

        // Reset sau khi thêm
        $this->selectedAttributes = [];
        $this->selectedVariant = null;
        $this->selectedFoodId = null;
    }


    public function increment($sku)
    {
        $this->cart[$sku]['quantity']++;
    }

    public function decrement($sku)
    {
        if ($this->cart[$sku]['quantity'] > 1) {
            $this->cart[$sku]['quantity']--;
        } else {
            unset($this->cart[$sku]);
        }
    }

    public function remove($sku)
    {
        unset($this->cart[$sku]);
    }

    public function getTotalProperty()
    {
        return collect($this->cart)->sum(fn($item) => $item['price'] * $item['quantity']);
    }

    public function goToCheckout()
    {
        session()->put('cart', $this->cart);
        session()->put('cart_total', $this->total);

        return redirect()->route('thanh-toan');
    }

    public function render()
    {
        return view('livewire.client.select-food');
    }
}
