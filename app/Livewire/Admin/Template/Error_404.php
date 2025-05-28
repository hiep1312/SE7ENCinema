<?php

namespace App\Livewire\Admin\Template;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]

class Error_404 extends Component
{
    public function render()
    {
        return view('livewire.admin.template.pages.samples.error-404');
    }
}
