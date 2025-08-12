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
        $this->reset(['search', 'statusFilter']);
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

        $query = Showtime::select('*', DB::raw('DATE(start_time) as show_date'))->whereHas('movie')
            ->when($this->search, function($query) {
                $query->where(function ($subQuery){
                    $subQuery->whereHas('movie', fn($q) => $q->where('title', 'like', '%' . $this->search . '%'))
                        ->orWhereHas('room', fn($q) => $q->where('name', 'like', '%' . $this->search . '%'));
                });
            })
            ->when($this->statusFilter, fn($query) => $query->where('status', $this->statusFilter))
            ->groupBy('show_date', 'id');

        if($this->sortByDate) $query->where('start_time', '>=', now()->subDays($this->sortByDate));
        else $query->where('start_time', '>=', now()->startOfDay());

        $showtimes = $query->orderBy('start_time', 'asc')->orderBy('status', 'asc')->get();
        dd($showtimes->toArray());

        return view('livewire.admin.showtimes.showtime-index', compact('showtimes'));
    }
}
