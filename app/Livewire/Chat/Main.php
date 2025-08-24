<?php

namespace App\Livewire\chat;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class Main extends Component
{

    #[Title('Chat - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {

        return view('livewire.chat.main');
    }
}
