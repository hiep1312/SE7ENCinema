<?php

namespace App\Livewire\Client;

use Livewire\Component;

class ContentPage extends Component
{
    public $selectedCategory = '';
    public $searchKeyword = '';
    public function updated($field)
    {
        $this->resetPage();
    }

    public function render()
    {
        $items = \App\Models\Movie::query()
            ->when($this->selectedCategory, fn($q) => $q->where('category', $this->selectedCategory))
            ->when($this->searchKeyword, fn($q) => $q->where('title', 'like', '%' . $this->searchKeyword . '%'))
            ->get();

        return view('livewire.client.content-page', compact('items'));
    }
}
