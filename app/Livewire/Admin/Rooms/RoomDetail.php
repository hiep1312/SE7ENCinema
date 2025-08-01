<?php

namespace App\Livewire\Admin\Rooms;

use App\Models\Room;
use App\Models\Booking;
use App\Models\Showtime;
use App\Models\BookingSeat;
use App\Models\Seat;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;

class RoomDetail extends Component
{
    use WithPagination;
    public $room;
    public $tabCurrent = 'analytics';

    // Biến tính toán thống kê tổng quan
    public $totalShowtimes = 0;
    public $averageUtilization = 0;
    public $maintenanceScore = 0;

    // Biến tính toán thời gian bảo trì
    public $referenceDate;
    public $nextMaintenanceDate;
    public $maintenanceStatus = null;
    public $daysSinceLastMaintenance;
    public $totalSecondsUntilMaintenance;
    public $totalDaysIn3Months;
    public $realTimeCountdown = [];

    // Chart data properties
    public $roomStatsData = [];
    public $occupancyData = [];
    public $seatStatusData = [];
    public $roomMoviesData = [];

    // Chart periods
    public $roomStatsPeriod = '7_days';
    public $occupancyPeriod = '7_days';
    public $seatStatusPeriod = '7_days';
    public $roomMoviesPeriod = '7_days';

    // Biến chuyển đổi
    public array $daysOfWeek = [
        'Monday'    => 'Thứ hai',
        'Tuesday'   => 'Thứ ba',
        'Wednesday' => 'Thứ tư',
        'Thursday'  => 'Thứ năm',
        'Friday'    => 'Thứ sáu',
        'Saturday'  => 'Thứ bảy',
        'Sunday'    => 'Chủ nhật',
    ];

    public function mount(int $room)
    {
        $this->room = Room::with([
            'seats',
            'showtimes' => function ($query) {
                $query->with('movie')->orderBy('start_time', 'desc');
            }
        ])->findOrFail($room);

        $this->calculateStatistics();
        $this->calculateMaintenanceInfo();
        $this->loadChartData();
    }

    public function changeRoomStatsPeriod($period)
    {
        $this->roomStatsPeriod = $period;
        $this->loadChartData();
    }

    public function changeOccupancyPeriod($period)
    {
        $this->occupancyPeriod = $period;
        $this->loadChartData();
    }

    public function changeSeatStatusPeriod($period)
    {
        $this->seatStatusPeriod = $period;
        $this->loadChartData();
    }

    public function changeRoomMoviesPeriod($period)
    {
        $this->roomMoviesPeriod = $period;
        $this->loadChartData();
    }

    public function loadChartData()
    {
        // 1. Thống kê tất cả phòng chiếu
        $this->roomStatsData = $this->getRoomStatsData($this->roomStatsPeriod);

        // 2. Tỷ lệ lấp đầy
        $this->occupancyData = $this->getOccupancyData($this->occupancyPeriod);

        // 3. Dữ liệu trạng thái ghế
        $this->seatStatusData = $this->getSeatStatusData($this->seatStatusPeriod);

        // 4. Dữ liệu phim được xem nhiều nhất
        $this->roomMoviesData = $this->getRoomMoviesData($this->roomMoviesPeriod);
    }

