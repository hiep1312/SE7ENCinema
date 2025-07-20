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
    public $activeDateTab = 0;

    public function mount()
    {
        // Tạo danh sách 4 ngày từ hôm nay
        $this->dates = collect(range(0, 3))->map(function ($day) {
            return Carbon::now()->addDays($day);
        });

        // Chọn ngày đầu tiên làm mặc định
        $this->selectedDate = $this->dates[0]->format('Y-m-d');
        $this->activeDateTab = 0;

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
        $movies = Movie::with(['showtimes' => function ($query) use ($selectedDate) {
            $query->whereDate('start_time', $selectedDate)
                  ->where('status', 'active')
                  ->orderBy('start_time');
        }, 'showtimes.room', 'genres'])
        ->whereHas('showtimes', function ($query) use ($selectedDate) {
            $query->whereDate('start_time', $selectedDate)
                  ->where('status', 'active');
        })
        ->where('status', 'showing')
        ->orderBy('title')
        ->get();

        // Tính toán số ghế trống cho mỗi showtime
        $this->calculateAvailableSeatsForCollection($movies);

        // Chỉ lọc các phim có ít nhất 1 showtime hợp lệ (chưa chiếu và còn ghế)
        $this->moviesWithShowtimes = $movies->filter(function($movie) {
            // Lọc showtimes hợp lệ cho mỗi movie
            $validShowtimes = $movie->showtimes->filter(function($showtime) {
                // Kiểm tra showtime chưa bắt đầu và còn ghế
                return $showtime->start_time->gt(now()) &&
                       isset($showtime->available_seats) &&
                       $showtime->available_seats > 0;
            });

            // Cập nhật lại showtimes của movie chỉ với những showtime hợp lệ
            $movie->showtimes = $validShowtimes->values();

            // Chỉ giữ movie nếu có ít nhất 1 showtime hợp lệ
            return $validShowtimes->count() > 0;
        })->values();
    }

    // Tính ghế trống cho collection Movie
    private function calculateAvailableSeatsForCollection($movies)
    {
        foreach ($movies as $movie) {
            foreach ($movie->showtimes as $showtime) {
                // Tính số ghế đã book (chỉ tính những booking đã thanh toán)
                $bookedSeatsCount = Booking::where('showtime_id', $showtime->id)
                    ->where('status', 'paid')
                    ->withCount('seats')
                    ->get()
                    ->sum('seats_count');

                // Tính số ghế còn trống
                $totalSeats = $showtime->room->capacity ?? 0;
                $availableSeats = $totalSeats - $bookedSeatsCount;

                // Gán vào showtime object
                $showtime->available_seats = max(0, $availableSeats);
            }
        }
    }

    public function bookShowtime($showtimeId)
    {
        // Kiểm tra showtime còn hợp lệ không trước khi booking
        $showtime = Showtime::find($showtimeId);

        if (!$showtime || $showtime->start_time->lte(now())) {
            $this->dispatch('_scToast', [
                [
                    'title' => 'Suất chiếu này đã hết hạn!',
                    'icon' => 'error',
                    'timer' => 3000,
                    'timerProgressBar' => true
                ],
                null
            ]);
            return;
        }

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
