<?php

namespace App\Livewire\Admin\DasboardChart;

use App\Models\User;
use App\Models\Booking;
use App\Models\FoodOrderItem;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Movie;

class Dashboard extends Component
{
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
    public $cityRevenueData = []; // Thêm dữ liệu doanh thu theo thành phố

    // Chart periods
    public $transactionHistoryPeriod = '7_days';
    public $revenueSourcePeriod = '7_days';
    public $foodManagementPeriod = '7_days';
    public $cityRevenuePeriod = '7_days'; // Thêm period cho chart thành phố

    // Additional statistics for detailed cards
    public $currentMonthRevenue = 0;
    public $lastMonthRevenue = 0;
    public $currentMonthBookings = 0;
    public $lastMonthBookings = 0;
    public $currentMonthUsers = 0;
    public $lastMonthUsers = 0;
    public $currentMonthMovies = 0;
    public $lastMonthMovies = 0;

    // Revenue chart properties
    public $revenuePeriod = '7_days';
    public $chartData = [];

    // Vietnamese month abbreviations
    private $vietnameseMonths = [
        1 => 'T1', 2 => 'T2', 3 => 'T3', 4 => 'T4', 5 => 'T5', 6 => 'T6',
        7 => 'T7', 8 => 'T8', 9 => 'T9', 10 => 'T10', 11 => 'T11', 12 => 'T12'
    ];

    // Map coordinates for Vietnamese cities
    private $cityCoordinates = [
        'Hà Nội' => ['x' => 50, 'y' => 30],
        'TP. Hồ Chí Minh' => ['x' => 45, 'y' => 75],
        'Đà Nẵng' => ['x' => 35, 'y' => 55],
        'Hải Phòng' => ['x' => 55, 'y' => 35],
        'Cần Thơ' => ['x' => 40, 'y' => 80],
        'Nha Trang' => ['x' => 30, 'y' => 65],
        'Huế' => ['x' => 30, 'y' => 50],
        'Vũng Tàu' => ['x' => 40, 'y' => 75],
        'Quy Nhơn' => ['x' => 25, 'y' => 60],
        'Đà Lạt' => ['x' => 35, 'y' => 70],
        'Vinh' => ['x' => 45, 'y' => 40],
        'Nam Định' => ['x' => 50, 'y' => 45],
        'Thái Nguyên' => ['x' => 55, 'y' => 25],
        'Bắc Ninh' => ['x' => 55, 'y' => 30],
        'Hải Dương' => ['x' => 55, 'y' => 35],
        'Phú Thọ' => ['x' => 50, 'y' => 20],
        'Lào Cai' => ['x' => 45, 'y' => 15],
        'Sơn La' => ['x' => 40, 'y' => 20],
        'Lạng Sơn' => ['x' => 60, 'y' => 25],
        'Quảng Ninh' => ['x' => 60, 'y' => 35],
        'Thái Bình' => ['x' => 55, 'y' => 40],
        'Hưng Yên' => ['x' => 55, 'y' => 35],
        'Hà Nam' => ['x' => 50, 'y' => 40],
        'Ninh Bình' => ['x' => 50, 'y' => 45],
        'Thanh Hóa' => ['x' => 45, 'y' => 45],
        'Nghệ An' => ['x' => 40, 'y' => 45],
        'Hà Tĩnh' => ['x' => 35, 'y' => 45],
        'Quảng Bình' => ['x' => 30, 'y' => 45],
        'Quảng Trị' => ['x' => 30, 'y' => 50],
        'Thừa Thiên Huế' => ['x' => 30, 'y' => 50],
        'Quảng Nam' => ['x' => 30, 'y' => 55],
        'Quảng Ngãi' => ['x' => 25, 'y' => 60],
        'Bình Định' => ['x' => 25, 'y' => 60],
        'Phú Yên' => ['x' => 25, 'y' => 65],
        'Khánh Hòa' => ['x' => 30, 'y' => 65],
        'Ninh Thuận' => ['x' => 30, 'y' => 70],
        'Bình Thuận' => ['x' => 35, 'y' => 70],
        'Bình Phước' => ['x' => 40, 'y' => 70],
        'Tây Ninh' => ['x' => 40, 'y' => 75],
        'Bình Dương' => ['x' => 40, 'y' => 75],
        'Đồng Nai' => ['x' => 40, 'y' => 75],
        'Bà Rịa - Vũng Tàu' => ['x' => 40, 'y' => 75],
        'Long An' => ['x' => 40, 'y' => 80],
        'Tiền Giang' => ['x' => 40, 'y' => 80],
        'Bến Tre' => ['x' => 40, 'y' => 80],
        'Trà Vinh' => ['x' => 40, 'y' => 80],
        'Vĩnh Long' => ['x' => 40, 'y' => 80],
        'Đồng Tháp' => ['x' => 40, 'y' => 80],
        'An Giang' => ['x' => 40, 'y' => 80],
        'Kiên Giang' => ['x' => 35, 'y' => 80],
        'Cà Mau' => ['x' => 35, 'y' => 85],
        'Bạc Liêu' => ['x' => 35, 'y' => 80],
        'Sóc Trăng' => ['x' => 40, 'y' => 80],
        'Hậu Giang' => ['x' => 40, 'y' => 80],
    ];

