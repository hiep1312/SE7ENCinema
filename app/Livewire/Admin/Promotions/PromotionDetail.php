<?php

namespace App\Livewire\Admin\Promotions;

use App\Models\Promotion;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class PromotionDetail extends Component
{

    public $promotionId;
    public $title;
    public $code;
    public $description;
    public $discount_type;
    public $discount_value;
    public $start_date;
    public $end_date;
    public $usage_limit;
    public $min_purchase;
    public $status;
    public $tabCurrent = 'overview';

     public function mount(Promotion $promotion)
    {
        $promotion = Promotion::findOrFail($promotion->id);
        $this->promotionId = $promotion->id;
        $this->title = $promotion->title;
        $this->code = $promotion->code;
        $this->description = $promotion->description;
        $this->discount_type = $promotion->discount_type;
        $this->discount_value = $promotion->discount_value;
        $this->start_date = $promotion->start_date->format('Y-m-d H:i');
        $this->end_date = $promotion->end_date->format('Y-m-d H:i');
        $this->usage_limit = $promotion->usage_limit;
        $this->min_purchase = $promotion->min_purchase;
        $this->status = $promotion->status;
    }

    public function updatePromotionStatuses()
    {
        // Chuyển active => expired
        Promotion::where('status', 'active')
            ->where('end_date', '<', now())
            ->update(['status' => 'expired']);
        // Chuyển expired => active nếu end_date > now
        Promotion::where('status', 'expired')
            ->where('end_date', '>', now())
            ->update(['status' => 'active']);
    }

    public function resetPromotionDetail()
    {
        $this->updatePromotionStatuses();
        // reload lại dữ liệu
        // $this->mount(Promotion::findOrFail($this->promotionId));
        // session()->flash('success', 'Đã cập nhật lại dữ liệu khuyến mãi mới nhất!');
    }

    #[Layout('components.layouts.admin')]
    #[Title('Promotion Detail')]
    public function render()
    {
        $this->updatePromotionStatuses();
        $promotion = Promotion::with([
            'usages.booking.user',
            'usages.booking.foodOrderItems.variant.foodItem',
        ])->findOrFail($this->promotionId);
        return view('livewire.admin.promotions.promotion-detail', compact('promotion'));
    }
}
