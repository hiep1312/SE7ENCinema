<?php

namespace App\Livewire\Client;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Movie;
use Livewire\Attributes\Layout; 
use Livewire\Attributes\Title;


class MovieList extends Component
{
    use WithPagination;

    public $activeTab = 'coming_soon'; // 'coming_soon' | 'showing'
    public $statusFilter = '';
    public $search = '';
    public $perPage = 8;

    protected $queryString = ['activeTab', 'statusFilter', 'search'];

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }


    public function render()
    {
        $query = Movie::query();

        // Lọc theo tab
        if ($this->activeTab === 'coming_soon') {
            $query->where('release_date', '>', now());
        } elseif ($this->activeTab === 'showing') {
            $query->where('release_date', '<=', now())->where('end_date', '>=', now());
        }

        // Lọc theo trạng thái tùy chọn
        if ($this->statusFilter !== '') {
            $query->where('status', $this->statusFilter);
        }

        // Tìm kiếm tiêu đề phim
        if (!empty($this->search)) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }

        $movies = $query->orderBy('release_date')->paginate($this->perPage);

        return view('livewire.client.movie-list', [
            'movies' => $movies
        ]);
    }
}
