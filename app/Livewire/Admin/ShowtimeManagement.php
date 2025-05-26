<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Movie;
use App\Models\Room;
use App\Models\Showtime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use function Laravel\Prompts\search;

class ShowtimeManagement extends Component
{
    use WithPagination;

    public $movies;
    public $rooms;
    public $selectedMovie;
    public $selectedRoom;
    public $startTime;
    public $selectedDate;
    public $price;
    public $searchMovie;
    public $searchFormat;
    public $editShowtimeId;
    public $editMovie;
    public $editRoom;
    public $editStartTime;
    public $editPrice;

    public $showtimesData = [];
    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'selectedMovie' => 'required|exists:movies,id',
        'selectedRoom' => 'required|exists:rooms,id',
        'startTime' => 'required|date_format:Y-m-d\TH:i',
        'price' => 'required|numeric|min:0',
    ];

    protected $editRules = [
        'editMovie' => 'required|exists:movies,id',
        'editRoom' => 'required|exists:rooms,id',
        'editStartTime' => 'required|date_format:Y-m-d\TH:i',
        'editPrice' => 'required|numeric|min:0',
    ];

    public function mount(): void
    {
        $this->movies = Movie::where('status', 'showing')->get();
        $this->rooms = Room::where('status', 'active')->get();
        $this->selectedDate = Carbon::today()->format('Y-m-d');
        $this->loadShowtimes();
    }

    public function loadShowtimes(): void
    {
        $query = Showtime::query()
            ->with(['movie', 'room'])
            ->orderBy('start_time', 'desc');

        $this->showtimesData = $query->get(); // Lấy tất cả dữ liệu để debug, không dùng paginate ở đây
    }

    public function createShowtime(): void
    {
        $this->validate();

        $movie = Movie::find($this->selectedMovie);
        $room = Room::find($this->selectedRoom);

        if (!$movie || $movie->status !== 'showing' || !$room || $room->status !== 'active') {
            $this->addError('selectedMovie', 'Phim hoặc phòng không khả dụng.');
            return;
        }

        $start = Carbon::parse($this->startTime);
        $end = $start->copy()->addMinutes($movie->duration);

        $overlap = Showtime::where('room_id', $this->selectedRoom)
            ->where('status', 'active')
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('start_time', [$start, $end])
                    ->orWhereBetween('end_time', [$start, $end])
                    ->orWhere(function ($q) use ($start, $end) {
                        $q->where('start_time', '<=', $start)->where('end_time', '>=', $end);
                    });
            })
            ->exists();

        if ($overlap) {
            $this->addError('startTime', 'Phòng đã được đặt trong khung giờ này.');
            return;
        }

        Showtime::create([
            'movie_id' => $this->selectedMovie,
            'room_id' => $this->selectedRoom,
            'start_time' => $start,
            'end_time' => $end,
            'price' => $this->price ?: $movie->price, // Sử dụng giá mặc định từ movie nếu không nhập
            'status' => 'active',
        ]);

        $this->reset(['selectedMovie', 'selectedRoom', 'startTime', 'price']);
        $this->loadShowtimes();
        session()->flash('message', 'Tạo suất chiếu thành công!');
    }

    public function editShowtime(int $id): void
    {
        Log::info('editShowtime called', ['id' => $id, 'user' => Auth::user(), 'role' => session('role')]);

        if (!Auth::check() || (session('role', Auth::user()->role ?? 'guest') !== 'admin')) {
            Log::warning('Permission denied in editShowtime', ['role' => session('role')]);
            session()->flash('error', 'Chỉ quản trị viên mới có quyền chỉnh sửa.');
            return;
        }

        $showtime = Showtime::with(['movie', 'room'])->findOrFail($id);
        $this->editShowtimeId = $id;
        $this->editMovie = $showtime->movie_id;
        $this->editRoom = $showtime->room_id;
        $this->editStartTime = $showtime->start_time->format('Y-m-d\TH:i');
        $this->editPrice = $showtime->price;
    }

    public function updateShowtime(): void
    {
        Log::info('updateShowtime called', ['editShowtimeId' => $this->editShowtimeId, 'user' => Auth::user(), 'role' => session('role')]);

        if (!Auth::check() || (session('role', Auth::user()->role ?? 'guest') !== 'admin')) {
            Log::warning('Permission denied in updateShowtime', ['role' => session('role')]);
            session()->flash('error', 'Chỉ quản trị viên mới có quyền chỉnh sửa.');
            return;
        }

        $this->validate($this->editRules);

        $movie = Movie::find($this->editMovie);
        $room = Room::find($this->editRoom);

        if (!$movie || $movie->status !== 'showing' || !$room || $room->status !== 'active') {
            $this->addError('editMovie', 'Phim hoặc phòng không khả dụng.');
            return;
        }

        $start = Carbon::parse($this->editStartTime);
        $end = $start->copy()->addMinutes($movie->duration);

        $overlap = Showtime::where('room_id', $this->editRoom)
            ->where('id', '!=', $this->editShowtimeId)
            ->where('status', 'active')
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('start_time', [$start, $end])
                    ->orWhereBetween('end_time', [$start, $end])
                    ->orWhere(function ($q) use ($start, $end) {
                        $q->where('start_time', '<=', $start)->where('end_time', '>=', $end);
                    });
            })
            ->exists();

        if ($overlap) {
            $this->addError('editStartTime', 'Phòng đã được đặt trong khung giờ này.');
            return;
        }

        $showtime = Showtime::findOrFail($this->editShowtimeId);
        $showtime->update([
            'movie_id' => $this->editMovie,
            'room_id' => $this->editRoom,
            'start_time' => $start,
            'end_time' => $end,
            'price' => $this->editPrice,
            'status' => 'active',
        ]);

        $this->reset(['editShowtimeId', 'editMovie', 'editRoom', 'editStartTime', 'editPrice']);
        $this->loadShowtimes();
        session()->flash('message', 'Cập nhật suất chiếu thành công!');
    }

    public function deleteShowtime(int $id): void
    {
        Log::info('deleteShowtime called', ['id' => $id, 'user' => Auth::user(), 'role' => session('role')]);

        if (!Auth::check() || (session('role', Auth::user()->role ?? 'guest') !== 'admin')) {
            Log::warning('Permission denied in deleteShowtime', ['role' => session('role')]);
            session()->flash('error', 'Chỉ quản trị viên mới có quyền hủy.');
            return;
        }

        $showtime = Showtime::findOrFail($id);
        $showtime->update(['status' => 'canceled']);
        $this->loadShowtimes();
        session()->flash('message', 'Hủy suất chiếu thành công!');
    }

    public function updated($property): void
    {
        if (in_array($property, ['selectedDate', 'searchMovie', 'searchFormat'])) {
            $this->resetPage();
            $this->loadShowtimes();
        }
    }

    public function render()
    {
        $query = Showtime::query()
            ->with(['movie', 'room'])
            ->orderBy('start_time', 'desc');

        // // Lọc theo ngày chiếu nếu có chọn ngày
        // if ($this->selectedDate) {
        //     $query->whereDate('start_time', $this->selectedDate);
        // }

        // // Lọc theo tên phim và định dạng
        // if ($this->searchMovie || $this->searchFormat) {
        //     $query->whereHas('movie', function ($q) {
        //         if ($this->searchMovie) {
        //             $q->where('title', 'like', '%' . $this->searchMovie . '%');
        //         }
        //         if ($this->searchFormat) {
        //             $q->where('format', $this->searchFormat);
        //         }
        //     });
        // }

        $showtimes = $query->paginate(15);
        $isAdmin = Auth::check() && (session('role', Auth::user()->role ?? 'guest') === 'admin');
        $movies = $this->movies;
        $rooms = $this->rooms;

        return view('livewire.admin.showtime-management', compact('isAdmin', 'showtimes', 'movies', 'rooms'));
    }
}
