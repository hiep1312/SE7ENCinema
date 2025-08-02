<?php

namespace App\Livewire\Client\Components;

use Livewire\Component;

class Header extends Component
{

    public $searchKeyword = '';
    public $selectedCategory = '';

    public function updated($field)
    {
        $this->dispatch('filterChanged', $this->selectedCategory, $this->searchKeyword);
    }

    public function render()
    {
        return view('livewire.client.components.header');
    }
}
