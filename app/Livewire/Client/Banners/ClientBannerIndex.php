<?php

namespace App\Livewire\Client\Banners;

use App\Models\Banner;
use Livewire\Component;
use Livewire\Attributes\On;

class ClientBannerIndex extends Component
{
  public function render()
  {
      $banners = Banner::where('status', 'active')
                       ->where('start_date', '<=', now())
                       ->where('end_date', '>=', now())
                       ->orderBy('priority', 'desc')
                       ->orderBy('created_at', 'desc')
                       ->get();

      return view('livewire.client.template.banners.client-banner-index', compact('banners'));
  }
}
