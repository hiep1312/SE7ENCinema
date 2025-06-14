<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

class Test extends Component
{
    public $temp = "null";
    public $to = false;
    public function aibit($temp){
        $this->js('console.log', $temp);
    }

    public function toggle(){
        $this->to = !$this->to;
    }
    #[Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.test');
    }
}
