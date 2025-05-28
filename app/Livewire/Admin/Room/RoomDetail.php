<?php

namespace App\Livewire\Admin\Room;

use App\Models\Room;
use Livewire\Component;
use Carbon\Carbon;

class RoomDetail extends Component
{
    public $roomId;
    public $room;
    public $activeTab = 'overview';

    // Statistics
    public $totalBookings = 0;
    public $totalRevenue = 0;
    public $occupancyRate = 0;
    public $upcomingShowtimes = [];
    public $recentBookings = [];

    public function mount($roomId)
    {
        $this->roomId = $roomId;
        $this->room = Room::with([
            'seats',
            'showtimes' => function($query) {
                $query->with(['movie' => function($movieQuery) {
                        $movieQuery->withTrashed();
                    }])
                    ->orderBy('start_time', 'desc')
                    ->limit(10);
            }
        ])->findOrFail($roomId);

        $this->calculateStatistics();
        $this->loadUpcomingShowtimes();
        // $this->loadRecentBookings(); // Tạm comment vì chưa có booking model
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    private function calculateStatistics()
    {
        // Tính số suất chiếu trong 30 ngày qua (thay vì booking vì chưa có)
        $this->totalBookings = $this->room->showtimes()
            ->where('start_time', '>=', now()->subDays(30))
            ->where('status', 'completed')
            ->count();

        // Tính tổng doanh thu ước tính (giá suất chiếu * capacity)
        $completedShowtimes = $this->room->showtimes()
            ->where('start_time', '>=', now()->subDays(30))
            ->where('status', 'completed')
            ->get();

        $this->totalRevenue = $completedShowtimes->sum(function($showtime) {
            return $showtime->price * $this->room->capacity; // Ước tính full house
        });

        // Tính tỷ lệ lấp đầy (ước tính)
        $totalShowtimes = $this->room->showtimes()
            ->where('start_time', '>=', now()->subDays(30))
            ->count();

        if ($totalShowtimes > 0) {
            $this->occupancyRate = round(($this->totalBookings / $totalShowtimes) * 100, 2);
        }
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

    public function render()
    {
        return view('livewire.admin.rooms.room-detail');
    }
}
