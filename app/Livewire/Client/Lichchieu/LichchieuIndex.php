<?php

namespace App\Livewire\Client\Lichchieu;

use Livewire\Component;

use App\Models\Movie;
use App\Models\Showtime;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class LichchieuIndex extends Component
{
    public $selectedDate;
    public $dates = [];
    public $moviesWithShowtimes = [];
    public $activeDateTab = 0; // Thêm property để quản lý tab active

    public function mount()
    {
        // Tạo danh sách 7 ngày từ hôm nay
        $this->dates = collect(range(0, 3))->map(function ($day) {
            return Carbon::now()->addDays($day);
        });

        // Chọn ngày đầu tiên làm mặc định
        $this->selectedDate = $this->dates[0]->format('Y-m-d');
        $this->activeDateTab = 0; // Tab đầu tiên active

        // FIX: Bỏ comment để tải dữ liệu phim khi khởi tạo
        $this->loadMoviesForDate();
    }

    public function selectDate($date, $tabIndex)
    {
        $this->selectedDate = $date;
        $this->activeDateTab = $tabIndex;
        $this->loadMoviesForDate();
    }

    public function loadMoviesForDate()
    {
        $selectedDate = Carbon::parse($this->selectedDate);

        // Lấy các phim có suất chiếu trong ngày được chọn
        $this->moviesWithShowtimes = Movie::with(['showtimes' => function ($query) use ($selectedDate) {
            $query->whereDate('start_time', $selectedDate)
                  ->where('status', 'active') // Giữ nguyên cho showtimes vì có thể khác với movies
                  ->orderBy('start_time');
        }, 'showtimes.room', 'genres'])
        ->whereHas('showtimes', function ($query) use ($selectedDate) {
            $query->whereDate('start_time', $selectedDate)
                  ->where('status', 'active'); // Giữ nguyên cho showtimes
        })
        ->where('status', 'showing') // FIX: Đổi từ 'active' thành 'showing' để khớp với database
        ->orderBy('title')
        ->get();

        // Tính toán số ghế trống cho mỗi showtime
        $this->calculateAvailableSeats();
    }

    private function calculateAvailableSeats()
    {
        foreach ($this->moviesWithShowtimes as $movie) {
            foreach ($movie->showtimes as $showtime) {
                // Số ghế đã được đặt cho showtime này
                $bookedSeats = Booking::where('showtime_id', $showtime->id)
                    ->whereIn('status', ['confirmed', 'paid'])
                    ->withCount('seats')
                    ->get()
                    ->sum('seats_count');

                // Số ghế trống = capacity - số ghế đã đặt
                $availableSeats = $showtime->room->capacity - $bookedSeats;

                // Thêm thuộc tính available_seats vào showtime
                $showtime->available_seats = max(0, $availableSeats);
            }
        }
    }

    public function bookShowtime($showtimeId)
    {
        // Logic đặt vé - có thể redirect đến trang đặt vé
        $this->dispatch('_scToast', [
            [
                'title' => 'Chuyển hướng đến trang đặt vé...',
                'icon' => 'info',
                'timer' => 2000,
                'timerProgressBar' => true
            ],
            null
        ]);

        // Redirect logic here
        // return redirect()->route('booking.create', ['showtime' => $showtimeId]);
    }

    public function render()
    {
        return view('livewire.client.lichchieu.lichchieuindex');
    }
}
