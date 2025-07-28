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
    public $total_price_seats;

    protected $queryString = [
        'total_price_seats'
    ];

    public $booking_id;

    public $cart = [];

    #[\Livewire\Attributes\Computed]
    public function foodItems()
    {
        return FoodItem::with('variants.attributeValues.attribute')
            ->where('status', 'activate')
            ->get();
    }

    public function mount($booking_id)
    {
        $this->booking_id = $booking_id;

        if (session()->has('booking_locked_' . $this->booking_id)) {
            return redirect()->route('client.index');
        }

        // fallback nếu queryString không bind kịp
        if (!$this->total_price_seats) {
            $this->total_price_seats = request()->query('total_price_seats');
        }
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
        session()->put('cart_food_total', $this->total); // chỉ tiền food
        session()->put('cart_seat_total', $this->total_price_seats);

        // ✅ Nếu chưa có thì mới lưu
        if (!session()->has('payment_deadline_' . $this->booking_id)) {
            session()->put('payment_deadline_' . $this->booking_id, now()->addMinutes(10)->timestamp * 1000);
        }

        return redirect()->route('thanh-toan', ['booking_id' => $this->booking_id]); // route trang thanh toán VNPay
    }

    public function skipFood()
    {
        $this->cart = [];
        session()->put('cart', []);
        session()->put('cart_food_total', 0);
        session()->put('cart_seat_total', $this->total_price_seats);

        // ✅ Thêm điều kiện tạo deadline nếu chưa có
        if (!session()->has('payment_deadline_' . $this->booking_id)) {
            session()->put('payment_deadline_' . $this->booking_id, now()->addMinutes(10)->timestamp * 1000);
        }

        return redirect()->route('thanh-toan', ['booking_id' => $this->booking_id]);
    }





    public function render()
    {
        return view('livewire.client.select-food');
    }
}