    private function getRoomStatsData($period)
    {
        $query = Room::select('rooms.id', 'rooms.name')
            ->leftJoin('showtimes', 'rooms.id', '=', 'showtimes.room_id')
            ->leftJoin('bookings', function ($join) {
                $join->on('showtimes.id', '=', 'bookings.showtime_id')
                    ->where('bookings.status', '=', 'paid');
            })
            ->leftJoin('booking_seats', 'bookings.id', '=', 'booking_seats.booking_id');

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

        $roomStats = $query->whereBetween('bookings.created_at', [$startDate, $endDate])
            ->groupBy('rooms.id', 'rooms.name')
            ->selectRaw('COUNT(booking_seats.id) as tickets_sold, COALESCE(SUM(bookings.total_price), 0) as revenue')
            ->get();

        // Better handling of empty data
        if ($roomStats->isEmpty() || $roomStats->every(fn($room) => $room->tickets_sold == 0)) {
            // Get all rooms for consistent display
            $allRooms = Room::select('id', 'name')->get();
            $labels = $allRooms->pluck('name')->toArray();

            // Ensure we have at least one data point
            if (empty($labels)) {
                $labels = ['Không có dữ liệu'];
            }

            $ticketsData = array_fill(0, count($labels), 0);
            $revenueData = array_fill(0, count($labels), 0);
        } else {
            $labels = $roomStats->pluck('name')->toArray();
            $ticketsData = $roomStats->pluck('tickets_sold')->map(fn($val) => (int)$val)->toArray();
            $revenueData = $roomStats->pluck('revenue')->map(fn($val) => (int)$val)->toArray();
        }

        return [
            'labels' => $labels,
            'tickets' => $ticketsData,
            'revenue' => $revenueData
        ];
    }

    private function getOccupancyData($period)
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

        $totalBooked = BookingSeat::join('bookings', 'booking_seats.booking_id', '=', 'bookings.id')
            ->join('showtimes', 'bookings.showtime_id', '=', 'showtimes.id')
            ->where('showtimes.room_id', $this->room->id)
            ->where('bookings.status', 'paid')
            ->whereBetween('bookings.created_at', [$startDate, $endDate])
            ->count();

        $totalShowtimes = Showtime::where('room_id', $this->room->id)
            ->whereBetween('start_time', [$startDate, $endDate])
            ->count();

        $maxPossibleSeats = $this->room->capacity * max($totalShowtimes, 1);
        $occupancyRate = $maxPossibleSeats > 0 ? round(($totalBooked / $maxPossibleSeats) * 100, 1) : 0;
        $occupancyRate = min($occupancyRate, 100);