    public function mount()
    {
        $this->transactionHistoryPeriod = '7_days';
        $this->revenueSourcePeriod = '7_days';
        $this->foodManagementPeriod = '7_days';
        $this->revenuePeriod = '7_days';
        $this->cityRevenuePeriod = '7_days';

        // Load all statistics and chart data
        $this->loadStatistics();
        $this->loadChartData();

        // Khởi tạo dữ liệu mặc định cho biểu đồ
        $this->transactionHistoryData = $this->getTransactionHistoryData($this->transactionHistoryPeriod);
        $this->revenueSourceData = $this->getRevenueSourceData($this->revenueSourcePeriod);
        $this->foodManagementData = $this->getFoodManagementData($this->foodManagementPeriod);
        $this->cityRevenueData = $this->getCityRevenueData($this->cityRevenuePeriod);
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

    public function changeTransactionHistoryPeriod($period)
    {
        $this->transactionHistoryPeriod = $period;
        $this->transactionHistoryData = $this->getTransactionHistoryData($period);
        $this->dispatch('updateFilterText', elementId: 'transactionHistoryFilterText', text: $this->getFilterText($period));
    }

    public function changeRevenueSourcePeriod($period)
    {
        $this->revenueSourcePeriod = $period;
        $this->revenueSourceData = $this->getRevenueSourceData($period);
        $this->dispatch('updateFilterText', elementId: 'revenueSourceFilterText', text: $this->getFilterText($period));
    }

    public function changeFoodManagementPeriod($period)
    {
        $this->foodManagementPeriod = $period;
        $this->foodManagementData = $this->getFoodManagementData($period);
        $this->dispatch('updateFilterText', elementId: 'foodManagementFilterText', text: $this->getFilterText($period));
    }

    public function changeRevenuePeriod($period)
    {
        $this->revenuePeriod = $period;
        $this->chartData = $this->getRevenueData($period);
        $this->dispatch('updateRevenueChart', data: $this->chartData);
        $this->dispatch('updateFilterText', elementId: 'revenueFilterText', text: $this->getRevenueFilterText($period));
    }

    public function changeCityRevenuePeriod($period)
    {
        $this->cityRevenuePeriod = $period;
        $this->cityRevenueData = $this->getCityRevenueData($period);
        $this->dispatch('updateFilterText', elementId: 'cityRevenueFilterText', text: $this->getFilterText($period));
    }

    public function loadChartData()
    {
        $this->chartData = $this->getRevenueData($this->revenuePeriod);

        // Đảm bảo luôn có dữ liệu cho biểu đồ
        if (empty($this->transactionHistoryData)) {
            $this->transactionHistoryData = $this->getTransactionHistoryData($this->transactionHistoryPeriod);
        }

        if (empty($this->revenueSourceData)) {
            $this->revenueSourceData = $this->getRevenueSourceData($this->revenueSourcePeriod);
        }

        if (empty($this->foodManagementData)) {
            $this->foodManagementData = $this->getFoodManagementData($this->foodManagementPeriod);
        }

        if (empty($this->cityRevenueData)) {
            $this->cityRevenueData = $this->getCityRevenueData($this->cityRevenuePeriod);
        }
    }

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

    private function formatDate($date, $period)
    {
        if (in_array($period, ['3_days', '7_days', '30_days', '1_month'])) {
            return $date->format('d/m');
        } else {
            return $this->vietnameseMonths[$date->month] . ' ' . $date->year;
        }
    }

    private function getTransactionHistoryData($period)
    {
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
                $startDate = now()->subMonths(3)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case '1_year':
                $startDate = now()->subYear()->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case '2_years':
                $startDate = now()->subYears(2)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            default:
                $startDate = now()->subDays(6)->startOfDay();
                $endDate = now()->endOfDay();
        }

        $data = Booking::select('payment_method', DB::raw('COUNT(*) as transaction_count'))
            ->where('status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('payment_method')
            ->get();

        $paymentMethodLabels = [
            'credit_card' => 'Thẻ tín dụng',
            'e_wallet' => 'Ví điện tử',
            'cash' => 'Tiền mặt',
            'bank_transfer' => 'Chuyển khoản ngân hàng',
        ];

        $chartData = [];
        foreach ($data as $item) {
            $label = $paymentMethodLabels[$item->payment_method] ?? ucfirst(str_replace('_', ' ', $item->payment_method));
            $chartData[] = [
                'name' => $label,
                'value' => (int) $item->transaction_count,
            ];
        }

        // Handle case where no data is found for the period
        if (empty($chartData)) {
            return [
                ['name' => 'Không có dữ liệu', 'value' => 1] // Placeholder for empty chart
            ];
        }

        return $chartData;
    }

    private function getRevenueSourceData($period)
    {
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
                $startDate = now()->subMonths(3)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case '1_year':
                $startDate = now()->subYear()->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case '2_years':
                $startDate = now()->subYears(2)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            default:
                $startDate = now()->subDays(6)->startOfDay();
                $endDate = now()->endOfDay();
        }

        // Xác định kiểu nhóm thời gian: theo ngày (short periods) hoặc theo tháng (long periods)
        $useMonthly = in_array($period, ['1_year', '2_years']);
        $groupFormat = $useMonthly ? '%Y-%m' : '%Y-%m-%d';
        $labelFormat = $useMonthly ? 'm/Y' : 'd/m';
        $keyFormatPhp = $useMonthly ? 'Y-m' : 'Y-m-d';

        // Doanh thu vé theo mốc thời gian
        $ticketMap = Booking::where('status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw("DATE_FORMAT(created_at, '$groupFormat') as period"), DB::raw('SUM(total_price) as revenue'))
            ->groupBy('period')
            ->pluck('revenue', 'period')
            ->toArray();

        // Doanh thu đồ ăn theo mốc thời gian (join với bookings để dùng created_at của booking)
        $foodMap = DB::table('food_order_items')
            ->join('bookings', 'food_order_items.booking_id', '=', 'bookings.id')
            ->where('bookings.status', 'paid')
            ->whereBetween('bookings.created_at', [$startDate, $endDate])
            ->select(DB::raw("DATE_FORMAT(bookings.created_at, '$groupFormat') as period"), DB::raw('SUM(food_order_items.price) as revenue'))
            ->groupBy('period')
            ->pluck('revenue', 'period')
            ->toArray();

        // Tạo dãy labels liên tục theo ngày/tháng
        $labels = [];
        $ticketSeries = [];
        $foodSeries = [];

        $cursor = $startDate->copy()->startOfDay();
        while ($cursor->lte($endDate)) {
            $key = $cursor->format($keyFormatPhp);
            $labels[] = $cursor->format($labelFormat);
            $ticketSeries[] = (int)($ticketMap[$key] ?? 0);
            $foodSeries[] = (int)($foodMap[$key] ?? 0);

            if ($useMonthly) {
                $cursor->addMonthNoOverflow()->startOfDay();
            } else {
                $cursor->addDay()->startOfDay();
            }
        }

        // Nếu tất cả đều 0 thì trả về placeholder tránh lỗi chart rỗng
        if (array_sum($ticketSeries) === 0 && array_sum($foodSeries) === 0) {
            return [
                'labels' => [$useMonthly ? now()->format('m/Y') : now()->format('d/m')],
                'series' => [
                    ['name' => 'Vé', 'data' => [0]],
                    ['name' => 'Đồ ăn', 'data' => [0]],
                ]
            ];
        }

        return [
            'labels' => $labels,
            'series' => [
                ['name' => 'Vé', 'data' => $ticketSeries],
                ['name' => 'Đồ ăn', 'data' => $foodSeries],
            ]
        ];
    }

    private function getFoodManagementData($period)
    {
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
                $startDate = now()->subMonths(3)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case '1_year':
                $startDate = now()->subYear()->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case '2_years':
                $startDate = now()->subYears(2)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            default:
                $startDate = now()->subDays(6)->startOfDay();
                $endDate = now()->endOfDay();
        }

        // Lấy Top 10 món ăn theo doanh thu trong khoảng thời gian, chỉ tính booking paid
        $foodRows = DB::table('food_order_items as foi')
            ->join('bookings as b', 'foi.booking_id', '=', 'b.id')
            ->join('food_variants as fv', 'foi.food_variant_id', '=', 'fv.id')
            ->join('food_items as fi', 'fv.food_item_id', '=', 'fi.id')
            ->where('b.status', 'paid')
            ->whereBetween('b.created_at', [$startDate, $endDate])
            ->groupBy('fi.id', 'fi.name')
            ->select('fi.name', DB::raw('SUM(foi.quantity) as total_qty'), DB::raw('SUM(foi.price) as total_revenue'))
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();

        // Lấy Top 10 phim theo doanh thu trong khoảng thời gian
        $movieRows = DB::table('bookings as b')
            ->join('showtimes as s', 'b.showtime_id', '=', 's.id')
            ->join('movies as m', 's.movie_id', '=', 'm.id')
            ->where('b.status', 'paid')
            ->whereBetween('b.created_at', [$startDate, $endDate])
            ->groupBy('m.id', 'm.title')
            ->select('m.title', DB::raw('COUNT(*) as total_tickets'), DB::raw('SUM(b.total_price) as total_revenue'))
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();

        // Xử lý dữ liệu đồ ăn
        $foodLabels = [];
        $foodRevenue = [];
        $foodQuantity = [];

        if (!$foodRows->isEmpty()) {
            foreach ($foodRows as $r) {
                $foodLabels[] = $r->name ?? 'N/A';
                $foodRevenue[] = (int) $r->total_revenue;
                $foodQuantity[] = (int) $r->total_qty;
            }
        } else {
            $foodLabels = ['Không có dữ liệu đồ ăn'];
            $foodRevenue = [0];
            $foodQuantity = [0];
        }

        // Xử lý dữ liệu phim
        $movieLabels = [];
        $movieRevenue = [];
        $movieTickets = [];

        if (!$movieRows->isEmpty()) {
            foreach ($movieRows as $r) {
                $movieLabels[] = $r->title ?? 'N/A';
                $movieRevenue[] = (int) $r->total_revenue;
                $movieTickets[] = (int) $r->total_tickets;
            }
        } else {
            $movieLabels = ['Không có dữ liệu phim'];
            $movieRevenue = [0];
            $movieTickets = [0];
        }

        return [
            'labels' => $foodLabels,
            'revenue' => $foodRevenue,
            'quantity' => $foodQuantity,
            'movieLabels' => $movieLabels,
            'movieRevenue' => $movieRevenue,
            'movieTickets' => $movieTickets,
        ];
    }

