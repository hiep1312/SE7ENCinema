<?php

namespace App\Livewire\Admin\Rooms;

use App\Models\Room;
use App\Models\Seat;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Str;

class RoomEdit extends Component
{
    public $room;
    public $name = '';
    public $status = 'active';
    public $last_maintenance_date = null;
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
    public $seatAlgorithms = [
        'check_lonely'   => true,
        'check_sole'     => true,
        'check_diagonal' => true,
    ];
    public $schema = [];
    public $capacity = 0;

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255|unique:rooms,name,' . $this->room->id,
            'status' => 'required|in:active,maintenance,inactive',
            'last_maintenance_date' => 'nullable|date|before_or_equal:today',
            'rows' => 'required|integer|min:5|max:26',
            'seatsPerRow' => 'required|integer|min:10|max:30',
            'vipArr.*' => 'string|distinct:ignore_case',
            'coupleArr.*' => 'string|distinct:ignore_case',
            'priceStandard' => 'required|numeric|min:0',
            'priceVip' => 'numeric|min:0|gt:priceStandard|lt:priceCouple' . ($this->vipRows ? '|required' : '|nullable'),
            'priceCouple' => 'numeric|min:0|gt:priceStandard' . ($this->coupleRows ? '|required' : '|nullable'),
        ];

        if (!empty($this->vipArr)) $rules['coupleArr.*'] .= "|not_in:" . implode(",", $this->vipArr);
        if (!empty($this->coupleArr)) $rules['vipArr.*'] .= "|not_in:" . implode(",", $this->coupleArr);

        return $rules;
    }

    protected $messages = [
        'name.required' => 'Tên phòng chiếu là bắt buộc',
        'name.unique' => 'Tên phòng chiếu đã tồn tại',
        'status.required' => 'Trạng thái là bắt buộc',
        'last_maintenance_date.before_or_equal' => 'Ngày bảo trì không được lớn hơn ngày hôm nay.',
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

    public function mount(Room $room)
    {
        $this->room = $room;

        $this->fill($room->only('name', 'status', 'last_maintenance_date'));

        if ($this->last_maintenance_date) {
            $this->last_maintenance_date = $this->last_maintenance_date->format('Y-m-d');
        }

        if (!empty($room->seat_algorithms)) {
            $decoded = is_array($room->seat_algorithms)
                ? $room->seat_algorithms
                : json_decode($room->seat_algorithms, true);

            if (is_array($decoded)) {
                $this->seatAlgorithms = array_merge($this->seatAlgorithms, $decoded);
            }
        }

        $this->capacity = $room->capacity ?: ($this->rows * $this->seatsPerRow);

        $this->loadSeatConfiguration();

        if ($this->room->hasActiveShowtimes()) {
            return to_route('admin.rooms.index')
                ->with('error', "Không thể chỉnh sửa phòng đang có suất chiếu đang hoạt động!");
        }
    }

    protected function loadSeatConfiguration()
    {
        $seats = $this->room->seats()->get();

        if ($seats->isNotEmpty()) {
            $maxRow = $seats->max('seat_row');
            $this->rows = $maxRow ? (ord($maxRow) - 64) : 10;
            $this->seatsPerRow = $seats->max('seat_number') ?: 15;

            $standardSeat = $seats->where('seat_type', 'standard')->first();
            $vipSeat      = $seats->where('seat_type', 'vip')->first();
            $coupleSeat   = $seats->where('seat_type', 'couple')->first();

            $this->priceStandard = $standardSeat ? $standardSeat->price : 50000;
            $this->priceVip      = $vipSeat      ? $vipSeat->price      : 80000;
            $this->priceCouple   = $coupleSeat   ? $coupleSeat->price   : 120000;

            $this->formattedPriceStandard = number_format($this->priceStandard, 0, '.', '.');
            $this->formattedPriceVip      = $this->priceVip    ? number_format($this->priceVip, 0, '.', '.') : null;
            $this->formattedPriceCouple   = $this->priceCouple ? number_format($this->priceCouple, 0, '.', '.') : null;

            $vipRows    = $seats->where('seat_type', 'vip')->pluck('seat_row')->unique()->values()->all();
            $coupleRows = $seats->where('seat_type', 'couple')->pluck('seat_row')->unique()->values()->all();

            $this->vipRows    = implode(',', $vipRows);
            $this->coupleRows = implode(',', $coupleRows);
            $this->vipArr     = $vipRows;
            $this->coupleArr  = $coupleRows;
        }
    }

    public function updated($property)
    {
        if ($property === 'formattedPriceStandard' || $property === 'formattedPriceVip' || $property === 'formattedPriceCouple') {
            $this->{lcfirst(strstr($property, 'Price'))} = str_replace([',', '.'], '', $this->{$property});
        } elseif ($property === 'vipRows' || $property === 'coupleRows') {
            $this->{str_replace('Rows', 'Arr', $property)} = array_map(fn($row) => strtoupper(trim($row)), $this->{$property} ? explode(',', $this->{$property}) : []);
        } elseif ($property === 'schema') {
            if (is_string($this->schema)) {
                $decoded = json_decode($this->schema, true);
                if ($decoded) {
                    $this->schema = $decoded;
                    $this->capacity = $decoded['capacity'] ?? ($this->rows * $this->seatsPerRow);
                }
            }
        } elseif (str_starts_with($property, 'seatAlgorithms.')) {
            $k = explode('.', $property, 2)[1] ?? null;
            if ($k && isset($this->seatAlgorithms[$k])) {
                $v = $this->seatAlgorithms[$k];
                $this->seatAlgorithms[$k] = filter_var($v, FILTER_VALIDATE_BOOLEAN);
            }
        }
    }

    protected $listeners = ['schemaUpdated'];

    public function schemaUpdated($data)
    {
        $this->schema = $data;
        $this->capacity = $data['capacity'] ?? ($this->rows * $this->seatsPerRow);
    }

    public function updatedTemp()
    {
        $this->temp = array_filter($this->temp, fn($item) => !Str::contains($item, ['add-column-btn', 'aisle']));
    }

    public function updateRoom()
    {
        if ($this->room->hasActiveShowtimes()) {
            return to_route('admin.rooms.index')->with('error', "Không thể chỉnh sửa phòng đang có suất chiếu đang hoạt động!");
        }

        $this->validate();
        if (
            collect($this->vipArr)->some(fn($row) => in_array($row, $this->coupleArr)) ||
            collect($this->coupleArr)->some(fn($row) => in_array($row, $this->vipArr))
        ) {
            $this->addError('vipArr.*', 'Danh sách hàng ghế VIP không được chứa các hàng đã thuộc ghế đôi.');
            $this->addError('coupleArr.*', 'Danh sách hàng ghế đôi không được chứa các hàng đã thuộc ghế VIP.');
            return;
        }

        try {
            $this->room->update([
                'name'             => $this->name,
                'capacity'         => $this->capacity ?: ($this->rows * $this->seatsPerRow),
                'status'           => $this->status,
                'last_maintenance_date' => $this->last_maintenance_date ?: null,
                'seat_algorithms'  => json_encode($this->seatAlgorithms, JSON_UNESCAPED_UNICODE),
            ]);

            $this->updateSeatsConfiguration();

            return redirect()
                ->route('admin.rooms.index')
                ->with('success', 'Cập nhật phòng chiếu thành công!');
        } catch (\Throwable $e) {
            session()->flash('error', 'Có lỗi xảy ra trong quá trình cập nhật phòng chiếu. Vui lòng thử lại!');
        }
    }

    protected function updateSeatsConfiguration()
    {
        $this->room->seats()->delete();

        if (!empty($this->schema['rows'])) {
            $total = 0;

            foreach ($this->schema['rows'] as $row) {
                $rowLetter = $row['row'] ?? null;
                if (!$rowLetter || empty($row['seats']) || !is_array($row['seats'])) continue;

                $num = 0;
                foreach ($row['seats'] as $s) {
                    if (($s['type'] ?? null) === 'aisle') continue;

                    $num++;
                    $typeUi = $s['uiType'] ?? $s['type'] ?? 'standard';
                    $typeDb = $typeUi === 'double' ? 'couple' : $typeUi;

                    $status = $s['status'] ?? 'active';

                    $price = match ($typeDb) {
                        'vip'    => $this->priceVip,
                        'couple' => $this->priceCouple,
                        default  => $this->priceStandard,
                    };

                    Seat::create([
                        'room_id'     => $this->room->id,
                        'seat_row'    => $rowLetter,
                        'seat_number' => $num,
                        'seat_type'   => $typeDb,
                        'price'       => $price,
                        'status'      => $status,
                    ]);

                    $total++;
                }
            }

            $this->room->update(['capacity' => $total]);
        } else {
            $vipRows    = collect(explode(',', strtoupper($this->vipRows)))->map(fn($v) => trim($v))->filter();
            $coupleRows = collect(explode(',', strtoupper($this->coupleRows)))->map(fn($v) => trim($v))->filter();

            for ($i = 0; $i < $this->rows; $i++) {
                $rowLetter = chr(65 + $i);

                for ($j = 1; $j <= $this->seatsPerRow; $j++) {
                    $type  = 'standard';
                    $price = $this->priceStandard;

                    if ($vipRows->contains($rowLetter)) {
                        $type  = 'vip';
                        $price = $this->priceVip;
                    }
                    if ($coupleRows->contains($rowLetter)) {
                        $type  = 'couple';
                        $price = $this->priceCouple;
                    }

                    Seat::create([
                        'room_id'     => $this->room->id,
                        'seat_row'    => $rowLetter,
                        'seat_number' => $j,
                        'seat_type'   => $type,
                        'price'       => $price,
                        'status'      => 'active',
                    ]);
                }
            }
            $this->room->update(['capacity' => $this->rows * $this->seatsPerRow]);
        }
    }

    public function handleGenerateSeats()
    {
        $this->validateOnly('vipArr.*');
        $this->validateOnly('coupleArr.*');

        $this->dispatch(
            'generateSeats',
            $this->rows,
            $this->seatsPerRow,
            $this->vipArr,
            $this->coupleArr,
            $this->seatAlgorithms
        );
    }

    public function setTemp($data)
    {
        $this->temp = $data;
    }

    #[Title('Cập nhật phòng chiếu - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.admin.rooms.room-edit');
    }
}