        return [
            'occupancy_rate' => $occupancyRate,
            'total_booked' => $totalBooked,
            'max_possible' => $maxPossibleSeats
        ];
    }

    private function getSeatStatusData($period)
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

        // Tổng số ghế trong phòng theo loại
        $seatsByType = $this->room->seats->groupBy('seat_type');
        $seatTypeStats = [];

        $typeNames = [
            'standard' => 'Ghế thường',
            'vip' => 'Ghế VIP',
            'couple' => 'Ghế đôi',
        ];

        $statusLabels = ['Còn trống', 'Đã đặt', 'Bảo trì'];

        // Prepare data for stacked bar chart
        $chartCategories = []; // X-axis: seat types
        $chartSeries = [
            [
                'name' => $statusLabels[0], // Còn trống
                'data' => []
            ],
            [
                'name' => $statusLabels[1], // Đã đặt
                'data' => []
            ],
            [
                'name' => $statusLabels[2], // Bảo trì
                'data' => []
            ]
        ];

        // Lặp qua tất cả các loại ghế có trong phòng
        foreach ($seatsByType as $type => $seats) {
            if ($seats->count() == 0) continue;

            // Sử dụng tên từ mảng typeNames hoặc tạo tên mặc định
            $seatTypeName = $typeNames[$type] ?? ucfirst($type);

            // Get booked seats for this type based on date filter
            $bookedCount = BookingSeat::join('bookings', 'booking_seats.booking_id', '=', 'bookings.id')
                ->join('showtimes', 'bookings.showtime_id', '=', 'showtimes.id')
                ->join('seats', 'booking_seats.seat_id', '=', 'seats.id')
                ->where('showtimes.room_id', $this->room->id)
                ->where('seats.seat_type', $type)
                ->where('bookings.status', 'paid')
                ->whereBetween('bookings.created_at', [$startDate, $endDate])
                ->distinct('booking_seats.seat_id')
                ->count();

            // Get maintenance seats (assuming seats with status 'maintenance' or 'disabled')
            $maintenanceCount = $seats->whereIn('status', ['maintenance', 'disabled'])->count();

            $totalByType = $seats->count();
            $availableCount = $totalByType - $bookedCount - $maintenanceCount;

            // Ensure non-negative values
            $availableCount = max(0, $availableCount);
            $bookedCount = max(0, $bookedCount);
            $maintenanceCount = max(0, $maintenanceCount);

            // Add to chart data
            $chartCategories[] = $seatTypeName;
            $chartSeries[0]['data'][] = $availableCount;  // Available
            $chartSeries[1]['data'][] = $bookedCount;     // Booked
            $chartSeries[2]['data'][] = $maintenanceCount; // Maintenance

            // Keep detailed stats for table display
            $seatTypeStats[] = [
                'name' => $seatTypeName,
                'type' => $type,
                'total' => $totalByType,
                'available' => $availableCount,
                'booked' => $bookedCount,
                'maintenance' => $maintenanceCount,
                'utilization_rate' => $totalByType > 0 ? round(($bookedCount / $totalByType) * 100, 1) : 0
            ];
        }

        // Nếu không có dữ liệu
        if (empty($chartCategories)) {
            return [
                'total_seats' => 0,
                'booked_seats' => 0,
                'available_seats' => 0,
                'maintenance_seats' => 0,
                'occupancy_percentage' => 0,
                'seat_types' => [],
                'chart_data' => [
                    'categories' => ['Không có ghế'],
                    'series' => [
                        ['name' => $statusLabels[0], 'data' => [0]],
                        ['name' => $statusLabels[1], 'data' => [0]],
                        ['name' => $statusLabels[2], 'data' => [0]]
                    ]
                ]
            ];
        }

        $totalSeats = $this->room->seats->count();
        $totalBooked = array_sum($chartSeries[1]['data']);
        $totalMaintenance = array_sum($chartSeries[2]['data']);
        $totalAvailable = array_sum($chartSeries[0]['data']);

        return [
            'total_seats' => $totalSeats,
            'booked_seats' => $totalBooked,
            'available_seats' => $totalAvailable,
            'maintenance_seats' => $totalMaintenance,
            'occupancy_percentage' => $totalSeats > 0 ? round(($totalBooked / $totalSeats) * 100, 1) : 0,
            'seat_types' => $seatTypeStats,
            'chart_data' => [
                'categories' => $chartCategories,
                'series' => $chartSeries
            ]
        ];
    }

    private function getRoomMoviesData($period)
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

        $data = Booking::select('movies.title', DB::raw('COUNT(booking_seats.id) as tickets_sold'), DB::raw('SUM(bookings.total_price) as revenue'))
            ->join('showtimes', 'bookings.showtime_id', '=', 'showtimes.id')
            ->join('movies', 'showtimes.movie_id', '=', 'movies.id')
            ->join('booking_seats', 'bookings.id', '=', 'booking_seats.booking_id')
            ->where('showtimes.room_id', $this->room->id)
            ->where('bookings.status', 'paid')
            ->whereBetween('bookings.created_at', [$startDate, $endDate])
            ->groupBy('movies.id', 'movies.title')
            ->orderByDesc('tickets_sold')
            ->limit(10)
            ->get();

        // Ensure we always return valid data structure
        if ($data->isEmpty()) {
            return [
                'labels' => ['Không có dữ liệu'],
                'tickets' => [0],
                'revenue' => [0]
            ];
        }

        $labels = [];
        $ticketsData = [];
        $revenueData = [];

        foreach ($data as $item) {
            $labels[] = $item->title ?? 'Không có tên';
            $ticketsData[] = max((int)$item->tickets_sold, 0);
            $revenueData[] = max((int)$item->revenue, 0);
        }

        return [
            'labels' => $labels,
            'tickets' => $ticketsData,
            'revenue' => $revenueData
        ];
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

    public function calculateMaintenanceInfo()
    {
        // Lấy ngày tham chiếu (last_maintenance_date (nếu có) hoặc created_at)
        $this->referenceDate = Carbon::parse($this->room->last_maintenance_date ?: $this->room->created_at);
        $this->totalDaysIn3Months = $this->referenceDate->copy()->diffInDays($this->referenceDate->copy()->addMonths(3));

        // Tính ngày bảo trì tiếp theo (3 tháng sau đó)
        $this->nextMaintenanceDate = $this->referenceDate->copy()->addMonths(3);

        // Tính số ngày từ lần bảo trì cuối & Tổng số giây kể từ ngày bảo trì tiếp theo
        $this->daysSinceLastMaintenance = $this->referenceDate->diffInDays(now());
        $this->totalSecondsUntilMaintenance = now()->diffInSeconds($this->nextMaintenanceDate, true);

        //Tính ngày giờ phút giây kể từ ngày bảo trì tiếp theo
        $totalSeconds = $this->totalSecondsUntilMaintenance;
        $daysDiffMaintenanceDate = floor($totalSeconds / 86400);
        $hoursDiffMaintenanceDate = floor(($totalSeconds % 86400) / 3600);
        $minutesDiffMaintenanceDate = floor(($totalSeconds % 3600) / 60);
        $secondsDiffMaintenanceDate = $totalSeconds % 60;

        if ($this->nextMaintenanceDate->isPast()) $this->maintenanceStatus = 'overdue';
        else $secondsDiffMaintenanceDate += 1;

        $this->realTimeCountdown = [
            'days' => $daysDiffMaintenanceDate,
            'hours' => $hoursDiffMaintenanceDate,
            'minutes' => $minutesDiffMaintenanceDate,
            'seconds' => $secondsDiffMaintenanceDate
        ];

        // Cập nhật điểm bảo trì
        $this->calculateMaintenanceScore();
    }

    protected function calculateMaintenanceScore()
    {
        // Lấy giá trị lớn nhất của 0 và (tính toán điểm bảo trì dựa trên số ngày 3 tháng (~ 90) x 100 (=> %) (Đảo ngược thành giá trị % (100 - value)))
        $score = max(0, 100 - (($this->daysSinceLastMaintenance / $this->totalDaysIn3Months) * 100));
        $this->maintenanceScore = round($score);
    }

    protected function calculateStatistics()
    {
        $this->totalShowtimes = $this->room->showtimes()
            ->where('start_time', '>=', now()->subDays(30))
            ->count();

        /* Tính mức độ sử dụng: Tổng số suất chiếu của phòng trong 30 ngày / 30 (ngày/tháng) / 8 (suất chiếu/ngày) x 100 => % */
        $this->averageUtilization = round((($this->totalShowtimes / 30) / 8) * 100);
    }

    #[Title('Chi tiết phòng chiếu - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        $recentShowtimes = $this->room->showtimes()
            ->with('movie')
            ->where('start_time', '<=', now())
            ->orderBy('start_time', 'desc')
            ->paginate(10, ['*'], 'recent_showtimes');

        $upcomingShowtimes = $this->room->showtimes()
            ->with('movie')
            ->where('start_time', '>', now())
            ->where('status', 'active')
            ->orderBy('start_time', 'asc')
            ->paginate(10, ['*'], 'upcoming_showtimes');

        // Đảm bảo dữ liệu chart được load trước khi dispatch
        $this->loadChartData();

        ($this->tabCurrent === "analytics" || ($this->js('chartInstances = {}') || false)) && $this->dispatch('updateData',
            $this->occupancyData ?? [],
            $this->seatStatusData ?? [],
            $this->roomStatsData ?? [],
            $this->roomMoviesData ?? [],
            [
                'roomStatsFilterText' => $this->getFilterText($this->roomStatsPeriod),
                'occupancyFilterText' => $this->getFilterText($this->occupancyPeriod),
                'seatStatusFilterText' => $this->getFilterText($this->seatStatusPeriod),
                'roomMoviesFilterText' => $this->getFilterText($this->roomMoviesPeriod)
            ]
        );

        return view('livewire.admin.rooms.room-detail', compact(
            'recentShowtimes',
            'upcomingShowtimes'
        ));
    }
}
