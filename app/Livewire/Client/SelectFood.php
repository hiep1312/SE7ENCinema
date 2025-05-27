<?php

namespace App\Livewire\Client;

use Livewire\Component;
use App\Models\Booking;
use App\Models\FoodVariant;
use App\Models\FoodOrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
class SelectFood extends Component
{
    public $booking_id;
    public $booking;
    public $foodVariants;
    public $selectedFood = []; // ['food_variant_id' => quantity]

    public function mount($booking_id)
    {
        $this->booking_id = $booking_id;
        $this->booking = Booking::findOrFail($booking_id);
        $this->foodVariants = FoodVariant::where('status', 'available')->get();
    }

    public function updatedSelectedFood($value, $key)
    {
        // Bạn có thể validate số lượng ở đây
    }

    public function goToConfirmBooking()
    {
        DB::beginTransaction();
        try {
            // Xóa các food_order_items cũ (nếu có)
            FoodOrderItem::where('booking_id', $this->booking_id)->delete();

            $totalFoodPrice = 0;

            foreach ($this->selectedFood as $variantId => $quantity) {
                if ($quantity > 0) {
                    $variant = $this->foodVariants->where('id', $variantId)->first();
                    if ($variant) {
                        FoodOrderItem::create([
                            'booking_id' => $this->booking_id,
                            'food_variant_id' => $variantId,
                            'quantity' => $quantity,
                            'price' => $variant->price * $quantity,
                        ]);
                        $totalFoodPrice += $variant->price * $quantity;
                    }
                }
            }

            // Cập nhật tổng tiền trong booking (ghế + đồ ăn)
            $bookingSeatsPrice = $this->booking->bookingSeats->sum(function ($item) {
                return $item->seat->price;
            });

            $this->booking->total_price = $bookingSeatsPrice + $totalFoodPrice;
            $this->booking->save();

            DB::commit();

            return redirect()->route('booking.confirm', ['booking_id' => $this->booking_id]);
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Lỗi khi lưu đồ ăn.');
        }
    }

    public function render()
    {
        return view('livewire.client.select-food')->layout('client');;
    }
}
