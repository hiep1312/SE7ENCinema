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

    public function deleteMovie(array $status, int $movieId)
    {
        if(!$status['isConfirmed']) return;
        $movie = Movie::find($movieId);

        // Kiểm tra xem có suất chiếu đang hoạt động không
        if ($movie->hasActiveShowtimes()) {
            session()->flash('error', 'Không thể xóa phim có suất chiếu trong tương lai!');
            return;
        }

        // Soft delete
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
        if(!$status['isConfirmed']) return;
        $movie = Movie::onlyTrashed()->find($movieId);

        // Kiểm tra quyền xóa cứng
        if ($movie->showtimes()->exists()) {
            session()->flash('error', 'Không thể xóa vĩnh viễn phim có lịch sử suất chiếu!');
            return;
        }

        // Xóa tất cả thể loại trước
        $movie->genres()->detach();

        if(isset($movie->poster) && Storage::disk('public')->exists($movie->poster)) Storage::disk('public')->delete($movie->poster);
        // Xóa cứng phim
        $movie->forceDelete();
        session()->flash('success', 'Xóa vĩnh viễn phim thành công!');
    }

    public function resetFilters()
    {
        $this->reset(['search', 'statusFilter', 'showtimeFilter']);
        $this->resetPage();
    }

    public function updateStatusMoviesAndShowtimes(){
        Movie::all()->each(function($movie){
            $releaseDate = Carbon::parse($movie->release_date);
            $endDate = !$movie->end_date ?: Carbon::parse($movie->end_date);
            if(is_object($endDate) && $endDate->isPast()) $movie->status = 'ended';
            else if($releaseDate->isFuture()) $movie->status = 'coming_soon';
            else $movie->status = 'showing';
            $movie->save();
        });

        Showtime::all()->each(function ($showtime) {
            $startTime = $showtime->start_time;
            $endTime = $showtime->end_time;
            if($endTime->isPast()) $showtime->status = 'completed';
            elseif(($startTime->isFuture() || $endTime->isFuture()) && $showtime->status === 'completed') $showtime->status = 'active';
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
            // Áp dụng các bộ lọc chỉ khi không xem phòng đã xóa
            if ($this->statusFilter) {
                $query->where('status', $this->statusFilter);
            }

            if ($this->showtimeFilter) {
                if ($this->showtimeFilter === 'has_showtimes') {
                    $query->whereHas('showtimes', function($q) {
                        $q->where('start_time', '>=', now())
                          ->where('status', 'active');
                    });
                } elseif ($this->showtimeFilter === 'no_showtimes') {
                    $query->whereDoesntHave('showtimes', function($q) {
                        $q->where('start_time', '>=', now())
                          ->where('status', 'active');
                    });
                }
            }
        }

        $movies = $query
            ->when($this->search, function ($query) {
                $query->withTrashed();
                $query->where('title', 'like', '%' . $this->search . '%');
            })
            ->with(['showtimes' => function($query) {
                $query->with('room')
                    ->where('start_time', '>=', now())
                    ->where('status', 'active')
                    ->orderBy('start_time', 'asc')
                    ->limit(1);
            }])
            ->orderBy('status', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('livewire.admin.movies.movie-index', compact('movies'));
    }
}
