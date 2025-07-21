<?php

namespace App\Livewire\Admin\Showtimes;

use App\Models\Movie;
use App\Models\Room;
use App\Models\Showtime;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class ShowtimeEdit extends Component
{
    public $showtimeItem;
    public $start_time = null;
    public $price = null;
    public $status = 'active';

    public $rules = [
        'start_time' => 'required|date_format:Y-m-d\TH:i|after_or_equal:now',
        'price' => 'required|numeric|min:0',
        'status' => 'required|in:active,canceled',
    ];

    public $messages = [
        'start_time.required' => 'Vui lòng chọn khung giờ chiếu.',
        'start_time.date_format' => 'Khung giờ chiếu không hợp lệ.',
        'start_time.after_or_equal' => 'Khung giờ chiếu phải lớn hơn hoặc bằng thời điểm hiện tại.',
        'price.required' => 'Vui lòng nhập giá khung giờ.',
        'price.numeric' => 'Giá khung giờ phải là một số.',
        'price.min' => 'Giá khung giờ không được nhỏ hơn 0.',
        'status.required' => 'Vui lòng chọn trạng thái cho suất chiếu.',
        'status.in' => 'Trạng thái suất chiếu không hợp lệ. Chỉ chấp nhận: đang chiếu hoặc đã huỷ.',
    ];

    public function mount(int $showtime){
        $this->showtimeItem = Showtime::with('movie', 'room')->findOrFail($showtime);
        $this->fill($this->showtimeItem->only('price', 'status') + ['start_time' => $this->showtimeItem->start_time->format('Y-m-d\TH:i')]);

        if(!($this->showtimeItem->status !== "completed" && $this->showtimeItem->start_time->isFuture())) return redirect()->route('admin.showtimes.index');
    }

    public function updateShowtime()
    {
        $this->validate();

        $this->showtimeItem->update([
            'start_time' => $this->start_time,
            'end_time' => Carbon::parse($this->start_time)->addMinutes($this->showtimeItem->movie->duration),
            'price' => $this->price,
            'status' => $this->status,
        ]);

        return redirect()->route('admin.showtimes.index')->with('success', 'Cập nhật suất chiếu thành công!');
    }

    #[Title('Chỉnh sửa suất chiếu - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        $movies = Movie::where('status', 'showing')->get();
        $rooms = Room::where('status', 'active')->get();
        $relatedShowtimes = Showtime::where('movie_id', $this->showtimeItem->movie_id)->whereNot('id', $this->showtimeItem->id)->where('start_time', '>', Carbon::today())->orderBy('status', 'asc')->orderBy('start_time', 'asc')->get();

        return view('livewire.admin.showtimes.showtime-edit', compact('movies', 'rooms', 'relatedShowtimes'));
    }
}
