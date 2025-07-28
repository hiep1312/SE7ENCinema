<?php

namespace App\Livewire;

use App\Models\Room;
use App\Models\Booking;
use App\Models\Showtime;
use App\Models\Ticket;
use App\Models\BookingSeat;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Test extends Component
{
    // Dữ liệu cho các biểu đồ
    public $todayTicketsData = [];
    public $occupancyRateData = [];
    public $ticketStatusData = [];
    public $roomRevenueData = [];
    public $topMoviesData = [];

    // Periods for charts
    public $ticketsPeriod = 'today';
    public $occupancyPeriod = 'today';
    public $statusPeriod = 'today';
    public $revenuePeriod = 'monthly';
    public $moviesPeriod = 'monthly';

    // Thống kê tổng quan
    public $totalRooms = 0;
    public $totalCapacity = 0;
    public $todayRevenue = 0;
    public $todayTickets = 0;

    public function mount()
    {
        $this->calculateOverallStatistics();
        $this->loadChartData();
    }

    public function changeTicketsPeriod($period)
    {
        $this->ticketsPeriod = $period;
        $this->loadChartData();
    }

    public function changeOccupancyPeriod($period)
    {
        $this->occupancyPeriod = $period;
        $this->loadChartData();
    }

    public function changeStatusPeriod($period)
    {
        $this->statusPeriod = $period;
        $this->loadChartData();
    }

    public function changeRevenuePeriod($period)
    {
        $this->revenuePeriod = $period;
        $this->loadChartData();
    }

    public function changeMoviesPeriod($period)
    {
        $this->moviesPeriod = $period;
        $this->loadChartData();
    }

    protected function calculateOverallStatistics()
    {
        $this->totalRooms = Room::count();
        $this->totalCapacity = Room::sum('capacity');

        // Doanh thu hôm nay
        $this->todayRevenue = Booking::join('showtimes', 'bookings.showtime_id', '=', 'showtimes.id')
            ->join('rooms', 'showtimes.room_id', '=', 'rooms.id')
            ->where('bookings.status', 'paid')
            ->whereDate('bookings.created_at', today())
            ->sum('bookings.total_price');

        // Số vé bán hôm nay
        $this->todayTickets = BookingSeat::join('bookings', 'booking_seats.booking_id', '=', 'bookings.id')
            ->join('showtimes', 'bookings.showtime_id', '=', 'showtimes.id')
            ->where('bookings.status', 'paid')
            ->whereDate('bookings.created_at', today())
            ->count();
    }

    public function loadChartData()
    {
        // 1. Tổng số vé đã bán theo từng phòng
        $this->todayTicketsData = $this->getTodayTicketsData($this->ticketsPeriod);

        // 2. Tỷ lệ lấp đầy từng phòng
        $this->occupancyRateData = $this->getOccupancyData($this->occupancyPeriod);

        // 3. Số vé đã check-in / chưa check-in / đã hủy
        $this->ticketStatusData = $this->getTicketStatusData($this->statusPeriod);

        // 4. Doanh thu từng phòng
        $this->roomRevenueData = $this->getRoomRevenueData($this->revenuePeriod);

        // 5. Top phim được xem nhiều nhất
        $this->topMoviesData = $this->getTopMoviesData($this->moviesPeriod);
    }

    private function getTodayTicketsData($period)
    {
        $query = BookingSeat::join('bookings', 'booking_seats.booking_id', '=', 'bookings.id')
            ->join('showtimes', 'bookings.showtime_id', '=', 'showtimes.id')
            ->join('rooms', 'showtimes.room_id', '=', 'rooms.id')
            ->where('bookings.status', 'paid');

        switch ($period) {
            case 'today':
                $query->whereDate('bookings.created_at', today());
                break;
            case 'week':
                $query->whereBetween('bookings.created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('bookings.created_at', now()->month)
                      ->whereYear('bookings.created_at', now()->year);
                break;
        }

        return $query->select('rooms.name', DB::raw('COUNT(booking_seats.id) as tickets_sold'))
            ->groupBy('rooms.id', 'rooms.name')
            ->orderBy('tickets_sold', 'desc')
            ->pluck('tickets_sold', 'name')
            ->toArray();
    }

    private function getOccupancyData($period)
    {
        $dateCondition = match($period) {
            'today' => ['bookings.created_at', '>=', today()],
            'week' => ['bookings.created_at', '>=', now()->startOfWeek()],
            'month' => ['bookings.created_at', '>=', now()->startOfMonth()],
            default => ['bookings.created_at', '>=', today()]
        };

        $occupancyData = Room::select(
                'rooms.name',
                'rooms.capacity',
                DB::raw('COALESCE(COUNT(booking_seats.id), 0) as total_booked')
            )
            ->leftJoin('showtimes', 'rooms.id', '=', 'showtimes.room_id')
            ->leftJoin('bookings', function($join) use ($dateCondition) {
                $join->on('showtimes.id', '=', 'bookings.showtime_id')
                     ->where('bookings.status', '=', 'paid')
                     ->where($dateCondition[0], $dateCondition[1], $dateCondition[2]);
            })
            ->leftJoin('booking_seats', 'bookings.id', '=', 'booking_seats.booking_id')
            ->whereNull('rooms.deleted_at')
            ->groupBy('rooms.id', 'rooms.name', 'rooms.capacity')
            ->get();

        return $occupancyData->mapWithKeys(function ($room) {
            // Tính tỷ lệ lấp đầy dựa trên tổng số suất chiếu của phòng
            $totalShowtimes = Showtime::where('room_id', $room->id)->count();
            $maxPossibleSeats = $room->capacity * $totalShowtimes;

            $rate = $maxPossibleSeats > 0 ? round(($room->total_booked / $maxPossibleSeats) * 100, 1) : 0;
            return [$room->name => $rate];
        })->toArray();
    }

    private function getTicketStatusData($period)
    {
        $dateCondition = match($period) {
            'today' => ['tickets.created_at', '>=', today()],
            'week' => ['tickets.created_at', '>=', now()->startOfWeek()],
            'month' => ['tickets.created_at', '>=', now()->startOfMonth()],
            default => ['tickets.created_at', '>=', today()]
        };

        $statusData = Ticket::join('booking_seats', 'tickets.booking_seat_id', '=', 'booking_seats.id')
            ->join('bookings', 'booking_seats.booking_id', '=', 'bookings.id')
            ->where('bookings.status', 'paid')
            ->where($dateCondition[0], $dateCondition[1], $dateCondition[2])
            ->select(
                DB::raw('SUM(CASE WHEN tickets.taken = 1 THEN 1 ELSE 0 END) as checked_in'),
                DB::raw('SUM(CASE WHEN tickets.taken = 0 AND tickets.status = "active" THEN 1 ELSE 0 END) as not_checked_in'),
                DB::raw('SUM(CASE WHEN tickets.status = "canceled" THEN 1 ELSE 0 END) as canceled')
            )
            ->first();

        return [
            'checked_in' => $statusData->checked_in ?? 0,
            'not_checked_in' => $statusData->not_checked_in ?? 0,
            'canceled' => $statusData->canceled ?? 0
        ];
    }

    private function getRoomRevenueData($period)
    {
        $query = Booking::select('rooms.name', DB::raw('SUM(bookings.total_price) as revenue'))
            ->join('showtimes', 'bookings.showtime_id', '=', 'showtimes.id')
            ->join('rooms', 'showtimes.room_id', '=', 'rooms.id')
            ->where('bookings.status', 'paid')
            ->whereNull('rooms.deleted_at');

        switch ($period) {
            case 'daily':
                $query->whereBetween('bookings.created_at', [now()->subDays(30), now()]);
                break;
            case 'monthly':
                $query->whereYear('bookings.created_at', now()->year);
                break;
            case 'yearly':
                $query->where('bookings.created_at', '>=', now()->subYears(2));
                break;
        }

        return $query->groupBy('rooms.id', 'rooms.name')
            ->orderByDesc('revenue')
            ->pluck('revenue', 'name')
            ->toArray();
    }

    private function getTopMoviesData($period)
    {
        $query = Booking::select('movies.title', DB::raw('COUNT(booking_seats.id) as tickets_sold'), DB::raw('SUM(bookings.total_price) as revenue'))
            ->join('showtimes', 'bookings.showtime_id', '=', 'showtimes.id')
            ->join('movies', 'showtimes.movie_id', '=', 'movies.id')
            ->join('booking_seats', 'bookings.id', '=', 'booking_seats.booking_id')
            ->where('bookings.status', 'paid');

        switch ($period) {
            case 'daily':
                $query->whereBetween('bookings.created_at', [now()->subDays(30), now()]);
                break;
            case 'monthly':
                $query->whereYear('bookings.created_at', now()->year);
                break;
            case 'yearly':
                $query->where('bookings.created_at', '>=', now()->subYears(2));
                break;
        }

        $data = $query->groupBy('movies.id', 'movies.title')
            ->orderByDesc('tickets_sold')
            ->limit(10)
            ->get();

        $labels = [];
        $ticketsData = [];
        $revenueData = [];

        foreach ($data as $item) {
            $labels[] = $item->title;
            $ticketsData[] = $item->tickets_sold;
            $revenueData[] = $item->revenue;
        }

        return [
            'labels' => $labels,
            'tickets' => $ticketsData,
            'revenue' => $revenueData
        ];
    }

    #[Title('Phân tích phòng chiếu - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.test.test');
    }
}
