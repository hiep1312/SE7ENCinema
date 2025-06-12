<?php

namespace App\Livewire\Admin\Banners;

use App\Models\Banner;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Poll;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class BannerIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $priorityFilter = null;

    protected $listeners = ['refreshBanners' => '$refresh'];

    public function updateExpiredBanners()
    {
        // Tự động chuyển trạng thái banner hết hạn thành inactive
        Banner::where('status', 'active')
            ->where('end_date', '<', now())
            ->update(['status' => 'inactive']);
    }

    public function deleteBanner(array $status, int $bannerId)
    {
        if(!$status['isConfirmed']) return;

        $banner = Banner::find($bannerId);
        if ($banner) {
            // Xóa file ảnh nếu tồn tại
            if ($banner->image && file_exists(public_path($banner->image))) {
                unlink(public_path($banner->image));
            }

            // Xóa cứng banner
            $banner->delete();
            session()->flash('error', 'Xóa banner thành công!');
        }
    }

    public function toggleStatus(int $bannerId)
    {
        $banner = Banner::find($bannerId);
        if ($banner) {
            $newStatus = $banner->status === 'active' ? 'inactive' : 'active';

            // Kiểm tra nếu banner đã hết hạn thì không cho phép kích hoạt
            if ($newStatus === 'active' && $banner->end_date < now()) {
                session()->flash('error', 'Không thể kích hoạt banner đã hết hạn!');
                return;
            }

            $banner->update(['status' => $newStatus]);
            session()->flash('success', 'Cập nhật trạng thái banner thành công!');
        }
    }

    public function resetFilters()
    {
        $this->reset(['search', 'statusFilter', 'priorityFilter']);
    }

    // Helper method to check banner status
    public function getBannerDisplayStatus($banner)
    {
        if ($banner->end_date < now()) {
            return 'expired';
        } elseif ($banner->start_date > now()) {
            return 'upcoming';
        } elseif ($banner->status === 'active' && $banner->start_date <= now() && $banner->end_date >= now()) {
            return 'active';
        } else {
            return 'inactive';
        }
    }

    public function getPriorityColor($priority)
    {
        if ($priority >= 76) return 'success';
        if ($priority >= 51) return 'warning';
        if ($priority >= 26) return 'info';
        return 'secondary';
    }

    #[Title('Quản lý Banner - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        // Cập nhật trạng thái banner hết hạn trước khi render
        $this->updateExpiredBanners();

        $query = Banner::query();

        // Áp dụng bộ lọc
        if ($this->search) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->priorityFilter !== null && $this->priorityFilter !== '') {
    $query->where('priority', '>=', $this->priorityFilter);
}

// Hoặc nếu bạn muốn filter theo khoảng (range) dựa trên giá trị được chọn
if ($this->priorityFilter !== null && $this->priorityFilter !== '') {
    $priority = (int) $this->priorityFilter;

    // Tạo khoảng +/- 5 điểm xung quanh giá trị được chọn
    $minPriority = max(0, $priority - 5);
    $maxPriority = min(100, $priority + 5);

    $query->whereBetween('priority', [$minPriority, $maxPriority]);
}

// Hoặc filter chính xác theo giá trị
if ($this->priorityFilter !== null && $this->priorityFilter !== '') {
    $query->where('priority', '=', $this->priorityFilter);
}

        $banners = $query
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('livewire.admin.banners.banner-index', compact('banners'));
    }
}