    private function getCityRevenueData($period)
    {
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
                $startDate = now()->subMonths(3)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case '1_year':
                $startDate = now()->subYear()->startOfDay();
                $endDate = now()->endOfDay();
                break;
            case '2_years':
                $startDate = now()->subYears(2)->startOfDay();
                $endDate = now()->endOfDay();
                break;
            default:
                $startDate = now()->subDays(6)->startOfDay();
                $endDate = now()->endOfDay();
        }

        // Lấy dữ liệu doanh thu theo thành phố từ users và bookings
        $cityRevenue = DB::table('users as u')
            ->join('bookings as b', 'u.id', '=', 'b.user_id')
            ->where('b.status', 'paid')
            ->whereBetween('b.created_at', [$startDate, $endDate])
            ->whereNotNull('u.address')
            ->select(
                'u.address',
                DB::raw('SUM(b.total_price) as total_revenue'),
                DB::raw('COUNT(DISTINCT b.id) as total_bookings'),
                DB::raw('COUNT(DISTINCT u.id) as total_users')
            )
            ->groupBy('u.address')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();

        $cityData = [];
        $mapData = [];

        foreach ($cityRevenue as $item) {
            $cityName = $this->extractCityFromAddress($item->address);
            if ($cityName && isset($this->cityCoordinates[$cityName])) {
                $coordinates = $this->cityCoordinates[$cityName];

                $cityData[] = [
                    'city' => $cityName,
                    'revenue' => (int) $item->total_revenue,
                    'bookings' => (int) $item->total_bookings,
                    'users' => (int) $item->total_users,
                    'percentage' => 0 // Sẽ tính sau
                ];

                $mapData[] = [
                    'city' => $cityName,
                    'x' => $coordinates['x'],
                    'y' => $coordinates['y'],
                    'revenue' => (int) $item->total_revenue,
                    'bookings' => (int) $item->total_bookings
                ];
            }
        }

