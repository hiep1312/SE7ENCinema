<?php

namespace App\Livewire\Client\MovieBooking;

use Livewire\Component;
use App\Models\Showtime;
use App\Models\Movie;
use App\Models\Booking;
use Carbon\Carbon;

class MovieBooking extends Component
{
    public $showtime;
    public $movie;
    public $showTrailerModal = false;
    public $selectedDate;
    public $dates = [];
    public $showtimesForDate = [];
    public $activeDateTab = 0;

    public function mount($movie_id)
    {
        $this->movie = Movie::with(['genres'])->findOrFail($movie_id);

        // Tạo danh sách ngày (4 ngày từ hôm nay)
        $this->dates = collect(range(0, 3))->map(function ($day) {
            return Carbon::now()->addDays($day);
        });

        $this->selectedDate = $this->dates[0]->format('Y-m-d');
        $this->activeDateTab = 0;

        $this->loadShowtimesForDate();
    }

    public function selectDate($date, $tabIndex)
    {
        $this->selectedDate = $date;
        $this->activeDateTab = $tabIndex;
        $this->loadShowtimesForDate();
    }

    public function loadShowtimesForDate()
    {
        $selectedDate = Carbon::parse($this->selectedDate);

        $showtimes = Showtime::with(['room'])
            ->where('movie_id', $this->movie->id)
            ->whereDate('start_time', $selectedDate)
            ->where('status', 'active')
            ->orderBy('start_time')
            ->get();

        // Tính toán số ghế còn trống
        foreach ($showtimes as $showtime) {
            $bookedSeatsCount = Booking::where('showtime_id', $showtime->id)
                ->where('status', 'paid')
                ->withCount('seats')
                ->get()
                ->sum('seats_count');

            $totalSeats = $showtime->room->capacity ?? 0;
            $availableSeats = $totalSeats - $bookedSeatsCount;

            $showtime->available_seats = max(0, $availableSeats);
        }

        // Lọc chỉ những suất chiếu trong tương lai và có ghế trống
        $this->showtimesForDate = $showtimes->filter(function ($showtime) {
            return $showtime->start_time->gt(now()) && $showtime->available_seats > 0;
        })->values();
    }

    public function bookShowtime($showtimeId)
    {
        $showtime = Showtime::find($showtimeId);

        if (!$showtime || $showtime->start_time->lte(now())) {
            $this->dispatch('_scToast', [
                [
                    'title' => 'Suất chiếu này đã hết hạn!',
                    'icon' => 'error',
                    'timer' => 3000,
                    'timerProgressBar' => true
                ],
                null
            ]);
            return;
        }

        // Redirect đến trang booking với showtime_id (Cái này để tạm Thằng QUÂN THÊM LINK ĐẶT GHẾ VÀO ĐÂY)
        return redirect()->route('client.booking.select_seats', ['showtime_id' => $showtime->id]);
    }

    public function openTrailerModal()
    {
        $this->showTrailerModal = true;
    }

    public function closeTrailerModal()
    {
        $this->showTrailerModal = false;
    }

    public function render()
    {
        return view('livewire.client.movie-booking.movie-booking');
    }
}
