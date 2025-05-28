<?php

namespace App\Livewire\Admin\Template;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]

class Basic_element extends Component
{
    public function render()
    {
        return view('livewire.admin.template.pages.forms.basic_elements');
    }
}
