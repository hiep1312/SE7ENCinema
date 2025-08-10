<?php

namespace App\Livewire\Admin\Dashboard;

use App\Models\Booking;
use App\Models\User;
use App\Models\Movie;
use App\Models\Showtime;
use App\Models\FoodOrderItem;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class DashboardIndex extends Component
{
    public $revenuePeriod = '7_days';
    public $comboPeriod = '7_days';
    public $piePeriod = '7_days';
    public $radarPeriod = '7_days';

    public $chartData = [];
    public $comboChartData = [];
    public $pieChartData = [];
    public $radarChartData = [];

    public $currentMonthRevenue = 0;
    public $lastMonthRevenue = 0;
    public $currentMonthBookings = 0;
    public $lastMonthBookings = 0;
    public $currentMonthUsers = 0;
    public $lastMonthUsers = 0;
    public $currentMonthMovies = 0;
    public $lastMonthMovies = 0;

    // Vietnamese month abbreviations
    private $vietnameseMonths = [
        1 => 'T1', 2 => 'T2', 3 => 'T3', 4 => 'T4', 5 => 'T5', 6 => 'T6',
        7 => 'T7', 8 => 'T8', 9 => 'T9', 10 => 'T10', 11 => 'T11', 12 => 'T12'
    ];

    public function mount()
    {
        $this->loadStatistics();
        $this->loadAllChartData();
    }

    public function loadStatistics()
    {
        $now = Carbon::now();
        $currentMonth = $now->copy()->startOfMonth();
        $lastMonth = $now->copy()->subMonth()->startOfMonth();

        // Doanh thu
        $this->currentMonthRevenue = Booking::where('status', 'paid')
            ->whereBetween('created_at', [$currentMonth, $now])
            ->sum('total_price');

        $this->lastMonthRevenue = Booking::where('status', 'paid')
            ->whereBetween('created_at', [$lastMonth, $currentMonth])
            ->sum('total_price');

        // Số đặt vé
        $this->currentMonthBookings = Booking::where('status', 'paid')
            ->whereBetween('created_at', [$currentMonth, $now])
            ->count();

        $this->lastMonthBookings = Booking::where('status', 'paid')
            ->whereBetween('created_at', [$lastMonth, $currentMonth])
            ->count();

        // Số người dùng mới
        $this->currentMonthUsers = User::whereBetween('created_at', [$currentMonth, $now])
            ->count();

        $this->lastMonthUsers = User::whereBetween('created_at', [$lastMonth, $currentMonth])
            ->count();

        // Số phim mới
        $this->currentMonthMovies = Movie::whereBetween('created_at', [$currentMonth, $now])
            ->count();

        $this->lastMonthMovies = Movie::whereBetween('created_at', [$lastMonth, $currentMonth])
            ->count();
    }

    public function loadAllChartData()
    {
        $this->chartData = $this->getRevenueData($this->revenuePeriod);
        $this->comboChartData = $this->getComboData($this->comboPeriod);
        $this->pieChartData = $this->getPieData($this->piePeriod);
        $this->radarChartData = $this->getRadarData($this->radarPeriod);
    }

    // Unified method for changing periods
    public function changePeriod($chartType, $period)
    {
        switch ($chartType) {
            case 'revenue':
                $this->revenuePeriod = $period;
                $this->chartData = $this->getRevenueData($period);
                $this->dispatch('updateRevenueChart', data: $this->chartData);
                $this->dispatch('updateFilterText', elementId: 'revenueFilterText', text: $this->getFilterText($period));
                break;
            case 'combo':
                $this->comboPeriod = $period;
                $this->comboChartData = $this->getComboData($period);
                $this->dispatch('updateComboChart', data: $this->comboChartData);
                $this->dispatch('updateFilterText', elementId: 'comboFilterText', text: $this->getFilterText($period));
                break;
            case 'pie':
                $this->piePeriod = $period;
                $this->pieChartData = $this->getPieData($period);
                $this->dispatch('updatePieChart', data: $this->pieChartData);
                $this->dispatch('updateFilterText', elementId: 'pieFilterText', text: $this->getFilterText($period));
                break;
            case 'radar':
                $this->radarPeriod = $period;
                $this->radarChartData = $this->getRadarData($period);
                $this->dispatch('updateRadarChart', data: $this->radarChartData);
                $this->dispatch('updateFilterText', elementId: 'radarFilterText', text: $this->getFilterText($period));
                break;
        }
    }

    // Backward compatibility methods
    public function changeRevenuePeriod($period)
    {
        $this->changePeriod('revenue', $period);
    }

    public function changeMoviePeriod($period)
    {
        $this->changePeriod('combo', $period);
    }

    public function changeFoodPeriod($period)
    {
        $this->changePeriod('combo', $period);
    }

    // Unified data generation method
    private function generateDateRange($period)
    {
        $now = Carbon::now();
        $data = [];

        switch ($period) {
            case '3_days':
                for ($i = 2; $i >= 0; $i--) {
                    $data[] = $now->copy()->subDays($i);
                }
                break;
            case '7_days':
                for ($i = 6; $i >= 0; $i--) {
                    $data[] = $now->copy()->subDays($i);
                }
                break;
            case '30_days':
                for ($i = 29; $i >= 0; $i--) {
                    $data[] = $now->copy()->subDays($i);
                }
                break;
            case '1_month':
                for ($i = 29; $i >= 0; $i--) {
                    $data[] = $now->copy()->subDays($i);
                }
                break;
            case '3_months':
                for ($i = 2; $i >= 0; $i--) {
                    $data[] = $now->copy()->subMonths($i);
                }
                break;
            case '6_months':
                for ($i = 5; $i >= 0; $i--) {
                    $data[] = $now->copy()->subMonths($i);
                }
                break;
            case '1_year':
                for ($i = 11; $i >= 0; $i--) {
                    $data[] = $now->copy()->subMonths($i);
                }
                break;
            case '2_years':
                for ($i = 23; $i >= 0; $i--) {
                    $data[] = $now->copy()->subMonths($i);
                }
                break;
            case '3_years':
                for ($i = 35; $i >= 0; $i--) {
                    $data[] = $now->copy()->subMonths($i);
                }
                break;
            default:
                for ($i = 6; $i >= 0; $i--) {
                    $data[] = $now->copy()->subDays($i);
                }
                break;
        }

        return $data;
    }

    private function getRevenueData($period)
    {
        $dates = $this->generateDateRange($period);
        $data = [];

        foreach ($dates as $date) {
            $revenue = Booking::where('status', 'paid')
                ->whereDate('created_at', $date)
                ->sum('total_price');
            $bookings = Booking::where('status', 'paid')
                ->whereDate('created_at', $date)
                ->count();

            $data[] = [
                'x' => $this->formatDate($date, $period),
                'y' => $revenue,
                'bookings' => $bookings
            ];
        }

        return $data;
    }

    // Combo Chart: Movies + Food + Users
    private function getComboData($period)
    {
        $dates = $this->generateDateRange($period);
        $data = [];

        foreach ($dates as $date) {
            // Movies data
            $newMovies = Movie::whereDate('created_at', $date)->count();
            $activeMovies = Movie::whereHas('showtimes', function($query) use ($date) {
                $query->whereDate('start_time', $date);
            })->count();

            // Food data
            $foodRevenue = FoodOrderItem::whereDate('created_at', $date)->sum('price');
            $foodOrders = FoodOrderItem::whereDate('created_at', $date)->count();

            // Users data
            $newUsers = User::whereDate('created_at', $date)->count();

            $data[] = [
                'x' => $this->formatDate($date, $period),
                'movies' => $newMovies,
                'activeMovies' => $activeMovies,
                'foodRevenue' => $foodRevenue,
                'foodOrders' => $foodOrders,
                'users' => $newUsers
            ];
        }

        return $data;
    }

    // Pie Chart: Revenue Distribution
    private function getPieData($period)
    {
        $dates = $this->generateDateRange($period);
        $startDate = $dates[0];
        $endDate = end($dates);

        // Revenue from tickets
        $ticketRevenue = Booking::where('status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_price');

        // Revenue from food
        $foodRevenue = FoodOrderItem::whereBetween('created_at', [$startDate, $endDate])
            ->sum('price');

        // Total bookings
        $totalBookings = Booking::where('status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Total food orders
        $totalFoodOrders = FoodOrderItem::whereBetween('created_at', [$startDate, $endDate])
            ->count();

        return [
            'revenue' => [
                ['name' => 'Doanh thu vé', 'value' => $ticketRevenue],
                ['name' => 'Doanh thu đồ ăn', 'value' => $foodRevenue]
            ],
            'orders' => [
                ['name' => 'Đặt vé', 'value' => $totalBookings],
                ['name' => 'Đặt đồ ăn', 'value' => $totalFoodOrders]
            ]
        ];
    }

    // Radar Chart: Performance Metrics
    private function getRadarData($period)
    {
        $dates = $this->generateDateRange($period);
        $startDate = $dates[0];
        $endDate = end($dates);

        // Calculate various metrics
        $totalRevenue = Booking::where('status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_price');

        $totalBookings = Booking::where('status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $totalUsers = User::whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $totalMovies = Movie::whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $totalFoodRevenue = FoodOrderItem::whereBetween('created_at', [$startDate, $endDate])
            ->sum('price');

        $avgBookingValue = $totalBookings > 0 ? $totalRevenue / $totalBookings : 0;

        return [
            'categories' => ['Doanh thu', 'Đặt vé', 'Người dùng', 'Phim mới', 'Đồ ăn', 'TB/đơn'],
            'values' => [
                $this->normalizeValue($totalRevenue, 10000000), // Normalize to 0-100
                $this->normalizeValue($totalBookings, 1000),
                $this->normalizeValue($totalUsers, 500),
                $this->normalizeValue($totalMovies, 50),
                $this->normalizeValue($totalFoodRevenue, 5000000),
                $this->normalizeValue($avgBookingValue, 200000)
            ]
        ];
    }

    private function normalizeValue($value, $maxValue)
    {
        return min(100, ($value / $maxValue) * 100);
    }

    private function formatDate($date, $period)
    {
        if (in_array($period, ['3_days', '7_days', '30_days', '1_month'])) {
            return $date->format('d/m');
        } else {
            return $this->vietnameseMonths[$date->month] . ' ' . $date->year;
        }
    }

    public function getFilterText($period)
    {
        switch ($period) {
            case '3_days':
                return '3 ngày gần nhất';
            case '7_days':
                return '7 ngày gần nhất';
            case '30_days':
                return '30 ngày gần nhất';
            case '1_month':
                return '1 tháng gần nhất';
            case '3_months':
                return '3 tháng gần nhất';
            case '6_months':
                return '6 tháng gần nhất';
            case '1_year':
                return '1 năm gần nhất';
            case '2_years':
                return '2 năm gần nhất';
            case '3_years':
                return '3 năm gần nhất';
            default:
                return '7 ngày gần nhất';
        }
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

    public function getMovieGrowth()
    {
        if ($this->lastMonthMovies == 0) return 0;
        return round((($this->currentMonthMovies - $this->lastMonthMovies) / $this->lastMonthMovies) * 100, 1);
    }

    #[Title('Dashboard - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.admin.dashboard.dashboard-index');
    }
}
