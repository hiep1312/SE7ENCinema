<?php

namespace App\Livewire\Admin\Rooms;

use App\Models\Room;
use Livewire\Component;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;

class RoomDetail extends Component
{
    use WithPagination;
    public $room;
    public $tabCurrent = 'overview';

    // Biến tính toán thống kê tống quan
    public $totalShowtimes = 0; /* Tổng số suất chiếu trong 30 */
    public $averageUtilization = 0; /* Mức độ sử dụng */
    public $maintenanceScore = 0; /* Điểm bảo trì */

    // Biến tính toán thời gian bảo trì
    public $referenceDate; /* Ngày bảo trì cuối cùng */
    public $nextMaintenanceDate; /* Ngày bảo trì tiếp theo */
    public $maintenanceStatus = null; /* Trạng thái bảo trì */
    public $daysSinceLastMaintenance; /* Số ngày từ lần bảo trì cuối cùng */
    public $totalSecondsUntilMaintenance; /* Tổng số giây kể từ ngày bảo trì tiếp theo */
    public $totalDaysIn3Months; /* Tổng số ngày trong 3 tháng */
    public $realTimeCountdown = []; /* Cập nhật thời gian thực */

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

        $this->calculateStatistics();
        $this->calculateMaintenanceInfo();
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
            ->paginate(10);

        $upcomingShowtimes = $this->room->showtimes()
            ->with('movie')
            ->where('start_time', '>', now())
            ->where('status', 'active')
            ->orderBy('start_time', 'asc')
            ->paginate(10);

        return view('livewire.admin.rooms.room-detail', compact('recentShowtimes', 'upcomingShowtimes'));
    }
}
