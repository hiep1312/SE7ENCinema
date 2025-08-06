<?php

namespace App\Livewire\Admin\Rooms;

use App\Models\Room;
use App\Models\Seat;
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
    public $priceStandard = 0;
    public $priceVip = 0;
    public $priceCouple = 0;
    public $checkLonely = true;
    public $checkSole = true;
    public $checkDiagonal = true;

    protected $rules = [
        'name' => 'required|string|max:255|unique:rooms,name',
        'status' => 'required|in:active,maintenance,inactive',
        'last_maintenance_date' => 'nullable|date|before_or_equal:today',
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
        'last_maintenance_date.before_or_equal' => 'Ngày bảo trì không được lớn hơn ngày hôm nay.',
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

    public function mount(Room $room)
    {
        $this->room = $room;
        $this->fill($room->only('name', 'status', 'last_maintenance_date'));
        $this->checkLonely = $room->check_lonely;
        $this->checkSole = $room->check_sole;
        $this->checkDiagonal = $room->check_diagonal;

        // Format date
        if ($this->last_maintenance_date) {
            $this->last_maintenance_date = $this->last_maintenance_date->format('Y-m-d');
        }

        // Load seat configuration from existing seats
        $this->loadSeatConfiguration();

        if ($this->room->hasActiveShowtimes()) {
            return to_route('admin.rooms.index')->with('error', "Không thể chỉnh sửa phòng đang có suất chiếu đang hoạt động!");
        }
    }

    protected function loadSeatConfiguration()
    {
        $seats = $this->room->seats()->get();

        if ($seats->isNotEmpty()) {
            // Tính số hàng và ghế mỗi hàng
            $this->rows = $seats->max('seat_row') ? ord($seats->max('seat_row')) - 64 : 10;
            $this->seatsPerRow = $seats->max('seat_number') ?: 15;

            // Lấy giá từ ghế hiện tại
            $standardSeat = $seats->where('seat_type', 'standard')->first();
            $vipSeat = $seats->where('seat_type', 'vip')->first();
            $coupleSeat = $seats->where('seat_type', 'couple')->first();

            $this->priceStandard = $standardSeat ? $standardSeat->price : 50000;
            $this->priceVip = $vipSeat ? $vipSeat->price : 80000;
            $this->priceCouple = $coupleSeat ? $coupleSeat->price : 120000;

            // Lấy danh sách hàng VIP và Couple
            $vipRows = $seats->where('seat_type', 'vip')->pluck('seat_row')->unique()->toArray();
            $coupleRows = $seats->where('seat_type', 'couple')->pluck('seat_row')->unique()->toArray();

            $this->vipRows = implode(',', $vipRows);
            $this->coupleRows = implode(',', $coupleRows);
        } else {
            // Giá trị mặc định
            $this->priceStandard = 50000;
            $this->priceVip = 80000;
            $this->priceCouple = 120000;
        }
    }

    public function updateRoom()
    {
        if ($this->room->hasActiveShowtimes()) {
            return to_route('admin.rooms.index')->with('error', "Không thể chỉnh sửa phòng đang có suất chiếu đang hoạt động!");
        }

        // Update validation rule for unique name
        $this->rules['name'] = $this->rules['name'] . ',' . $this->room->id;
        $this->validate();

        try {
            // Update room info
            $this->room->update([
                'name' => $this->name,
                'capacity' => $this->rows * $this->seatsPerRow,
                'status' => $this->status,
                'last_maintenance_date' => $this->last_maintenance_date ?: null,
                'check_lonely' => $this->checkLonely,
                'check_sole' => $this->checkSole,
                'check_diagonal' => $this->checkDiagonal,
            ]);

            // Update seats configuration
            $this->updateSeatsConfiguration();

            return redirect()->route('admin.rooms.index')->with('success', 'Cập nhật phòng chiếu thành công!');
        } catch (\Exception $e) {
            session()->flash('error', 'Có lỗi xảy ra trong quá trình cập nhật phòng chiếu. Vui lòng thử lại!');
        }
    }

    protected function updateSeatsConfiguration()
    {
        // Delete existing seats
        $this->room->seats()->delete();

        // Parse VIP and Couple rows
        $vipRows = collect(explode(',', strtoupper($this->vipRows)))
            ->map(fn($v) => trim($v))
            ->filter();
        $coupleRows = collect(explode(',', strtoupper($this->coupleRows)))
            ->map(fn($v) => trim($v))
            ->filter();

        // Create new seats
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
                    'room_id' => $this->room->id,
                    'seat_row' => $rowLetter,
                    'seat_number' => $j,
                    'seat_type' => $type,
                    'price' => $price,
                    'status' => 'active',
                ]);
            }
        }
    }

    public function handleGenerateSeats()
    {
        $this->validate();
        $this->dispatch('generateSeats', $this->rows, $this->seatsPerRow, $this->vipRows, $this->coupleRows , $this->checkLonely, $this->checkSole, $this->checkDiagonal);
    }

    #[Title('Cập nhật phòng chiếu - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.admin.rooms.room-edit');
    }
}
