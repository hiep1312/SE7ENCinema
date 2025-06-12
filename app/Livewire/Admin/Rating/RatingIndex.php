<?php

namespace App\Livewire\Admin\Rating;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Rating;
use App\Models\Movie;

class RatingIndex extends Component
{
    use WithPagination;
    public $scores = [1, 2, 3, 4, 5];
    public $counts = [];
    public $search = '';
    public $starFilter = '';
    public $movieFilter = '';
    // showdelete để bao giờ cần thì làm khôi phục
    public $showDeleted = '';
    public function realtimeCheckOperation()
    {
        $this->ratings = Rating::with(['movie', 'user'])
            ->when($this->search, function ($query) {
                $query->whereHas('user', function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->search, function ($query) {
                $query->whereHas('movie', function ($query) {
                    $query->where('title', 'like', '%' . $this->search . '%');
                });
            })
            ->where('score', 'like', '%' . $this->starFilter . '%');
    }
    public function resetFilters()
    {
        $this->reset(['search', 'movieFilter', 'starFilter']);
    }
    public function softDelete(array $status, int $rating_id)
    {
        if (!$status['isConfirmed'])
            return;
        $rating = Rating::findOrFail($rating_id);
        if ($rating->delete()) {
            session()->flash('success', 'Xóa đánh giá thành công!');
        } else {
            session()->flash('success', 'Xóa đánh giá không thành công!');
        }
    }
    #[Title('Danh sách đánh giá - SE7ENCinema')]
    #[Layout('components.layouts.admin')]

    public function render()
    {
        // đếm số đánh giá theo người dùng và theo phim
        foreach ($this->scores as $score) {
            $this->counts[$score] = Rating::with(['user'])
                ->where('score', $score)
                ->when(
                    $this->movieFilter,
                    fn($query) =>
                    $query->where('movie_id', $this->movieFilter)
                )
                ->when(
                    $this->search,
                    fn($query) =>
                    $query->whereHas(
                        'user',
                        fn($query) =>
                        $query->where('name', 'like', '%' . $this->search . '%')
                    )
                )->count();
        }
        $movies = Movie::select('title', 'id')->get();
        $query = Rating::with(['movie', 'user'])
            ->when($this->search, function ($query) {
                $query->whereHas('user', function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->movieFilter, function ($query) {
                $query->whereHas('movie', function ($query) {
                    $query->where('id', '=', $this->movieFilter);
                });
            })
            ->where('score', 'like', '%' . $this->starFilter . '%');
        if ($this->showDeleted)
            $query->onlyTrashed();
        $ratings = $query->orderBy($this->showDeleted ? 'deleted_at' : 'created_at', 'desc')->paginate(15);
        return view('livewire.admin.rating.rating-index', compact('ratings', 'movies'));
    }
}
