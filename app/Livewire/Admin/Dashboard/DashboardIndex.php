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
    public $revenuePeriod = '1_month';
    public $chartData = [];
    public $currentMonthRevenue = 0;
    public $lastMonthRevenue = 0;
    public $currentMonthBookings = 0;
    public $lastMonthBookings = 0;
    public $currentMonthUsers = 0;
    public $lastMonthUsers = 0;
    public $currentMonthMovies = 0;
    public $lastMonthMovies = 0;

    public function mount()
    {
        $this->loadStatistics();
        $this->loadChartData();
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

    public function loadChartData()
    {
        $this->chartData = $this->getRevenueData($this->revenuePeriod);
    }

    public function changeRevenuePeriod($period)
    {
        $this->revenuePeriod = $period;
        $this->loadChartData();
        $this->dispatch('updateRevenueChart', data: $this->chartData);
        $this->dispatch('updateFilterText', elementId: 'revenueFilterText', text: $this->getFilterText($period));
    }

    private function getRevenueData($period)
    {
        $data = [];
        $now = Carbon::now();

        switch ($period) {
            case '3_days':
                for ($i = 2; $i >= 0; $i--) {
                    $date = $now->copy()->subDays($i);
                    $revenue = Booking::where('status', 'paid')
                        ->whereDate('created_at', $date)
                        ->sum('total_price');
                    $bookings = Booking::where('status', 'paid')
                        ->whereDate('created_at', $date)
                        ->count();
                    $data[] = [
                        'x' => $date->format('d/m'),
                        'y' => $revenue,
                        'bookings' => $bookings
                    ];
                }
                break;

            case '7_days':
                for ($i = 6; $i >= 0; $i--) {
                    $date = $now->copy()->subDays($i);
                    $revenue = Booking::where('status', 'paid')
                        ->whereDate('created_at', $date)
                        ->sum('total_price');
                    $bookings = Booking::where('status', 'paid')
                        ->whereDate('created_at', $date)
                        ->count();
                    $data[] = [
                        'x' => $date->format('d/m'),
                        'y' => $revenue,
                        'bookings' => $bookings
                    ];
                }
                break;

            case '30_days':
                for ($i = 29; $i >= 0; $i--) {
                    $date = $now->copy()->subDays($i);
                    $revenue = Booking::where('status', 'paid')
                        ->whereDate('created_at', $date)
                        ->sum('total_price');
                    $bookings = Booking::where('status', 'paid')
                        ->whereDate('created_at', $date)
                        ->count();
                    $data[] = [
                        'x' => $date->format('d/m'),
                        'y' => $revenue,
                        'bookings' => $bookings
                    ];
                }
                break;

            case '1_month':
                for ($i = 29; $i >= 0; $i--) {
                    $date = $now->copy()->subDays($i);
                    $revenue = Booking::where('status', 'paid')
                        ->whereDate('created_at', $date)
                        ->sum('total_price');
                    $bookings = Booking::where('status', 'paid')
                        ->whereDate('created_at', $date)
                        ->count();
                    $data[] = [
                        'x' => $date->format('d/m'),
                        'y' => $revenue,
                        'bookings' => $bookings
                    ];
                }
                break;

            case '3_months':
                for ($i = 2; $i >= 0; $i--) {
                    $date = $now->copy()->subMonths($i);
                    $revenue = Booking::where('status', 'paid')
                        ->whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->sum('total_price');
                    $bookings = Booking::where('status', 'paid')
                        ->whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->count();
                    $data[] = [
                        'x' => $date->format('M Y'),
                        'y' => $revenue,
                        'bookings' => $bookings
                    ];
                }
                break;

            case '6_months':
                for ($i = 5; $i >= 0; $i--) {
                    $date = $now->copy()->subMonths($i);
                    $revenue = Booking::where('status', 'paid')
                        ->whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->sum('total_price');
                    $bookings = Booking::where('status', 'paid')
                        ->whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->count();
                    $data[] = [
                        'x' => $date->format('M Y'),
                        'y' => $revenue,
                        'bookings' => $bookings
                    ];
                }
                break;

            case '1_year':
                for ($i = 11; $i >= 0; $i--) {
                    $date = $now->copy()->subMonths($i);
                    $revenue = Booking::where('status', 'paid')
                        ->whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->sum('total_price');
                    $bookings = Booking::where('status', 'paid')
                        ->whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->count();
                    $data[] = [
                        'x' => $date->format('M Y'),
                        'y' => $revenue,
                        'bookings' => $bookings
                    ];
                }
                break;

            case '2_years':
                for ($i = 23; $i >= 0; $i--) {
                    $date = $now->copy()->subMonths($i);
                    $revenue = Booking::where('status', 'paid')
                        ->whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->sum('total_price');
                    $bookings = Booking::where('status', 'paid')
                        ->whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->count();
                    $data[] = [
                        'x' => $date->format('M Y'),
                        'y' => $revenue,
                        'bookings' => $bookings
                    ];
                }
                break;

            case '3_years':
                for ($i = 35; $i >= 0; $i--) {
                    $date = $now->copy()->subMonths($i);
                    $revenue = Booking::where('status', 'paid')
                        ->whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->sum('total_price');
                    $bookings = Booking::where('status', 'paid')
                        ->whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->count();
                    $data[] = [
                        'x' => $date->format('M Y'),
                        'y' => $revenue,
                        'bookings' => $bookings
                    ];
                }
                break;

            default:
                // Default to 1 month view
                for ($i = 29; $i >= 0; $i--) {
                    $date = $now->copy()->subDays($i);
                    $revenue = Booking::where('status', 'paid')
                        ->whereDate('created_at', $date)
                        ->sum('total_price');
                    $bookings = Booking::where('status', 'paid')
                        ->whereDate('created_at', $date)
                        ->count();
                    $data[] = [
                        'x' => $date->format('d/m'),
                        'y' => $revenue,
                        'bookings' => $bookings
                    ];
                }
                break;
        }

        return $data;
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
                return '1 tháng gần nhất';
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
