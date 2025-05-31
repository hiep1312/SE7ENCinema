<?php

namespace App\Livewire\Admin\Rooms;

use App\Models\Room;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class RoomDetail extends Component
{
    public $roomId;
    public $room;
    public $activeTab = 'overview';

    // Statistics
    public $totalShowtimes = 0;
    public $averageUtilization = 0;
    public $maintenanceScore = 0;
    public $upcomingShowtimes = [];
    public $recentBookings;
    public $recentShowtimes;

    // Maintenance tracking
    public $nextMaintenanceDate;
    public $daysUntilMaintenance;
    public $hoursUntilMaintenance;
    public $minutesUntilMaintenance;
    public $maintenanceStatus;
    public $daysSinceLastMaintenance;

    // Thêm các thuộc tính mới cho thời gian đếm ngược chi tiết
    public $totalSecondsUntilMaintenance;
    public $realTimeCountdown = [];

    public function mount($roomId)
    {
        $this->roomId = $roomId;
        $this->room = Room::with([
            'seats',
            'showtimes' => function($query) {
                $query->with(['movie' => function($movieQuery) {
                        $movieQuery->withTrashed();
                    }])
                    ->orderBy('start_time', 'desc');
            }
        ])->findOrFail($roomId);

        // Get recent showtimes separately
        $this->recentShowtimes = $this->room->showtimes()
            ->with(['movie' => function($movieQuery) {
                $movieQuery->withTrashed();
            }])
            ->orderBy('start_time', 'desc')
            ->take(10)
            ->get();

        // Khởi tạo recentBookings là collection rỗng
        $this->recentBookings = collect();

        $this->calculateStatistics();
        $this->calculateMaintenanceInfo();
        $this->loadUpcomingShowtimes();
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    private function calculateMaintenanceInfo()
    {
        // Lấy ngày tham chiếu (last_maintenance_date hoặc created_at)
        $referenceDate = $this->room->last_maintenance_date
            ? Carbon::parse($this->room->last_maintenance_date)
            : Carbon::parse($this->room->created_at);

        // Tính ngày bảo trì tiếp theo (3 tháng từ ngày tham chiếu)
        $this->nextMaintenanceDate = $referenceDate->copy()->addMonths(3);

        // Tính số ngày từ lần bảo trì cuối
        $this->daysSinceLastMaintenance = $referenceDate->diffInDays(now());

        // Tính thời gian còn lại đến lần bảo trì tiếp theo
        $now = now();

        if ($this->nextMaintenanceDate->isPast()) {
            // Đã quá hạn bảo trì
            $this->totalSecondsUntilMaintenance = -$now->diffInSeconds($this->nextMaintenanceDate);
            $overdueDays = $now->diffInDays($this->nextMaintenanceDate);
            $overdueHours = $now->copy()->subDays($overdueDays)->diffInHours($this->nextMaintenanceDate);
            $overdueMinutes = $now->copy()->subDays($overdueDays)->subHours($overdueHours)->diffInMinutes($this->nextMaintenanceDate);
            $overdueSeconds = $now->copy()->subDays($overdueDays)->subHours($overdueHours)->subMinutes($overdueMinutes)->diffInSeconds($this->nextMaintenanceDate);

            $this->daysUntilMaintenance = -$overdueDays;
            $this->hoursUntilMaintenance = -$overdueHours;
            $this->minutesUntilMaintenance = -$overdueMinutes;
            $this->realTimeCountdown = [
                'days' => -$overdueDays,
                'hours' => -$overdueHours,
                'minutes' => -$overdueMinutes,
                'seconds' => -$overdueSeconds
            ];
            $this->maintenanceStatus = 'overdue';
        } else {
            // Còn thời gian
            $this->totalSecondsUntilMaintenance = $now->diffInSeconds($this->nextMaintenanceDate);

            $totalSeconds = $this->totalSecondsUntilMaintenance;
            $days = floor($totalSeconds / 86400);
            $hours = floor(($totalSeconds % 86400) / 3600);
            $minutes = floor(($totalSeconds % 3600) / 60);
            $seconds = $totalSeconds % 60;

            $this->daysUntilMaintenance = $days;
            $this->hoursUntilMaintenance = $hours;
            $this->minutesUntilMaintenance = $minutes;

            $this->realTimeCountdown = [
                'days' => $days,
                'hours' => $hours,
                'minutes' => $minutes,
                'seconds' => $seconds
            ];

            // Xác định trạng thái bảo trì
            if ($days <= 7) {
                $this->maintenanceStatus = 'urgent';
            } elseif ($days <= 30) {
                $this->maintenanceStatus = 'warning';
            } else {
                $this->maintenanceStatus = 'normal';
            }
        }

        // Cập nhật maintenance score dựa trên thời gian bảo trì
        $this->calculateMaintenanceScore();
    }

    // Thêm phương thức để format số với 2 chữ số thập phân
    public function formatNumber($number, $decimals = 2)
    {
        return number_format($number, $decimals, '.', ',');
    }

    // Thêm phương thức để cập nhật đếm ngược theo thời gian thực
    public function updateCountdown()
    {
        $this->calculateMaintenanceInfo();
    }

    private function calculateMaintenanceScore()
    {
        // Tính điểm bảo trì dựa trên thời gian từ lần bảo trì cuối
        $maxDays = 90; // 3 tháng
        $score = max(0, 100 - (($this->daysSinceLastMaintenance / $maxDays) * 100));

        // Điều chỉnh điểm số dựa trên trạng thái
        switch ($this->maintenanceStatus) {
            case 'overdue':
                $score = max(0, $score - 20);
                break;
            case 'urgent':
                $score = max(0, $score - 10);
                break;
        }

        $this->maintenanceScore = round($score);
    }

    private function calculateStatistics()
    {
        // Tính toán thống kê trong 30 ngày qua
        $thirtyDaysAgo = now()->subDays(30);

        $this->totalShowtimes = $this->room->showtimes()
            ->where('start_time', '>=', $thirtyDaysAgo)
            ->count();

        // Tính mức độ sử dụng trung bình (giả định)
        $totalDays = 30;
        $averageShowtimesPerDay = $this->totalShowtimes / $totalDays;
        $maxShowtimesPerDay = 8; // Giả định tối đa 8 suất/ngày
        $this->averageUtilization = round(($averageShowtimesPerDay / $maxShowtimesPerDay) * 100);
    }

    private function loadUpcomingShowtimes()
    {
        $this->upcomingShowtimes = $this->room->showtimes()
            ->with(['movie' => function($query) {
                $query->withTrashed();
            }])
            ->where('start_time', '>=', now())
            ->where('status', 'active')
            ->orderBy('start_time', 'asc')
            ->limit(5)
            ->get();
    }

    // Phương thức này sẽ được sử dụng khi có model Booking
    private function loadRecentBookings()
    {
        // Khởi tạo là collection rỗng
        $this->recentBookings = collect();
    }

    public function getMaintenanceStatusText()
    {
        switch ($this->maintenanceStatus) {
            case 'overdue':
                return 'Quá hạn bảo trì';
            case 'urgent':
                return 'Cần bảo trì gấp';
            case 'warning':
                return 'Sắp đến hạn bảo trì';
            default:
                return 'Bình thường';
        }
    }

    public function getMaintenanceStatusColor()
    {
        switch ($this->maintenanceStatus) {
            case 'overdue':
                return 'danger';
            case 'urgent':
                return 'warning';
            case 'warning':
                return 'info';
            default:
                return 'success';
        }
    }

    #[Title('Chi tiết phòng chiếu - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.admin.rooms.room-detail');
    }
}
