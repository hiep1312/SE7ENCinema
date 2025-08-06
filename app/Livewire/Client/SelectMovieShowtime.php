<?php

namespace App\Livewire\Client;

use Livewire\Component;
use App\Models\Movie;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class SelectMovieShowtime extends Component
{
    public $movies;

    public function mount()
    {
        $this->movies = Movie::whereIn('status', ['coming_soon', 'showing'])
            ->with(['showtimes' => function ($query) {
                $query->whereIn('status', ['active'])
                    ->orderBy('start_time');
            }])
            ->get();
    }

    public function goToSelectSeats($showtimeId)
    {
        return redirect()->route('client.booking.select_seats', ['showtime_id' => $showtimeId]);
    }

    #[Title('Danh sách phim - SE7ENCinema')]
    #[Layout('components.layouts.client')]
    public function render()
    {
        return view('livewire.client.select-movie-showtime', [
            'movies' => $this->movies,
        ]);
    }
}
