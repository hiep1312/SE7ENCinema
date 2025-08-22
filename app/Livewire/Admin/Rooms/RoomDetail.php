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

    public $roomStatsPeriod = '7_days';
    public $occupancyPeriod = '7_days';
    public $seatStatusPeriod = '7_days';
    public $roomMoviesPeriod = '7_days';

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
        $this->referenceDate = Carbon::parse($this->room->last_maintenance_date ?: $this->room->created_at);
        $this->totalDaysIn3Months = $this->referenceDate->copy()->diffInDays($this->referenceDate->copy()->addMonths(3));

        $this->nextMaintenanceDate = $this->referenceDate->copy()->addMonths(3);
        $this->daysSinceLastMaintenance = $this->referenceDate->diffInDays(now());
        $this->totalSecondsUntilMaintenance = now()->diffInSeconds($this->nextMaintenanceDate, true);

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
        $this->calculateMaintenanceScore();
    }

    protected function calculateMaintenanceScore()
    {
        $score = max(0, 100 - (($this->daysSinceLastMaintenance / $this->totalDaysIn3Months) * 100));
        $this->maintenanceScore = round($score);
    }

    protected function calculateStatistics()
    {
        $this->totalShowtimes = $this->room->showtimes()
            ->where('start_time', '>=', now()->subDays(30))
            ->count();
        $this->averageUtilization = round((($this->totalShowtimes / 30) / 8) * 100);
    }

    #[Title('Chi tiết phòng chiếu - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        $chartRoomStatsData = new RoomStatsData();
        $this->realtimeUpdateCharts($chartRoomStatsData);
        $chartRoomOccupancyData = new RoomOccupancyData($this->room);
        $this->realtimeUpdateCharts($chartRoomOccupancyData);
        $chartRoomSeatStatusData = new RoomSeatStatusData($this->room);
        $this->realtimeUpdateCharts($chartRoomSeatStatusData);
        $chartRoomMoviesData = new RoomMoviesData($this->room);
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

        return view('livewire.admin.rooms.room-detail', compact('recentShowtimes', 'upcomingShowtimes', 'chartRoomStatsData', 'chartRoomOccupancyData', 'chartRoomSeatStatusData', 'chartRoomMoviesData'));
    }
}
