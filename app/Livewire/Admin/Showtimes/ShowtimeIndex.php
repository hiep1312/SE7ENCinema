<?php

namespace App\Livewire\Admin\Showtimes;

use App\Models\Showtime;
use Carbon\Carbon;
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
    public $priceFilters = '';
    public $priceMaxMin = '';
    public $startTime = [
        'from' => null,
        'to' => null,
    ];
    public function mount()
    {
        $showtimes = Showtime::all();
        $this->priceMaxMin = [$showtimes->min('price'), $showtimes->max('price')];
        $this->priceFilters = $this->priceMaxMin['1'];
    }

    public function deleteShowtime(array $status, int $showtimeId)
    {
        if (!$status['isConfirmed']) return;
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
        $this->priceFilters = $this->priceMaxMin['1'];
        $this->startTime = [
            'from' => null,
            'to' => null,
        ];
        $this->resetPage();
    }

    public function realtimeUpdateShowtimes()
    {
        Showtime::all()->each(function ($showtime) {
            $startTime = $showtime->start_time;
            $endTime = $showtime->end_time;
            if ($endTime->isPast()) $showtime->status = 'completed';
            elseif (($startTime->isFuture() || $endTime->isFuture()) && $showtime->status === 'completed') $showtime->status = 'active';
            $showtime->save();
        });
    }

    #[Title('Danh sách suất chiếu - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        $this->realtimeUpdateShowtimes();

        if ($this->startTime['from']) {
            $this->startTime['from'] = Carbon::parse($this->startTime['from'])->toDateTimeString();
        }

        if ($this->startTime['to']) {
            $this->startTime['to'] = Carbon::parse($this->startTime['to'])->toDateTimeString();
        }

        $query = Showtime::query()->whereHas('movie')
            ->when($this->search, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->whereHas('movie', fn($q) => $q->where('title', 'like', '%' . $this->search . '%'));
                    $subQuery->orWhereHas('room', fn($q) => $q->where('name', 'like', '%' . $this->search . '%'));
                });
            })
            ->when($this->statusFilter, fn($query) => $query->where('status', $this->statusFilter))
            ->when($this->priceFilters, fn($query) => $query->where('price', '<=', $this->priceFilters))
            ->when($this->startTime['from'], fn($query) => $query->where('start_time', '>=', $this->startTime['from']))
            ->when($this->startTime['to'], fn($query) => $query->where('end_time', '<=', $this->startTime['to']));
        $showtimes = $query->orderBy('status', 'asc')->orderBy('start_time', 'asc')->paginate(20);
        return view('livewire.admin.showtimes.showtime-index', compact('showtimes'));
    }
}
