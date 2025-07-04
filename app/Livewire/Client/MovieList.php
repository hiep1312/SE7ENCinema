<?php

namespace App\Livewire\Client;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Movie;
use App\Models\Genre;

class MovieList extends Component
{
    use WithPagination;

    public $tabCurrent = 'coming_soon'; // Đổi từ $activeTab thành $tabCurrent
    public $genreFilter = '';
    public $search = '';
    public $perPage = 9;

    public function setTab($tab)
    {
        $this->tabCurrent = $tab; // Đổi từ $activeTab thành $tabCurrent
        // $this->resetPage();
    }

    public function setGenreFilter($genre)
    {
        $this->genreFilter = $genre;
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Movie::query();

        // Filter by tab
        if ($this->tabCurrent === 'coming_soon') { // Đổi từ $activeTab thành $tabCurrent
            $query->where('release_date', '>', now());
        } elseif ($this->tabCurrent === 'showing') { // Đổi từ $activeTab thành $tabCurrent
            $query->where('release_date', '<=', now())
                  ->where(function ($q) {
                      $q->where('end_date', '>=', now())
                        ->orWhereNull('end_date');
                  });
        } else { // ended
            $query->whereNotNull('end_date')
                  ->where('end_date', '<', now());
        }

        // Filter by genre
        if ($this->genreFilter) {
            $query->whereHas('genres', function ($q) {
                $q->where('name', $this->genreFilter);
            });
        }

        // Search by title or description
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        $movies = $query->orderBy('release_date', 'desc')->paginate($this->perPage);
        $genres = Genre::pluck('name')->sort()->all();

        return view('livewire.client.movie-list', [
            'movies' => $movies,
            'genres' => $genres,
        ]);
    }
}