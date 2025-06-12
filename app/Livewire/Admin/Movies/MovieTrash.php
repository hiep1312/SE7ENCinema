<?php

namespace App\Livewire\Admin\Movies;

use App\Models\Genre;
use App\Models\Movie;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

class MovieTrash extends Component
{
    use WithPagination;

    public $title = '';
    public $genre_id = '';
    public $deleted_at_from = '';
    public $deleted_at_to = '';
    public $perPage = 10;

    protected $queryString = ['title', 'genre_id', 'deleted_at_from', 'deleted_at_to'];

    public function updatingTitle()
    {
        $this->resetPage();
    }

    public function updatingGenreId()
    {
        $this->resetPage();
    }

    public function updatingDeletedAtFrom()
    {
        $this->resetPage();
    }

    public function updatingDeletedAtTo()
    {
        $this->resetPage();
    }

    public function restore($movieId)
    {
        try {
            $movie = Movie::onlyTrashed()->findOrFail($movieId);
            $movie->restore();
            session()->flash('success', 'Phim đã được khôi phục thành công.');
        } catch (\Exception $e) {
            session()->flash('error', 'Khôi phục phim thất bại: ' . $e->getMessage());
        }
    }

    public function forceDelete($movieId)
    {
        try {
            $movie = Movie::onlyTrashed()->findOrFail($movieId);
            if ($movie->poster) {
                Storage::disk('public')->delete($movie->poster);
            }
            $movie->forceDelete();
            session()->flash('success', 'Xóa phim vĩnh viễn thành công.');
        } catch (\Exception $e) {
            session()->flash('error', 'Xóa vĩnh viễn thất bại: ' . $e->getMessage());
        }
    }

    #[Layout('components.layouts.admin')]
    #[Title('Quản lý phim')]

    public function render()
    {
        $query = Movie::onlyTrashed()->with('genres');

        if ($this->title) {
            $query->where('title', 'like', '%' . $this->title . '%');
        }

        if ($this->genre_id) {
            $query->whereHas('genres', fn($q) => $q->where('genres.id', $this->genre_id));
        }

        if ($this->deleted_at_from) {
            $query->where('deleted_at', '>=', $this->deleted_at_from);
        }

        if ($this->deleted_at_to) {
            $query->where('deleted_at', '<=', $this->deleted_at_to);
        }

        $movies = $query->paginate($this->perPage);
        $genres = Genre::all();

        return view('livewire.admin.movies.movie-trash', [
            'movies' => $movies,
            'genres' => $genres,
        ]);
    }
}