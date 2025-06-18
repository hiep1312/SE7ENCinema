<?php

namespace App\Livewire\Admin\Showtimes;

use Livewire\Component;
use App\Models\Movie;
use App\Models\Room;
use App\Models\Showtime;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class ShowtimeCreate extends Component
{
    public $movies;
    public $rooms;
    public $selectedMovie;
    public $selectedRoom;
    public $startTime;
    public $price;
    public $poster;

    protected $rules = [
        'selectedMovie' => 'required|exists:movies,id',
        'selectedRoom' => 'required|exists:rooms,id',
        'startTime' => 'required|date_format:Y-m-d\TH:i',
        'price' => 'nullable|numeric|min:0',
    ];

    protected $messages = [
        'selectedMovie.required' => 'Vui lòng chọn phim.',
        'selectedMovie.exists' => 'Phim đã chọn không tồn tại.',

        'selectedRoom.required' => 'Vui lòng chọn phòng chiếu.',
        'selectedRoom.exists' => 'Phòng chiếu đã chọn không tồn tại.',

        'startTime.required' => 'Vui lòng chọn thời gian bắt đầu.',
        'startTime.date_format' => 'Thời gian bắt đầu không đúng định dạng. Định dạng hợp lệ là YYYY-MM-DDTHH:MM.',

        'price.numeric' => 'Giá vé phải là một số.',
        'price.min' => 'Giá vé phải lớn hơn hoặc bằng 0.',
    ];

    private function canModifyShowtime($movieId, $roomId, $startTime): array
    {
        $movie = Movie::find($movieId);
        $room = Room::find($roomId);

        if (!$movie) {
            return ['success' => false, 'message' => 'Phim không tồn tại.'];
        }

        if (!in_array($movie->status, ['showing'])) {
            return ['success' => false, 'message' => 'Phim không trong trạng thái phù hợp để tạo suất chiếu.'];
        }

        if (!$room || $room->status !== 'active') {
            return ['success' => false, 'message' => 'Phòng chiếu không tồn tại hoặc không trong trạng thái hoạt động.'];
        }

        $start = Carbon::parse($startTime, 'Asia/Ho_Chi_Minh');
        $now = Carbon::now('Asia/Ho_Chi_Minh');
        $end = $start->copy()->addMinutes($movie->duration);

        if ($start->lte($now)) {
            return ['success' => false, 'message' => 'Không thể tạo suất chiếu ngay thời gian hiện tại.'];
        }


        // LƯU Ý: Tao có thể tạo lúc éo nào chả được mà m bắt t chỉ được tạo trong thời gian quy đinh vớ vẩn????
        if ($now->isSameDay($start)) {
            $diffInMinutes = $now->diffInMinutes($start, false);
            if ($diffInMinutes <= 59) {
                return ['success' => false, 'message' => 'Suất chiếu phải được tạo trước ít nhất 1 tiếng.'];
            }
        } else {
            $diffInHours = $now->diffInHours($start, false);
            if ($diffInHours <= 3) {
                return ['success' => false, 'message' => 'Suất chiếu phải được tạo trước ít nhất 3 giờ.'];
            }
        }


        $query = Showtime::where('room_id', $roomId)
            ->whereIn('status', ['active'])
            ->where(function ($query) use ($start, $end) {
                $query->where(function($q) use ($start, $end) {
                    $q->where('start_time', '<=', $start)->where('end_time', '>', $start);
                })->orWhere(function($q) use ($start, $end) {
                    $q->where('start_time', '<', $end)->where('end_time', '>=', $end);
                })->orWhere(function($q) use ($start, $end) {
                    $q->where('start_time', '>=', $start)->where('end_time', '<=', $end);
                });
            });

        if ($query->exists()) {
            return ['success' => false, 'message' => 'Phòng đã có suất chiếu trong khung thời gian này.'];
        }

        return ['success' => true, 'message' => ''];
    }

    public function mount(): void
    {
        $this->movies = Movie::whereIn('status', ['showing'])->get();
        $this->rooms = Room::where('status', 'active')->get();
        $this->startTime = Carbon::now('Asia/Ho_Chi_Minh')->format('Y-m-d\TH:i');
    }

    public function createShowtime()
    {
        $this->validate();

        $canModify = $this->canModifyShowtime($this->selectedMovie, $this->selectedRoom, $this->startTime);

        if (!$canModify['success']) {
            $this->addError('startTime', $canModify['message']);
            return;
        }

        $movie = Movie::find($this->selectedMovie);
        $start = Carbon::parse($this->startTime, 'Asia/Ho_Chi_Minh');
        $end = $start->copy()->addMinutes($movie->duration);

        Showtime::create([
            'movie_id' => $this->selectedMovie,
            'room_id' => $this->selectedRoom,
            'start_time' => $start,
            'end_time' => $end,
            'price' => $this->price ?: $movie->price,
            'status' => 'active',
        ]);

        return redirect()->route('admin.showtimes.index')->with('message', 'Tạo suất chiếu thành công!');
    }

    #[Layout('components.layouts.admin')]
    #[Title('Quản lý suất chiếu')]
    public function render()
    {
        return view('livewire.admin.showtimes.showtime-create', [
            'movies' => $this->movies,
            'rooms' => $this->rooms,
        ]);
    }
}