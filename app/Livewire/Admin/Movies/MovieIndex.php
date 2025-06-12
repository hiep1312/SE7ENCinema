<?php

namespace App\Livewire\Admin\Movies;

use App\Models\Genre;
use App\Models\Movie;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class MovieIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $genreId = '';
    public $status = '';
    public $perPage = 10;

    protected $queryString = ['search', 'genreId', 'status'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingGenreId()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function delete($movieId)
    {
        try {
            $movie = Movie::findOrFail($movieId);
            $movie->delete();
            session()->flash('success', 'Đã chuyển phim vào thùng rác thành công.');
        } catch (\Exception $e) {
            session()->flash('error', 'Xóa phim thất bại: ' . $e->getMessage());
        }
    }

    #[Layout('components.layouts.admin')]
    #[Title('Quản lý phim')]

    public function render()
    {
        $query = Movie::query()->with('genres');

        if ($this->search) {
            $query->where('title', 'like', "%{$this->search}%");
        }

        if ($this->genreId) {
            $query->whereHas('genres', fn($q) => $q->where('genres.id', $this->genreId));
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        $movies = $query->orderBy('created_at', 'desc')->paginate($this->perPage);
        $genres = Genre::all();

        return view('livewire.admin.movies.movie-index', [
            'movies' => $movies,
            'genres' => $genres,
        ]);
    }
}