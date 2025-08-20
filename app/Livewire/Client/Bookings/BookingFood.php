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

class BookingFood extends Component
{
    use WithPagination;

    public $carts = [];
    public $cartTempVariantId = null;
    public $cartTempVariant = null;
    public $cartTempVariantQuantity = 1;

    public $booking;
    public $seatHold = 0;

    public function mount(string $bookingCode){
        $this->booking = Booking::with('showtime.movie', 'showtime.room', 'user', 'seats', 'bookingSeats')->where('booking_code', $bookingCode)->first();
        $this->seatHold = SeatHold::where('showtime_id', $this->booking?->showtime_id)->where('user_id', Auth::id())->where('status', 'holding')->where('expires_at', '>', now())->first();

        if(!$this->booking || $this->booking->user_id !== Auth::id() || !$this->seatHold) abort(404);
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
                    FoodOrderItem::create([
                        'booking_id' => $this->booking->id,
                        'food_variant_id' => $cart[2]->id,
                        'quantity' => $foodVariantCurrent->quantity_available > $cart[0] ? $cart[0] : $foodVariantCurrent->quantity_available,
                        'price' => $cart[1],
                    ]);
                }
            }
        }

        //redirect
    }

    public function realtimeUpdateCountdown(){
        SeatHold::where('expires_at', '<=', now())->where('status', 'holding')->update(['status' => 'expired']);
    }

    #[Title('Chọn đồ ăn - SE7ENCinema')]
    #[Layout('components.layouts.client')]
    public function render()
    {
        $this->realtimeUpdateCountdown();

        $foodItems = FoodItem::where('status', 'activate')->whereHas('variants')->paginate(10);
        $this->js("setDataFoods", $foodItems->map(function($foodItem) {
            $foodItem->variantsData = $foodItem->getAllVariants();
            $foodItem->availableAttributes = $foodItem->availableAttributes;
            return $foodItem;
        }));

        if(isset($this->cartTempVariant) && ($this->cartTempVariantQuantity > ($this->cartTempVariant->quantity_available - (array_key_exists($this->cartTempVariantId, $this->carts) ? $this->carts[$this->cartTempVariantId][0] : 0)))) $this->cartTempVariantQuantity = 0;

        return view('livewire.client.bookings.booking-food', compact('foodItems'));
    }
}
