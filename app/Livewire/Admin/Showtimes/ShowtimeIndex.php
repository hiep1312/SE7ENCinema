<?php

namespace App\Livewire\Admin\Showtimes;

use App\Models\Showtime;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class ShowtimeIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $sortByDate = '';
    public $activeDate = '';

    public function deleteShowtime(array $status, int $showtimeId)
    {
        if(!$status['isConfirmed']) return;
        $showtime = Showtime::find($showtimeId);

        if ($showtime->isLockedForDeletion()) {
            session()->flash('error', 'Không thể xóa suất chiếu sẽ diễn ra trong vòng 1 giờ tới hoặc đang diễn ra, hoặc đã có người đặt vé!');
            return;
        }

        $showtime->delete();
        session()->flash('success', 'Xóa suất chiếu thành công!');
    }

    public function resetFilters()
    {
        $this->reset(['search', 'statusFilter', 'sortByDate']);
        $this->resetPage();
    }

    public function setActiveDate($date)
    {
        $this->activeDate = $date;
    }

    public function realtimeUpdateShowtimes(){
        Showtime::all()->each(function ($showtime) {
            $startTime = $showtime->start_time;
            $endTime = $showtime->end_time;
            if($endTime->isPast()) $showtime->status = 'completed';
            elseif(($startTime->isFuture() || $endTime->isFuture()) && $showtime->status === 'completed') $showtime->status = 'active';
            $showtime->save();
        });
    }

    #[Title('Danh sách suất chiếu - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        $this->realtimeUpdateShowtimes();

        $query = Showtime::with('movie', 'room')->whereHas('movie')
            ->when($this->search, function($query) {
                $query->where(function ($subQuery){
                    $subQuery->whereHas('movie', fn($q) => $q->where('title', 'like', '%' . $this->search . '%'))
                        ->orWhereHas('room', fn($q) => $q->where('name', 'like', '%' . $this->search . '%'));
                });
            })
            ->when($this->statusFilter, fn($query) => $query->where('status', $this->statusFilter))
            ->select('*', DB::raw('DATE(start_time) as show_date'));

        // Sửa logic lọc ngày
        if($this->sortByDate) {
            // Lọc từ quá khứ đến hôm nay
            $query->where('start_time', '>=', now()->subDays($this->sortByDate))
                  ->where('start_time', '<=', now()->endOfDay());
        } else {
            // Hiển thị từ hôm nay đến 6 ngày tiếp theo (có suất chiếu)
            $query->where('start_time', '>=', now()->startOfDay());
        }

        $showtimes = $query->orderBy('start_time', 'asc')
                          ->orderBy('status', 'asc')
                          ->paginate(30)
                          ->groupBy(['show_date', 'movie_id']);

        // Nếu không có sortByDate, giới hạn hiển thị 7 ngày (từ hôm nay)
        if (!$this->sortByDate && $showtimes->isNotEmpty()) {
            $limitedShowtimes = collect();
            $dayCount = 0;
            $maxDays = 7;

            foreach ($showtimes as $date => $movies) {
                if ($dayCount >= $maxDays) break;
                $limitedShowtimes[$date] = $movies;
                $dayCount++;
            }
            $showtimes = $limitedShowtimes;
        }

        // Set active date to first date if not set
        if (empty($this->activeDate) && $showtimes->isNotEmpty()) {
            $this->activeDate = $showtimes->keys()->first();
        }

        return view('livewire.admin.showtimes.showtime-index', compact('showtimes'));
    }
}
