<?php

namespace App\Livewire\Admin\Movies;

use App\Models\Booking;
use App\Models\Movie;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class MovieDetail extends Component
{
    use WithPagination;
    public $movie;
    public $tabCurrent = 'overview';

    public function mount(int $movie)
    {
        $this->movie = Movie::with('genres', 'ratings')->findOrFail($movie);
    }

    #[Title('Chi tiết phim - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        $recentShowtimes = $this->movie->showtimes()
            ->with('room')
            ->where('start_time', '<=', now())
            ->orderBy('start_time', 'desc')
            ->paginate(10, ['*'], 'recent_showtimes');

        $upcomingShowtimes = $this->movie->showtimes()
            ->with('room')
            ->where('start_time', '>', now())
            ->where('status', 'active')
            ->orderBy('start_time', 'asc')
            ->paginate(10, ['*'], 'upcoming_showtimes');

        $bookings = Booking::whereHas('showtime', function($q){
            $q->where('movie_id', $this->movie->id);
        })->with(['showtime.room', 'foodOrderItems', 'user'])
        ->orderBy('status', 'asc')
        ->orderBy('created_at', 'desc');
        $totalOrdersIn30Days = (clone $bookings)->whereBetween('created_at', [now()->subDays(30), now()])->count();
        $bookings = $bookings->paginate(15);

        $ratings = $this->movie->ratings()->with('user')->orderBy('created_at', 'desc')->paginate(10, ['*'], 'ratings');
        $comments = $this->movie->comments()->with('user')->orderBy('created_at', 'desc')->paginate(10, ['*'], 'comments');

        return view('livewire.admin.movies.movie-detail', compact('recentShowtimes', 'upcomingShowtimes', 'ratings', 'comments', 'bookings', 'totalOrdersIn30Days'));
    }
}
