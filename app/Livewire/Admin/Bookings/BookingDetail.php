<?php

namespace App\Livewire\Admin\Bookings;

use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\FoodOrderItem;
use App\Models\Ticket;
use App\Models\Movie;
use App\Models\FoodItem;
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
    // public $ticketData = [];
    // public $statusData = [];
    public $topMovies = [];
    public $seatsData = [];
    public $foodsData = [];
    public $topFoods = [];
    public $topMoviesAndRooms = [];

    // Chart periods
    public $revenuePeriod = '7_days';
    // public $ticketPeriod = 'monthly';
    // public $statusPeriod = 'monthly';
    public $topMoviesPeriod = '7_days';
    public $seatsPeriod = '7_days';
    public $foodsPeriod = '7_days';
    public $topFoodsPeriod = '7_days';

    // Filter options for charts - không cần nữa vì dùng khoảng thời gian cố định
    // public $availableYears = [], $availableMonths = [];
    // public $revenueYear, $revenueMonth;
    // public $ticketYear, $ticketMonth, $ticketDay;
    // public $statusYear, $statusMonth, $statusDay;
    // public $topMoviesYear, $topMoviesMonth;

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

    public function changeTopMoviesPeriod($period)
    {
        $this->topMoviesPeriod = $period;
        $this->loadChartData();
    }

    public function changeSeatsPeriod($period)
    {
        $this->seatsPeriod = $period;
        $this->loadChartData();
    }

    public function changeFoodsPeriod($period)
    {
        $this->foodsPeriod = $period;
        $this->loadChartData();
    }

    public function changeTopFoodsPeriod($period)
    {
        $this->topFoodsPeriod = $period;
        $this->loadChartData();
    }

    public function loadChartData()
    {
        // 1. Doanh thu theo thời gian
        $this->revenueData = $this->getRevenueData($this->revenuePeriod);

        // 2. Top phim + vé bán + đơn hàng + TB doanh thu/đơn + TB vé/đơn
        $this->topMovies = $this->getTopMoviesData($this->topMoviesPeriod);

        // 3. Dữ liệu ghế theo thời gian
        $this->seatsData = $this->getSeatsData($this->seatsPeriod);

        // 4. Dữ liệu món ăn theo thời gian
        $this->foodsData = $this->getFoodsData($this->foodsPeriod);

        // 5. Top món ăn được đặt nhiều nhất
        $this->topFoods = $this->getTopFoodsByPeriod($this->topFoodsPeriod);
    }

    private function getRevenueData($period)
    {
        $query = Booking::where('status', 'paid');

        switch ($period) {
            case '3_days':
                // Lấy dữ liệu 3 ngày gần nhất (bao gồm hôm nay)
                $startDate = now()->subDays(2)->startOfDay();
                $endDate = now()->endOfDay();

                $data = $query->selectRaw('DATE(created_at) as date, SUM(total_price) as revenue, COUNT(*) as bookings')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();

                // Tạo dữ liệu cho tất cả 3 ngày, kể cả ngày không có dữ liệu
                $labels = [];
                $revenueData = [];
                $bookingsData = [];
                $avgRevenueData = [];

                for ($i = 0; $i < 3; $i++) {
                    $currentDate = now()->subDays(2 - $i)->format('Y-m-d');
                    $dateLabel = now()->subDays(2 - $i)->format('d/m');

                    $dayData = $data->where('date', $currentDate)->first();

                    $labels[] = $dateLabel;
                    $revenueData[] = $dayData ? $dayData->revenue : 0;
                    $bookingsData[] = $dayData ? $dayData->bookings : 0;
                    $avgRevenueData[] = $dayData && $dayData->bookings > 0 ? round($dayData->revenue / $dayData->bookings) : 0;
                }

                return [
                    'labels' => $labels,
                    'revenue' => $revenueData,
                    'bookings' => $bookingsData,
                    'avgRevenue' => $avgRevenueData
                ];

            case '7_days':
                // Lấy dữ liệu 7 ngày gần nhất (bao gồm hôm nay)
                $startDate = now()->subDays(6)->startOfDay();
                $endDate = now()->endOfDay();

                $data = $query->selectRaw('DATE(created_at) as date, SUM(total_price) as revenue, COUNT(*) as bookings')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();

                // Tạo dữ liệu cho tất cả 7 ngày
                $labels = [];
                $revenueData = [];
                $bookingsData = [];
                $avgRevenueData = [];

                for ($i = 0; $i < 7; $i++) {
                    $currentDate = now()->subDays(6 - $i)->format('Y-m-d');
                    $dateLabel = now()->subDays(6 - $i)->format('d/m');

                    $dayData = $data->where('date', $currentDate)->first();

                    $labels[] = $dateLabel;
                    $revenueData[] = $dayData ? $dayData->revenue : 0;
                    $bookingsData[] = $dayData ? $dayData->bookings : 0;
                    $avgRevenueData[] = $dayData && $dayData->bookings > 0 ? round($dayData->revenue / $dayData->bookings) : 0;
                }

                return [
                    'labels' => $labels,
                    'revenue' => $revenueData,
                    'bookings' => $bookingsData,
                    'avgRevenue' => $avgRevenueData
                ];

            case '15_days':
                // Lấy dữ liệu 15 ngày gần nhất (bao gồm hôm nay)
                $startDate = now()->subDays(14)->startOfDay();
                $endDate = now()->endOfDay();

                $data = $query->selectRaw('DATE(created_at) as date, SUM(total_price) as revenue, COUNT(*) as bookings')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();

                // Tạo dữ liệu cho tất cả 15 ngày
                $labels = [];
                $revenueData = [];
                $bookingsData = [];
                $avgRevenueData = [];

                for ($i = 0; $i < 15; $i++) {
                    $currentDate = now()->subDays(14 - $i)->format('Y-m-d');
                    $dateLabel = now()->subDays(14 - $i)->format('d/m');

                    $dayData = $data->where('date', $currentDate)->first();

                    $labels[] = $dateLabel;
                    $revenueData[] = $dayData ? $dayData->revenue : 0;
                    $bookingsData[] = $dayData ? $dayData->bookings : 0;
                    $avgRevenueData[] = $dayData && $dayData->bookings > 0 ? round($dayData->revenue / $dayData->bookings) : 0;
                }

                return [
                    'labels' => $labels,
                    'revenue' => $revenueData,
                    'bookings' => $bookingsData,
                    'avgRevenue' => $avgRevenueData
                ];

            case '30_days':
                // Lấy dữ liệu 30 ngày gần nhất (bao gồm hôm nay)
                $startDate = now()->subDays(29)->startOfDay();
                $endDate = now()->endOfDay();

                $data = $query->selectRaw('DATE(created_at) as date, SUM(total_price) as revenue, COUNT(*) as bookings')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();

                // Tạo dữ liệu cho tất cả 30 ngày
                $labels = [];
                $revenueData = [];
                $bookingsData = [];
                $avgRevenueData = [];

                for ($i = 0; $i < 30; $i++) {
                    $currentDate = now()->subDays(29 - $i)->format('Y-m-d');
                    $dateLabel = now()->subDays(29 - $i)->format('d/m');

                    $dayData = $data->where('date', $currentDate)->first();

                    $labels[] = $dateLabel;
                    $revenueData[] = $dayData ? $dayData->revenue : 0;
                    $bookingsData[] = $dayData ? $dayData->bookings : 0;
                    $avgRevenueData[] = $dayData && $dayData->bookings > 0 ? round($dayData->revenue / $dayData->bookings) : 0;
                }

                return [
                    'labels' => $labels,
                    'revenue' => $revenueData,
                    'bookings' => $bookingsData,
                    'avgRevenue' => $avgRevenueData
                ];

            case '3_months':
                // Lấy dữ liệu 3 tháng gần nhất
                $startDate = now()->subMonths(2)->startOfDay();
                $endDate = now()->endOfDay();

                $data = $query->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(total_price) as revenue, COUNT(*) as bookings')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->groupBy('year', 'month')
                    ->orderBy('year')
                    ->orderBy('month')
                    ->get();

                $labels = [];
                $revenueData = [];
                $bookingsData = [];
                $avgRevenueData = [];

                foreach ($data as $item) {
                    $labels[] = 'T' . $item->month . '/' . $item->year;
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

            case '6_months':
                // Lấy dữ liệu 6 tháng gần nhất
                $startDate = now()->subMonths(5)->startOfDay();
                $endDate = now()->endOfDay();

                $data = $query->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(total_price) as revenue, COUNT(*) as bookings')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->groupBy('year', 'month')
                    ->orderBy('year')
                    ->orderBy('month')
                    ->get();

                $labels = [];
                $revenueData = [];
                $bookingsData = [];
                $avgRevenueData = [];

                foreach ($data as $item) {
                    $labels[] = 'T' . $item->month . '/' . $item->year;
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

            case '9_months':
                // Lấy dữ liệu 9 tháng gần nhất
                $startDate = now()->subMonths(8)->startOfDay();
                $endDate = now()->endOfDay();

                $data = $query->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(total_price) as revenue, COUNT(*) as bookings')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->groupBy('year', 'month')
                    ->orderBy('year')
                    ->orderBy('month')
                    ->get();

                $labels = [];
                $revenueData = [];
                $bookingsData = [];
                $avgRevenueData = [];

                foreach ($data as $item) {
                    $labels[] = 'T' . $item->month . '/' . $item->year;
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

            case '1_year':
                // Lấy dữ liệu 1 năm gần nhất (12 tháng)
                $startDate = now()->subYear()->startOfDay();
                $endDate = now()->endOfDay();

                $data = $query->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(total_price) as revenue, COUNT(*) as bookings')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->groupBy('year', 'month')
                    ->orderBy('year')
                    ->orderBy('month')
                    ->get();

                $labels = [];
                $revenueData = [];
                $bookingsData = [];
                $avgRevenueData = [];

                foreach ($data as $item) {
                    $labels[] = 'T' . $item->month . '/' . $item->year;
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

            case '2_years':
                // Lấy dữ liệu 2 năm gần nhất
                $startDate = now()->subYears(1)->startOfDay();
                $endDate = now()->endOfDay();

                $data = $query->selectRaw('YEAR(created_at) as year, SUM(total_price) as revenue, COUNT(*) as bookings')
                    ->whereBetween('created_at', [$startDate, $endDate])
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

    private function getTopMoviesData($period)
    {
        $query = Booking::select('movies.title',
            DB::raw('SUM(bookings.total_price) as revenue'),
            DB::raw('COUNT(*) as bookings'),
            DB::raw('SUM(booking_seats_count) as tickets')
        )
            ->join('showtimes', 'bookings.showtime_id', '=', 'showtimes.id')
            ->join('movies', 'showtimes.movie_id', '=', 'movies.id')
            ->where('bookings.status', 'paid')
            ->join(DB::raw('(SELECT booking_id, COUNT(*) as booking_seats_count FROM booking_seats GROUP BY booking_id) as seat_counts'), 'bookings.id', '=', 'seat_counts.booking_id');

        switch ($period) {
            case '3_days':
                // Lấy top phim 3 ngày gần nhất (bao gồm hôm nay)
                $startDate = now()->subDays(2)->startOfDay();
                $endDate = now()->endOfDay();
                $data = $query->whereBetween('bookings.created_at', [$startDate, $endDate])
                    ->groupBy('movies.title')
                    ->orderByDesc('revenue')
                    ->limit(5)
                    ->get();
                break;
            case '7_days':
                // Lấy top phim 7 ngày gần nhất (bao gồm hôm nay)
                $startDate = now()->subDays(6)->startOfDay();
                $endDate = now()->endOfDay();
                $data = $query->whereBetween('bookings.created_at', [$startDate, $endDate])
                    ->groupBy('movies.title')
                    ->orderByDesc('revenue')
                    ->limit(5)
                    ->get();
                break;
            case '30_days':
                // Lấy top phim 30 ngày gần nhất (bao gồm hôm nay)
                $startDate = now()->subDays(29)->startOfDay();
                $endDate = now()->endOfDay();
                $data = $query->whereBetween('bookings.created_at', [$startDate, $endDate])
                    ->groupBy('movies.title')
                    ->orderByDesc('revenue')
                    ->limit(5)
                    ->get();
                break;
            case '3_months':
                // Lấy top phim 3 tháng gần nhất
                $startDate = now()->subMonths(2)->startOfDay();
                $endDate = now()->endOfDay();
                $data = $query->whereBetween('bookings.created_at', [$startDate, $endDate])
                    ->groupBy('movies.title')
                    ->orderByDesc('revenue')
                    ->limit(5)
                    ->get();
                break;
            case '6_months':
                // Lấy top phim 6 tháng gần nhất
                $startDate = now()->subMonths(5)->startOfDay();
                $endDate = now()->endOfDay();
                $data = $query->whereBetween('bookings.created_at', [$startDate, $endDate])
                    ->groupBy('movies.title')
                    ->orderByDesc('revenue')
                    ->limit(5)
                    ->get();
                break;
            case '9_months':
                // Lấy top phim 9 tháng gần nhất
                $startDate = now()->subMonths(8)->startOfDay();
                $endDate = now()->endOfDay();
                $data = $query->whereBetween('bookings.created_at', [$startDate, $endDate])
                    ->groupBy('movies.title')
                    ->orderByDesc('revenue')
                    ->limit(5)
                    ->get();
                break;
            case '1_year':
                // Lấy top phim 1 năm gần nhất (12 tháng)
                $startDate = now()->subYear()->startOfDay();
                $endDate = now()->endOfDay();
                $data = $query->whereBetween('bookings.created_at', [$startDate, $endDate])
                    ->groupBy('movies.title')
                    ->orderByDesc('revenue')
                    ->limit(5)
                    ->get();
                break;
            case '2_years':
                // Lấy top phim 2 năm gần nhất
                $startDate = now()->subYears(1)->startOfDay();
                $endDate = now()->endOfDay();
                $data = $query->whereBetween('bookings.created_at', [$startDate, $endDate])
                    ->groupBy('movies.title')
                    ->orderByDesc('revenue')
                    ->limit(5)
                    ->get();
                break;
            case '15_days':
                // Lấy top phim 15 ngày gần nhất (bao gồm hôm nay)
                $startDate = now()->subDays(14)->startOfDay();
                $endDate = now()->endOfDay();
                $data = $query->whereBetween('bookings.created_at', [$startDate, $endDate])
                    ->groupBy('movies.title')
                    ->orderByDesc('revenue')
                    ->limit(5)
                    ->get();
                break;
            case '3_years':
                // Lấy top phim 3 năm gần nhất
                $startDate = now()->subYears(2)->startOfDay();
                $endDate = now()->endOfDay();
                $data = $query->whereBetween('bookings.created_at', [$startDate, $endDate])
                    ->groupBy('movies.title')
                    ->orderByDesc('revenue')
                    ->limit(5)
                    ->get();
                break;
            case '6_years':
                // Lấy top phim 6 năm gần nhất
                $startDate = now()->subYears(5)->startOfDay();
                $endDate = now()->endOfDay();
                $data = $query->whereBetween('bookings.created_at', [$startDate, $endDate])
                    ->groupBy('movies.title')
                    ->orderByDesc('revenue')
                    ->limit(5)
                    ->get();
                break;
            default:
                $startDate = now()->subDays(6)->startOfDay();
                $endDate = now()->endOfDay();
                $data = $query->whereBetween('bookings.created_at', [$startDate, $endDate])
                    ->groupBy('movies.title')
                    ->orderByDesc('revenue')
                    ->limit(5)
                    ->get();
                break;
        }

        $labels = [];
        $revenueData = [];
        $ticketsData = [];
        $bookingsData = [];

        foreach ($data as $item) {
            $labels[] = $item->title;
            $revenueData[] = $item->revenue;
            $ticketsData[] = $item->tickets;
            $bookingsData[] = $item->bookings;
        }

        return [
            'labels' => $labels,
            'revenue' => $revenueData,
            'tickets' => $ticketsData,
            'bookings' => $bookingsData,
        ];
    }

    private function getSeatsData($period)
    {
        $query = Booking::select(
            DB::raw('DATE(bookings.created_at) as date'),
            DB::raw('COUNT(DISTINCT bookings.id) as total_bookings'),
            DB::raw('SUM(booking_seats_count) as total_seats'),
            DB::raw('AVG(booking_seats_count) as avg_seats_per_booking'),
            DB::raw('COUNT(DISTINCT showtimes.movie_id) as total_movies'),
            DB::raw('COUNT(DISTINCT showtimes.room_id) as total_rooms')
        )
            ->join(DB::raw('(SELECT booking_id, COUNT(*) as booking_seats_count FROM booking_seats GROUP BY booking_id) as seat_counts'), 'bookings.id', '=', 'seat_counts.booking_id')
            ->join('showtimes', 'bookings.showtime_id', '=', 'showtimes.id')
            ->where('bookings.status', 'paid');

        switch ($period) {
            case '3_days':
                $startDate = now()->subDays(2)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case '7_days':
                $startDate = now()->subDays(6)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case '30_days':
                $startDate = now()->subDays(29)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case '3_months':
                $startDate = now()->subMonths(2)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case '6_months':
                $startDate = now()->subMonths(5)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case '9_months':
                $startDate = now()->subMonths(8)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case '1_year':
                $startDate = now()->subYear()->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case '2_years':
                $startDate = now()->subYears(1)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case '15_days':
                $startDate = now()->subDays(14)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case '3_years':
                $startDate = now()->subYears(2)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case '6_years':
                $startDate = now()->subYears(5)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            default:
                $startDate = now()->subDays(6)->startOfDay();
                $endDate = now()->endOfDay();
                break;
        }

        $data = $query->whereBetween('bookings.created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = [];
        $totalSeatsData = [];
        $totalBookingsData = [];
        $avgSeatsData = [];
        $totalMoviesData = [];
        $totalRoomsData = [];

        foreach ($data as $item) {
            $labels[] = date('d/m', strtotime($item->date));
            $totalSeatsData[] = $item->total_seats;
            $totalBookingsData[] = $item->total_bookings;
            $avgSeatsData[] = round($item->avg_seats_per_booking, 1);
            $totalMoviesData[] = $item->total_movies;
            $totalRoomsData[] = $item->total_rooms;
        }

        return [
            'labels' => $labels,
            'totalSeats' => $totalSeatsData,
            'totalBookings' => $totalBookingsData,
            'avgSeats' => $avgSeatsData,
            'totalMovies' => $totalMoviesData,
            'totalRooms' => $totalRoomsData,
        ];
    }

    private function getFoodsData($period)
    {
        $query = Booking::select(
            DB::raw('DATE(bookings.created_at) as date'),
            DB::raw('COUNT(DISTINCT bookings.id) as total_bookings'),
            DB::raw('SUM(food_orders_count) as total_food_orders'),
            DB::raw('SUM(food_items_count) as total_food_items'),
            DB::raw('SUM(food_revenue) as total_food_revenue'),
            DB::raw('AVG(food_orders_count) as avg_food_orders_per_booking'),
            DB::raw('AVG(food_items_count) as avg_food_items_per_booking'),
            DB::raw('AVG(food_revenue) as avg_food_revenue_per_booking')
        )
            ->leftJoin(DB::raw('(SELECT booking_id, COUNT(*) as food_orders_count, SUM(quantity) as food_items_count, SUM(price * quantity) as food_revenue FROM food_order_items GROUP BY booking_id) as food_counts'), 'bookings.id', '=', 'food_counts.booking_id')
            ->where('bookings.status', 'paid');

        switch ($period) {
            case '3_days':
                $startDate = now()->subDays(2)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case '7_days':
                $startDate = now()->subDays(6)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case '30_days':
                $startDate = now()->subDays(29)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case '3_months':
                $startDate = now()->subMonths(2)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case '6_months':
                $startDate = now()->subMonths(5)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case '9_months':
                $startDate = now()->subMonths(8)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case '1_year':
                $startDate = now()->subYear()->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case '2_years':
                $startDate = now()->subYears(1)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case '15_days':
                $startDate = now()->subDays(14)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case '3_years':
                $startDate = now()->subYears(2)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case '6_years':
                $startDate = now()->subYears(5)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            default:
                $startDate = now()->subDays(6)->startOfDay();
                $endDate = now()->endOfDay();
                break;
        }

        $data = $query->whereBetween('bookings.created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = [];
        $totalFoodOrdersData = [];
        $totalFoodItemsData = [];
        $totalFoodRevenueData = [];
        $avgFoodOrdersData = [];
        $avgFoodItemsData = [];
        $avgFoodRevenueData = [];

        foreach ($data as $item) {
            $labels[] = date('d/m', strtotime($item->date));
            $totalFoodOrdersData[] = $item->total_food_orders ?? 0;
            $totalFoodItemsData[] = $item->total_food_items ?? 0;
            $totalFoodRevenueData[] = $item->total_food_revenue ?? 0;
            $avgFoodOrdersData[] = round($item->avg_food_orders_per_booking ?? 0, 1);
            $avgFoodItemsData[] = round($item->avg_food_items_per_booking ?? 0, 1);
            $avgFoodRevenueData[] = round($item->avg_food_revenue_per_booking ?? 0, 0);
        }

        return [
            'labels' => $labels,
            'totalFoodOrders' => $totalFoodOrdersData,
            'totalFoodItems' => $totalFoodItemsData,
            'totalFoodRevenue' => $totalFoodRevenueData,
            'avgFoodOrders' => $avgFoodOrdersData,
            'avgFoodItems' => $avgFoodItemsData,
            'avgFoodRevenue' => $avgFoodRevenueData,
        ];
    }

    private function getTopFoodsByPeriod($period)
    {
        $query = FoodOrderItem::select(
            'food_items.name as food_name',
            DB::raw('SUM(food_order_items.quantity) as total_quantity'),
            DB::raw('SUM(food_order_items.price * food_order_items.quantity) as total_revenue'),
            DB::raw('COUNT(DISTINCT food_order_items.booking_id) as total_bookings')
        )
            ->join('food_variants', 'food_order_items.food_variant_id', '=', 'food_variants.id')
            ->join('food_items', 'food_variants.food_item_id', '=', 'food_items.id')
            ->join('bookings', 'food_order_items.booking_id', '=', 'bookings.id')
            ->where('bookings.status', 'paid');

        switch ($period) {
            case '3_days':
                $startDate = now()->subDays(2)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case '7_days':
                $startDate = now()->subDays(6)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case '30_days':
                $startDate = now()->subDays(29)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case '3_months':
                $startDate = now()->subMonths(2)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case '6_months':
                $startDate = now()->subMonths(5)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case '9_months':
                $startDate = now()->subMonths(8)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case '1_year':
                $startDate = now()->subYear()->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case '2_years':
                $startDate = now()->subYears(1)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case '15_days':
                $startDate = now()->subDays(14)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case '3_years':
                $startDate = now()->subYears(2)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case '6_years':
                $startDate = now()->subYears(5)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            default:
                $startDate = now()->subDays(6)->startOfDay();
                $endDate = now()->endOfDay();
                break;
        }

        return $query->whereBetween('bookings.created_at', [$startDate, $endDate])
            ->groupBy('food_items.name')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();
    }

    private function getTopMoviesAndRoomsByPeriod($period)
    {
        // Top phim được đặt nhiều nhất
        $topMovies = Booking::select(
            'movies.title as movie_title',
            DB::raw('COUNT(DISTINCT bookings.id) as total_bookings'),
            DB::raw('SUM(booking_seats_count) as total_seats'),
            DB::raw('SUM(bookings.total_price) as total_revenue')
        )
            ->join('showtimes', 'bookings.showtime_id', '=', 'showtimes.id')
            ->join('movies', 'showtimes.movie_id', '=', 'movies.id')
            ->join(DB::raw('(SELECT booking_id, COUNT(*) as booking_seats_count FROM booking_seats GROUP BY booking_id) as seat_counts'), 'bookings.id', '=', 'seat_counts.booking_id')
            ->where('bookings.status', 'paid');

        // Top phòng được sử dụng nhiều nhất
        $topRooms = Booking::select(
            'rooms.name as room_name',
            DB::raw('COUNT(DISTINCT bookings.id) as total_bookings'),
            DB::raw('SUM(booking_seats_count) as total_seats'),
            DB::raw('SUM(bookings.total_price) as total_revenue')
        )
            ->join('showtimes', 'bookings.showtime_id', '=', 'showtimes.id')
            ->join('rooms', 'showtimes.room_id', '=', 'rooms.id')
            ->join(DB::raw('(SELECT booking_id, COUNT(*) as booking_seats_count FROM booking_seats GROUP BY booking_id) as seat_counts'), 'bookings.id', '=', 'seat_counts.booking_id')
            ->where('bookings.status', 'paid');

        switch ($period) {
            case '3_days':
                $startDate = now()->subDays(2)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case '7_days':
                $startDate = now()->subDays(6)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case '30_days':
                $startDate = now()->subDays(29)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case '1_month':
                $startDate = now()->subMonth()->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case '3_months':
                $startDate = now()->subMonths(2)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case '1_year':
                $startDate = now()->subYear()->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case '2_years':
                $startDate = now()->subYears(1)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            default:
                $startDate = now()->subDays(6)->startOfDay();
                $endDate = now()->endOfDay();
                break;
        }

        $topMovies = $topMovies->whereBetween('bookings.created_at', [$startDate, $endDate])
            ->groupBy('movies.title')
            ->orderByDesc('total_seats')
            ->limit(5)
            ->get();

        $topRooms = $topRooms->whereBetween('bookings.created_at', [$startDate, $endDate])
            ->groupBy('rooms.name')
            ->orderByDesc('total_seats')
            ->limit(5)
            ->get();

        return [
            'topMovies' => $topMovies,
            'topRooms' => $topRooms
        ];
    }

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

        // Đảm bảo dữ liệu chart được load trước khi dispatch
        $this->loadChartData();

        ($this->tabCurrent === "information" || ($this->js('chartInstances = {}') || false)) && $this->dispatch('updateData',
            $this->revenueData ?? [],
            $this->topMovies ?? [],
            $this->seatsData ?? [],
            $this->foodsData ?? [],
            $this->topFoods ?? [],
            [
                'revenueFilterText' => $this->getFilterText($this->revenuePeriod),
                'topMoviesFilterText' => $this->getFilterText($this->topMoviesPeriod),
                'seatsFilterText' => $this->getFilterText($this->seatsPeriod),
                'foodsFilterText' => $this->getFilterText($this->foodsPeriod),
                'topFoodsFilterText' => $this->getFilterText($this->topFoodsPeriod)
            ]
        );

        return view('livewire.admin.bookings.booking-detail', compact('tickets'));
    }
}
