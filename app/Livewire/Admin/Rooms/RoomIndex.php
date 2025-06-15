<?php

namespace App\Livewire\Admin\Rooms;

use App\Models\Room;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class RoomIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $showDeleted = false;
    public $statusFilter = '';
    public $showtimeFilter = '';

    public function deleteRoom(array $status, int $roomId)
    {
        if(!$status['isConfirmed']) return;
        $room = Room::find($roomId);

        // Kiểm tra xem có suất chiếu đang hoạt động không
        if ($room->hasActiveShowtimes()) {
            session()->flash('error', 'Không thể xóa phòng có suất chiếu trong tương lai!');
            return;
        }

        // Soft delete
        $room->delete();
        session()->flash('success', 'Xóa phòng chiếu thành công!');
    }

    public function restoreRoom(int $roomId)
    {
        $room = Room::onlyTrashed()->find($roomId);

        $room->restore();
        session()->flash('success', 'Khôi phục phòng chiếu thành công!');
    }

    public function forceDeleteRoom(array $status, int $roomId)
    {
        if(!$status['isConfirmed']) return;
        $room = Room::onlyTrashed()->find($roomId);

        // Kiểm tra quyền xóa cứng
        if ($room->showtimes()->exists()) {
            session()->flash('error', 'Không thể xóa vĩnh viễn phòng có lịch sử suất chiếu!');
            return;
        }

        // Xóa tất cả ghế trước
        $room->seats()->delete();

        // Xóa cứng phòng
        $room->forceDelete();
        session()->flash('success', 'Xóa vĩnh viễn phòng chiếu thành công!');
    }

    public function resetFilters()
    {
        $this->reset(['search', 'statusFilter', 'showtimeFilter']);
        $this->resetPage();
    }

    #[Title('Danh sách phòng chiếu - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        $query = Room::query();

        // Hiển thị phòng đã xóa hoặc chưa xóa
        if ($this->showDeleted) {
            $query = Room::onlyTrashed();
        } else {
            // Áp dụng các bộ lọc chỉ khi không xem phòng đã xóa
            if ($this->statusFilter) {
                $query->where('status', $this->statusFilter);
            }

            if ($this->showtimeFilter) {
                if ($this->showtimeFilter === 'has_showtimes') {
                    $query->whereHas('showtimes', function($q) {
                        $q->where('start_time', '>=', now())
                          ->where('status', 'active');
                    });
                } elseif ($this->showtimeFilter === 'no_showtimes') {
                    $query->whereDoesntHave('showtimes', function($q) {
                        $q->where('start_time', '>=', now())
                          ->where('status', 'active');
                    });
                }
            }
        }

        $rooms = $query
            ->when($this->search, function ($query) {
                $query->withTrashed();
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->withCount('seats')
            ->with(['showtimes' => function($query) {
                $query->with('movie')
                    ->where('start_time', '>=', now())
                    ->where('status', 'active')
                    ->orderBy('start_time', 'asc')
                    ->limit(1); // Chỉ lấy suất chiếu gần nhất
            }])
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view('livewire.admin.rooms.room-index', compact('rooms'));
    }
}
