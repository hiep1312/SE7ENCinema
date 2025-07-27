<?php

namespace App\Livewire\Admin\Bookings;

use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\Ticket;
use App\Models\Movie;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use SE7ENCinema\scAlert;

class BookingDetail extends Component
{
    use WithPagination, scAlert;
    public $booking;
    public $tabCurrent = 'information';

    // Chart data properties
    public $revenueData = [];
    public $ticketData = [];
    public $statusData = [];
    public $topMovies = [];

    // Chart periods
    public $revenuePeriod = 'monthly';
    public $ticketPeriod = 'monthly';
    public $statusPeriod = 'monthly';
    public $topMoviesPeriod = 'monthly';

    public function mount(int $booking){
        $this->booking = Booking::with('showtime.movie', 'showtime.room', 'user', 'seats', 'promotionUsages', 'foodOrderItems.variant.foodItem', 'foodOrderItems.variant.attributeValues.attribute')->findOrFail($booking);

        $this->cleanupBookingsAndUpdateData(['isConfirmed' => true]);
        $this->loadChartData();
    }

    public function changeRevenuePeriod($period)
    {
        $this->revenuePeriod = $period;
        $this->loadChartData();
    }

    public function changeTicketPeriod($period)
    {
        $this->ticketPeriod = $period;
        $this->loadChartData();
    }

    public function changeStatusPeriod($period)
    {
        $this->statusPeriod = $period;
        $this->loadChartData();
    }

    public function changeTopMoviesPeriod($period)
    {
        $this->topMoviesPeriod = $period;
        $this->loadChartData();
    }

    public function loadChartData()
    {
        // 1. Doanh thu theo thời gian
        $this->revenueData = $this->getRevenueData($this->revenuePeriod);

        // 2. Vé bán theo thời gian
        $this->ticketData = $this->getTicketData($this->ticketPeriod);

        // 3. Trạng thái đơn hàng theo thời gian
        $this->statusData = $this->getStatusData($this->statusPeriod);

        // 4. Top phim theo thời gian
        $this->topMovies = $this->getTopMoviesData($this->topMoviesPeriod);
    }

    private function getRevenueData($period)
    {
        $query = Booking::where('status', 'paid');

        switch ($period) {
            case 'daily':
                $data = $query->selectRaw('DATE(created_at) as date, SUM(total_price) as revenue, COUNT(*) as bookings')
                    ->whereBetween('created_at', [now()->subDays(30), now()])
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();

                $labels = [];
                $revenueData = [];
                $bookingsData = [];
                $avgRevenueData = [];

                foreach ($data as $item) {
                    $labels[] = $item->date;
                    $revenueData[] = $item->revenue;
                    $bookingsData[] = $item->bookings;
                    $avgRevenueData[] = $item->bookings > 0 ? round($item->revenue / $item->bookings) : 0;
                }

                return [
                    'labels' => $labels,
                    'revenue' => $revenueData,
                    'bookings' => $bookingsData,
                    'avgRevenue' => $avgRevenueData
                ];

            case 'monthly':
                $data = $query->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(total_price) as revenue, COUNT(*) as bookings')
                    ->whereYear('created_at', now()->year)
                    ->groupBy('year', 'month')
                    ->orderBy('year')
                    ->orderBy('month')
                    ->get();

                $labels = [];
                $revenueData = [];
                $bookingsData = [];
                $avgRevenueData = [];

                foreach ($data as $item) {
                    $labels[] = 'Tháng ' . $item->month . '/' . $item->year;
                    $revenueData[] = $item->revenue;
                    $bookingsData[] = $item->bookings;
                    $avgRevenueData[] = $item->bookings > 0 ? round($item->revenue / $item->bookings) : 0;
                }

                return [
                    'labels' => $labels,
                    'revenue' => $revenueData,
                    'bookings' => $bookingsData,
                    'avgRevenue' => $avgRevenueData
                ];

            case 'yearly':
                $data = $query->selectRaw('YEAR(created_at) as year, SUM(total_price) as revenue, COUNT(*) as bookings')
                    ->groupBy('year')
                    ->orderBy('year')
                    ->get();

                $labels = [];
                $revenueData = [];
                $bookingsData = [];
                $avgRevenueData = [];

                foreach ($data as $item) {
                    $labels[] = 'Năm ' . $item->year;
                    $revenueData[] = $item->revenue;
                    $bookingsData[] = $item->bookings;
                    $avgRevenueData[] = $item->bookings > 0 ? round($item->revenue / $item->bookings) : 0;
                }

                return [
                    'labels' => $labels,
                    'revenue' => $revenueData,
                    'bookings' => $bookingsData,
                    'avgRevenue' => $avgRevenueData
                ];
        }
    }