        // Tính phần trăm
        $totalRevenue = array_sum(array_column($cityData, 'revenue'));
        if ($totalRevenue > 0) {
            foreach ($cityData as &$city) {
                $city['percentage'] = round(($city['revenue'] / $totalRevenue) * 100, 1);
            }
        }

        return [
            'cityData' => $cityData,
            'mapData' => $mapData,
            'totalRevenue' => $totalRevenue
        ];
    }

    private function extractCityFromAddress($address)
    {
        if (!$address) return null;

        $address = strtolower($address);

        // Danh sách các thành phố và từ khóa nhận dạng
        $cityKeywords = [
            'Hà Nội' => ['hà nội', 'ha noi', 'hn', 'thành phố hà nội'],
            'TP. Hồ Chí Minh' => ['tp. hồ chí minh', 'tp hcm', 'hồ chí minh', 'ho chi minh', 'hcm', 'sài gòn', 'saigon'],
            'Đà Nẵng' => ['đà nẵng', 'da nang', 'thành phố đà nẵng'],
            'Hải Phòng' => ['hải phòng', 'hai phong', 'thành phố hải phòng'],
            'Cần Thơ' => ['cần thơ', 'can tho', 'thành phố cần thơ'],
            'Nha Trang' => ['nha trang', 'khánh hòa', 'khanh hoa'],
            'Huế' => ['huế', 'hue', 'thừa thiên huế', 'thua thien hue'],
            'Vũng Tàu' => ['vũng tàu', 'vung tau', 'bà rịa - vũng tàu', 'ba ria vung tau'],
            'Quy Nhơn' => ['quy nhơn', 'quy nhon', 'bình định', 'binh dinh'],
            'Đà Lạt' => ['đà lạt', 'da lat', 'lâm đồng', 'lam dong'],
            'Vinh' => ['vinh', 'nghệ an', 'nghe an'],
            'Nam Định' => ['nam định', 'nam dinh'],
            'Thái Nguyên' => ['thái nguyên', 'thai nguyen'],
            'Bắc Ninh' => ['bắc ninh', 'bac ninh'],
            'Hải Dương' => ['hải dương', 'hai duong'],
            'Phú Thọ' => ['phú thọ', 'phu tho'],
            'Lào Cai' => ['lào cai', 'lao cai'],
            'Sơn La' => ['sơn la', 'son la'],
            'Lạng Sơn' => ['lạng sơn', 'lang son'],
            'Quảng Ninh' => ['quảng ninh', 'quang ninh'],
            'Thái Bình' => ['thái bình', 'thai binh'],
            'Hưng Yên' => ['hưng yên', 'hung yen'],
            'Hà Nam' => ['hà nam', 'ha nam'],
            'Ninh Bình' => ['ninh bình', 'ninh binh'],
            'Thanh Hóa' => ['thanh hóa', 'thanh hoa'],
            'Nghệ An' => ['nghệ an', 'nghe an'],
            'Hà Tĩnh' => ['hà tĩnh', 'ha tinh'],
            'Quảng Bình' => ['quảng bình', 'quang binh'],
            'Quảng Trị' => ['quảng trị', 'quang tri'],
            'Thừa Thiên Huế' => ['thừa thiên huế', 'thua thien hue'],
            'Quảng Nam' => ['quảng nam', 'quang nam'],
            'Quảng Ngãi' => ['quảng ngãi', 'quang ngai'],
            'Bình Định' => ['bình định', 'binh dinh'],
            'Phú Yên' => ['phú yên', 'phu yen'],
            'Khánh Hòa' => ['khánh hòa', 'khanh hoa'],
            'Ninh Thuận' => ['ninh thuận', 'ninh thuan'],
            'Bình Thuận' => ['bình thuận', 'binh thuan'],
            'Bình Phước' => ['bình phước', 'binh phuoc'],
            'Tây Ninh' => ['tây ninh', 'tay ninh'],
            'Bình Dương' => ['bình dương', 'binh duong'],
            'Đồng Nai' => ['đồng nai', 'dong nai'],
            'Bà Rịa - Vũng Tàu' => ['bà rịa - vũng tàu', 'ba ria vung tau'],
            'Long An' => ['long an'],
            'Tiền Giang' => ['tiền giang', 'tien giang'],
            'Bến Tre' => ['bến tre', 'ben tre'],
            'Trà Vinh' => ['trà vinh', 'tra vinh'],
            'Vĩnh Long' => ['vĩnh long', 'vinh long'],
            'Đồng Tháp' => ['đồng tháp', 'dong thap'],
            'An Giang' => ['an giang'],
            'Kiên Giang' => ['kiên giang', 'kien giang'],
            'Cà Mau' => ['cà mau', 'ca mau'],
            'Bạc Liêu' => ['bạc liêu', 'bac lieu'],
            'Sóc Trăng' => ['sóc trăng', 'soc trang'],
            'Hậu Giang' => ['hậu giang', 'hau giang'],
        ];

        foreach ($cityKeywords as $cityName => $keywords) {
            foreach ($keywords as $keyword) {
                if (strpos($address, $keyword) !== false) {
                    return $cityName;
                }
            }
        }

        return null;
    }

    private function getFilterText($period)
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
            case '1_year':
                return '1 năm gần nhất';
            case '2_years':
                return '2 năm gần nhất';
            default:
                return '7 ngày gần nhất';
        }
    }

    private function getRevenueFilterText($period)
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

        // Calculate total ticket revenue for current and last year
        $currentYearTicketRevenue = Booking::where('status', 'paid')
            ->whereYear('created_at', $currentYear)
            ->sum('total_price');

        $lastYearTicketRevenue = Booking::where('status', 'paid')
            ->whereYear('created_at', $lastYear)
            ->sum('total_price');

        // Calculate total food revenue for current and last year
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

        // Sum up all revenue sources for total annual revenue
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
        // Tính toán thống kê trước
        $this->calculateStatistics();

        // Đảm bảo dữ liệu chart được load trước khi dispatch
        $this->loadChartData();

        // Dispatch dữ liệu cho biểu đồ
        $this->dispatch('updateData',
            $this->transactionHistoryData ?? [],
            $this->revenueSourceData ?? [],
            $this->foodManagementData ?? [],
            [
                'transactionHistoryFilterText' => $this->getFilterText($this->transactionHistoryPeriod),
                'revenueSourceFilterText' => $this->getFilterText($this->revenueSourcePeriod),
                'foodManagementFilterText' => $this->getFilterText($this->foodManagementPeriod),
                'cityRevenueFilterText' => $this->getFilterText($this->cityRevenuePeriod),
            ]
        );

        // Dispatch dữ liệu cho revenue chart
        $this->dispatch('updateRevenueChart', data: $this->chartData);

        // Dispatch dữ liệu cho city revenue chart
        $this->dispatch('updateCityRevenueChart', data: $this->cityRevenueData);

        return view('livewire.admin.dasboard-chart.dashboard');
    }
}
