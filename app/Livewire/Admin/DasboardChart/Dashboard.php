<?php

namespace App\Livewire\Admin\DasboardChart;

use App\Charts\dashboard\RevenueChart;
use App\Charts\dashboard\RevenueSourceChart;
use App\Charts\dashboard\TransactionHistoryChart;
use App\Charts\dashboard\TopMoviesChart;
use App\Charts\dashboard\TopFoodsChart;
use App\Charts\dashboard\SeatsAnalysisChart;
use App\Charts\dashboard\ShowtimeTimeSlotChart;
use App\Models\User;
use App\Models\Booking;
use App\Models\Movie;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use SE7ENCinema\scChart;

class Dashboard extends Component
{
    use scChart;

    public $customValue = 1;
    public $customUnit = 'months';

    // Thống kê tổng quan
    public $totalMoviesShowing = 0;
    public $moviesShowingGrowthPercent = 0;
    public $totalUsers = 0;
    public $usersGrowthPercent = 0;
    public $totalRevenueThisYear = 0;
    public $revenueYearGrowthPercent = 0;
    public $totalRevenueToday = 0;
    public $revenueTodayGrowthPercent = 0;
    public $totalPaidTicketsToday = 0;
    public $ticketsTodayGrowthPercent = 0;
    public $totalActiveUsers = 0;
    public $activeUsersGrowthPercent = 0;

    // Chart data properties
    public $transactionHistoryData = [];
    public $revenueSourceData = [];
    public $foodManagementData = [];

    public $filters = '7_days';
    public $chartData = [];

    // Additional statistics for detailed cards
    public $currentMonthRevenue = 0;
    public $lastMonthRevenue = 0;
    public $currentMonthBookings = 0;
    public $lastMonthBookings = 0;
    public $currentMonthUsers = 0;
    public $lastMonthUsers = 0;
    public $fromDate;
    public $rangeDays;

    public function mount()
    {
        $this->fromDate = Carbon::now()->subDays(3)->format('Y-m-d');
        $this->rangeDays = '3 days';
    }

    public function getRevenueGrowth()
    {
        if ($this->lastMonthRevenue == 0) return 0;
        return round((($this->currentMonthRevenue - $this->lastMonthRevenue) / $this->lastMonthRevenue) * 100, 1);
    }

    public function getBookingGrowth()
    {
        if ($this->lastMonthBookings == 0) return 0;
        return round((($this->currentMonthBookings - $this->lastMonthBookings) / $this->lastMonthBookings) * 100, 1);
    }

    public function getUserGrowth()
    {
        if ($this->lastMonthUsers == 0) return 0;
        return round((($this->currentMonthUsers - $this->lastMonthUsers) / $this->lastMonthUsers) * 100, 1);
    }

