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
use SE7ENCinema\scChart;
use App\Charts\admin\movie\dailyChart;
use App\Charts\admin\movie\ratioChart;
use App\Charts\admin\movie\showtimeChart;

class MovieDetail extends Component
{
    use WithPagination,scChart;
    public $movie;
    public $tabCurrent = 'chart';
    public $fromDate = null;
    public $rangeDays = null;
    public $compareDate = null;
    public $rangeUnit = 'months';

    public function mount(int $movie)
    {
        $this->fromDate = Carbon::now()->subDays(2)->format('Y-m-d');
        $this->rangeDays = 2;
        $this->movie = Movie::with('genres', 'ratings')->findOrFail($movie);
    }
    public function updateStatusMovieAndShowtimes()
    {
        $releaseDate = Carbon::parse($this->movie->release_date);
        $endDate = !$this->movie->end_date ?: Carbon::parse($this->movie->end_date);
        if (is_object($endDate) && $endDate->isPast()) $this->movie->status = 'ended';
        else if ($releaseDate->isFuture()) $this->movie->status = 'coming_soon';
        else $this->movie->status = 'showing';
        $this->movie->save();

        Showtime::where('movie_id', $this->movie->id)->each(function ($showtime) {
            $startTime = $showtime->start_time;
            $endTime = $showtime->end_time;
            if ($endTime->isPast()) $showtime->status = 'completed';
            elseif (($startTime->isFuture() || $endTime->isFuture()) && $showtime->status === 'completed') $showtime->status = 'active';
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

        $bookings = Booking::whereHas('showtime', function ($q) {
            $q->where('movie_id', $this->movie->id);
        })->with(['showtime.room', 'foodOrderItems', 'user'])
            ->orderBy('status', 'asc')
            ->orderBy('created_at', 'desc');

        $dailyChart = new dailyChart($this->movie);
        $showtimeChart = new showtimeChart($this->movie);
        $ratioChart = new ratioChart($this->movie);
        $this->realtimeUpdateCharts(
            [$dailyChart,[$this->fromDate,$this->rangeDays,$this->compareDate,$this->rangeUnit]], 
            [$showtimeChart,[$this->fromDate,$this->rangeDays,$this->compareDate,$this->rangeUnit]], 
            [$ratioChart,[$this->fromDate,$this->rangeDays,$this->rangeUnit]]
        );

        $totalOrdersIn30Days = (clone $bookings)->whereBetween('created_at', [now()->subDays(30), now()])->count();
        $bookings = $bookings->paginate(15);
        $ratings = $this->movie->ratings()->with('user')->orderBy('created_at', 'desc')->paginate(10, ['*'], 'ratings');
        $comments = $this->movie->comments()->with('user')->orderBy('created_at', 'desc')->paginate(10, ['*'], 'comments');

        return view('livewire.admin.movies.movie-detail', compact('recentShowtimes', 'upcomingShowtimes', 'ratings', 'comments', 'bookings', 'totalOrdersIn30Days','dailyChart','ratioChart','showtimeChart'));
    }
}
