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
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


#[Title('Chọn món ăn - SE7ENCinema')]
#[Layout('components.layouts.client')]


class SelectFood extends Component
{
    public $selectedFoodId = null;
    public $selectedAttributes = [];
    public $selectedVariant = null;
    public $total_price_seats;

    protected $queryString = [
        'total_price_seats'
    ];

    public $booking_id;

    public $cart = [];

    #[\Livewire\Attributes\Computed]
    public function foodItems()
    {
        return FoodItem::with([
            'variants' => function ($q) {
                $q->select('id', 'food_item_id', 'price', 'quantity_available', 'sku');
            },
            'variants.attributeValues.attribute'
        ])->where('status', 'activate')->get();
    }

    public function mount($booking_id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->booking_id = $booking_id;

        if (session()->has('booking_locked_' . $this->booking_id)) {
            return redirect()->route('client.index');
        }

        $deadlineKey = 'payment_deadline_' . $this->booking_id;
        if (session()->has($deadlineKey)) {
            $deadline = session($deadlineKey);
            if (now()->timestamp * 1000 > $deadline) {
                session()->forget([
                    'cart',
                    'cart_food_total',
                    'cart_seat_total',
                    $deadlineKey
                ]);

                $this->cart = [];

                return redirect()->route('client.index')->with('message', 'Thời gian giữ món đã hết, vui lòng đặt lại!');
            }
        }

        if (!$this->total_price_seats) {
            $this->total_price_seats = request()->query('total_price_seats');
        }
    }




    public function selectFood($foodId)
    {
        $food = FoodItem::with('variants.attributeValues.attribute')->find($foodId);

        $hasAttributes = $food->variants
            ->flatMap(fn($v) => $v->attributeValues)
            ->isNotEmpty();

        if (!$hasAttributes) {
            $variant = $food->variants->first();
            if ($variant) {
                $sku = $variant->sku;

                if (isset($this->cart[$sku])) {
                    $this->cart[$sku]['quantity']++;
                } else {
                    $this->cart[$sku] = [
                        'variant_id' => $variant->id,
                        'name' => $variant->foodItem->name,
                        'attributes' => [],
                        'price' => $variant->price,
                        'quantity' => 1,
                    ];
                }
            }

            $this->selectedFoodId = null;
            return;
        }

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

        if ($this->selectedVariant->quantity_available <= 0) {
            $this->addError('variant', 'Sản phẩm này đã hết hàng!');
            return;
        }

        $sku = $this->selectedVariant->sku;
        $currentQty = $this->cart[$sku]['quantity'] ?? 0;

        if ($currentQty >= $this->selectedVariant->quantity_available) {
            $this->addError('variant', 'Bạn đã chọn vượt quá số lượng tồn kho!');
            return;
        }

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

        $this->selectedAttributes = [];
        $this->selectedVariant = null;
        $this->selectedFoodId = null;
    }


    public function increment($sku)
    {
        $variant = FoodVariant::find($this->cart[$sku]['variant_id']);

        if (!$variant) return;

        $currentQty = $this->cart[$sku]['quantity'];

        if ($currentQty >= $variant->quantity_available) return;

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
        foreach ($this->cart as $sku => $item) {
            $variant = FoodVariant::find($item['variant_id']);
            if (!$variant || $item['quantity'] > $variant->quantity_available) {
                $this->addError('checkout', "Sản phẩm {$item['name']} vượt quá số lượng tồn kho!");
                return;
            }
        }

        session()->put('cart', $this->cart);
        session()->put('cart_food_total', $this->total);
        session()->put('cart_seat_total', $this->total_price_seats);

        if (!session()->has('payment_deadline_' . $this->booking_id)) {
            session()->put('payment_deadline_' . $this->booking_id, now()->addMinutes(10)->timestamp * 1000);
        }

        return redirect()->route('client.thanh-toan', ['booking_id' => $this->booking_id]);
    }


    public function skipFood()
    {
        $this->cart = [];
        session()->put('cart', []);
        session()->put('cart_food_total', 0);
        session()->put('cart_seat_total', $this->total_price_seats);

        if (!session()->has('payment_deadline_' . $this->booking_id)) {
            session()->put('payment_deadline_' . $this->booking_id, now()->addMinutes(10)->timestamp * 1000);
        }

        return redirect()->route('client.thanh-toan', ['booking_id' => $this->booking_id]);
    }

    public function render()
    {
        return view('livewire.client.select-food');
    }
}
