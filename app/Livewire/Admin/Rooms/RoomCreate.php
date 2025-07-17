<?php

namespace App\Livewire\Admin\Rooms;

use App\Models\Room;
use App\Models\Seat;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class RoomCreate extends Component
{
    public $name = '';
    public $status = 'active';
    public $rows = 10;
    public $seatsPerRow = 15;
    public $vipRows = '';
    public $coupleRows = '';

    protected $rules = [
        'name' => 'required|string|max:255|unique:rooms,name',
        'status' => 'required|in:active,maintenance,inactive',
        'rows' => 'required|integer|min:5|max:26',
        'seatsPerRow' => 'required|integer|min:10|max:30',
        'vipRows' => 'nullable|string',
        'coupleRows' => 'nullable|string',
    ];

    protected $messages = [
        'name.required' => 'Tên phòng chiếu là bắt buộc',
        'name.unique' => 'Tên phòng chiếu đã tồn tại',
        'rows.required' => 'Số hàng ghế là bắt buộc',
        'rows.min' => 'Số hàng ghế tối thiểu là 5',
        'rows.max' => 'Số hàng ghế tối đa là 26',
        'seatsPerRow.required' => 'Số ghế mỗi hàng là bắt buộc',
        'seatsPerRow.min' => 'Số ghế mỗi hàng tối thiểu là 10',
        'seatsPerRow.max' => 'Số ghế mỗi hàng tối đa là 30',
    ];

    public function createRoom()
    {
        $this->validate();

        try {
            // Create room
            $room = Room::create([
                'name' => $this->name,
                'capacity' => $this->rows * $this->seatsPerRow,
                'status' => $this->status,
            ]);

            // Generate seats automatically
            $this->generateSeats($room);

            return redirect()->route('admin.rooms.index')->with('success', 'Tạo phòng chiếu và sơ đồ ghế thành công!');
        } catch (\Exception $e) {
            session()->flash('error', 'Có lỗi xảy ra trong quá trình tạo phòng chiếu. Vui lòng thử lại!');
        }
    }

    private function generateSeats($room)
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
                    'room_id' => $room->id,
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

    #[Title('Tạo phòng chiếu - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.admin.rooms.room-create');
    }
}