    private function getTicketData($period)
    {
        $query = Ticket::where('status', '!=', 'canceled');

        switch ($period) {
            case 'daily':
                $data = $query->selectRaw('DATE(tickets.created_at) as date, COUNT(*) as tickets, COUNT(DISTINCT booking_seats.booking_id) as bookings')
                    ->join('booking_seats', 'tickets.booking_seat_id', '=', 'booking_seats.id')
                    ->whereBetween('tickets.created_at', [now()->subDays(30), now()])
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();

                $labels = [];
                $ticketsData = [];
                $bookingsData = [];
                $avgTicketsData = [];

                foreach ($data as $item) {
                    $labels[] = $item->date;
                    $ticketsData[] = $item->tickets;
                    $bookingsData[] = $item->bookings;
                    $avgTicketsData[] = $item->bookings > 0 ? round($item->tickets / $item->bookings, 1) : 0;
                }

                return [
                    'labels' => $labels,
                    'tickets' => $ticketsData,
                    'bookings' => $bookingsData,
                    'avgTicketsPerBooking' => $avgTicketsData
                ];

            case 'monthly':
                $data = $query->selectRaw('YEAR(tickets.created_at) as year, MONTH(tickets.created_at) as month, COUNT(*) as tickets, COUNT(DISTINCT booking_seats.booking_id) as bookings')
                    ->join('booking_seats', 'tickets.booking_seat_id', '=', 'booking_seats.id')
                    ->whereYear('tickets.created_at', now()->year)
                    ->groupBy('year', 'month')
                    ->orderBy('year')
                    ->orderBy('month')
                    ->get();

                $labels = [];
                $ticketsData = [];
                $bookingsData = [];
                $avgTicketsData = [];

                foreach ($data as $item) {
                    $labels[] = 'Tháng ' . $item->month . '/' . $item->year;
                    $ticketsData[] = $item->tickets;
                    $bookingsData[] = $item->bookings;
                    $avgTicketsData[] = $item->bookings > 0 ? round($item->tickets / $item->bookings, 1) : 0;
                }

                return [
                    'labels' => $labels,
                    'tickets' => $ticketsData,
                    'bookings' => $bookingsData,
                    'avgTicketsPerBooking' => $avgTicketsData
                ];

            case 'yearly':
                $data = $query->selectRaw('YEAR(tickets.created_at) as year, COUNT(*) as tickets, COUNT(DISTINCT booking_seats.booking_id) as bookings')
                    ->join('booking_seats', 'tickets.booking_seat_id', '=', 'booking_seats.id')
                    ->groupBy('year')
                    ->orderBy('year')
                    ->get();

                $labels = [];
                $ticketsData = [];
                $bookingsData = [];
                $avgTicketsData = [];

                foreach ($data as $item) {
                    $labels[] = 'Năm ' . $item->year;
                    $ticketsData[] = $item->tickets;
                    $bookingsData[] = $item->bookings;
                    $avgTicketsData[] = $item->bookings > 0 ? round($item->tickets / $item->bookings, 1) : 0;
                }

                return [
                    'labels' => $labels,
                    'tickets' => $ticketsData,
                    'bookings' => $bookingsData,
                    'avgTicketsPerBooking' => $avgTicketsData
                ];
        }
    }

