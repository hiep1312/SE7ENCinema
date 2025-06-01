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

    public function updateStatus($movieId, $status)
    {
        $movie = Movie::findOrFail($movieId);
        $today = now()->startOfDay();
        $releaseDate = \Carbon\Carbon::parse($movie->release_date);
        $endDate = $movie->end_date ? \Carbon\Carbon::parse($movie->end_date) : null;

        if ($status == 'coming_soon' && $releaseDate->lte($today)) {
            session()->flash('error', 'Không thể đặt trạng thái "Sắp chiếu" cho phim đã đến ngày phát hành hoặc đã chiếu.');
            return;
        }

        if ($status == 'showing') {
            if ($releaseDate->gt($today)) {
                session()->flash('error', 'Không thể đặt trạng thái "Đang chiếu" cho phim chưa đến ngày phát hành.');
                return;
            }
            if ($endDate && $endDate->lt($today)) {
                session()->flash('error', 'Không thể đặt trạng thái "Đang chiếu" cho phim đã kết thúc thời gian chiếu.');
                return;
            }
        }

        if ($status == 'ended') {
            if (!$endDate || $endDate->gte($today)) {
                session()->flash('error', 'Không thể đặt trạng thái "Đã kết thúc" khi phim vẫn đang trong thời gian chiếu.');
                return;
            }
        }

        $movie->update(['status' => $status]);
        session()->flash('success', 'Cập nhật trạng thái thành công');
    }

    public function delete($movieId)
    {
        $movie = Movie::findOrFail($movieId);
        $movie->delete();
        session()->flash('success', 'Đã xóa phim thành công.');
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