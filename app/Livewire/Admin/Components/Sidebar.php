<?php

namespace App\Livewire\Admin\Components;
use Livewire\Attributes\Layout;
use Livewire\Component;


#[Layout('components.layouts.admin')]

class Sidebar extends Component
{
    public function render()
    {
        return view('livewire.admin.components.sidebar');
    }
}
