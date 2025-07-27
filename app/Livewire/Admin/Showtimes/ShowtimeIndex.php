<?php

namespace App\Livewire\Admin\Showtimes;

use App\Models\Showtime;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class ShowtimeIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $priceFilter = [];
    public $rangePrice = [];

    public function mount(){
        $showtimes = Showtime::all();
        $this->priceFilter = $this->rangePrice = [$showtimes->min('price'), $showtimes->max('price')];
        $this->js('updateSlider');
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
        $this->reset(['search', 'statusFilter']);
        $this->priceFilter = $this->rangePrice;
        $this->js('resetSlider');
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

        $query = Showtime::query()->whereHas('movie')
            ->when($this->search, function($query) {
                $query->where(function ($subQuery){
                    $subQuery->whereHas('movie', fn($q) => $q->where('title', 'like', '%' . $this->search . '%'));
                    $subQuery->orWhereHas('room', fn($q) => $q->where('name', 'like', '%' . $this->search . '%'));
                });
            })
            ->when($this->statusFilter, fn($query) => $query->where('status', $this->statusFilter))
            ->when($this->priceFilter, fn($query) => $query->whereBetween('price', $this->priceFilter));

        $showtimes = $query->orderBy('status', 'asc')->orderBy('start_time', 'asc')->paginate(20);

        return view('livewire.admin.showtimes.showtime-index', compact('showtimes'));
    }
}
