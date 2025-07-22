<?php

namespace App\Livewire\Admin\Movies;

use App\Models\Booking;
use App\Models\Movie;
use App\Models\Showtime;
use Carbon\Carbon;
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

    public function updateStatusMovieAndShowtimes(){
        $releaseDate = Carbon::parse($this->movie->release_date);
        $endDate = !$this->movie->end_date ?: Carbon::parse($this->movie->end_date);
        if(is_object($endDate) && $endDate->isPast()) $this->movie->status = 'ended';
        else if($releaseDate->isFuture()) $this->movie->status = 'coming_soon';
        else $this->movie->status = 'showing';
        $this->movie->save();

        Showtime::where('movie_id', $this->movie->id)->each(function ($showtime) {
            $startTime = $showtime->start_time;
            $endTime = $showtime->end_time;
            if($endTime->isPast()) $showtime->status = 'completed';
            elseif(($startTime->isFuture() || $endTime->isFuture()) && $showtime->status === 'completed') $showtime->status = 'active';
            $showtime->save();
        });
    }

    #[Title('Chi tiết phim - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        $this->updateStatusMovieAndShowtimes();

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
