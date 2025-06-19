<?php

namespace App\Livewire\Admin\Movies;

use App\Models\Genre;
use App\Models\Movie;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

class MovieCreate extends Component
{
    use WithFileUploads;

    public $title = '';
    public $description = null;
    public $duration = null;
    public $release_date = null;
    public $end_date = null;
    public $director = null;
    public $actors = null;
    public $age_restriction = 'P';
    public $poster = null;
    public $trailer_url = null;
    public $format = '2D';
    public $price = null;
    public $status = "coming_soon";

    /* Genre */
    public $searchGenre = '';
    public $genresSelected = [];

    /* Tab */
    public $tabCurrent = 'showtimes';
    public $baseShowtimeStart = null;
    public $baseShowtimeEnd = null;

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'duration' => 'required|integer|min:1',
        'release_date' => 'required|date',
        'end_date' => 'nullable|date|after_or_equal:release_date',
        'director' => 'nullable|string|max:255',
        'actors' => 'nullable|string',
        'age_restriction' => 'required|in:P,K,T13,T16,T18,C',
        'poster' => 'nullable|image|max:20480',
        'trailer_url' => 'nullable|url',
        'format' => 'required|in:2D,3D,4DX,IMAX',
        'price' => 'required|integer|min:0',
        'status' => 'required|in:coming_soon,showing,ended',
    ];

    protected $messages = [
        'title.required' => 'Vui lòng nhập tiêu đề phim.',
        'title.max' => 'Tiêu đề phim không được vượt quá 255 ký tự.',
        'duration.required' => 'Vui lòng nhập thời lượng phim.',
        'duration.integer' => 'Thời lượng phim phải là một số nguyên.',
        'duration.min' => 'Thời lượng phim phải lớn hơn 0 phút.',
        'release_date.required' => 'Vui lòng chọn ngày khởi chiếu.',
        'release_date.date' => 'Ngày khởi chiếu không hợp lệ.',
        'end_date.date' => 'Ngày kết thúc không hợp lệ.',
        'end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày khởi chiếu.',
        'director.max' => 'Tên đạo diễn không được vượt quá 255 ký tự.',
        'age_restriction.required' => 'Vui lòng chọn độ tuổi giới hạn.',
        'age_restriction.in' => 'Giá trị độ tuổi không hợp lệ.',
        'poster.image' => 'Ảnh poster phải là một hình ảnh hợp lệ (jpg, png, v.v).',
        'poster.max' => 'Dung lượng ảnh không được vượt quá 20MB.',
        'trailer_url.url' => 'Đường dẫn trailer không hợp lệ.',
        'format.required' => 'Vui lòng chọn định dạng phim.',
        'format.in' => 'Định dạng phim không hợp lệ.',
        'price.required' => 'Vui lòng nhập giá vé.',
        'price.integer' => 'Giá vé phải là một số.',
        'price.min' => 'Giá vé không được âm.',
        'status.required' => 'Vui lòng chọn trạng thái phim.',
        'status.in' => 'Trạng thái phim không hợp lệ.',
    ];

    public function updatingBaseShowtimeStart() {
        $this->validate([
            'baseShowtimeStart' => 'nullable|date_format:Y-m-d\TH:i|after_or_equal:+2 hours',
        ], [
            'baseShowtimeStart.date_format' => 'Thời gian bắt đầu chiếu phải có định dạng đúng: YYYY-MM-DD HH:MM.',
            'baseShowtimeStart.after_or_equal' => 'Thời gian bắt đầu chiếu phải cách thời điểm hiện tại ít nhất 2 tiếng.',
        ]);
    }

    public function updatingBaseShowtimeEnd() {
        $this->validate([
            'baseShowtimeEnd' => 'nullable|date_format:Y-m-d\TH:i|after:baseShowtimeStart',
        ], [
            'baseShowtimeEnd.date_format' => 'Thời gian kết thúc chiếu phải có định dạng đúng: YYYY-MM-DD HH:MM.',
            'baseShowtimeEnd.after_or_equal' => 'Thời gian kết thúc chiếu phải sau thời gian bắt đầu chiếu.',
        ]);
    }

    public function createMovie()
    {
        $this->validate();

        $imagePath = '';
        !$this->poster ?: ($imagePath = $this->poster->store('movies', 'public'));

        $movieAdded =  Movie::create([
            'title' => $this->title,
            'description' => $this->description,
            'duration' => $this->duration,
            'release_date' => $this->release_date,
            'end_date' => $this->end_date,
            'director' => $this->director,
            'actors' => $this->actors,
            'age_restriction' => $this->age_restriction,
            'poster' => $imagePath,
            'trailer_url' => $this->trailer_url,
            'format' => $this->format,
            'price' => $this->price,
            'status' => $this->status,
        ]);

        $movieAdded->genres()->attach($this->genresSelected);

        return redirect()->route('admin.movies.index')->with('success', 'Thêm mới phim thành công!');
    }

    #[Title('Thêm phim - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        $genres = Genre::select('id', 'name')->when($this->searchGenre, fn ($query) => $query->where('name', 'like', '%' . $this->searchGenre . '%'))->get();
        return view('livewire.admin.movies.movie-create', compact('genres'));
    }
}
