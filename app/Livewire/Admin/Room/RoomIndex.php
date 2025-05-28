<?php

namespace App\Livewire\Admin\Room;

use App\Models\Room;
use Livewire\Component;
use Livewire\WithPagination;

class RoomIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 5;
    public $showDeleted = false;

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingShowDeleted()
    {
        $this->resetPage();
    }

    public function deleteRoom($roomId)
    {
        try {
            $room = Room::findOrFail($roomId);

            // Kiểm tra quyền xóa
            if (!$room->canDelete()) {
                session()->flash('error', 'Không thể xóa phòng có suất chiếu trong tương lai!');
                return;
            }

            // Soft delete
            $room->delete();
            session()->flash('success', 'Xóa phòng chiếu thành công!');

        } catch (\Exception $e) {
            session()->flash('error', 'Có lỗi xảy ra khi xóa phòng chiếu: ' . $e->getMessage());
        }
    }

    public function restoreRoom($roomId)
    {
        try {
            $room = Room::withTrashed()->findOrFail($roomId);
            $room->restore();
            session()->flash('success', 'Khôi phục phòng chiếu thành công!');
        } catch (\Exception $e) {
            session()->flash('error', 'Có lỗi xảy ra khi khôi phục phòng chiếu!');
        }
    }

    public function forceDeleteRoom($roomId)
    {
        try {
            $room = Room::withTrashed()->findOrFail($roomId);

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

        } catch (\Exception $e) {
            session()->flash('error', 'Có lỗi xảy ra khi xóa vĩnh viễn phòng chiếu!');
        }
    }

    public function render()
    {
        $query = Room::query();

        // Hiển thị phòng đã xóa hoặc chưa xóa
        if ($this->showDeleted) {
            $query = Room::onlyTrashed();
        }

        $rooms = $query
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->withCount('seats')
            ->with(['showtimes' => function($query) {
                $query->where('start_time', '>=', now())
                      ->where('status', 'active')
                      ->orderBy('start_time', 'asc');
            }])
            ->orderBy('id', 'desc')
            ->paginate($this->perPage);

        return view('livewire.admin.rooms.room-index', compact('rooms'));
    }
}
