<?php

namespace App\Livewire\Admin\Showtime;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Movie;
use App\Models\Room;
use App\Models\Showtime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Broadcast;
use App\Events\ShowtimeEvent;

class ShowtimeManagement_copy extends Component
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
    public $showModal = false;
    public $showDeleteModal = false;
    public $deleteShowtimeId;
    public $showtimesData = [];
    public $isDateFiltered = false;

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'selectedMovie' => 'required|exists:movies,id',
        'selectedRoom' => 'required|exists:rooms,id',
        'startTime' => 'required|date_format:Y-m-d\TH:i',
        'price' => 'nullable|numeric|min:0',
    ];

    protected $editRules = [
        'editMovie' => 'required|exists:movies,id',
        'editRoom' => 'required|exists:rooms,id',
        'editStartTime' => 'required|date_format:Y-m-d\TH:i',
        'editPrice' => 'nullable|numeric|min:0',
    ];

    public function mount(): void
    {
        $this->movies = Movie::whereIn('status', ['showing'])->get(); // Lay phim dang chieu
        $this->rooms = Room::where('status', 'active')->get();
        $this->selectedDate = Carbon::today()->format('Y-m-d');
        $this->updateShowtimeStatuses();
        $this->loadShowtimes();
    }

    public function updateShowtimeStatuses(): void
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh');
        // Cập nhật tất cả showtimes đã kết thúc
        $completedShowtimeIds = Showtime::where('status', 'active')
            ->where('end_time', '<=', $now)
            ->pluck('id');

        if ($completedShowtimeIds->count() > 0) {
            Showtime::whereIn('id', $completedShowtimeIds)->update(['status' => 'completed']);
            Log::info('Updated showtimes to completed status', ['ids' => $completedShowtimeIds->toArray(), 'timestamp' => $now]);
        }
    }

    public function getRealTimeStatus($showtime): string
    {
        if ($showtime->status === 'canceled') {
            return 'canceled';
        }

        $now = Carbon::now('Asia/Ho_Chi_Minh');
        $endTime = Carbon::parse($showtime->end_time, 'Asia/Ho_Chi_Minh');

        // Nếu đã kết thúc thì cập nhật status trong DB và trả về completed
        if ($endTime->lte($now)) {
            if ($showtime->status !== 'completed') {
                $showtime->update(['status' => 'completed']);
            }
            return 'completed';
        }

        return $showtime->status; // Trả về status hiện tại từ DB
    }

    // Sửa lại hàm canEditShowtime
    public function canEditShowtime($showtime): bool
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh');
        $startTime = Carbon::parse($showtime->start_time, 'Asia/Ho_Chi_Minh');
        $endTime = Carbon::parse($showtime->end_time, 'Asia/Ho_Chi_Minh');

        // Kiểm tra trạng thái real-time
        $realTimeStatus = $this->getRealTimeStatus($showtime);

        // Kiểm tra trạng thái suất chiếu
        if (in_array($realTimeStatus, ['completed', 'canceled'])) {
            return false;
        }

        // Kiểm tra phim có tồn tại
        $movie = $showtime->movie;
        if (!$movie) {
            return false;
        }

        // Kiểm tra trạng thái phim
        if (in_array($movie->status, ['ended'])) {
            return false;
        }

        // Nếu đã bắt đầu chiếu thì không cho chỉnh sửa
        if ($now->gte($startTime)) {
            return false;
        }

        // Nếu cùng ngày, phải trước ít nhất 59 phút
        if ($now->isSameDay($startTime)) {
            $diffInMinutes = $now->diffInMinutes($startTime);
            if ($diffInMinutes < 59) {
                return false;
            }

            return true;
        }

        // Sửa logic: Nếu khác ngày thì phải trước ít nhất 1 ngày (24 giờ)
        $diffInHours = $now->diffInHours($startTime, false);
        if ($diffInHours < 24) {
            return false;
        }

        $diffInDays = $now->diffInDays($startTime, false);
        return true;
    }

        // Sửa lại hàm canDeleteShowtime
    public function canDeleteShowtime($showtimeId): array
    {
        $showtime = Showtime::find($showtimeId);

        if (!$showtime) {
            return ['success' => false, 'message' => 'Suất chiếu không tồn tại.'];
        }

        if (!$showtime->movie) {
            return ['success' => false, 'message' => 'Không thể xóa vì phim đã bị xóa khỏi hệ thống.'];
        }

        if (!$showtime->room) {
            return ['success' => false, 'message' => 'Không thể xóa vì phòng chiếu đã bị xóa khỏi hệ thống.'];
        }

        $now = Carbon::now('Asia/Ho_Chi_Minh');
        $startTime = Carbon::parse($showtime->start_time, 'Asia/Ho_Chi_Minh');
        $endTime = Carbon::parse($showtime->end_time, 'Asia/Ho_Chi_Minh');

        // Kiểm tra trạng thái real-time
        $realTimeStatus = $this->getRealTimeStatus($showtime);

        // Không thể xóa nếu đã hoàn thành
        if ($realTimeStatus === 'completed') {
            return ['success' => false, 'message' => 'Không thể xóa suất chiếu đã hoàn thành.'];
        }

        // Không thể xóa nếu đã bắt đầu hoặc đang chiếu
        if ($now->gte($startTime)) {
            return ['success' => false, 'message' => 'Không thể xóa suất chiếu đã bắt đầu hoặc đang diễn ra.'];
        }

        // Nếu cùng ngày, phải trước ít nhất 59 phút
        if ($now->isSameDay($startTime)) {
            $diffInMinutes = $now->diffInMinutes($startTime, false);
            if ($diffInMinutes < 59) {
                return [
                    'success' => false,
                    'message' => "Chỉ có thể xóa suất chiếu trước ít nhất 1 tiếng so với thời gian chiếu. Thời gian còn lại: {$diffInMinutes} phút."
                ];
            }
        } else {
            // Sửa logic: Nếu khác ngày thì phải trước ít nhất 1 ngày (24 giờ)
            $diffInHours = $now->diffInHours($startTime, false);
            if ($diffInHours < 24) {
                return [
                    'success' => false,
                    'message' => "Chỉ có thể xóa suất chiếu trước ít nhất 24 giờ. Thời gian còn lại: " . round($diffInHours, 1) . " giờ."
                ];
            }
        }

        return ['success' => true, 'message' => ''];
    }

    public function loadShowtimes(): void
    {
        $this->updateShowtimeStatuses();

        $query = Showtime::with(['movie', 'room'])
            ->orderBy('start_time', 'desc');

        if ($this->isDateFiltered && $this->selectedDate) {
            $query->whereDate('start_time', $this->selectedDate);
        }

        if ($this->searchMovie || $this->searchFormat) {
            $query->whereHas('movie', function ($q) {
                if ($this->searchMovie) {
                    $q->where('title', 'like', '%' . $this->searchMovie . '%');
                }
                if ($this->searchFormat) {
                    $q->where('format', $this->searchFormat);
                }
            });
        }

        $this->showtimesData = $query->get();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['selectedMovie', 'selectedRoom', 'startTime', 'price', 'editShowtimeId', 'editMovie', 'editRoom', 'editStartTime', 'editPrice']);
        $this->resetErrorBag();
        $this->dispatch('close-modal');
    }

    public function openDeleteModal($id)
    {
        $canDelete = $this->canDeleteShowtime($id);

        if (!$canDelete['success']) {
            session()->flash('error', $canDelete['message']);
            return;
        }

        $this->deleteShowtimeId = $id;
        $this->showDeleteModal = true;
        $this->dispatch('open-delete-modal');
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->deleteShowtimeId = null;
        $this->dispatch('close-delete-modal');
    }

    // Sửa lại hàm canModifyShowtime
    private function canModifyShowtime($movieId, $roomId, $startTime, $excludeId = null): array
    {
        $movie = Movie::find($movieId);
        $room = Room::find($roomId);

        // Kiểm tra phim có tồn tại và trạng thái
        if (!$movie) {
            return ['success' => false, 'message' => 'Phim không tồn tại.'];
        }

        if (!in_array($movie->status, ['showing', 'coming_soon'])) {
            return ['success' => false, 'message' => 'Phim không trong trạng thái phù hợp để tạo suất chiếu.'];
        }

        // Kiểm tra phòng chiếu có tồn tại và hoạt động
        if (!$room || $room->status !== 'active') {
            return ['success' => false, 'message' => 'Phòng chiếu không tồn tại hoặc không trong trạng thái hoạt động.'];
        }

        $start = Carbon::parse($startTime, 'Asia/Ho_Chi_Minh');
        $now = Carbon::now('Asia/Ho_Chi_Minh');
        $end = $start->copy()->addMinutes($movie->duration);

        // Kiểm tra thời gian trong quá khứ
        if ($start->lte($now)) {
            return ['success' => false, 'message' => 'Không thể tạo suất chiếu ngay thời gian hiện tại.'];
        }

        // Kiểm tra khoảng cách tối thiểu từ hiện tại
        if ($now->isSameDay($start)) {
            // Nếu cùng ngày, phải trước ít nhất 59 phút
            $diffInMinutes = $now->diffInMinutes($start, false);
            if ($diffInMinutes < 59) {
                return ['success' => false, 'message' => 'Suất chiếu phải được tạo trước ít nhất 1 tiếng.'];
            }
        } else {
            // Nếu khác ngày, phải trước ít nhất 24 giờ
            $diffInHours = $now->diffInHours($start, false);
            if ($diffInHours < 24) {
                return ['success' => false, 'message' => 'Suất chiếu phải được tạo trước ít nhất 24 giờ.'];
            }
        }


        // Kiểm tra xung đột với các suất chiếu khác (giữ nguyên logic cũ)
        $query = Showtime::where('room_id', $roomId)
            ->whereIn('status', ['active'])
            ->where(function ($query) use ($start, $end) {
                $query->where(function($q) use ($start, $end) {
                    $q->where('start_time', '<=', $start)
                    ->where('end_time', '>', $start);
                })->orWhere(function($q) use ($start, $end) {
                    $q->where('start_time', '<', $end)
                    ->where('end_time', '>=', $end);
                })->orWhere(function($q) use ($start, $end) {
                    $q->where('start_time', '>=', $start)
                    ->where('end_time', '<=', $end);
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

    private function canUpdateShowtime($showtimeId, $movieId, $roomId, $startTime)
    {
        $showtime = Showtime::find($showtimeId);

        if (!$showtime) {
            return ['success' => false, 'message' => 'Suất chiếu không tồn tại.'];
        }

        $canEdit = $this->canEditShowtime($showtime);
        if (!$canEdit['can_edit']) {
            return ['success' => false, 'message' => $canEdit['message']];
        }

        return $this->canModifyShowtime($movieId, $roomId, $startTime, $showtimeId);
    }

        public function createShowtime(): void
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

        $this->closeModal();
        $this->loadShowtimes();
        session()->flash('message', 'Tạo suất chiếu thành công!');
    }

    public function editShowtime(int $id): void
    {
        $showtime = Showtime::with(['movie', 'room'])->findOrFail($id);

        $canEdit = $this->canEditShowtime($showtime);
        if (!$canEdit['can_edit']) {
            session()->flash('error', $canEdit['message']);
            $this->closeModal();
            return;
        }

        $this->editShowtimeId = $id;
        $this->editMovie = $showtime->movie_id;
        $this->editRoom = $showtime->room_id;
        $this->editStartTime = $showtime->start_time->format('Y-m-d\TH:i');
        $this->editPrice = $showtime->price;
    }

    private function broadcastShowtimeUpdate($showtime, $event, $message)
    {
        Broadcast::channel('showtime-channel', function () {
            return true;
        });

        broadcast(new ShowtimeEvent($showtime, $event, $message))->toOthers();
    }

    // Sửa lại hàm getShowtimeDisplayData để tính thời gian còn lại chính xác hơn
    public function getShowtimeDisplayData($showtime)
    {
        // Refresh showtime từ DB để có status mới nhất
        $showtime->refresh();

        $realTimeStatus = $this->getRealTimeStatus($showtime);
        $canEdit = $this->canEditShowtime($showtime);
        $canDeleteResult = $this->canDeleteShowtime($showtime->id);
        $now = Carbon::now('Asia/Ho_Chi_Minh');
        $startTime = Carbon::parse($showtime->start_time, 'Asia/Ho_Chi_Minh');
        $endTime = Carbon::parse($showtime->end_time, 'Asia/Ho_Chi_Minh');

        $statusMap = [
            'active' => ['class' => 'bg-primary', 'text' => 'Đang hoạt động'],
            'completed' => ['class' => 'bg-success', 'text' => 'Đã hoàn thành'],
            'canceled' => ['class' => 'bg-danger', 'text' => 'Đã hủy']
        ];

        // Tính thời gian còn lại - SỬA LOGIC
        $timeRemaining = null;
        if ($realTimeStatus === 'active') {
            if ($startTime->gt($now)) {
                // Chưa bắt đầu - hiển thị thời gian đến khi bắt đầu
                $timeRemaining = [
                    'type' => 'until_start',
                    'text' => 'Còn ' . $startTime->diffForHumans($now)
                ];
            } elseif ($startTime->lte($now) && $endTime->gt($now)) {
                // Đang chiếu - hiển thị thời gian đến khi kết thúc
                $timeRemaining = [
                    'type' => 'showing',
                    'text' => 'Đang chiếu (Kết thúc ' . $endTime->diffForHumans($now) . ')'
                ];
            }
        }

        return [
            'realTimeStatus' => $realTimeStatus,
            'canEdit' => $canEdit,
            'canDeleteResult' => $canDeleteResult,
            'now' => $now,
            'startTime' => $startTime,
            'endTime' => $endTime,
            'timeRemaining' => $timeRemaining,
            'status' => $statusMap[$realTimeStatus] ?? ['class' => 'bg-secondary', 'text' => 'N/A']
        ];
    }

        public function updateShowtime(): void
    {
        $this->validate([
            'editMovie' => 'required',
            'editRoom' => 'required',
            'editStartTime' => 'required',
            'editPrice' => 'nullable|numeric|min:0'
        ]);

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

        $this->closeModal();
        $this->loadShowtimes();
        session()->flash('message', 'Cập nhật suất chiếu thành công!');
    }

    public function confirmDeleteShowtime(): void
    {
        $canDelete = $this->canDeleteShowtime($this->deleteShowtimeId);

        if (!$canDelete['success']) {
            session()->flash('error', $canDelete['message']);
            $this->closeDeleteModal();
            return;
        }

        $showtime = Showtime::findOrFail($this->deleteShowtimeId);

        $this->broadcastShowtimeUpdate(
            $showtime,
            'showtime-deleted',
            'Suất chiếu đã bị xóa'
        );

        $showtime->forceDelete();

        $this->closeDeleteModal();
        $this->loadShowtimes();
        session()->flash('message', 'Xóa suất chiếu thành công!');
    }

    public function deleteShowtime(int $id): void
    {
        $this->openDeleteModal($id);
    }

    public function updatedSearchMovie()
    {
        $this->resetPage();
        $this->loadShowtimes();
    }

    public function updatedSearchFormat()
    {
        $this->resetPage();
        $this->loadShowtimes();
    }

    public function refreshData()
    {
        try {
            $this->updateShowtimeStatuses();
            $this->loadShowtimes();

            // Không flash message khi auto refresh để tránh spam
            // if (!request()->header('X-Livewire')) {
            //     session()->flash('message', 'Dữ liệu đã được làm mới tự động.');
            // }

            $this->dispatch('data-refreshed');
        } catch (\Exception $e) {
            Log::error('Auto refresh failed: ' . $e->getMessage());
        }
    }

    public function resetAllFilters()
    {
        $this->searchMovie = '';
        $this->searchFormat = '';
        $this->selectedDate = Carbon::today()->format('Y-m-d');
        $this->isDateFiltered = false;
        $this->resetPage();
        $this->loadShowtimes();
    }

    public function render()
    {
        $this->updateShowtimeStatuses();

        $query = Showtime::with(['movie', 'room'])
            ->orderBy('start_time', 'desc');

        if ($this->isDateFiltered && $this->selectedDate) {
            $query->whereDate('start_time', $this->selectedDate);
        }

        if ($this->searchMovie || $this->searchFormat) {
            $query->whereHas('movie', function ($q) {
                if ($this->searchMovie) {
                    $q->where('title', 'like', '%' . $this->searchMovie . '%');
                }
                if ($this->searchFormat) {
                    $q->where('format', $this->searchFormat);
                }
            });
        }

        $showtimes = $query->paginate(15);
        $movies = $this->movies;
        $rooms = $this->rooms;

        return view('livewire.admin.showtime.showtime-management', compact('showtimes', 'movies', 'rooms'));
    }
}
