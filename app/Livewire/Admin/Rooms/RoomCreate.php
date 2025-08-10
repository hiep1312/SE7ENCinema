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
    public $vipRows = null;
    public $vipArr = [];
    public $coupleRows = null;
    public $coupleArr = [];
    public $priceStandard = null;
    public $formattedPriceStandard = null;
    public $priceVip = null;
    public $formattedPriceVip = null;
    public $priceCouple = null;
    public $formattedPriceCouple = null;
    public $temp = [];
    public $checkLonely = true;
    public $checkSole = true;
    public $checkDiagonal = true;

    protected function rules() {
        $rules = [
            'name' => 'required|string|max:255|unique:rooms,name',
            'status' => 'required|in:active,maintenance,inactive',
            'rows' => 'required|integer|min:5|max:26',
            'seatsPerRow' => 'required|integer|min:10|max:30',
            'vipArr.*' => 'string|distinct:ignore_case',
            'coupleArr.*' => 'string|distinct:ignore_case',
            'priceStandard' => 'required|numeric|min:0',
            'priceVip' => 'numeric|min:0|gt:priceStandard|lt:priceCouple' . ($this->vipRows ? '|required' : '|nullable'),
            'priceCouple' => 'numeric|min:0|gt:priceStandard' . ($this->coupleRows ? '|required' : '|nullable'),
        ];


        if(!empty($this->vipArr)) $rules['coupleArr.*'] .= "|not_in:" . implode("," , $this->vipArr);
        if(!empty($this->coupleArr)) $rules['vipArr.*'] .= "|not_in:" . implode(",", $this->coupleArr);

    //    dd($this->vipArr , $this->coupleArr , $rules);
        return $rules;
    }

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
        'vipArr.*.string' => 'Mỗi giá trị trong danh sách hàng ghế VIP phải là một chuỗi ký tự.',
        'vipArr.*.distinct' => 'Danh sách hàng ghế VIP không được chứa các giá trị trùng lặp.',
        'vipArr.*.not_in' => 'Danh sách hàng ghế VIP không được chứa các hàng đã thuộc ghế đôi.',
        'coupleArr.*.string' => 'Mỗi giá trị trong danh sách hàng ghế đôi phải là một chuỗi ký tự.',
        'coupleArr.*.distinct' => 'Danh sách hàng ghế đôi không được chứa các giá trị trùng lặp.',
        'coupleArr.*.not_in' => 'Danh sách hàng ghế đôi không được chứa các hàng đã thuộc ghế VIP.',
        'priceStandard.required' => 'Giá ghế thường là bắt buộc',
        'priceStandard.numeric' => 'Giá ghế thường phải là một số.',
        'priceStandard.min' => 'Giá ghế thường tối thiểu là 0 VNĐ',
        'priceVip.required' => 'Giá ghế VIP là bắt buộc',
        'priceVip.numeric' => 'Giá ghế VIP phải là một số.',
        'priceVip.min' => 'Giá ghế VIP tối thiểu là 0 VNĐ',
        'priceVip.gt' => 'Giá ghế VIP phải lớn hơn giá ghế thường',
        'priceVip.lt' => 'Giá ghế VIP phải nhỏ hơn giá ghế đôi',
        'priceCouple.required' => 'Giá ghế đôi là bắt buộc',
        'priceCouple.numeric' => 'Giá ghế đôi phải là một số.',
        'priceCouple.min' => 'Giá ghế đôi tối thiểu là 0 VNĐ',
        'priceCouple.gt' => 'Giá ghế đôi phải lớn hơn giá ghế thường',
    ];

    public function updated($property){
        if($property === 'formattedPriceStandard' || $property === 'formattedPriceVip' || $property === 'formattedPriceCouple')
            $this->{lcfirst(strstr($property, 'Price'))} = str_replace([',', '.'], '', $this->{$property});
        elseif($property === 'vipRows' || $property === 'coupleRows')
            $this->{str_replace('Rows', 'Arr', $property)} = array_map(fn($row) => strtoupper(trim($row)), $this->{$property} ? explode(',', $this->{$property}) : []);
    }

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
                'check_lonely' => $this->checkLonely,
                'check_sole' => $this->checkSole,
                'check_diagonal' => $this->checkDiagonal,
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
        $this->validateOnly('vipArr.*');
        $this->validateOnly('coupleArr.*');
        $this->dispatch('generateSeats', $this->rows, $this->seatsPerRow, $this->vipArr, $this->coupleArr, $this->checkLonely, $this->checkSole, $this->checkDiagonal);
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
