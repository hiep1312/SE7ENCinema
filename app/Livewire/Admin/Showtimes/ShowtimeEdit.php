<?php

namespace App\Livewire\Admin\Showtimes;

use Livewire\Component;
use App\Models\Movie;
use App\Models\Room;
use App\Models\Showtime;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class ShowtimeEdit extends Component
{
    public $movies;
    public $rooms;
    public $editShowtimeId;
    public $editMovie;
    public $editRoom;
    public $editStartTime;
    public $editPrice;
    public $poster;

    protected $rules = [
        'editMovie' => 'required|exists:movies,id',
        'editRoom' => 'required|exists:rooms,id',
        'editStartTime' => 'required|date_format:Y-m-d\TH:i',
        'editPrice' => 'nullable|numeric|min:0',
    ];


    private function canModifyShowtime($movieId, $roomId, $startTime, $excludeId = null): array
    {
        $movie = Movie::find($movieId);
        $room = Room::find($roomId);

        if (!$movie) {
            return ['success' => false, 'message' => 'Phim không tồn tại.'];
        }

        if (!in_array($movie->status, ['showing', 'coming_soon'])) {
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

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        if ($query->exists()) {
            return ['success' => false, 'message' => 'Phòng đã có suất chiếu trong khung thời gian này.'];
        }

        return ['success' => true, 'message' => ''];
    }

    public function canEditShowtime($showtime): array
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh');
        $startTime = Carbon::parse($showtime->start_time, 'Asia/Ho_Chi_Minh');
        $endTime = Carbon::parse($showtime->end_time, 'Asia/Ho_Chi_Minh');

        $realTimeStatus = $this->getRealTimeStatus($showtime);

        if (in_array($realTimeStatus, ['completed', 'canceled'])) {
            return ['can_edit' => false, 'message' => 'Không thể chỉnh sửa suất chiếu đã hoàn thành hoặc đã hủy.'];
        }

        $movie = $showtime->movie;
        if (!$movie) {
            return ['can_edit' => false, 'message' => 'Phim không tồn tại.'];
        }

        if (in_array($movie->status, ['ended'])) {
            return ['can_edit' => false, 'message' => 'Không thể chỉnh sửa suất chiếu của phim đã kết thúc.'];
        }

        if ($now->gte($startTime)) {
            return ['can_edit' => false, 'message' => 'Không thể chỉnh sửa suất chiếu đã bắt đầu hoặc đang chiếu.'];
        }

        if ($now->isSameDay($startTime)) {
            $diffInMinutes = $now->diffInMinutes($startTime, false);
            if ($diffInMinutes <= 59) {
                return ['can_edit' => false, 'message' => "Chỉ có thể chỉnh sửa suất chiếu trước ít nhất 1 tiếng. Thời gian còn lại: {$diffInMinutes} phút."];
            }
            return ['can_edit' => true, 'message' => "Có thể chỉnh sửa. Thời gian còn lại: {$diffInMinutes} phút."];
        }

        $diffInHours = $now->diffInHours($startTime, false);
        if ($diffInHours <= 3) {
            return ['can_edit' => false, 'message' => "Chỉ có thể chỉnh sửa suất chiếu trước ít nhất 3 giờ. Thời gian còn lại: " . round($diffInHours, 1) . " giờ."];
        }

        $diffInDays = $now->diffInDays($startTime, false);
        return ['can_edit' => true, 'message' => "Có thể chỉnh sửa. Suất chiếu sau {$diffInDays} ngày."];
    }

    public function getRealTimeStatus($showtime): string
    {
        if ($showtime->status === 'canceled') {
            return 'canceled';
        }

        $now = Carbon::now('Asia/Ho_Chi_Minh');
        $endTime = Carbon::parse($showtime->end_time, 'Asia/Ho_Chi_Minh');

        if ($endTime->lte($now)) {
            if ($showtime->status !== 'completed') {
                $showtime->update(['status' => 'completed']);
            }
            return 'completed';
        }

        return $showtime->status;
    }

    public function mount(Showtime $showtime)
    {
        $this->movies = Movie::whereIn('status', ['showing'])->get();
        $this->rooms = Room::where('status', 'active')->get();
        $showtime = Showtime::with(['movie', 'room'])->findOrFail($showtime->id);

        $canEdit = $this->canEditShowtime($showtime);
        if (!$canEdit['can_edit']) {
            session()->flash('error', $canEdit['message']);
            return redirect()->route('admin.showtimes.index');
        }

        $this->editShowtimeId = $showtime->id;
        $this->editMovie = $showtime->movie_id;
        $this->editRoom = $showtime->room_id;
        $this->editStartTime = $showtime->start_time->format('Y-m-d\TH:i');
        $this->editPrice = $showtime->price;
    }

    public function updateShowtime()
    {
        $this->validate();

        $canModify = $this->canModifyShowtime($this->editMovie, $this->editRoom, $this->editStartTime, $this->editShowtimeId);

        if (!$canModify['success']) {
            $this->addError('editStartTime', $canModify['message']);
            return;
        }

        $showtime = Showtime::find($this->editShowtimeId);
        $movie = Movie::find($this->editMovie);
        $start = Carbon::parse($this->editStartTime, 'Asia/Ho_Chi_Minh');
        $end = $start->copy()->addMinutes($movie->duration);


        $showtime->update([
            'movie_id' => $this->editMovie,
            'room_id' => $this->editRoom,
            'start_time' => $start,
            'end_time' => $end,
            'price' => $this->editPrice ?: $movie->price,
        ]);

        return redirect()->route('admin.showtimes.index')->with('message', 'Cập nhật suất chiếu thành công!');
    }

    #[Layout('components.layouts.admin')]
    #[Title('Quản lý suất chiếu')]
    public function render()
    {
        return view('livewire.admin.showtimes.showtime-edit', [
            'movies' => $this->movies,
            'rooms' => $this->rooms,
        ]);
    }
}