    private function calculateStatistics()
    {
        // Tính toán tháng hiện tại và tháng trước
        $currentMonthStart = Carbon::now()->startOfMonth();
        $currentMonthEnd = Carbon::now()->endOfMonth();
        $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        $rangeDays = (int) $this->rangeDays;
        $fromDate = $this->fromDate ? Carbon::parse($this->fromDate) : Carbon::now()->subDays($rangeDays);
        $toDate = $fromDate->copy()->addDays($rangeDays);

        // Đếm phim có status = 'showing'
        $this->totalMoviesShowing = Movie::where('status', 'showing')
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->count();

        // Phim đang chiếu trong tháng trước
        $lastMonthMovies = Movie::where('status', 'showing')
            ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->count();

        $this->moviesShowingGrowthPercent = $lastMonthMovies > 0 ?
            round((($this->totalMoviesShowing - $lastMonthMovies) / $lastMonthMovies) * 100, 1) : ($this->totalMoviesShowing > 0 ? 100 : 0);

        // 2. Tổng số người dùng (tất cả user đến hiện tại)
        $this->totalUsers = User::count();
        $lastMonthTotalUsers = User::where('created_at', '<=', $lastMonthEnd)->count();

        $this->usersGrowthPercent = $lastMonthTotalUsers > 0 ?
            round((($this->totalUsers - $lastMonthTotalUsers) / $lastMonthTotalUsers) * 100, 1) : ($this->totalUsers > 0 ? 100 : 0);

        // 3. Doanh thu tháng hiện tại vs tháng trước
        $this->totalRevenueThisYear = Booking::where('status', 'paid')
            ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->sum('total_price');

        $lastMonthRevenue = Booking::where('status', 'paid')
            ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->sum('total_price');

        $this->revenueYearGrowthPercent = $lastMonthRevenue > 0 ?
            round((($this->totalRevenueThisYear - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1) : ($this->totalRevenueThisYear > 0 ? 100 : 0);

        // 4. Số vé đã thanh toán tháng hiện tại vs tháng trước
        $this->totalPaidTicketsToday = DB::table('booking_seats')
            ->join('bookings', 'booking_seats.booking_id', '=', 'bookings.id')
            ->where('bookings.status', 'paid')
            ->whereBetween('bookings.created_at', [$currentMonthStart, $currentMonthEnd])
            ->count();

        $lastMonthTickets = DB::table('booking_seats')
            ->join('bookings', 'booking_seats.booking_id', '=', 'bookings.id')
            ->where('bookings.status', 'paid')
            ->whereBetween('bookings.created_at', [$lastMonthStart, $lastMonthEnd])
            ->count();

        $this->ticketsTodayGrowthPercent = $lastMonthTickets > 0 ?
            round((($this->totalPaidTicketsToday - $lastMonthTickets) / $lastMonthTickets) * 100, 1) : ($this->totalPaidTicketsToday > 0 ? 100 : 0);

        // 5. Người dùng mới tháng hiện tại vs tháng trước
        $this->totalActiveUsers = User::whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
            ->count();

        $lastMonthActiveUsers = User::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->count();

        $this->activeUsersGrowthPercent = $lastMonthActiveUsers > 0 ?
            round((($this->totalActiveUsers - $lastMonthActiveUsers) / $lastMonthActiveUsers) * 100, 1) : ($this->totalActiveUsers > 0 ? 100 : 0);

        // 6. Lưu lại cho các method khác (nếu cần)
        $this->currentMonthRevenue = $this->totalRevenueThisYear;
        $this->lastMonthRevenue = $lastMonthRevenue;
        $this->currentMonthBookings = $this->totalPaidTicketsToday;
        $this->lastMonthBookings = $lastMonthTickets;
        $this->currentMonthUsers = $this->totalActiveUsers;
        $this->lastMonthUsers = $lastMonthActiveUsers;
    }

    #[Title('Dashboard - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        $this->calculateStatistics();

        $transactionHistory = new TransactionHistoryChart;
        $revenueSource = new RevenueSourceChart;
        $revenue = new RevenueChart;
        $topMovies = new TopMoviesChart();
        $topFoods = new TopFoodsChart();
        $seatsAnalysis = new SeatsAnalysisChart();
        $showtimeTimeSlot = new ShowtimeTimeSlotChart();
        $this->realtimeUpdateCharts(
            [$transactionHistory, [$this->fromDate, $this->rangeDays]],
            [$revenueSource, [$this->fromDate, $this->rangeDays]],
            [$revenue, [$this->fromDate, $this->rangeDays]],
            [$topMovies, [$this->fromDate, $this->rangeDays]],
            [$topFoods, [$this->fromDate, $this->rangeDays]],
            [$seatsAnalysis, [$this->fromDate, $this->rangeDays]],
            [$showtimeTimeSlot, [$this->fromDate, $this->rangeDays]]
        );

        $this->dispatch('updateRevenueChart', data: $this->chartData);

        return view(
            'livewire.admin.dasboard-chart.dashboard',
            [
                'revenue' => $revenue,
                'transactionHistory' => $transactionHistory,
                'revenueSource' => $revenueSource,
                'topMovies' => $topMovies,
                'topFoods' => $topFoods,
                'seatsAnalysis' => $seatsAnalysis,
                'showtimeTimeSlot' => $showtimeTimeSlot,
                'totalMoviesShowing' => $this->totalMoviesShowing,
                'moviesShowingGrowthPercent' => $this->moviesShowingGrowthPercent,
                'totalRevenueThisYear' => $this->totalRevenueThisYear,
                'revenueYearGrowthPercent' => $this->revenueYearGrowthPercent,
                'totalPaidTicketsToday' => $this->totalPaidTicketsToday,
                'ticketsTodayGrowthPercent' => $this->ticketsTodayGrowthPercent,
                'totalActiveUsers' => $this->totalActiveUsers,
                'activeUsersGrowthPercent' => $this->activeUsersGrowthPercent,
                'totalUsers' => $this->totalUsers,
                'usersGrowthPercent' => $this->usersGrowthPercent,
                'filters' => $this->filters
            ]
        );
    }
}
