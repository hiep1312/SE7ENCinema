<?php

namespace App\Livewire\Client\Bookings;

use App\Models\Promotion;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use SE7ENCinema\scAlert;

class BookingPayment extends Component
{
    use WithPagination, scAlert;

    public $seachPromotion = '';
    public $selectedPromotionId = null;

    public function realtimeUpdateStatus()
    {
        Promotion::where('end_date', '>=', now())->where('status', 'expired')->update(['status' => 'active']);
        Promotion::where('end_date', '<', now())->where('status', '!=', 'expired')->update(['status' => 'expired']);
    }

    public function applyPromotion(){
        if(empty($this->seachPromotion)){
            // $this->scAlert('Lỗi xác thực', 'Vui lòng nhập mã giảm giá!', 'error');
            return;
        }

        $promotionId = Promotion::where('code', $this->seachPromotion)->first()?->id;
        is_int($promotionId) && ($this->selectedPromotionId = $promotionId);
        // $this->scAlert($promotionId ? 'Thành công' : 'Lỗi xác thực', $promotionId ? '' : 'Không tìm thấy mã giảm giá áp dụng!', $promotionId ? '' : 'error');
    }

    #[Title('Thanh toán đơn hàng - SE7ENCinema')]
    #[Layout('components.layouts.client')]
    public function render()
    {
        $this->realtimeUpdateStatus();

        $promotions = Promotion::whereDoesntHave('usages', function ($query) {
            $query->whereHas('booking', fn($q) => $q->where('user_id', Auth::id()));
        })
        // ->where('status', 'active')
        ->when($this->seachPromotion, fn($query) => $query->where(function ($subQuery) {
            $subQuery->whereLike('code', '%'. trim($this->seachPromotion) .'%')
                ->orWhereLike('title', '%' . trim($this->seachPromotion) . '%');
        }))
        ->paginate(10);

        return view('livewire.client.bookings.booking-payment', compact('promotions'));
    }
}
