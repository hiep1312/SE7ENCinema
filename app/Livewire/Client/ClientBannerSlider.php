<?php

namespace App\Livewire\Client;

use App\Models\Banner;
use Livewire\Component;
use Carbon\Carbon;

class ClientBannerSlider extends Component
{
    public $banners;

    public function mount()
    {
        // Lấy các banner hoạt động, trong khoảng thời gian hợp lệ, sắp xếp theo độ ưu tiên và ngày tạo
        $this->banners = Banner::where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.client.template.client-banner-slider');
    }
}
