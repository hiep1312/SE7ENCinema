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
    public $activeDate = null;

    public function setActiveDate(string $date): void
    {
        $this->activeDate = $date;
    }

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

        if($this->sortByDate) {
            $query->where('start_time', '>=', now()->subDays($this->sortByDate)->startOfDay())
                  ->where('start_time', '<', now()->startOfDay());
        } else {
            $query->where('start_time', '>=', now()->startOfDay());
        }

        $showtimes = $query->orderBy('start_time', 'asc')->orderBy('status', 'asc')->get()->groupBy(['show_date', 'movie_id']);

        $showtimes = $showtimes->sortKeys();

        if (!$this->sortByDate || $this->sortByDate === '' || $this->sortByDate === '0') {
            $limitedShowtimes = collect();
            $dateCount = 0;
            foreach ($showtimes as $date => $movies) {
                if ($dateCount < 7) {
                    $limitedShowtimes->put($date, $movies);
                    $dateCount++;
                } else {
                    break;
                }
            }
            $showtimes = $limitedShowtimes;
        }

        $dateKeys = $showtimes->keys();
        if ($dateKeys->isNotEmpty()) {
            $today = now()->toDateString();
            if ($this->sortByDate && $this->sortByDate !== '' && $this->sortByDate !== '0') {
                $this->activeDate = $dateKeys->first();
            } else {
                if ($this->activeDate === null) {
                    $this->activeDate = $dateKeys->contains($today) ? $today : $dateKeys->first();
                } elseif (!$dateKeys->contains($this->activeDate)) {
                    $this->activeDate = $dateKeys->contains($today) ? $today : $dateKeys->first();
                }
            }
        } else {
            $this->activeDate = null;
        }

        return view('livewire.admin.showtimes.showtime-index', compact('showtimes'));
    }
}