    private function getStatusData($period)
    {
        $query = Booking::query();

        switch ($period) {
            case 'daily':
                $data = $query->selectRaw('status, COUNT(*) as count')
                    ->whereBetween('created_at', [now()->subDays(30), now()])
                    ->groupBy('status')
                    ->get();

                $result = ['paid' => 0, 'pending' => 0, 'failed' => 0, 'expired' => 0];
                foreach ($data as $item) {
                    $result[$item->status] = $item->count;
                }
                return $result;

            case 'monthly':
                $data = $query->selectRaw('status, COUNT(*) as count')
                    ->whereYear('created_at', now()->year)
                    ->groupBy('status')
                    ->get();

                $result = ['paid' => 0, 'pending' => 0, 'failed' => 0, 'expired' => 0];
                foreach ($data as $item) {
                    $result[$item->status] = $item->count;
                }
                return $result;

            case 'yearly':
                $data = $query->selectRaw('status, COUNT(*) as count')
                    ->groupBy('status')
                    ->get();

                $result = ['paid' => 0, 'pending' => 0, 'failed' => 0, 'expired' => 0];
                foreach ($data as $item) {
                    $result[$item->status] = $item->count;
                }
                return $result;
        }
    }

    private function getTopMoviesData($period)
    {
        $query = Booking::select('movies.title', DB::raw('SUM(bookings.total_price) as revenue'), DB::raw('COUNT(*) as bookings'))
            ->join('showtimes', 'bookings.showtime_id', '=', 'showtimes.id')
            ->join('movies', 'showtimes.movie_id', '=', 'movies.id')
            ->where('bookings.status', 'paid');

        switch ($period) {
            case 'daily':
                $data = $query->whereBetween('bookings.created_at', [now()->subDays(30), now()])
                    ->groupBy('movies.title')
                    ->orderByDesc('revenue')
                    ->limit(5)
                    ->get();
                break;

            case 'monthly':
                $data = $query->whereYear('bookings.created_at', now()->year)
                    ->groupBy('movies.title')
                    ->orderByDesc('revenue')
                    ->limit(5)
                    ->get();
                break;

            case 'yearly':
                $data = $query->groupBy('movies.title')
                    ->orderByDesc('revenue')
                    ->limit(5)
                    ->get();
                break;
        }

        $labels = [];
        $revenueData = [];
        $bookingsData = [];
        $avgRevenueData = [];

        foreach ($data as $item) {
            $labels[] = $item->title;
            $revenueData[] = $item->revenue;
            $bookingsData[] = $item->bookings;
            $avgRevenueData[] = $item->bookings > 0 ? round($item->revenue / $item->bookings) : 0;
        }

        return [
            'labels' => $labels,
            'revenue' => $revenueData,
            'bookings' => $bookingsData,
            'avgRevenue' => $avgRevenueData
        ];
    }

    public function cleanupBookingsAndUpdateData(?array $status = null){
        if($this->booking->status === 'expired' && ($this->booking->showtime->start_time->addMinutes(-15) <= now() || $this->booking->created_at->addMinutes(30) <= now())){
            if(is_array($status) && isset($status['isConfirmed'])):
                $this->booking->delete();
                return redirect()->route('admin.bookings.index');
            endif;

            $this->scAlert('Đơn hàng này đã bị xóa do hết hạn giữ!', 'Đơn hàng đã bị hệ thống tự động xóa vì đã quá thời gian giữ. Bạn sẽ được chuyển hướng về danh sách đơn hàng!', 'info', 'cleanupBookingsAndSyncShowtimes');
            session()->flash('deleteExpired', true);
        }

        $showtime = $this->booking->showtime;
        $startTime = $showtime->start_time;
        $endTime = $showtime->end_time;
        if($endTime->isPast()) $showtime->status = 'completed';
        elseif(($startTime->isFuture() || $endTime->isFuture()) && $showtime->status === 'completed') $showtime->status = 'active';
        $showtime->save();

        Ticket::with('bookingSeat.booking.showtime')->each(function (Ticket $ticket) {
            $showtime = $ticket->bookingSeat->booking->showtime;
            if($showtime->start_time->isFuture() && $ticket->status === 'canceled') $ticket->status = 'active';
            elseif($showtime->end_time->isPast() && $ticket->status === 'active') $ticket->status = 'canceled';

            $ticket->save();
        });
    }

    #[Title('Chi tiết đơn hàng - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        $tickets = BookingSeat::where('booking_id', $this->booking->id)->with('ticket')->get()->map(fn($bookingSeat) => $bookingSeat->ticket);

        return view('livewire.admin.bookings.booking-detail', compact('tickets'));
    }
}
