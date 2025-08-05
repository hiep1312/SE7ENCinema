<?php

namespace App\Livewire\Admin\Movies;

use App\Models\Movie;
use App\Models\Showtime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class MovieIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $showDeleted = false;
    public $statusFilter = '';
    public $showtimeFilter = '';
    public $durationFilter = '';
    public $priceFilter = '';
    public $releaseDateFilter = [
        'from' => null,
        'to' => null,
    ];
    public $durationMaxMin = '';
    public $priceMaxMin = '';
    public $releaseDateMin = '';

    public function mount()
    {
        $this->durationMaxMin = ['min' => Movie::min('duration'), 'max' => Movie::max('duration')];
        $this->priceMaxMin = ['min' => Movie::min('price'), 'max' => Movie::max('price')];
        $this->releaseDateMin = Carbon::parse(Movie::min('release_date'))->year;
        $this->durationFilter = $this->durationMaxMin['max'];
        $this->priceFilter = $this->priceMaxMin['max'];
    }
    public function deleteMovie(array $status, int $movieId)
    {
        if (!$status['isConfirmed']) return;
        $movie = Movie::find($movieId);

        if ($movie->hasActiveShowtimes()) {
            session()->flash('error', 'Không thể xóa phim có suất chiếu trong tương lai!');
            return;
        }

        $movie->delete();
        session()->flash('success', 'Xóa phim thành công!');
    }

    public function restoreMovie(int $movieId)
    {
        $movie = Movie::onlyTrashed()->find($movieId);

        $movie->restore();
        session()->flash('success', 'Khôi phục phim thành công!');
    }

    public function forceDeleteMovie(array $status, int $movieId)
    {
        if (!$status['isConfirmed']) return;
        $movie = Movie::onlyTrashed()->find($movieId);

        // Kiểm tra quyền xóa cứng
        if ($movie->showtimes()->exists()) {
            session()->flash('error', 'Không thể xóa vĩnh viễn phim có lịch sử suất chiếu!');
            return;
        }

        // Xóa tất cả thể loại trước
        $movie->genres()->detach();

        if (isset($movie->poster) && Storage::disk('public')->exists($movie->poster)) Storage::disk('public')->delete($movie->poster);
        // Xóa cứng phim
        $movie->forceDelete();
        session()->flash('success', 'Xóa vĩnh viễn phim thành công!');
    }

    public function resetFilters()
    {
        $this->reset(['search', 'statusFilter', 'showtimeFilter', 'releaseDateFilter']);
        $this->durationFilter = $this->durationMaxMin['max'];
        $this->priceFilter = $this->priceMaxMin['max'];
        $this->resetPage();
    }

    public function updateStatusMoviesAndShowtimes()
    {
        Movie::all()->each(function ($movie) {
            $releaseDate = Carbon::parse($movie->release_date);
            $endDate = !$movie->end_date ?: Carbon::parse($movie->end_date);
            if (is_object($endDate) && $endDate->isPast()) $movie->status = 'ended';
            else if ($releaseDate->isFuture()) $movie->status = 'coming_soon';
            else $movie->status = 'showing';
            $movie->save();
        });

        Showtime::all()->each(function ($showtime) {
            $startTime = $showtime->start_time;
            $endTime = $showtime->end_time;
            if ($endTime->isPast()) $showtime->status = 'completed';
            elseif (($startTime->isFuture() || $endTime->isFuture()) && $showtime->status === 'completed') $showtime->status = 'active';
            $showtime->save();
        });
    }

    #[Title('Danh sách phim - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        $this->updateStatusMoviesAndShowtimes();

        $query = Movie::query();

        if ($this->showDeleted) {
            $query = Movie::onlyTrashed();
        } else {
            $fnShowtimeFilter = function ($q) {
                $q->where('start_time', '>=', now())
                    ->where('status', 'active');
            };

            $query->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })->when($this->showtimeFilter, function ($query) use ($fnShowtimeFilter) {
                $this->showtimeFilter === 'has_showtimes'
                    ? $query->whereHas('showtimes', $fnShowtimeFilter)
                    : $query->whereDoesntHave('showtimes', $fnShowtimeFilter);
            });
        }
        $query->when($this->durationFilter, function ($query) {
            $query->where('duration', '<=', $this->durationFilter);
        });
        $query->when($this->priceFilter, function ($query) {
            $query->where('price', '<=', $this->priceFilter);
        });

        $query->when($this->releaseDateFilter['from'], function ($query) {
            $query->whereYear('release_date', '>=', $this->releaseDateFilter['from']);
        });

        $query->when($this->releaseDateFilter['to'], function ($query) {
            $query->whereYear('end_date', '<=', $this->releaseDateFilter['to']);
        });


        $movies = $query->when($this->search, function ($query) {
            $query->withTrashed();
            $query->where('title', 'like', '%' . $this->search . '%')
                ->orWhereHas('genres', function ($gq) {
                    $gq->where('name', 'like', '%' . $this->search . '%');
                });
        })
            ->with(['showtimes' => function ($query) {
                $query->with('room')
                    ->where('start_time', '>=', now())
                    ->where('status', 'active')
                    ->orderBy('start_time', 'asc')
                    ->limit(1);
            }])
            ->orderBy('status', 'asc')
            ->orderByDesc('created_at')
            ->paginate(20);
        return view('livewire.admin.movies.movie-index', compact('movies'));
    }
}
