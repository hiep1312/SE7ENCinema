<?php

namespace App\Livewire\Admin\Rooms;

use App\Models\Room;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class RoomEdit extends Component
{
    public $room;
    public $name = '';
    public $status = 'active';
    public $last_maintenance_date = null;
    public $rows = 10;
    public $seatsPerRow = 15;
    public $vipRows = '';
    public $coupleRows = '';

    protected $rules = [
        'name' => 'required|string|max:255|unique:rooms,name',
        'status' => 'required|in:active,maintenance,inactive',
        'last_maintenance_date' => 'nullable|date|before_or_equal:today',
        'rows' => 'required|integer|min:5|max:26',
        'seatsPerRow' => 'required|integer|min:10|max:30',
        'vipRows' => 'nullable|string',
        'coupleRows' => 'nullable|string',
    ];

    protected $messages = [
        'name.required' => 'Tên phòng chiếu là bắt buộc',
        'name.unique' => 'Tên phòng chiếu đã tồn tại',
        'last_maintenance_date.before_or_equal' => 'Ngày bảo trì không được lớn hơn ngày hôm nay.',
        'rows.required' => 'Số hàng ghế là bắt buộc',
        'rows.min' => 'Số hàng ghế tối thiểu là 5',
        'rows.max' => 'Số hàng ghế tối đa là 26',
        'seatsPerRow.required' => 'Số ghế mỗi hàng là bắt buộc',
        'seatsPerRow.min' => 'Số ghế mỗi hàng tối thiểu là 10',
        'seatsPerRow.max' => 'Số ghế mỗi hàng tối đa là 30',
    ];

    public function mount(Room $room)
    {
        $this->room = $room;
        $this->fill($room->only('name', 'status', 'last_maintenance_date'));
        !$this->last_maintenance_date ?: ($this->last_maintenance_date = $this->last_maintenance_date->format('Y-m-d'));

        if($this->room->hasActiveShowtimes()) return to_route('admin.rooms.index')->with('error', "Không thể chỉnh sửa phòng đang có suất chiếu đang hoạt động!");
    }

    public function updateRoom()
    {
        if($this->room->hasActiveShowtimes()) return to_route('admin.rooms.index')->with('error', "Không thể chỉnh sửa phòng đang có suất chiếu đang hoạt động!");

        $this->rules['name'] = $this->rules['name'] . ',' . $this->room->id;
        $this->validate();

        $this->room->update([
            'name' => $this->name,
            'capacity' => $this->room->capacity,
            'status' => $this->status,
            'last_maintenance_date' => $this->last_maintenance_date ?: null,
        ]);

        return redirect()->route('admin.rooms.index')->with('success', 'Cập nhật phòng chiếu thành công!');
    }

    #[Title('Cập nhật phòng chiếu - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.admin.rooms.room-edit');
    }
}
