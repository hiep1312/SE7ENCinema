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
    public $seatStatusData = [];     // Mới: tình trạng ghế
    public $topMoviesData = [];

    // Chart periods (kept for backward compatibility)
    public $roomStatsPeriod = 'monthly';
    public $occupancyPeriod = 'monthly';
    public $seatStatusPeriod = 'monthly';
    public $moviesPeriod = 'monthly';

    // NEW: Dynamic filter options for charts
    public $availableYears = [], $availableMonths = [], $availableDays = [];

    // Room Stats filters
    public $roomStatsYear, $roomStatsMonth, $roomStatsDay;

    // Occupancy filters
    public $occupancyYear, $occupancyMonth, $occupancyDay;

    // Seat Status filters
    public $seatStatusYear, $seatStatusMonth, $seatStatusDay;

    // Top Movies filters
    public $topMoviesYear, $topMoviesMonth, $topMoviesDay;

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
            'showtimes' => function($query) {
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

        // Set default values for all charts
        $defaultYear = $this->availableYears[0] ?? now()->year;
        $this->availableMonths = $this->getAvailableMonths($defaultYear);
        $defaultMonth = $this->availableMonths[0] ?? now()->month;
        $this->availableDays = $this->getAvailableDays($defaultYear, $defaultMonth);
        $defaultDay = $this->availableDays[0] ?? now()->day;

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

        // Top Movies
        $this->topMoviesYear = $defaultYear;
        $this->topMoviesMonth = $defaultMonth;
        $this->topMoviesDay = $defaultDay;
    }

    // NEW: Get available years/months/days with data
    private function getAvailableYears()
    {
        return Booking::join('showtimes', 'bookings.showtime_id', '=', 'showtimes.id')
            ->where('showtimes.room_id', $this->room->id)
            ->selectRaw('YEAR(bookings.created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();
    }

    private function getAvailableMonths($year = null)
    {
        $year = $year ?? now()->year;
        return Booking::join('showtimes', 'bookings.showtime_id', '=', 'showtimes.id')
            ->where('showtimes.room_id', $this->room->id)
            ->whereYear('bookings.created_at', $year)
            ->selectRaw('MONTH(bookings.created_at) as month')
            ->distinct()
            ->orderBy('month')
            ->pluck('month')
            ->toArray();
    }

    private function getAvailableDays($year = null, $month = null)
    {
        $year = $year ?? now()->year;
        $month = $month ?? now()->month;
        return Booking::join('showtimes', 'bookings.showtime_id', '=', 'showtimes.id')
            ->where('showtimes.room_id', $this->room->id)
            ->whereYear('bookings.created_at', $year)
            ->whereMonth('bookings.created_at', $month)
            ->selectRaw('DAY(bookings.created_at) as day')
            ->distinct()
            ->orderBy('day')
            ->pluck('day')
            ->toArray();
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

    // NEW: Room Stats filter methods
    public function changeRoomStatsYear($year)
    {
        $this->roomStatsYear = $year;
        $this->availableMonths = $this->getAvailableMonths($year);
        $this->roomStatsMonth = $this->availableMonths[0] ?? 1;
        $this->availableDays = $this->getAvailableDays($year, $this->roomStatsMonth);
        $this->roomStatsDay = $this->availableDays[0] ?? 1;
        $this->loadChartData();
    }

    public function changeRoomStatsMonth($month)
    {
        $this->roomStatsMonth = $month;
        $this->availableDays = $this->getAvailableDays($this->roomStatsYear, $month);
        $this->roomStatsDay = $this->availableDays[0] ?? 1;
        $this->loadChartData();
    }

    public function changeRoomStatsDay($day)
    {
        $this->roomStatsDay = $day;
        $this->loadChartData();
    }

    // NEW: Occupancy filter methods
    public function changeOccupancyYear($year)
    {
        $this->occupancyYear = $year;
        $this->availableMonths = $this->getAvailableMonths($year);
        $this->occupancyMonth = $this->availableMonths[0] ?? 1;
        $this->availableDays = $this->getAvailableDays($year, $this->occupancyMonth);
        $this->occupancyDay = $this->availableDays[0] ?? 1;
        $this->loadChartData();
    }

    public function changeOccupancyMonth($month)
    {
        $this->occupancyMonth = $month;
        $this->availableDays = $this->getAvailableDays($this->occupancyYear, $month);
        $this->occupancyDay = $this->availableDays[0] ?? 1;
        $this->loadChartData();
    }

    public function changeOccupancyDay($day)
    {
        $this->occupancyDay = $day;
        $this->loadChartData();
    }

    // NEW: Seat Status filter methods
    public function changeSeatStatusYear($year)
    {
        $this->seatStatusYear = $year;
        $this->availableMonths = $this->getAvailableMonths($year);
        $this->seatStatusMonth = $this->availableMonths[0] ?? 1;
        $this->availableDays = $this->getAvailableDays($year, $this->seatStatusMonth);
        $this->seatStatusDay = $this->availableDays[0] ?? 1;
        $this->loadChartData();
    }

    public function changeSeatStatusMonth($month)
    {
        $this->seatStatusMonth = $month;
        $this->availableDays = $this->getAvailableDays($this->seatStatusYear, $month);
        $this->seatStatusDay = $this->availableDays[0] ?? 1;
        $this->loadChartData();
    }

    public function changeSeatStatusDay($day)
    {
        $this->seatStatusDay = $day;
        $this->loadChartData();
    }

    // NEW: Top Movies filter methods
    public function changeTopMoviesYear($year)
    {
        $this->topMoviesYear = $year;
        $this->availableMonths = $this->getAvailableMonths($year);
        $this->topMoviesMonth = $this->availableMonths[0] ?? 1;
        $this->availableDays = $this->getAvailableDays($year, $this->topMoviesMonth);
        $this->topMoviesDay = $this->availableDays[0] ?? 1;
        $this->loadChartData();
    }

    public function changeTopMoviesMonth($month)
    {
        $this->topMoviesMonth = $month;
        $this->availableDays = $this->getAvailableDays($this->topMoviesYear, $month);
        $this->topMoviesDay = $this->availableDays[0] ?? 1;
        $this->loadChartData();
    }

    public function changeTopMoviesDay($day)
    {
        $this->topMoviesDay = $day;
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

    public function changeMoviesPeriod($period)
    {
        $this->moviesPeriod = $period;
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

        // 4. Top phim được xem nhiều nhất trong phòng này
        $this->topMoviesData = $this->getTopMoviesData($this->moviesPeriod);

        // Dispatch event to re-render charts when data changes
        if ($this->tabCurrent === 'analytics') {
            $this->dispatch('tabChanged', 'analytics');
        }
    }

    // UPDATED: Modified to use dynamic filters
    private function getAllRoomsStatsData($period)
    {
        // Use dynamic filters based on current selection
        $dateCondition = $this->getDateConditionForRoomStats($period);

        // Lấy thống kê tất cả phòng
        $roomStats = Room::select('rooms.id', 'rooms.name')
            ->leftJoin('showtimes', 'rooms.id', '=', 'showtimes.room_id')
            ->leftJoin('bookings', function($join) use ($dateCondition) {
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

        // Nếu không có dữ liệu, tạo dữ liệu mẫu cho tất cả phòng
        if ($roomStats->isEmpty()) {
            $allRooms = Room::select('id', 'name')->get();
            $labels = $allRooms->pluck('name')->toArray();
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

    // UPDATED: Modified to use dynamic filters
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

    // UPDATED: Modified to use dynamic filters
    private function getSeatStatusData($period)
    {
        $dateCondition = $this->getDateConditionForSeatStatus($period);

        // Tổng số ghế trong phòng
        $totalSeats = $this->room->seats->count();

        $query = BookingSeat::join('bookings', 'booking_seats.booking_id', '=', 'bookings.id')
            ->join('showtimes', 'bookings.showtime_id', '=', 'showtimes.id')
            ->join('seats', 'booking_seats.seat_id', '=', 'seats.id')
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

        // Ghế đã được đặt trong khoảng thời gian
        $bookedSeats = $query->distinct('booking_seats.seat_id')->count();

        // Ghế trống
        $availableSeats = $totalSeats - $bookedSeats;

        // Thống kê theo loại ghế
        $seatsByType = $this->room->seats->groupBy('seat_type');
        $seatTypeStats = [];

        foreach ($seatsByType as $type => $seats) {
            $typeQuery = BookingSeat::join('bookings', 'booking_seats.booking_id', '=', 'bookings.id')
                ->join('showtimes', 'bookings.showtime_id', '=', 'showtimes.id')
                ->join('seats', 'booking_seats.seat_id', '=', 'seats.id')
                ->where('showtimes.room_id', $this->room->id)
                ->where('seats.seat_type', $type)
                ->where('bookings.status', 'paid');

            // Apply same date filters
            if (isset($dateCondition['year'])) {
                $typeQuery->whereYear('bookings.created_at', $dateCondition['year']);
            }
            if (isset($dateCondition['month'])) {
                $typeQuery->whereMonth('bookings.created_at', $dateCondition['month']);
            }
            if (isset($dateCondition['day'])) {
                $typeQuery->whereDay('bookings.created_at', $dateCondition['day']);
            }

            $bookedByType = $typeQuery->distinct('booking_seats.seat_id')->count();

            $seatTypeStats[] = [
                'name' => match($type) {
                    'standard' => 'Ghế thường',
                    'vip' => 'Ghế VIP',
                    'couple' => 'Ghế đôi',
                    default => ucfirst($type)
                },
                'booked' => $bookedByType,
                'total' => $seats->count(),
                'available' => $seats->count() - $bookedByType
            ];
        }

        return [
            'total_seats' => $totalSeats,
            'booked_seats' => $bookedSeats,
            'available_seats' => $availableSeats,
            'occupancy_percentage' => $totalSeats > 0 ? round(($bookedSeats / $totalSeats) * 100, 1) : 0,
            'seat_types' => $seatTypeStats,
            // Dữ liệu cho biểu đồ
            'chart_data' => [
                'labels' => ['Đã đặt', 'Còn trống'],
                'data' => [$bookedSeats, $availableSeats],
                'colors' => ['#dc3545', '#28a745']
            ]
        ];
    }

    // UPDATED: Modified to use dynamic filters
    private function getTopMoviesData($period)
    {
        $dateCondition = $this->getDateConditionForTopMovies($period);

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
            $labels[] = $item->title;
            $ticketsData[] = (int)$item->tickets_sold;
            $revenueData[] = (int)$item->revenue;
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

    private function getDateConditionForTopMovies($period)
    {
        return [
            'year' => $this->topMoviesYear,
            'month' => $this->topMoviesMonth,
            'day' => $this->topMoviesDay
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
