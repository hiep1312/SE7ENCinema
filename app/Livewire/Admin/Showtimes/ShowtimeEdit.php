<?php

namespace App\Livewire\Admin\Showtimes;

use App\Models\Movie;
use App\Models\Room;
use App\Models\Showtime;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use SE7ENCinema\scAlert;

class ShowtimeEdit extends Component
{
    use scAlert;

    public $showtimeItem;
    public $room_id = null;
    public $start_time = null;
    public $status = 'active';

    /* Modal type */
    public $modalType = null;
    public $searchModal = '';
    public $modalSelected = null;

    protected function rules(){
        $rules = [
            'room_id' => 'required|integer|exists:rooms,id',
            'start_time' => 'required|date_format:Y-m-d\TH:i|after_or_equal:now',
            'status' => 'required|in:active,canceled',
        ];

        if(isset($this->showtimeItem->movie_id) && ($movie = Movie::find($this->showtimeItem->movie_id)) && $movie->end_date) $rules['start_time'] .= "|before:" . Carbon::parse($movie->end_date)->subMinutes($movie->duration);

        return $rules;
    }

    protected $messages = [
        'room_id.required' => 'Vui lòng chọn phòng chiếu.',
        'room_id.integer' => 'ID phòng chiếu không hợp lệ.',
        'room_id.exists' => 'Phòng chiếu được chọn không tồn tại.',
        'start_time.required' => 'Vui lòng chọn khung giờ chiếu.',
        'start_time.date_format' => 'Khung giờ chiếu không hợp lệ.',
        'start_time.after_or_equal' => 'Khung giờ chiếu phải lớn hơn hoặc bằng thời điểm hiện tại.',
        'start_time.before' => 'Khung giờ chiếu phải trước ngày kết thúc phim.',
        'status.required' => 'Vui lòng chọn trạng thái cho suất chiếu.',
        'status.in' => 'Trạng thái suất chiếu không hợp lệ. Chỉ chấp nhận: đang chiếu hoặc đã huỷ.',
    ];

    public function mount(Showtime $showtime){
        $this->showtimeItem = $showtime->load('movie', 'room');
        $this->fill($showtime->only('room_id', 'status') + ['start_time' => $showtime->start_time->format('Y-m-d\TH:i')]);

        if(!($showtime->status !== "completed" && $showtime->start_time->isFuture())) return redirect()->route('admin.showtimes.index');
    }

    public function setData(){
        $modalText = $this->modalType==="movie" ? 'phim chiếu' : 'phòng chiếu';
        if(empty($this->modalSelected)) $this->scAlert("Chưa chọn {$modalText}", "Bạn chưa chọn {$modalText} nào. Hệ thống sẽ tiếp tục mà không cập nhật {$modalText}.", 'warning', '');
        else $this->{"{$this->modalType}_id"} = $this->modalSelected;
    }

    public function resetModal(){
        $this->reset(['modalType', 'searchModal', 'modalSelected']);
    }

    public function updateShowtime()
    {
        $this->validate();

        $this->showtimeItem->update([
            'room_id' => $this->room_id,
            'start_time' => $this->start_time,
            'end_time' => Carbon::parse($this->start_time)->addMinutes($this->showtimeItem->movie?->duration ?? 0),
            'status' => $this->status,
        ]);

        return redirect()->route('admin.showtimes.index')->with('success', 'Cập nhật suất chiếu thành công!');
    }

    public function updateStatusMovies()
    {
        Movie::all()->each(function ($movie) {
            $releaseDate = Carbon::parse($movie->release_date);
            $endDate = !$movie->end_date ?: Carbon::parse($movie->end_date);
            if (is_object($endDate) && $endDate->isPast()) $movie->status = 'ended';
            elseif($releaseDate->isFuture()) $movie->status = 'coming_soon';
            else $movie->status = 'showing';
            $movie->save();
        });
    }

    #[Title('Chỉnh sửa suất chiếu - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        $this->updateStatusMovies();

        $relatedShowtimes = Showtime::where('movie_id', $this->showtimeItem->movie_id)->whereNot('id', $this->showtimeItem->id)->where('start_time', '>', Carbon::today())->orderBy('status', 'asc')->orderBy('start_time', 'asc')->get();
        $rooms = Room::select('id', 'name')->where('status', 'active')->get();

        if(!empty($this->start_time)){
            $startTimeCarbon = Carbon::parse($this->start_time);
            $endTimeCarbon = $startTimeCarbon->clone()->addMinutes($this->showtimeItem->movie?->duration ?? 0);
            $roomsFiltered = $rooms->toQuery()->select('id', 'name')->where('status', 'active')->whereDoesntHave('showtimes', function($query) use ($startTimeCarbon, $endTimeCarbon){
                $query->where('start_time', '<', $endTimeCarbon)
                    ->where('end_time', '>', $startTimeCarbon)
                    ->where('id', '!=', $this->showtimeItem->id)
                    ->where('status', 'active');
            })->get();

            (!empty($this->room_id) && !$roomsFiltered->contains('id', $this->room_id)) && (($this->room_id = null) || $this->scToast('Phòng chiếu bị trùng thời gian, lựa chọn của bạn đã bị ghi đè', 'warning', 5000, true, ''));

            $rooms = $roomsFiltered;
        }

        $modalData = [];
        if($this->modalType){
            $fieldName = $this->modalType==="movie" ? 'title' : 'name';
            $modalData = ${"{$this->modalType}s"}->when($this->searchModal, function($collection) use ($fieldName) {
                return $collection->filter(function ($item) use ($fieldName) {
                    return str_contains(mb_strtolower($item[$fieldName], "UTF-8"), mb_strtolower(trim($this->searchModal), "UTF-8"));
                });
            })->sortBy($fieldName);
            (is_null($this->modalSelected)) && ($this->modalSelected = $modalData->contains('id', $this->{"{$this->modalType}_id"}) ? $this->{"{$this->modalType}_id"} : null);
        }

        return view('livewire.admin.showtimes.showtime-edit', compact('rooms', 'modalData', 'relatedShowtimes'));
    }
}
