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
    public $usageFilter = '';
    public $discountTypeFilter = '';

    public function deletePromotion(array $status, int $promotionId)
    {
        if(!$status['isConfirmed']) return;
        $promotion = Promotion::findOrFail($promotionId);

        if ($promotion->usages()->exists() && $promotion->status !== "expired") {
            session()->flash('error', 'Không thể xóa mã giảm giá đã có người sử dụng và chưa hết hạn!');
            return;
        }

        $promotion->delete();
        session()->flash('success', 'Xóa mã giảm giá thành công!');
    }

    public function resetFilters()
    {
        $this->reset(['search', 'statusFilter', 'usageFilter', 'discountTypeFilter']);
        $this->resetPage();
    }

    public function realtimeUpdateStatus()
    {
        Promotion::where('end_date', '>=', now())->where('status', 'expired')->update(['status' => 'active']);
        Promotion::where('end_date', '<', now())->where('status', '!=', 'expired')->update(['status' => 'expired']);
    }

    #[Title('Danh sách mã giảm giá - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        $this->realtimeUpdateStatus();

        $query = Promotion::query()->with('usages.booking.user')->withCount('usages')
            ->when($this->search, function ($query) {
                $query->where('code', 'like', '%' . trim($this->search) . '%')
                    ->orWhere('title', 'like', '%' . trim($this->search) . '%');
            })
            ->when($this->statusFilter, fn($query) => $query->where('status', $this->statusFilter))
            ->when($this->usageFilter !== '', fn($query) => $this->usageFilter ? $query->whereHas('usages') : $query->whereDoesntHave('usages'))
            ->when($this->discountTypeFilter, fn($query) => $query->where('discount_type', $this->discountTypeFilter));

        $promotions= $query->orderByDesc('created_at')->paginate(20);

        return view('livewire.admin.promotions.promotion-index', compact('promotions'));
    }
}
