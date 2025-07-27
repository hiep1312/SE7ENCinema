<?php

namespace App\Livewire\Admin\Test;

use App\Models\Booking;
use App\Models\Ticket;
use App\Models\Movie;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

class IndexChart extends Component
{
    #[Layout('components.layouts.admin')]
    public $revenueData = [];
    public $ticketData = [];
    public $statusData = [];
    public $topMovies = [];

    public function mount()
    {
        // 1. Tổng doanh thu theo tháng (12 tháng gần nhất)
        $this->revenueData = Booking::selectRaw('MONTH(created_at) as month, SUM(total_price) as revenue')
            ->where('status', 'paid')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('revenue', 'month')
            ->toArray();

        // 2. Số lượng vé bán theo tháng
        $this->ticketData = Ticket::selectRaw('MONTH(created_at) as month, COUNT(*) as tickets')
            ->where('status', '!=', 'canceled')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('tickets', 'month')
            ->toArray();

        // 3. Đơn theo trạng thái
        $this->statusData = Booking::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // 4. Top 5 phim có doanh thu cao nhất
        $this->topMovies = Booking::select('movies.title', DB::raw('SUM(bookings.total_price) as revenue'))
            ->join('showtimes', 'bookings.showtime_id', '=', 'showtimes.id')
            ->join('movies', 'showtimes.movie_id', '=', 'movies.id')
            ->where('bookings.status', 'paid')
            ->groupBy('movies.title')
            ->orderByDesc('revenue')
            ->limit(5)
            ->pluck('revenue', 'title')
            ->toArray();
    }

    public function render()
    {
        return view('livewire.admin.test.index-chart');
    }
}
