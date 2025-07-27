<?php

namespace App\Livewire\Admin\Promotions;

use App\Models\Promotion;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class PromotionDetail extends Component
{
    use WithPagination;
    public $promotion;
    public $tabCurrent = 'overview';

    public function mount(int $promotion)
    {
        $this->promotion = Promotion::with('usages')->findOrFail($promotion);
    }

    public function realtimeUpdateStatus()
    {
        Promotion::where('end_date', '>=', now())->where('status', 'expired')->update(['status' => 'active']);
        Promotion::where('end_date', '<', now())->where('status', '!=', 'expired')->update(['status' => 'expired']);
    }

    #[Title('Chi tiết mã giảm giá - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        $this->realtimeUpdateStatus();

        $usages = $this->promotion->usages()->with('booking.user', 'booking.foodOrderItems.variant.foodItem')->paginate(10);

        return view('livewire.admin.promotions.promotion-detail', compact('usages'));
    }
}
