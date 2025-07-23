<?php

namespace App\Livewire\Admin\Genres;

use App\Models\Genre;
use App\Models\Movie;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class GenreIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $movieFilter = '';
    public $showAllMovies = [];

    public function deleteGenre(array $status, int $genreId)
    {
        if(!$status['isConfirmed']) return;
        $genre = Genre::find($genreId);

        // Xóa tất cả phim trước
        $genre->movies()->detach();

        $genre->delete();
        session()->flash('success', 'Xóa thể loại thành công!');
    }

    public function resetFilters()
    {
        $this->reset(['search', 'movieFilter']);
        $this->resetPage();
    }

    #[Title('Danh sách thể loại - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        $query = Genre::with('movies')->when($this->search, function($query) {
            $query->where(function($subQuery){
                $subQuery->whereLike('name', '%' . trim($this->search) . '%')
                    ->orWhereLike('description', '%' . trim($this->search) . '%');
            });
        });

        $movies = Movie::select('id', 'title')->whereIn('id', $query->get()->flatMap(fn($genre) => $genre->movies->pluck('id'))->unique())->get();
        $query->when($this->movieFilter, fn($query) => $query->whereHas('movies', fn($q) => $q->where('movies.id', $this->movieFilter)));
        $genres = $query->orderByDesc('created_at')->paginate(20);

        return view('livewire.admin.genres.genre-index', compact('genres', 'movies'));
    }
}
