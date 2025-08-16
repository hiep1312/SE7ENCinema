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
    public $filterDailyChart = "3_days";

    // public $dailyChart = 'monthly';
    public $checkinChart = '3_days';
    // public $showtimeChart = '3_days';

    public function mount(int $movie)
    {
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
    public function getFromDate($type)
    {
        switch ($type) {
            case '3_days':
                return Carbon::now()->subDays(3);
            case '7_days':
                return Carbon::now()->subDays(7);
            case '15_days':
                return Carbon::now()->subDays(15);
            case '30_days':
                return Carbon::now()->subDays(30);
            case '3_months':
                return Carbon::now()->subMonths(3)->startOfMonth();
            case '6_months':
                return Carbon::now()->subMonths(6)->startOfMonth();
            case '9_months':
                return Carbon::now()->subMonths(9)->startOfMonth();
            case '1_years':
                return Carbon::now()->subYears(1)->startOfYear();
            case '2_years':
                return Carbon::now()->subYears(2)->startOfYear();
            case '3_years':
                return Carbon::now()->subYears(3)->startOfYear();
            case '6_years':
                return Carbon::now()->subYears(6)->startOfYear();
            default:
                return null;
        }
    }
    // chỉnh button lọc theo giá trị
    private function getFilterText($period)
    {
        switch ($period) {
            case '3_days':
                return '3 ngày gần nhất';
            case '7_days':
                return '7 ngày gần nhất';
            case '15_days':
                return '15 ngày gần nhất';
            case '30_days':
                return '30 ngày gần nhất';
            case '3_months':
                return '3 tháng gần nhất';
            case '6_months':
                return '6 tháng gần nhất';
            case '9_months':
                return '9 tháng gần nhất';
            case '1_year':
                return '1 năm gần nhất';
            case '2_years':
                return '2 năm gần nhất';
            case '3_years':
                return '3 năm gần nhất';
            case '6_years':
                return '6 năm gần nhất';
            default:
                return '7 ngày gần nhất';
        }
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

        $bookingChart = Booking::whereHas('showtime', function ($q) {
            $q->where('movie_id', $this->movie->id);
        })->with(['showtime.room'])->get();

        $dailyChart = new dailyChart($this->movie);
        $showtimeChart = new showtimeChart($this->movie);
        $ratioChart = new ratioChart($this->movie);
        $this->realtimeUpdateCharts([$dailyChart, $this->filterDailyChart], [$showtimeChart, null], $ratioChart);
        // CHART Vé đã bán theo ngày
        // Lấy danh sách booking trong 7 ngày gần đây

        // CHART Vé đã bán theo suất chiếu
        // capacity của room
        $showtimes = $bookingChart
            ->pluck('showtime')
            ->unique();
        $fromShowtime = $this->getFromDate(/* $this->showtimeChart */ []);
        if ($fromShowtime) {
            $showtimes = $showtimes->filter(function ($showtime) use ($fromShowtime) {
                return Carbon::parse($showtime->start_time)->gte($fromShowtime);
            })->values();
        }
        $bookingCountFormatted = $showtimes
            ->filter(fn($showtime) => $showtime->room)
            ->map(function ($showtime) use ($bookingChart) {
                $timeKey = Carbon::parse($showtime->start_time)->format('H:i');
                $capacity = $showtime->room->capacity;
                $bookingsOfShowtime = $bookingChart->filter(function ($booking) use ($showtime) {
                    return $booking->showtime->id === $showtime->id;
                });
                return [
                    'timeKey' => $timeKey,
                    'paid' => $bookingsOfShowtime->where('status', 'paid')->count(),
                    'failed' => $bookingsOfShowtime->where('status', 'failed')->count(),
                    'capacity' => $capacity,
                    'revenue'=> $bookingsOfShowtime->where('status', 'paid')->sum('total_price'),
                ];
            })
            ->groupBy('timeKey')
            ->map(function ($items) {
                return [
                    'paid' => $items->sum('paid'),
                    'failed' => $items->sum('failed'),
                    'capacity' => $items->sum('capacity'),
                    'revenue' => $items->sum('revenue'),
                ];
            })
            ->sortKeys();
        // CHART tròn
        $fromCheckinChart = $this->getFromDate($this->checkinChart);
        if ($fromCheckinChart) {
            $totalCount = (clone $bookings)->where('status', 'paid')->where('created_at', '>=', $fromCheckinChart)->count();
            $showtime = (clone $bookingChart)->pluck('showtime')->where('start_time', '>=', $fromCheckinChart)->pluck('room');
            $caps = $showtime->sum('capacity');
            $result = [
                'totalCount' => $totalCount,
                'caps' => $caps,
            ];
        }

        ($this->tabCurrent === "chart" || ($this->js('chartInstances = {}') || false)) && $this->dispatch(
            'updateData',
            $bookingCountFormatted,
            $result,
            [
                'filterShowtimeChart' => $this->getFilterText(/* $this->showtimeChart */[]),
                'checkinFilter' => $this->getFilterText($this->checkinChart),
            ]
        );
        $totalOrdersIn30Days = (clone $bookings)->whereBetween('created_at', [now()->subDays(30), now()])->count();
        $bookings = $bookings->paginate(15);
        $ratings = $this->movie->ratings()->with('user')->orderBy('created_at', 'desc')->paginate(10, ['*'], 'ratings');
        $comments = $this->movie->comments()->with('user')->orderBy('created_at', 'desc')->paginate(10, ['*'], 'comments');

        return view('livewire.admin.movies.movie-detail', compact('recentShowtimes', 'upcomingShowtimes', 'ratings', 'comments', 'bookings', 'totalOrdersIn30Days', 'bookingCountFormatted', 'result','dailyChart','ratioChart','showtimeChart'));
    }
}
