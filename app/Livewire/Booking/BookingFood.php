<?php

namespace App\Livewire\Booking;

use Livewire\Component;
use App\Models\FoodItem;
use App\Models\FoodVariant;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;


#[Title('Chỉnh sửa món ăn - SE7ENCinema')]
#[Layout('components.layouts.client')]

class BookingFood extends Component
{
    public $foodItems = [];
    public ?FoodItem $selectedItem = null;

    public $availableAttributes = [];
    public $selectedAttributeValues = [];

    public ?FoodVariant $currentVariant = null;
    public $currentQuantity = 1;

    public $order = [];
    public $showModal = false;

    public function mount()
    {
        $this->foodItems = FoodItem::where('status', 'activate')->get();
    }

    

    public function selectItem($itemId)
    {
        $this->selectedItem = FoodItem::with([
            'variants.attributeValues.attribute'
        ])->find($itemId);

        if ($this->selectedItem) {
            $this->prepareAttributeSelection();
            $this->findAndSetCurrentVariant();
            $this->showModal = true;
        }
    }

    protected function prepareAttributeSelection()
    {
        $this->availableAttributes = [];
        $this->selectedAttributeValues = [];

        if (!$this->selectedItem || !$this->selectedItem->variants) {
            return;
        }

        foreach ($this->selectedItem->variants as $variant) {
            foreach ($variant->attributeValues as $attrVal) {
                $this->availableAttributes[$attrVal->attribute->name][] = $attrVal->value;
            }
        }

        foreach ($this->availableAttributes as $name => $values) {
            $unique_values = array_values(array_unique($values));
            $this->availableAttributes[$name] = $unique_values;
            $this->selectedAttributeValues[$name] = $unique_values[0] ?? null;
        }
    }

    public function selectAttribute($attributeName, $value)
    {
        $this->selectedAttributeValues[$attributeName] = $value;
        $this->findAndSetCurrentVariant();
    }

    protected function findAndSetCurrentVariant()
    {
        if (empty($this->selectedAttributeValues)) {
            $this->currentVariant = $this->selectedItem->variants->first();
        } else {
            $this->currentVariant = $this->selectedItem->variants->first(function ($variant) {
                $matches = 0;
                foreach ($variant->attributeValues as $attrVal) {
                    if (($this->selectedAttributeValues[$attrVal->attribute->name] ?? null) == $attrVal->value) {
                        $matches++;
                    }
                }
                return $matches === count($this->selectedAttributeValues);
            });
        }

        if ($this->currentVariant) {
            $this->currentQuantity = $this->order[$this->currentVariant->id] ?? 1;
        } else {
            $this->currentQuantity = 1;
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset([
            'selectedItem',
            'availableAttributes',
            'selectedAttributeValues',
            'currentVariant',
            'currentQuantity'
        ]);
    }

    public function decrementQuantity()
    {
        $this->currentQuantity = max(1, $this->currentQuantity - 1);
    }

    public function incrementQuantity()
    {
        $this->currentQuantity++;
    }

    public function addToOrder()
    {
        if ($this->currentVariant && $this->currentQuantity > 0) {
            $this->order[$this->currentVariant->id] = $this->currentQuantity;
        }
        $this->closeModal();
    }

    #[Computed]
    public function orderDetails()
    {
        if (empty($this->order)) {
            return [];
        }

        $variantIds = array_keys($this->order);

        // 👉 JOIN lấy tên FoodItem trực tiếp
        $variants = FoodVariant::query()
            ->whereIn('food_variants.id', $variantIds)
            ->with('attributeValues.attribute')
            ->join('food_items', 'food_variants.food_item_id', '=', 'food_items.id')
            ->select('food_variants.*', 'food_items.name as food_item_name')
            ->get();


        $details = [];
        $totalPrice = 0;

        foreach ($variants as $variant) {
            $quantity = $this->order[$variant->id];
            $subtotal = $variant->price * $quantity;

            $details[] = [
                'variant' => $variant,
                'food_item_name' => $variant->food_item_name,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
            ];

            $totalPrice += $subtotal;
        }

        return [
            'items' => $details,
            'total_price' => $totalPrice,
        ];
    }

    public function render()
    {
        return view('livewire.booking.booking-food');
    }
}
