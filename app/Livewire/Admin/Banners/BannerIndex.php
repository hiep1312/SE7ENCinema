<?php

namespace App\Livewire\Admin\Banners;

use App\Models\Banner;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class BannerIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $priorityFilter = [0, 100];

    public function mount(){
        $this->js('updateSlider');
    }

    public function deleteBanner(array $status, int $bannerId)
    {
        if (!$status['isConfirmed']) return;

        $banner = Banner::onlyTrashed()->find($bannerId);

        // Kiểm tra image, nếu có thì xóa
        !Storage::disk('public')->exists($banner->image) ?: Storage::disk('public')->delete($banner->image);

        $banner->delete();
        session()->flash('success', 'Xóa banner thành công!');
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
        } else {
            session()->flash('error', 'Không tìm thấy banner cần cập nhật!');
        }
    }

    public function resetFilters()
    {
        $this->reset(['search', 'statusFilter', 'priorityFilter']);
        $this->resetPage();
        $this->js('resetSlider');
    }

    #[Title('Quản lý Banner - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        $query = Banner::query()
            ->when($this->search, function ($query) {
                $query->where(function($subQuery) {
                    $subQuery->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('link', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, fn($query) => $query->where('status', $this->statusFilter))
            ->where('priority', '>=', $this->priorityFilter[0])
            ->where('priority', '<=', $this->priorityFilter[1])
            ->orderBy('priority', 'desc')->orderBy('created_at', 'desc');

        $displayStatuses = array_map(function($banner){
            $banner['displayStatus'] = ((isset($banner['end_date']) && $banner['end_date'] < now()) ? 'expired' : (($banner['start_date'] > now() && $banner['status'] === 'active') ? 'upcoming' : ($banner['status'] === 'active' ? 'active' : 'inactive')));
            return $banner;
        }, (clone $query)->get(['start_date', 'end_date', 'status'])->toArray());

        $banners = $query->paginate(20);

        return view('livewire.admin.banners.banner-index', compact('banners', 'displayStatuses'));
    }
}
