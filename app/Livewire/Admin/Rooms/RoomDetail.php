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
    // Đặt tab mặc định là 'analytics' (charts)
    public $tabCurrent = 'analytics';

    // Biến tính toán thống kê tống quan
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

    // Chart data properties (updated)
    public $allRoomsStatsData = [];  // Gộp vé bán và doanh thu theo phòng
    public $occupancyRateData = [];
    public $seatStatusData = [];     // Tình trạng ghế
    public $roomMoviesData = [];     // Phim được xem nhiều nhất của phòng

    // Chart periods (kept for backward compatibility)
public $roomStatsPeriod = '7_days';

    public $occupancyPeriod = '7_days';
    public $seatStatusPeriod = '7_days';
    public $roomMoviesPeriod = 'monthly';

    // NEW: Dynamic filter options for charts
    public $availableYears = [], $availableMonths = [], $availableDays = [];

    // Room Stats filters
    public $roomStatsYear, $roomStatsMonth, $roomStatsDay;

    // Occupancy filters
    public $occupancyYear, $occupancyMonth, $occupancyDay;

    // Seat Status filters
    public $seatStatusYear, $seatStatusMonth, $seatStatusDay;

    // Room Movies filters
    public $roomMoviesYear, $roomMoviesMonth, $roomMoviesDay;

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

        // Initialize dynamic filters
        $this->initializeFilters();

        $this->calculateStatistics();
        $this->calculateMaintenanceInfo();
        $this->loadChartData();
    }

    // NEW: Initialize dynamic filters
    private function initializeFilters()
    {
        $this->availableYears = $this->getAvailableYears();

        // Set default values for all charts - FIX: Check if arrays are empty
        $defaultYear = !empty($this->availableYears) ? $this->availableYears[0] : now()->year;
        $this->availableMonths = $this->getAvailableMonths($defaultYear);
        $defaultMonth = !empty($this->availableMonths) ? $this->availableMonths[0] : now()->month;
        $this->availableDays = $this->getAvailableDays($defaultYear, $defaultMonth);
        $defaultDay = !empty($this->availableDays) ? $this->availableDays[0] : now()->day;

        // Room Stats
        $this->roomStatsYear = $defaultYear;
        $this->roomStatsMonth = $defaultMonth;
        $this->roomStatsDay = $defaultDay;

        // Occupancy
        $this->occupancyYear = $defaultYear;
        $this->occupancyMonth = $defaultMonth;
        $this->occupancyDay = $defaultDay;

        // Seat Status
        $this->seatStatusYear = $defaultYear;
        $this->seatStatusMonth = $defaultMonth;
        $this->seatStatusDay = $defaultDay;

        // Room Movies
        $this->roomMoviesYear = $defaultYear;
        $this->roomMoviesMonth = $defaultMonth;
        $this->roomMoviesDay = $defaultDay;
    }

    // NEW: Get available years/months/days with data - FIX: Add fallback for empty data
    private function getAvailableYears()
    {
        $years = Booking::join('showtimes', 'bookings.showtime_id', '=', 'showtimes.id')
            ->where('showtimes.room_id', $this->room->id)
            ->selectRaw('YEAR(bookings.created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        // FIX: If no data, return current year
        return !empty($years) ? $years : [now()->year];
    }

    private function getAvailableMonths($year = null)
    {
        $year = $year ?? now()->year;
        $months = Booking::join('showtimes', 'bookings.showtime_id', '=', 'showtimes.id')
            ->where('showtimes.room_id', $this->room->id)
            ->whereYear('bookings.created_at', $year)
            ->selectRaw('MONTH(bookings.created_at) as month')
            ->distinct()
            ->orderBy('month')
            ->pluck('month')
            ->toArray();

        // FIX: If no data, return current month
        return !empty($months) ? $months : [now()->month];
    }

    private function getAvailableDays($year = null, $month = null)
    {
        $year = $year ?? now()->year;
        $month = $month ?? now()->month;
        $days = Booking::join('showtimes', 'bookings.showtime_id', '=', 'showtimes.id')
            ->where('showtimes.room_id', $this->room->id)
            ->whereYear('bookings.created_at', $year)
            ->whereMonth('bookings.created_at', $month)
            ->selectRaw('DAY(bookings.created_at) as day')
            ->distinct()
            ->orderBy('day')
            ->pluck('day')
            ->toArray();

        // FIX: If no data, return current day
        return !empty($days) ? $days : [now()->day];
    }

    // Test method để debug tab switching
    public function testTabChange()
    {
        $this->tabCurrent = $this->tabCurrent === 'analytics' ? 'overview' : 'analytics';
        $this->dispatch('tabChanged', $this->tabCurrent);
    }

    // Method to handle tab changes
    public function changeTab($tab)
    {
        $this->tabCurrent = $tab;

        // Dispatch custom event when switching to analytics tab to re-render charts
        if ($tab === 'analytics') {
            $this->dispatch('tabChanged', 'analytics');
        }
    }

    // NEW: Room Stats filter methods - FIX: Add proper error handling
    public function changeRoomStatsYear($year)
    {
        $this->roomStatsYear = $year;
        $this->availableMonths = $this->getAvailableMonths($year);
        $this->roomStatsMonth = !empty($this->availableMonths) ? $this->availableMonths[0] : 1;
        $this->availableDays = $this->getAvailableDays($year, $this->roomStatsMonth);
        $this->roomStatsDay = !empty($this->availableDays) ? $this->availableDays[0] : 1;
        $this->loadChartData();
    }

    public function changeRoomStatsMonth($month)
    {
        $this->roomStatsMonth = $month;
        $this->availableDays = $this->getAvailableDays($this->roomStatsYear, $month);
        $this->roomStatsDay = !empty($this->availableDays) ? $this->availableDays[0] : 1;
        $this->loadChartData();
    }

    public function changeRoomStatsDay($day)
    {
        $this->roomStatsDay = $day;
        $this->loadChartData();
    }

    // NEW: Occupancy filter methods - FIX: Add proper error handling
    public function changeOccupancyYear($year)
    {
        $this->occupancyYear = $year;
        $this->availableMonths = $this->getAvailableMonths($year);
        $this->occupancyMonth = !empty($this->availableMonths) ? $this->availableMonths[0] : 1;
        $this->availableDays = $this->getAvailableDays($year, $this->occupancyMonth);
        $this->occupancyDay = !empty($this->availableDays) ? $this->availableDays[0] : 1;
        $this->loadChartData();
    }

    public function changeOccupancyMonth($month)
    {
        $this->occupancyMonth = $month;
        $this->availableDays = $this->getAvailableDays($this->occupancyYear, $month);
        $this->occupancyDay = !empty($this->availableDays) ? $this->availableDays[0] : 1;
        $this->loadChartData();
    }

    public function changeOccupancyDay($day)
    {
        $this->occupancyDay = $day;
        $this->loadChartData();
    }

    // NEW: Seat Status filter methods - FIX: Add proper error handling
    public function changeSeatStatusYear($year)
    {
        $this->seatStatusYear = $year;
        $this->availableMonths = $this->getAvailableMonths($year);
        $this->seatStatusMonth = !empty($this->availableMonths) ? $this->availableMonths[0] : 1;
        $this->availableDays = $this->getAvailableDays($year, $this->seatStatusMonth);
        $this->seatStatusDay = !empty($this->availableDays) ? $this->availableDays[0] : 1;
        $this->loadChartData();
    }

    public function changeSeatStatusMonth($month)
    {
        $this->seatStatusMonth = $month;
        $this->availableDays = $this->getAvailableDays($this->seatStatusYear, $month);
        $this->seatStatusDay = !empty($this->availableDays) ? $this->availableDays[0] : 1;
        $this->loadChartData();
    }

    public function changeSeatStatusDay($day)
    {
        $this->seatStatusDay = $day;
        $this->loadChartData();
    }

    // NEW: Room Movies filter methods - FIX: Add proper error handling
    public function changeRoomMoviesYear($year)
    {
        $this->roomMoviesYear = $year;
        $this->availableMonths = $this->getAvailableMonths($year);
        $this->roomMoviesMonth = !empty($this->availableMonths) ? $this->availableMonths[0] : 1;
        $this->availableDays = $this->getAvailableDays($year, $this->roomMoviesMonth);
        $this->roomMoviesDay = !empty($this->availableDays) ? $this->availableDays[0] : 1;
        $this->loadChartData();
    }

    public function changeRoomMoviesMonth($month)
    {
        $this->roomMoviesMonth = $month;
        $this->availableDays = $this->getAvailableDays($this->roomMoviesYear, $month);
        $this->roomMoviesDay = !empty($this->availableDays) ? $this->availableDays[0] : 1;
        $this->loadChartData();
    }

    public function changeRoomMoviesDay($day)
    {
        $this->roomMoviesDay = $day;
        $this->loadChartData();
    }

    // UPDATED: Keep old methods for backward compatibility but update to use new filters
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
        // 1. Thống kê tất cả phòng (vé bán + doanh thu)
        $this->allRoomsStatsData = $this->getAllRoomsStatsData($this->roomStatsPeriod);

        // 2. Tỷ lệ lấp đầy phòng hiện tại
        $this->occupancyRateData = $this->getOccupancyData($this->occupancyPeriod);

        // 3. Tình trạng ghế
        $this->seatStatusData = $this->getSeatStatusData($this->seatStatusPeriod);

        // 4. Phim được xem nhiều nhất của phòng này
        $this->roomMoviesData = $this->getRoomMoviesData($this->roomMoviesPeriod);

        // Dispatch event to re-render charts when data changes
        if ($this->tabCurrent === 'analytics') {
            $this->dispatch('tabChanged', 'analytics');
        }
    }

    // UPDATED: Modified to use dynamic filters - FIX: Better data validation
    private function getAllRoomsStatsData($period)
    {
        // Use dynamic filters based on current selection
        $dateCondition = $this->getDateConditionForRoomStats($period);

        // Lấy thống kê tất cả phòng
        $roomStats = Room::select('rooms.id', 'rooms.name')
            ->leftJoin('showtimes', 'rooms.id', '=', 'showtimes.room_id')
            ->leftJoin('bookings', function ($join) use ($dateCondition) {
                $join->on('showtimes.id', '=', 'bookings.showtime_id')
                    ->where('bookings.status', '=', 'paid');

                // Apply dynamic date filters
                if (isset($dateCondition['year'])) {
                    $join->whereYear('bookings.created_at', $dateCondition['year']);
                }
                if (isset($dateCondition['month'])) {
                    $join->whereMonth('bookings.created_at', $dateCondition['month']);
                }
                if (isset($dateCondition['day'])) {
                    $join->whereDay('bookings.created_at', $dateCondition['day']);
                }
            })
            ->leftJoin('booking_seats', 'bookings.id', '=', 'booking_seats.booking_id')
            ->groupBy('rooms.id', 'rooms.name')
            ->selectRaw('COUNT(booking_seats.id) as tickets_sold, COALESCE(SUM(bookings.total_price), 0) as revenue')
            ->get();

        // FIX: Better handling of empty data
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

    // UPDATED: Modified to use dynamic filters - FIX: Better error handling
    private function getOccupancyData($period)
    {
        $dateCondition = $this->getDateConditionForOccupancy($period);

        $query = BookingSeat::join('bookings', 'booking_seats.booking_id', '=', 'bookings.id')
            ->join('showtimes', 'bookings.showtime_id', '=', 'showtimes.id')
            ->where('showtimes.room_id', $this->room->id)
            ->where('bookings.status', 'paid');

        // Apply dynamic date filters
        if (isset($dateCondition['year'])) {
            $query->whereYear('bookings.created_at', $dateCondition['year']);
        }
        if (isset($dateCondition['month'])) {
            $query->whereMonth('bookings.created_at', $dateCondition['month']);
        }
        if (isset($dateCondition['day'])) {
            $query->whereDay('bookings.created_at', $dateCondition['day']);
        }

        $totalBooked = $query->count();

        $showtimeQuery = Showtime::where('room_id', $this->room->id);

        // Apply same date filters to showtimes
        if (isset($dateCondition['year'])) {
            $showtimeQuery->whereYear('start_time', $dateCondition['year']);
        }
        if (isset($dateCondition['month'])) {
            $showtimeQuery->whereMonth('start_time', $dateCondition['month']);
        }
        if (isset($dateCondition['day'])) {
            $showtimeQuery->whereDay('start_time', $dateCondition['day']);
        }

        $totalShowtimes = $showtimeQuery->count();
        $maxPossibleSeats = $this->room->capacity * max($totalShowtimes, 1);
        $occupancyRate = $maxPossibleSeats > 0 ? round(($totalBooked / $maxPossibleSeats) * 100, 1) : 0;
        $occupancyRate = min($occupancyRate, 100);

        return [
            'occupancy_rate' => $occupancyRate,
            'total_booked' => $totalBooked,
            'max_possible' => $maxPossibleSeats
        ];
    }

    // UPDATED: Modified to use dynamic filters - FIX: Better data structure and colors
    // UPDATED: Modified to use dynamic filters - FIX: Better data structure and colors
    private function getSeatStatusData($period)
    {
        $dateCondition = $this->getDateConditionForSeatStatus($period);

        // Tổng số ghế trong phòng theo loại
        $seatsByType = $this->room->seats->groupBy('seat_type');
        $seatTypeStats = [];

        $typeNames = [
            'standard' => 'Ghế thường',
            'vip' => 'Ghế VIP',
            'couple' => 'Ghế đôi',
            // 'aisle' => 'Ghế lối đi'
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

        // FIX: Lặp qua tất cả các loại ghế có trong phòng, không chỉ những loại đã định nghĩa
        foreach ($seatsByType as $type => $seats) {
            if ($seats->count() == 0) continue;

            // FIX: Sử dụng tên từ mảng typeNames hoặc tạo tên mặc định
            $seatTypeName = $typeNames[$type] ?? ucfirst($type);

            // Get booked seats for this type based on date filter
            $bookedQuery = BookingSeat::join('bookings', 'booking_seats.booking_id', '=', 'bookings.id')
                ->join('showtimes', 'bookings.showtime_id', '=', 'showtimes.id')
                ->join('seats', 'booking_seats.seat_id', '=', 'seats.id')
                ->where('showtimes.room_id', $this->room->id)
                ->where('seats.seat_type', $type)
                ->where('bookings.status', 'paid');

            // Apply date filters
            if (isset($dateCondition['year'])) {
                $bookedQuery->whereYear('bookings.created_at', $dateCondition['year']);
            }
            if (isset($dateCondition['month'])) {
                $bookedQuery->whereMonth('bookings.created_at', $dateCondition['month']);
            }
            if (isset($dateCondition['day'])) {
                $bookedQuery->whereDay('bookings.created_at', $dateCondition['day']);
            }

            $bookedCount = $bookedQuery->distinct('booking_seats.seat_id')->count();

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

    // NEW: Get room movies data - Phim được xem nhiều nhất của phòng
    private function getRoomMoviesData($period)
    {
        $dateCondition = $this->getDateConditionForRoomMovies($period);

        $query = Booking::select('movies.title', DB::raw('COUNT(booking_seats.id) as tickets_sold'), DB::raw('SUM(bookings.total_price) as revenue'))
            ->join('showtimes', 'bookings.showtime_id', '=', 'showtimes.id')
            ->join('movies', 'showtimes.movie_id', '=', 'movies.id')
            ->join('booking_seats', 'bookings.id', '=', 'booking_seats.booking_id')
            ->where('showtimes.room_id', $this->room->id)
            ->where('bookings.status', 'paid');

        // Apply dynamic date filters
        if (isset($dateCondition['year'])) {
            $query->whereYear('bookings.created_at', $dateCondition['year']);
        }
        if (isset($dateCondition['month'])) {
            $query->whereMonth('bookings.created_at', $dateCondition['month']);
        }
        if (isset($dateCondition['day'])) {
            $query->whereDay('bookings.created_at', $dateCondition['day']);
        }

        $data = $query->groupBy('movies.id', 'movies.title')
            ->orderByDesc('tickets_sold')
            ->limit(10)
            ->get();

        // FIX: Ensure we always return valid data structure
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

    // NEW: Helper methods to get date conditions for each chart
    private function getDateConditionForRoomStats($period)
    {
        return [
            'year' => $this->roomStatsYear,
            'month' => $this->roomStatsMonth,
            'day' => $this->roomStatsDay
        ];
    }

    private function getDateConditionForOccupancy($period)
    {
        return [
            'year' => $this->occupancyYear,
            'month' => $this->occupancyMonth,
            'day' => $this->occupancyDay
        ];
    }

    private function getDateConditionForSeatStatus($period)
    {
        return [
            'year' => $this->seatStatusYear,
            'month' => $this->seatStatusMonth,
            'day' => $this->seatStatusDay
        ];
    }

    private function getDateConditionForRoomMovies($period)
    {
        return [
            'year' => $this->roomMoviesYear,
            'month' => $this->roomMoviesMonth,
            'day' => $this->roomMoviesDay
        ];
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

        return view('livewire.admin.rooms.room-detail', compact('recentShowtimes', 'upcomingShowtimes'));
    }
}
