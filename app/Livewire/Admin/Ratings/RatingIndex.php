<?php

namespace App\Livewire\Admin\Ratings;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Rating;
use App\Models\Movie;

class RatingIndex extends Component
{
    use WithPagination;

    public $countsStar = [];
    public $search = '';
    public $starFilter = '';
    public $movieFilter = '';

    public function resetFilters()
    {
        $this->reset(['search', 'movieFilter', 'starFilter']);
        $this->resetPage();
    }

    public function softDeleteRating(array $status, int $id)
    {
        if (!$status['isConfirmed']) return;

        $rating = Rating::findOrFail($id);

        if ($rating->delete()) {
            session()->flash('success', 'Xóa bài đánh giá thành công!');
        } else {
            session()->flash('error', 'Xóa bài đánh giá không thành công!');
        }
    }

    public function restoreRating(array $status, int $id)
    {
        if (!$status['isConfirmed']) return;

        $rating = Rating::onlyTrashed()->findOrFail($id);

        if ($rating->restore()) {
            session()->flash('success', 'Khôi phục bài đánh giá thành công!');
        } else {
            session()->flash('error', 'Khôi phục bài đánh giá không thành công!');
        }
    }

    #[Title('Danh sách đánh giá - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        $subQuerySearch = function ($query) {
            $query->where(function($query){
                $query->where('review', 'like', '%' . $this->search . '%')
                ->orWhereHas('user', fn ($query) => $query->where('name', 'like', '%' . $this->search . '%'));
            });
        };
        $subQueryMovieFilter = function ($query) {
            $query->whereHas('movie', function ($query) {
                $query->where('id', '=', $this->movieFilter);
            });
        };

        // Đếm số đánh giá theo người dùng và theo phim
        foreach (range(1, 5) as $score) {
            $this->countsStar[$score] = Rating::with('user')
                ->where('score', $score)
                ->when($this->movieFilter, $subQueryMovieFilter)
                ->when($this->search, $subQuerySearch)->count();
        }

        $query = Rating::withTrashed()->with('movie', 'user')
            ->when($this->search, $subQuerySearch)
            ->where('score', 'like', '%' . $this->starFilter . '%');
        $movies = Movie::select('title', 'id')
            ->whereIn('id', $query->pluck('movie_id')->unique())
            ->get();
        $query->when($this->movieFilter, $subQueryMovieFilter);

        $ratings = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('livewire.admin.ratings.rating-index', compact('ratings', 'movies'));
    }
}
