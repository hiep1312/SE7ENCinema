<?php

namespace App\Livewire\Admin\Showtimes;

use App\Models\Movie;
use App\Models\Room;
use App\Models\Showtime;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class ShowtimeCreate extends Component
{
    public $movie_id = null;
    public $room_id = null;
    public $start_time = null;
    public $price = null;

    public $relatedShowtimes = null;

    public $rules = [
        'movie_id' => 'required|integer|exists:movies,id',
        'room_id' => 'required|integer|exists:rooms,id',
        'start_time' => 'required|date_format:Y-m-d\TH:i|after_or_equal:now',
        'price' => 'required|numeric|min:0',
    ];

    public $messages = [
        'movie_id.required' => 'Vui lòng chọn phim chiếu.',
        'movie_id.integer' => 'ID phim chiếu không hợp lệ.',
        'movie_id.exists' => 'Phim chiếu được chọn không tồn tại.',
        'room_id.required' => 'Vui lòng chọn phòng chiếu.',
        'room_id.integer' => 'ID phòng chiếu không hợp lệ.',
        'room_id.exists' => 'Phòng chiếu được chọn không tồn tại.',
        'start_time.required' => 'Vui lòng chọn khung giờ chiếu.',
        'start_time.date_format' => 'Khung giờ chiếu không hợp lệ.',
        'start_time.after_or_equal' => 'Khung giờ chiếu phải lớn hơn hoặc bằng thời điểm hiện tại.',
        'price.required' => 'Vui lòng nhập giá khung giờ.',
        'price.numeric' => 'Giá khung giờ phải là một số.',
        'price.min' => 'Giá khung giờ không được nhỏ hơn 0.',
    ];

    public function updatedMovieId(){
        $this->relatedShowtimes = Showtime::where('movie_id', $this->movie_id)->where('start_time', '>', Carbon::today())->orderBy('status', 'asc')->orderBy('start_time', 'asc')->get();
    }

    public function createShowtime(){
        $this->validate();

        Showtime::create([
            'movie_id' => $this->movie_id,
            'room_id' => $this->room_id,
            'start_time' => $this->start_time,
            'end_time' => Carbon::parse($this->start_time)->addMinutes(Movie::findOrFail($this->movie_id)->duration),
            'price' => $this->price,
            'status' => 'active',
        ]);

        return redirect()->route('admin.showtimes.index')->with('success', 'Tạo suất chiếu thành công!');
    }

    #[Title('Tạo suất chiếu - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        $movies = Movie::where('status', 'showing')->get();
        $rooms = Room::where('status', 'active')->get();
        return view('livewire.admin.showtimes.showtime-create', compact('movies', 'rooms'));
    }
}
