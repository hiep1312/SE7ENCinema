<?php

namespace App\Livewire\Admin\Food;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\FoodItem;

class FoodList extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';
    public string $sortDate = 'desc'; // 'desc' (mới nhất) hoặc 'asc' (cũ nhất)

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'sortDate' => ['except' => 'desc'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingSortDate()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = FoodItem::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $foodItems = $query
            ->orderBy('created_at', $this->sortDate)
            ->paginate(10);

        return view('livewire.admin.food.food-list', compact('foodItems'));
    }
}
