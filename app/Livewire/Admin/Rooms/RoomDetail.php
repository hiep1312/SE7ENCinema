<?php

namespace App\Livewire\Admin\Rooms;

use App\Charts\ChartRooms\RoomStatsData;
use App\Charts\ChartRooms\RoomOccupancyData;
use App\Charts\ChartRooms\RoomSeatStatusData;
use App\Charts\ChartRooms\RoomMoviesData;
use App\Models\Room;
use Livewire\Component;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use SE7ENCinema\scChart;

class RoomDetail extends Component
{
    use WithPagination, scChart;
    public $room;
    public $tabCurrent = 'analytics';

    public $totalShowtimes = 0;
    public $averageUtilization = 0;
    public $maintenanceScore = 0;

    public $referenceDate;
    public $nextMaintenanceDate;
    public $maintenanceStatus = null;
    public $daysSinceLastMaintenance;
    public $totalSecondsUntilMaintenance;
    public $totalDaysIn3Months;
    public $realTimeCountdown = [];

    public $roomStatsData = [];
    public $occupancyData = [];
    public $seatStatusData = [];
    public $roomMoviesData = [];

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

    public function loadChartData()
    {
        // Không cần filter theo thời gian nữa
        $this->roomStatsData = [];
        $this->occupancyData = [];
        $this->seatStatusData = [];
        $this->roomMoviesData = [];
    }

    public function calculateMaintenanceInfo()
    {
        $this->referenceDate = Carbon::parse($this->room->last_maintenance_date ?: $this->room->created_at);
        $this->totalDaysIn3Months = $this->referenceDate->copy()->diffInDays($this->referenceDate->copy()->addMonths(3));

        $this->nextMaintenanceDate = $this->referenceDate->copy()->addMonths(3);

        $currentTime = now();
        $this->daysSinceLastMaintenance = $this->referenceDate->diffInDays($currentTime);
        $this->totalSecondsUntilMaintenance = $currentTime->diffInSeconds($this->nextMaintenanceDate, true);

        $totalSeconds = $this->totalSecondsUntilMaintenance;
        $daysDiffMaintenanceDate = floor($totalSeconds / 86400);
        $hoursDiffMaintenanceDate = floor(($totalSeconds % 86400) / 3600);
        $minutesDiffMaintenanceDate = floor(($totalSeconds % 3600) / 60);
        $secondsDiffMaintenanceDate = $totalSeconds % 60;

        if ($this->nextMaintenanceDate->isPast()) {
            $this->maintenanceStatus = 'overdue';
        } else {
            $this->maintenanceStatus = null;
            $secondsDiffMaintenanceDate += 1;
        }

        $this->realTimeCountdown = [
            'days' => $daysDiffMaintenanceDate,
            'hours' => $hoursDiffMaintenanceDate,
            'minutes' => $minutesDiffMaintenanceDate,
            'seconds' => $secondsDiffMaintenanceDate
        ];

        $this->calculateMaintenanceScore();
    }

    protected function calculateMaintenanceScore()
    {
        $currentDaysSinceMaintenance = $this->referenceDate->diffInDays(now());
        $score = max(0, 100 - (($currentDaysSinceMaintenance / $this->totalDaysIn3Months) * 100));
        $this->maintenanceScore = round($score);
    }

    protected function calculateStatistics()
    {
        $this->totalShowtimes = $this->room->showtimes()
            ->where('start_time', '>=', now()->subDays(30))
            ->count();
        $this->averageUtilization = round((($this->totalShowtimes / 30) / 8) * 100);
    }

    public function updateMaintenanceRealtime()
    {
        $this->calculateMaintenanceInfo();

        return [
            'maintenanceScore' => $this->maintenanceScore,
            'daysSinceLastMaintenance' => $this->daysSinceLastMaintenance,
            'realTimeCountdown' => $this->realTimeCountdown,
            'maintenanceStatus' => $this->maintenanceStatus
        ];
    }

    #[Title('Chi tiết phòng chiếu - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        $chartRoomStatsData = new RoomStatsData($this->room);
        $chartRoomStatsData->loadData();
        $this->realtimeUpdateCharts($chartRoomStatsData);

        $chartRoomOccupancyData = new RoomOccupancyData($this->room);
        $this->realtimeUpdateCharts($chartRoomOccupancyData);

        $chartRoomSeatStatusData = new RoomSeatStatusData($this->room);
        $this->realtimeUpdateCharts($chartRoomSeatStatusData);

        $chartRoomMoviesData = new RoomMoviesData($this->room);
        $chartRoomMoviesData->loadData();
        $this->realtimeUpdateCharts($chartRoomMoviesData);

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

        $this->loadChartData();

        $this->calculateMaintenanceInfo();

        ($this->tabCurrent === "analytics" || ($this->js('chartInstances = {}') || false)) && $this->dispatch('updateData',
            $this->occupancyData ?? [],
            $this->seatStatusData ?? [],
            $this->roomStatsData ?? [],
            $this->roomMoviesData ?? []
        );

        return view('livewire.admin.rooms.room-detail', compact('recentShowtimes', 'upcomingShowtimes', 'chartRoomStatsData', 'chartRoomOccupancyData', 'chartRoomSeatStatusData', 'chartRoomMoviesData'));
    }
}
