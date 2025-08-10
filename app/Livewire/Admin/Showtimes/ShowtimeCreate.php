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

class ShowtimeCreate extends Component
{
    use scAlert;

    public $movie_id = null;
    public $room_id = null;
    public $start_time = null;

    public $relatedShowtimes = null;

    /* Modal type */
    public $modalType = null;
    public $searchModal = '';
    public $modalSelected = null;

    protected function rules() {
        $rules = [
            'movie_id' => 'required|integer|exists:movies,id',
            'room_id' => 'required|integer|exists:rooms,id',
            'start_time' => 'required|date_format:Y-m-d\TH:i|after_or_equal:now',
        ];

        if(isset($this->movie_id) && ($movie = Movie::find($this->movie_id)) && $movie->end_date) $rules['start_time'] .= "|before:" . Carbon::parse($movie->end_date)->subMinutes($movie->duration);

        return $rules;
    }

    protected $messages = [
        'movie_id.required' => 'Vui lòng chọn phim chiếu.',
        'movie_id.integer' => 'ID phim chiếu không hợp lệ.',
        'movie_id.exists' => 'Phim chiếu được chọn không tồn tại.',
        'room_id.required' => 'Vui lòng chọn phòng chiếu.',
        'room_id.integer' => 'ID phòng chiếu không hợp lệ.',
        'room_id.exists' => 'Phòng chiếu được chọn không tồn tại.',
        'start_time.required' => 'Vui lòng chọn khung giờ chiếu.',
        'start_time.date_format' => 'Khung giờ chiếu không hợp lệ.',
        'start_time.after_or_equal' => 'Khung giờ chiếu phải lớn hơn hoặc bằng thời điểm hiện tại.',
        'start_time.before' => 'Khung giờ chiếu phải trước ngày kết thúc phim.',
    ];

    public function updatedMovieId(){
        $this->relatedShowtimes = Showtime::where('movie_id', $this->movie_id)->where('start_time', '>', Carbon::today())->orderBy('status', 'asc')->orderBy('start_time', 'asc')->get();
    }

    public function setData(){
        $modalText = $this->modalType==="movie" ? 'phim chiếu' : 'phòng chiếu';
        if(empty($this->modalSelected)) $this->scAlert("Chưa chọn {$modalText}", "Bạn chưa chọn {$modalText} nào. Hệ thống sẽ tiếp tục mà không cập nhật {$modalText}.", 'warning', '');
        else $this->{"{$this->modalType}_id"} = $this->modalSelected;
    }

    public function resetModal(){
        $this->reset(['modalType', 'searchModal', 'modalSelected']);
    }

    public function createShowtime(){
        $this->validate();

        Showtime::create([
            'movie_id' => $this->movie_id,
            'room_id' => $this->room_id,
            'start_time' => $this->start_time,
            'end_time' => Carbon::parse($this->start_time)->addMinutes(Movie::findOrFail($this->movie_id)->duration),
            'status' => 'active',
        ]);

        return redirect()->route('admin.showtimes.index')->with('success', 'Tạo suất chiếu thành công!');
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

    #[Title('Tạo suất chiếu - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        $this->updateStatusMovies();

        $movies = Movie::select('id', 'title', 'poster', 'duration')->where('status', 'showing')->get();
        $rooms = Room::select('id', 'name')->where('status', 'active')->get();

        if(!empty($this->movie_id) && !empty($this->start_time)){
            $startTimeCarbon = Carbon::parse($this->start_time);
            $endTimeCarbon = $startTimeCarbon->clone()->addMinutes($movies->firstWhere('id', $this->movie_id)?->duration ?? 0);
            $roomsFiltered = $rooms->toQuery()->select('id', 'name')->where('status', 'active')->whereDoesntHave('showtimes', function($query) use ($startTimeCarbon, $endTimeCarbon){
                $query->where('start_time', '<', $endTimeCarbon)
                    ->where('end_time', '>', $startTimeCarbon)
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

        return view('livewire.admin.showtimes.showtime-create', compact('movies', 'rooms', 'modalData'));
    }
}
