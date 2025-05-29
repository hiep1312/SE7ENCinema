<?php

namespace App\Livewire\Admin\Rooms;

use App\Models\Room;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Collection;

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
    public $recentBookings;

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

        // Khởi tạo recentBookings là collection rỗng
        $this->recentBookings = collect();

        $this->calculateStatistics();
        $this->loadUpcomingShowtimes();
        // Tạm thời không gọi loadRecentBookings() nếu chưa có model Booking
        // $this->loadRecentBookings();
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

    // Phương thức này sẽ được sử dụng khi có model Booking
    private function loadRecentBookings()
    {
        // Khởi tạo là collection rỗng
        $this->recentBookings = collect();

        // Phần code này sẽ được uncomment khi có model Booking
        /*
        $recentShowtimes = $this->room->showtimes()
            ->with(['bookings.user', 'movie'])
            ->where('start_time', '>=', now()->subDays(7))
            ->orderBy('start_time', 'desc')
            ->limit(5)
            ->get();

        foreach ($recentShowtimes as $showtime) {
            foreach ($showtime->bookings->take(3) as $booking) {
                $this->recentBookings->push($booking);
            }
        }

        $this->recentBookings = $this->recentBookings->sortByDesc('created_at')->take(10);
        */
    }

    public function render()
    {
        return view('livewire.admin.rooms.room-detail');
    }
}
