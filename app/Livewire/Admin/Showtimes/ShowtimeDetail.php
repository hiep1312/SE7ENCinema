<?php

namespace App\Livewire\Admin\Showtimes;

use App\Models\Showtime;
use App\Models\Booking;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use SE7ENCinema\scChart;    
use App\Charts\Showtime\SeatFillRateChart;
use App\Charts\Showtime\RevenueTicketFoodChart;
use App\Models\BookingSeat;

class ShowtimeDetail extends Component
{
    use WithPagination, scChart;
    
    public $showtime;
    public $tabCurrent = 'chart';

    public $totalBookings = 0;
    public $totalRevenue = 0;
    public $occupancyRate = 0;
    public $averageTicketPrice = 0;
    public $totalSeats = 0;
    public $bookedSeats = 0;
    public $availableSeats = 0;

    public $filterChart = '7_days';

    public function mount(int $showtime)
    {
        $this->showtime = Showtime::with([
            'movie.genres',
            'room.seats'
        ])->findOrFail($showtime);

        $this->calculateStatistics();
        $this->filterChart = (string) $this->showtime->id;
    }

    protected function calculateStatistics()
    {
        // Tính tổng số ghế thực tế của phòng
        $totalSeats = $this->showtime->room->seats()->count();
        
        // Tính số vé đã bán (đếm từ booking_seats thông qua booking đã thanh toán)
        $totalTickets = BookingSeat::whereHas('booking', function($query) {
            $query->where('showtime_id', $this->showtime->id)
                  ->where('status', 'paid');
        })->count();

        // Tính tổng doanh thu từ Booking
        $this->totalRevenue = Booking::where('showtime_id', $this->showtime->id)
            ->where('status', 'paid')
            ->sum('total_price');

        // Cập nhật các thuộc tính
        $this->totalSeats = $totalSeats;
        $this->bookedSeats = $totalTickets;  // Số vé đã bán (1 ghế = 1 vé)
        $this->availableSeats = $totalSeats - $totalTickets;  // Số ghế còn trống
        $this->totalBookings = Booking::where('showtime_id', $this->showtime->id)
            ->where('status', 'paid')
            ->count();  // Số đơn hàng đã thanh toán
        
        // Tính tỉ lệ lấp đầy dựa trên số ghế thực tế
        $this->occupancyRate = $totalSeats > 0 
            ? round(($totalTickets / $totalSeats) * 100, 1) 
            : 0;

        $this->averageTicketPrice = $totalTickets > 0 ? round($this->totalRevenue / $totalTickets) : 0;
    }

    #[Title('Chi tiết suất chiếu - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        $bookings = Booking::where('showtime_id', $this->showtime->id)
            ->where('status', 'paid')
            ->with(['user', 'bookingSeats.seat'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

            // Khởi tạo chart và realtime update
            $seatFill = new SeatFillRateChart;
            $revenueTFChart = new RevenueTicketFoodChart;
    
            $this->realtimeUpdateCharts([$seatFill, $this->filterChart]);
            $this->realtimeUpdateCharts([$revenueTFChart, $this->filterChart]);

        return view(
            'livewire.admin.showtimes.showtime-detail',
            compact('bookings', 'seatFill', 'revenueTFChart')
        );
    }
}
