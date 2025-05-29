<?php

namespace App\Livewire\Admin\Rooms;

use App\Models\Room;
use App\Models\Seat;
use Livewire\Component;

class RoomEdit extends Component
{
    public $roomId;
    public $room;
    public $name = '';
    public $capacity = '';
    public $status = 'active';
    public $last_maintenance_date = '';

    // Seat configuration
    public $rows = 10;
    public $seatsPerRow = 15;
    public $vipRows = '';
    public $coupleRows = '';

    // Permission flags
    public $canEdit = false;
    public $canRegenerateSeats = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'capacity' => 'required|integer|min:50|max:500',
        'status' => 'required|in:active,maintenance,inactive',
        'last_maintenance_date' => 'nullable|date',
        'rows' => 'required|integer|min:5|max:26',
        'seatsPerRow' => 'required|integer|min:10|max:30',
        'vipRows' => 'nullable|string',
        'coupleRows' => 'nullable|string',
    ];

    protected $messages = [
        'name.required' => 'Tên phòng chiếu là bắt buộc',
        'capacity.required' => 'Sức chứa là bắt buộc',
        'capacity.min' => 'Sức chứa phải từ 50 ghế trở lên',
        'capacity.max' => 'Sức chứa không được vượt quá 500 ghế',
        'rows.required' => 'Số hàng ghế là bắt buộc',
        'rows.min' => 'Số hàng ghế tối thiểu là 5',
        'rows.max' => 'Số hàng ghế tối đa là 26',
        'seatsPerRow.required' => 'Số ghế mỗi hàng là bắt buộc',
        'seatsPerRow.min' => 'Số ghế mỗi hàng tối thiểu là 10',
        'seatsPerRow.max' => 'Số ghế mỗi hàng tối đa là 30',
    ];

    public function mount($roomId)
    {
        $this->roomId = $roomId;
        $this->room = Room::findOrFail($roomId);

        // Kiểm tra quyền chỉnh sửa
        $this->canEdit = $this->room->canEdit();
        $this->canRegenerateSeats = !$this->room->hasActiveShowtimes();

        if (!$this->canEdit) {
            session()->flash('warning', 'Phòng chiếu có suất chiếu đang hoạt động. Một số chức năng bị hạn chế.');
        }

        $this->name = $this->room->name;
        $this->capacity = $this->room->capacity;
        $this->status = $this->room->status;
        $this->last_maintenance_date = $this->room->last_maintenance_date?->format('Y-m-d');

        // Calculate current seat configuration
        $this->calculateCurrentSeatConfig();
    }

    public function updated($propertyName)
    {
        if (!$this->canEdit && in_array($propertyName, ['rows', 'seatsPerRow', 'vipRows', 'coupleRows'])) {
            session()->flash('error', 'Không thể thay đổi cấu hình ghế khi có suất chiếu đang hoạt động!');
            return;
        }

        $this->validateOnly($propertyName);

        if ($propertyName === 'rows' || $propertyName === 'seatsPerRow') {
            $this->capacity = $this->rows * $this->seatsPerRow;

            // Ensure capacity doesn't exceed 500
            if ($this->capacity > 500) {
                if ($propertyName === 'rows') {
                    $this->rows = floor(500 / $this->seatsPerRow);
                } else {
                    $this->seatsPerRow = floor(500 / $this->rows);
                }
                $this->capacity = $this->rows * $this->seatsPerRow;
            }

            // Ensure minimum capacity
            if ($this->capacity < 50) {
                if ($this->rows < 5) {
                    $this->rows = 5;
                }
                if ($this->seatsPerRow < 10) {
                    $this->seatsPerRow = 10;
                }
                $this->capacity = $this->rows * $this->seatsPerRow;
            }
        }
    }

    private function calculateCurrentSeatConfig()
    {
        $seats = $this->room->seats;
        if ($seats->count() > 0) {
            $maxRow = $seats->max('seat_row');
            $this->rows = ord($maxRow) - 64; // Convert letter to number

            $seatsInFirstRow = $seats->where('seat_row', 'A')->count();
            $this->seatsPerRow = $seatsInFirstRow > 0 ? $seatsInFirstRow : 15;

            // Ensure within limits
            $this->rows = max(5, min(26, $this->rows));
            $this->seatsPerRow = max(10, min(30, $this->seatsPerRow));

            // Get VIP and Couple rows
            $vipRows = $seats->where('seat_type', 'vip')->pluck('seat_row')->unique()->sort()->values();
            $this->vipRows = $vipRows->implode(',');

            $coupleRows = $seats->where('seat_type', 'couple')->pluck('seat_row')->unique()->sort()->values();
            $this->coupleRows = $coupleRows->implode(',');
        } else {
            // Set default values for empty room
            $this->rows = 10;
            $this->seatsPerRow = 15;
        }

        $this->capacity = $this->rows * $this->seatsPerRow;
    }

    public function updateRoom()
    {
        if (!$this->canEdit) {
            session()->flash('error', 'Không thể cập nhật phòng có suất chiếu đang hoạt động!');
            return;
        }

        $this->rules['name'] = 'required|string|max:255|unique:rooms,name,' . $this->roomId;
        $this->validate();

        try {
            $this->room->update([
                'name' => $this->name,
                'capacity' => $this->capacity,
                'status' => $this->status,
                'last_maintenance_date' => $this->last_maintenance_date ?: null,
            ]);

            session()->flash('success', 'Cập nhật phòng chiếu thành công!');
            return redirect()->route('admin.rooms.index');

        } catch (\Exception $e) {
            session()->flash('error', 'Có lỗi xảy ra khi cập nhật phòng chiếu!');
        }
    }

    public function regenerateSeats()
    {
        if (!$this->canRegenerateSeats) {
            session()->flash('error', 'Không thể tái tạo sơ đồ ghế khi có suất chiếu đang hoạt động!');
            return;
        }

        try {
            // Validate seat configuration
            $this->validate([
                'rows' => 'required|integer|min:5|max:26',
                'seatsPerRow' => 'required|integer|min:10|max:30',
                'vipRows' => 'nullable|string',
                'coupleRows' => 'nullable|string',
            ]);

            // Delete existing seats
            $this->room->seats()->delete();

            // Generate new seats based on configuration
            $this->generateSeatsWithConfig();

            // Update room capacity
            $this->room->update(['capacity' => $this->capacity]);

            session()->flash('success', 'Tái tạo sơ đồ ghế thành công!');

        } catch (\Exception $e) {
            session()->flash('error', 'Có lỗi xảy ra khi tái tạo sơ đồ ghế: ' . $e->getMessage());
        }
    }

    private function generateSeatsWithConfig()
    {
        $vipRowsArray = $this->vipRows ? explode(',', str_replace(' ', '', $this->vipRows)) : [];
        $coupleRowsArray = $this->coupleRows ? explode(',', str_replace(' ', '', $this->coupleRows)) : [];

        $seats = [];

        for ($row = 1; $row <= $this->rows; $row++) {
            $rowLetter = chr(64 + $row); // A, B, C, ...

            for ($seatNum = 1; $seatNum <= $this->seatsPerRow; $seatNum++) {
                $seatType = 'standard';
                $price = 50000; // Default price

                // Determine seat type
                if (in_array($rowLetter, $vipRowsArray)) {
                    $seatType = 'vip';
                    $price = 80000;
                } elseif (in_array($rowLetter, $coupleRowsArray)) {
                    $seatType = 'couple';
                    $price = 100000;
                }

                $seats[] = [
                    'room_id' => $this->room->id,
                    'seat_row' => $rowLetter,
                    'seat_number' => $seatNum,
                    'seat_type' => $seatType,
                    'price' => $price,
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        Seat::insert($seats);
    }

    public function render()
    {
        $seats = $this->room->seats()->orderBy('seat_row')->orderBy('seat_number')->get();

        return view('livewire.admin.rooms.room-edit', compact('seats'));
    }
}
