<?php

namespace App\Livewire\Admin\Scanner;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Index extends Component
{
    #[Title('Quét QR - SE7ENCinema')]
    #[Layout('livewire.admin.scanner.layout')]
    public function render()
    {
        return view('livewire.admin.scanner.index');
    }
}
