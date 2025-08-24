<?php

namespace App\Livewire\Client\Showtime;

use Livewire\Component;
use App\Models\Movie;
use App\Models\Booking;
use Carbon\Carbon;
use App\Models\Showtime;
class ShowtimeIndex extends Component
{
    public $selectedDate;
    public $dates = [];
    public $moviesWithShowtimes = [];
    public $activeDateTab = 0;

    public function mount()
    {
        $this->dates = collect(range(0, 3))->map(function ($day) {
            return Carbon::now()->addDays($day);
        });

        $this->selectedDate = $this->dates[0]->format('Y-m-d');
        $this->activeDateTab = 0;

        $this->loadMoviesForDate();
    }

    public function selectDate($date, $tabIndex)
    {
        $this->selectedDate = $date;
        $this->activeDateTab = $tabIndex;
        $this->loadMoviesForDate();
    }

    public function loadMoviesForDate()
    {
        $selectedDate = Carbon::parse($this->selectedDate);
        $movies = Movie::with(['showtimes' => function ($query) use ($selectedDate) {
            $query->whereDate('start_time', $selectedDate)
                ->where('status', 'active')
                ->orderBy('start_time');
        }, 'showtimes.room', 'genres'])
            ->whereHas('showtimes', function ($query) use ($selectedDate) {
                $query->whereDate('start_time', $selectedDate)
                    ->where('status', 'active');
            })
            ->where('status', 'showing')
            ->where('age_restriction', '!=', 'C')
            ->orderBy('title')
            ->get();

        $this->calculateAvailableSeatsForCollection($movies);

        $this->moviesWithShowtimes = $movies->filter(function ($movie) {
            $validShowtimes = $movie->showtimes->filter(function ($showtime) {
                return $showtime->start_time->gt(now()) &&
                    isset($showtime->available_seats) &&
                    $showtime->available_seats > 0;
            });

            $movie->showtimes = $validShowtimes->values();
            return $validShowtimes->count() > 0;
        })->values();
    }

    private function calculateAvailableSeatsForCollection($movies)
    {
        foreach ($movies as $movie) {
            foreach ($movie->showtimes as $showtime) {
                $bookedSeatsCount = Booking::where('showtime_id', $showtime->id)
                    ->where('status', 'paid')
                    ->withCount('seats')
                    ->get()
                    ->sum('seats_count');

                $totalSeats = $showtime->room->capacity ?? 0;
                $availableSeats = $totalSeats - $bookedSeatsCount;

                $showtime->available_seats = max(0, $availableSeats);
            }
        }
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

        $this->dispatch('_scToast', [
            [
                'title' => 'Chuyển hướng đến trang đặt vé...',
                'icon' => 'info',
                'timer' => 2000,
                'timerProgressBar' => true
            ],
            null
        ]);

        return redirect()->route('client.booking.select_seats', ['showtime_id' => $showtime->id]);
    }


    public function render()
    {
        return view('livewire.client.showtime.showtime-index');
    }
}
