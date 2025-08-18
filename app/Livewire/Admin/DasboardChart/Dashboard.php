<?php

namespace App\Livewire\Admin\DasboardChart;

use App\Charts\dashboard\FoodManagementChart;
use App\Charts\dashboard\RevenueChart;
use App\Charts\dashboard\RevenueSourceChart;
use App\Charts\dashboard\TransactionHistoryChart;
use App\Models\User;
use App\Models\Booking;
use App\Models\FoodOrderItem;
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

    public $startDate;
    public $endDate;
    public $customValue = 1;
    public $customUnit = 'months';
    public $showComparison = false;

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

    public function mount()
    {
        $this->endDate = Carbon::now()->format('Y-m-d');
        $this->startDate = Carbon::now()->subDays(30)->format('Y-m-d');
        
    }

    public function resetFilters()
    {
        $this->startDate = Carbon::now()->subDays(30)->format('Y-m-d');
        $this->endDate = Carbon::now()->format('Y-m-d');
        $this->customValue = 1;
        $this->customUnit = 'months';
        $this->showComparison = false;
    }

    public function toggleComparison()
    {
        $this->showComparison = !$this->showComparison;
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
        // 1. Tổng số phim đang chiếu và tăng trưởng
        $this->totalMoviesShowing = Movie::where('status', 'showing')->count();
        $lastMonthMovies = Movie::where('status', 'showing')
            ->where('created_at', '<', Carbon::now()->subMonth())->count();
        $this->moviesShowingGrowthPercent = $lastMonthMovies > 0 ?
            round((($this->totalMoviesShowing - $lastMonthMovies) / $lastMonthMovies) * 100, 1) : 0;

        // 2. Tổng số người dùng và tăng trưởng
        $this->totalUsers = User::count();
        $lastMonthUsers = User::where('created_at', '<', Carbon::now()->subMonth())->count();
        $this->usersGrowthPercent = $lastMonthUsers > 0 ?
            round((($this->totalUsers - $lastMonthUsers) / $lastMonthUsers) * 100, 1) : 0;

        // 3. Doanh thu năm hiện tại và so sánh với năm trước
        $currentYear = Carbon::now()->year;
        $lastYear = $currentYear - 1;

        $currentYearTicketRevenue = Booking::where('status', 'paid')
            ->whereYear('created_at', $currentYear)
            ->sum('total_price');

        $lastYearTicketRevenue = Booking::where('status', 'paid')
            ->whereYear('created_at', $lastYear)
            ->sum('total_price');

        $currentYearFoodRevenue = FoodOrderItem::whereHas('booking', function ($query) use ($currentYear) {
                $query->where('status', 'paid')
                      ->whereYear('created_at', $currentYear);
            })
            ->sum('price');

        $lastYearFoodRevenue = FoodOrderItem::whereHas('booking', function ($query) use ($lastYear) {
                $query->where('status', 'paid')
                      ->whereYear('created_at', $lastYear);
            })
            ->sum('price');

        $this->totalRevenueThisYear = $currentYearTicketRevenue + $currentYearFoodRevenue;
        $lastYearRevenue = $lastYearTicketRevenue + $lastYearFoodRevenue;

        $this->revenueYearGrowthPercent = $lastYearRevenue > 0 ?
            round((($this->totalRevenueThisYear - $lastYearRevenue) / $lastYearRevenue) * 100, 1) : 0;

        // 4. Doanh thu hôm nay và so sánh với hôm qua
        $this->totalRevenueToday = Booking::where('status', 'paid')
            ->whereDate('created_at', Carbon::today())
            ->sum('total_price');

        $yesterdayRevenue = Booking::where('status', 'paid')
            ->whereDate('created_at', Carbon::yesterday())
            ->sum('total_price');

        $this->revenueTodayGrowthPercent = $yesterdayRevenue > 0 ?
            round((($this->totalRevenueToday - $yesterdayRevenue) / $yesterdayRevenue) * 100, 1) : 0;

        // 5. Số vé đã thanh toán hôm nay và so sánh với hôm qua
        $this->totalPaidTicketsToday = Booking::where('status', 'paid')
            ->whereDate('created_at', Carbon::today())
            ->count();

        $yesterdayTickets = Booking::where('status', 'paid')
            ->whereDate('created_at', Carbon::yesterday())
            ->count();

        $this->ticketsTodayGrowthPercent = $yesterdayTickets > 0 ?
            round((($this->totalPaidTicketsToday - $yesterdayTickets) / $yesterdayTickets) * 100, 1) : 0;
    }

    #[Title('Dashboard - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        $transactionHis = new TransactionHistoryChart;
        $this->realtimeUpdateCharts([$transactionHis, $this->filters]);

        $revenueSource = new RevenueSourceChart;
        $this->realtimeUpdateCharts([$revenueSource, $this->filters]);

        $foodManagement = new FoodManagementChart;
        $this->realtimeUpdateCharts([$foodManagement, $this->filters]);

        $revenue = new RevenueChart;
        $this->realtimeUpdateCharts([$revenue, $this->filters]);

        // Tính toán thống kê trước
        $this->calculateStatistics();

        $this->dispatch('updateData',
            $this->transactionHistoryData ?? [],
            $this->revenueSourceData ?? [],
            $this->foodManagementData ?? []
        );

        $this->dispatch('updateRevenueChart', data: $this->chartData);

        return view('livewire.admin.dasboard-chart.dashboard', compact('transactionHis', 'revenueSource', 'foodManagement', 'revenue'));
    }
}
