<?php

namespace App\Livewire\Admin\Rooms;

use App\Models\Room;
use App\Models\Seat;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Str;

class RoomCreate extends Component
{
    public $name = '';
    public $status = 'active';
    public $rows = 10;
    public $seatsPerRow = 15;
    public $vipRows = 'A';
    public $coupleRows = 'B';
    public $priceStandard = 20000;
    public $priceVip = 20000;
    public $priceCouple = 20000;
    public $temp = [];

    protected $rules = [
        'name' => 'required|string|max:255|unique:rooms,name',
        'status' => 'required|in:active,maintenance,inactive',
        'rows' => 'required|integer|min:5|max:26',
        'seatsPerRow' => 'required|integer|min:10|max:30',
        'vipRows' => 'nullable|string',
        'coupleRows' => 'nullable|string',
        'priceStandard' => 'required|numeric|min:20000',
        'priceVip' => 'required|numeric|min:20000|gt:priceStandard',
        'priceCouple' => 'required|numeric|min:20000|gt:priceStandard',
    ];

    protected $messages = [
        'name.required' => 'Tên phòng chiếu là bắt buộc',
        'name.unique' => 'Tên phòng chiếu đã tồn tại',
        'status.required' => 'Trạng thái là bắt buộc',
        'rows.required' => 'Số hàng ghế là bắt buộc',
        'rows.min' => 'Số hàng ghế tối thiểu là 5',
        'rows.max' => 'Số hàng ghế tối đa là 26',
        'seatsPerRow.required' => 'Số ghế mỗi hàng là bắt buộc',
        'seatsPerRow.min' => 'Số ghế mỗi hàng tối thiểu là 10',
        'seatsPerRow.max' => 'Số ghế mỗi hàng tối đa là 30',
        'priceStandard.required' => 'Giá ghế thường là bắt buộc',
        'priceStandard.min' => 'Giá ghế thường tối thiểu là 20.000 VNĐ',
        'priceVip.required' => 'Giá ghế VIP là bắt buộc',
        'priceVip.min' => 'Giá ghế VIP tối thiểu là 20.000 VNĐ',
        'priceVip.gt' => 'Giá ghế VIP phải lớn hơn giá ghế thường',
        'priceCouple.required' => 'Giá ghế đôi là bắt buộc',
        'priceCouple.min' => 'Giá ghế đôi tối thiểu là 20.000 VNĐ',
        'priceCouple.gt' => 'Giá ghế đôi phải lớn hơn giá ghế thường',
    ];

    public function updatedTemp()
    {
        $this->temp = array_filter($this->temp, fn($item) => !Str::contains($item, ['add-column-btn', 'asile']));
    }

    public function createRoom()
    {
        $this->validate();

        try {
            $room = Room::create([
                'name' => $this->name,
                'capacity' => $this->rows * $this->seatsPerRow,
                'status' => $this->status,
            ]);

            $vipRows = collect(explode(',', strtoupper($this->vipRows)))->map(fn($v) => trim($v))->filter();
            $coupleRows = collect(explode(',', strtoupper($this->coupleRows)))->map(fn($v) => trim($v))->filter();

            for ($i = 0; $i < $this->rows; $i++) {
                $rowLetter = chr(65 + $i);

                for ($j = 1; $j <= $this->seatsPerRow; $j++) {
                    $type = 'standard';
                    $price = $this->priceStandard;

                    if ($vipRows->contains($rowLetter)) {
                        $type = 'vip';
                        $price = $this->priceVip;
                    }

                    if ($coupleRows->contains($rowLetter)) {
                        $type = 'couple';
                        $price = $this->priceCouple;
                    }

                    Seat::create([
                        'room_id' => $room->id,
                        'seat_row' => $rowLetter,
                        'seat_number' => $j,
                        'seat_type' => $type,
                        'price' => $price,
                        'status' => 'active',
                    ]);
                }
            }

            return redirect()->route('admin.rooms.index')->with('success', 'Tạo phòng chiếu và sơ đồ ghế thành công!');
        } catch (\Exception $e) {
            session()->flash('error', 'Có lỗi xảy ra trong quá trình tạo phòng chiếu. Vui lòng thử lại!');
        }
    }

    public function handleGenerateSeats()
    {
        $this->validate();
        $this->dispatch('generateSeats', $this->rows, $this->seatsPerRow, $this->vipRows, $this->coupleRows);
    }

    public function setTemp($data)
    {
        $this->temp = $data;
    }

    #[Title('Tạo phòng chiếu - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.admin.rooms.room-create');
    }
}
