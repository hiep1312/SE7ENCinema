<?php

namespace App\Livewire\Admin\Showtimes;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Movie;
use App\Models\Room;
use App\Models\Showtime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Broadcast;
use App\Events\ShowtimeEvent;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class ShowtimeIndex extends Component
{
    use WithPagination;

    public $movies;
    public $rooms;
    public $selectedDate;
    public $searchMovie;
    public $searchFormat;
    public $showtimesData = [];
    public $isDateFiltered = false;

    protected $paginationTheme = 'bootstrap';

    public function mount(): void
    {
        $this->movies = Movie::whereIn('status', ['showing'])->get();
        $this->rooms = Room::where('status', 'active')->get();
        $this->selectedDate = Carbon::today()->format('Y-m-d');
        $this->loadShowtimes();
    }

    public function updateShowtimeStatuses(): void
    {
        $now = Carbon::now('Asia/Ho_Chi_Minh');
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

        if ($endTime->lte($now)) {
            if ($showtime->status !== 'completed') {
                $showtime->update(['status' => 'completed']);
            }
            return 'completed';
        }

        return $showtime->status;
    }

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

        // Nếu cùng ngày, phải trước ít nhất 60 phút
        if ($now->isSameDay($startTime)) {
            $diffInMinutes = $now->diffInMinutes($startTime);
            if ($diffInMinutes <= 59) {
                return false;
            }

            return true;
        }

        // Sửa logic: Nếu khác ngày thì phải trước ít nhất 3 tiếng
        $diffInHours = $now->diffInHours($startTime, false);
        if ($diffInHours <= 3) {
            return false;
        }

        $diffInDays = $now->diffInDays($startTime, false);
        return true;
    }

    public function canDeleteShowtime($showtimeId): array
    {
        $showtime = Showtime::with(['movie', 'room'])->find($showtimeId);

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


        if ($now->gte($startTime)) {
            return ['success' => false, 'message' => 'Không thể xóa suất chiếu đã bắt đầu.'];
        }

        if ($now->isSameDay($startTime)) {
            $diffInMinutes = $now->diffInMinutes($startTime, false);
            if ($diffInMinutes <= 59) {
                return ['success' => false, 'message' => "Chỉ có thể xóa suất chiếu trước ít nhất 1 tiếng. Thời gian còn lại: {$diffInMinutes} phút."];
            }
        } else {
            $diffInHours = $now->diffInHours($startTime, false);
            if ($diffInHours <= 3) {
                return ['success' => false, 'message' => "Chỉ có thể xóa suất chiếu trước ít nhất 3 giờ. Thời gian còn lại: " . round($diffInHours, 1) . " giờ."];
            }
        }

        return ['success' => true, 'message' => ''];
    }

    public function getShowtimeDisplayData($showtime)
    {
        $showtime->loadMissing(['movie', 'room']);
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

        $timeRemaining = null;
        if ($realTimeStatus === 'active') {
            if ($startTime->gt($now)) {
                $timeRemaining = ['type' => 'until_start', 'text' => 'Còn ' . $startTime->diffForHumans($now)];
            } elseif ($startTime->lte($now) && $endTime->gt($now)) {
                $timeRemaining = ['type' => 'showing', 'text' => 'Đang chiếu (Kết thúc ' . $endTime->diffForHumans($now) . ')'];
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

    public function loadShowtimes(): void
    {
        $this->updateShowtimeStatuses();
        $query = Showtime::with(['movie', 'room'])->orderBy('start_time', 'desc');

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

        public function filterByDate()
    {
        $this->resetPage();
        $this->isDateFiltered = true;
    }

   private function broadcastShowtimeUpdate($showtime, $event, $message)
    {
        try {
            // Đơn giản hóa - chỉ log thay vì broadcast
            Log::info("Showtime {$event}: {$message}", [
                'showtime_id' => $showtime->id,
                'movie_title' => $showtime->movie->title ?? 'N/A'
            ]);
        } catch (\Exception $e) {
            Log::error('Broadcast error: ' . $e->getMessage());
        }
    }

   public function deleteShowtime($result, $showtimeId)
    {
        // Kiểm tra kết quả từ SweetAlert
        if ($result['isConfirmed']) {
            $canDelete = $this->canDeleteShowtime($showtimeId);
            if (!$canDelete['success']) {
                session()->flash('error', $canDelete['message']);
                return;
            }

            $showtime = Showtime::with(['movie', 'room'])->find($showtimeId);
            if (!$showtime) {
                session()->flash('error', 'Suất chiếu không tồn tại. Vui lòng làm mới trang và thử lại.');
                return;
            }

            // Kiểm tra dữ liệu liên quan
            if (!$showtime->movie || !$showtime->room) {
                session()->flash('error', 'Dữ liệu suất chiếu không hợp lệ (phim hoặc phòng không tồn tại).');
                return;
            }

            try {
                $this->broadcastShowtimeUpdate($showtime, 'showtime-deleted', 'Suất chiếu đã bị xóa');
                $showtime->delete();
                $this->loadShowtimes();
                session()->flash('message', 'Xóa suất chiếu thành công!');
            } catch (\Exception $e) {
                Log::error('Delete showtime error', [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'showtime_id' => $showtimeId,
                    'exception' => $e
                ]);
                session()->flash('error', 'Có lỗi xảy ra khi xóa suất chiếu: ' . $e->getMessage());
            }
        }
    }

    public function refreshData()
    {
        try {
            $this->updateShowtimeStatuses();
            $this->loadShowtimes();
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
    }

    #[Layout('components.layouts.admin')]
    #[Title('Quản lý suất chiếu')]
    public function render()
    {
        $this->updateShowtimeStatuses();

        $query = Showtime::with(['movie', 'room'])->orderBy('start_time', 'desc')
            ->whereHas('movie', function ($q) {
                $q->where('status', 'showing');
            })
            ->whereHas('room', function ($q) {
                $q->where('status', 'active');
            });

        // Filter theo ngày
        if ($this->isDateFiltered && $this->selectedDate) {
            $query->whereDate('start_time', $this->selectedDate);
        }

        // Filter theo tên phim và format
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

        return view('livewire.admin.showtimes.showtime-index', compact('showtimes'));
    }
}