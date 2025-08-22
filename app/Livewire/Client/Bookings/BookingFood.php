<?php

namespace App\Livewire\Client\Bookings;

use App\Models\Booking;
use App\Models\FoodItem;
use App\Models\FoodOrderItem;
use App\Models\FoodVariant;
use App\Models\SeatHold;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use SE7ENCinema\scAlert;

class BookingFood extends Component
{
    use WithPagination, scAlert;

    public $carts = [];
    public $cartTempVariantId = null;
    public $cartTempVariant = null;
    public $cartTempVariantQuantity = 1;

    public $booking;
    public $seatHold;

    public function mount(string $bookingCode){
        $this->booking = Booking::with('showtime.movie', 'showtime.room', 'user', 'seats', 'bookingSeats')->where('booking_code', $bookingCode)->first();
        $this->seatHold = SeatHold::where('showtime_id', $this->booking?->showtime_id)->where('user_id', Auth::id())->where('status', 'holding')->where('expires_at', '>', now())->first();

        if(!$this->booking || $this->booking->user_id !== Auth::id() || !$this->seatHold) abort(404);

        if(session()->has("__sc-cart__") && session("__sc-cart__")[0] === $bookingCode) $this->carts = session("__sc-cart__")[1];
    }

    public function updatedCartTempVariantId(){
        $this->cartTempVariantId && ($this->cartTempVariant = FoodVariant::with('foodItem')->find($this->cartTempVariantId)) && ($this->cartTempVariantQuantity = 1);
    }

    public function addToCart(){
        if(!isset($this->carts[$this->cartTempVariantId])) $this->carts[$this->cartTempVariantId] = [$this->cartTempVariantQuantity, $this->cartTempVariant->price, $this->cartTempVariant];
        else $this->carts[$this->cartTempVariantId][0]+=$this->cartTempVariantQuantity;
    }

    public function nextStep(){
        if($this->carts){
            foreach($this->carts as $cart){
                $foodVariantCurrent = FoodVariant::where('id', $cart[2]->id)->first();
                if($foodVariantCurrent && $foodVariantCurrent->quantity_available <= 0){
                    $orderQuantity = $foodVariantCurrent->quantity_available > $cart[0] ? $cart[0] : $foodVariantCurrent->quantity_available;
                    FoodOrderItem::create([
                        'booking_id' => $this->booking->id,
                        'food_variant_id' => $cart[2]->id,
                        'quantity' => $orderQuantity,
                        'price' => $cart[1],
                    ]);

                    $foodVariantCurrent->decrement('quantity_available', $orderQuantity);
                }
            }
        }

        return redirect()->route('client.thanh-toan', ['booking_id' => $this->booking->id]);
    }

    public function updateCartAndStatusCountdown(){
        SeatHold::where('expires_at', '<=', now())->where('status', 'holding')->update(['status' => 'expired']);
        if(!empty($this->carts)) foreach($this->carts as $foodId => $cart) {
                $foodVariantCurrent = FoodVariant::where('id', $cart[2]->id)->first();
                $messageChange = "";
                if(!$foodVariantCurrent || $foodVariantCurrent->quantity_available <= 0){
                    unset($this->carts[$foodId]);
                    $messageChange = "'{$cart[2]->foodItem->name}' hiện đã hết hàng và đã được xóa khỏi giỏ hàng";
                }elseif($foodVariantCurrent->quantity_available < $cart[0]){
                    $this->carts[$foodId][0] = $foodVariantCurrent->quantity_available;
                    $messageChange = "Số lượng '{$cart[2]->foodItem->name}' đã được điều chỉnh về tối đa ({$foodVariantCurrent->quantity_available})";
                }elseif($foodVariantCurrent->price !== $cart[1]){
                    $this->carts[$foodId][1] = $foodVariantCurrent->price;
                    $messageChange = "Giá '{$cart[2]->foodItem->name}' đã thay đổi";
                }
                $messageChange && $this->scToast($messageChange, 'warning', 5000, true);
                continue;
            }
    }

    #[Title('Chọn đồ ăn - SE7ENCinema')]
    #[Layout('components.layouts.client')]
    public function render()
    {
        $this->updateCartAndStatusCountdown();

        $foodItems = FoodItem::where('status', 'activate')->whereHas('variants')->paginate(10, ["*"], "food");
        $this->js("setDataFoods", $foodItems->map(function($foodItem) {
            $foodItem->variantsData = $foodItem->getAllVariants();
            $foodItem->availableAttributes = $foodItem->availableAttributes;
            return $foodItem;
        }));

        if(isset($this->cartTempVariant) && ($this->cartTempVariantQuantity > ($this->cartTempVariant->quantity_available - (array_key_exists($this->cartTempVariantId, $this->carts) ? $this->carts[$this->cartTempVariantId][0] : 0)))) $this->cartTempVariantQuantity = 0;
        if(!empty($this->carts)) session()->put('__sc-cart__', [$this->booking->booking_code, $this->carts]);
        else session()->forget("__sc-cart__");

        return view('livewire.client.bookings.booking-food', compact('foodItems'));
    }
}
