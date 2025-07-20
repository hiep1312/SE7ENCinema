<?php

namespace App\Livewire\Admin\Promotions;

use App\Models\Promotion;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class PromotionIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $bookingFilter = '';
    public $discountTypeFilter = '';

    public function deletePromotion(array $status, int $promotionId)
    {
        if (!$status['isConfirmed']) return;

        $promotion = Promotion::findOrFail($promotionId);

        // Kiểm tra nếu khuyến mãi đã được sử dụng
        if ($promotion->status === 'active' && ($promotion->used_count > 0 || $promotion->usages()->exists())) {
            session()->flash('error', 'Không thể xóa khuyến mãi đã được sử dụng!');
            return;
        }

        // Hard delete
        $promotion->delete();
        session()->flash('success', 'Xóa khuyến mãi thành công!');
    }

    public function resetFilters()
    {
        $this->reset(['search', 'statusFilter', 'bookingFilter', 'discountTypeFilter']);
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

    #[Title('Danh sách khuyến mãi - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        $this->updatePromotionStatuses();
        $promotions = Promotion::query()
            ->when($this->search, function ($query) {
                $query->where('code', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->bookingFilter, function ($query) {
                if ($this->bookingFilter === 'has_booking') {
                    $query->whereHas('usages');
                } elseif ($this->bookingFilter === 'no_booking') {
                    $query->whereDoesntHave('usages');
                }
            })
            ->when($this->discountTypeFilter, function ($query) {
                $query->where('discount_type', $this->discountTypeFilter);
            })
            ->with(['usages.booking.user'])
            ->withCount('usages')
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('livewire.admin.promotions.promotion-index', compact('promotions'));
    }
}
