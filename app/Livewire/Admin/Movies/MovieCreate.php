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

    public $title;
    public $description;
    public $duration;
    public $release_date;
    public $end_date;
    public $director;
    public $actors;
    public $age_restriction = '';
    public $poster;
    public $trailer_url;
    public $format = '';
    public $price = null;
    public $genre_ids = [];

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'duration' => 'required|integer|min:1|max:300',
            'release_date' => 'required|date|after_or_equal:' . now()->format('Y-m-d'),
            'end_date' => 'nullable|date|after_or_equal:release_date',
            'director' => 'nullable|string|max:255',
            'actors' => 'nullable|string|max:1000',
            'age_restriction' => 'required|in:P,K,T13,T16,T18,C',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'trailer_url' => 'nullable|url',
            'format' => 'required|in:2D,3D,4DX,IMAX',
            'price' => 'required|integer|min:0|max:1000000',
            'genre_ids' => 'required|array|min:1',
            'genre_ids.*' => 'exists:genres,id',
        ];
    }

    protected $messages = [
        'title.required' => 'Vui lòng nhập tiêu đề phim.',
        'title.string' => 'Tiêu đề phải là chuỗi ký tự.',
        'title.max' => 'Tiêu đề không được vượt quá 255 ký tự.',
        'description.max' => 'Mô tả không được vượt quá 1000 ký tự.',
        'duration.required' => 'Vui lòng nhập thời lượng phim.',
        'duration.integer' => 'Thời lượng phải là số nguyên.',
        'duration.min' => 'Thời lượng phải lớn hơn hoặc bằng 1 phút.',
        'duration.max' => 'Thời lượng không được vượt quá 300 phút.',
        'release_date.required' => 'Vui lòng nhập ngày phát hành.',
        'release_date.date' => 'Ngày phát hành không hợp lệ.',
        'release_date.after_or_equal' => 'Ngày phát hành phải từ hôm nay (02/06/2025) trở đi.',
        'end_date.date' => 'Ngày kết thúc không hợp lệ.',
        'end_date.after_or_equal' => 'Ngày kết thúc phải sau hoặc bằng ngày phát hành.',
        'director.max' => 'Tên đạo diễn không được vượt quá 255 ký tự.',
        'actors.max' => 'Danh sách diễn viên không được vượt quá 1000 ký tự.',
        'age_restriction.required' => 'Vui lòng chọn độ tuổi hạn chế.',
        'age_restriction.in' => 'Độ tuổi hạn chế không hợp lệ (P, K, T13, T16, T18, C).',
        'poster.image' => 'Poster phải là file ảnh.',
        'poster.mimes' => 'Poster chỉ chấp nhận định dạng JPEG, PNG, JPG.',
        'poster.max' => 'Kích thước poster tối đa 2MB.',
        'trailer_url.url' => 'Link trailer không hợp lệ (phải là URL).',
        'format.required' => 'Vui lòng chọn định dạng phim.',
        'format.in' => 'Định dạng không hợp lệ. Vui lòng chọn từ 2D, 3D, 4DX, hoặc IMAX.',
        'price.required' => 'Vui lòng nhập giá vé.',
        'price.integer' => 'Giá vé phải là số nguyên.',
        'price.min' => 'Giá vé phải lớn hơn hoặc bằng 0 VNĐ.',
        'price.max' => 'Giá vé không được vượt quá 1,000,000 VNĐ.',
        'genre_ids.required' => 'Vui lòng chọn ít nhất một thể loại.',
        'genre_ids.array' => 'Thể loại không hợp lệ.',
        'genre_ids.min' => 'Vui lòng chọn ít nhất một thể loại.',
        'genre_ids.*.exists' => 'Thể loại đã chọn không tồn tại.',
    ];

    public function mount()
    {
        $this->age_restriction = '';
        $this->format = '';
        $this->price = null;
    }

    public function store()
    {
        $validated = $this->validate();

        if ($this->poster) {
            $validated['poster'] = $this->poster->store('posters', 'public');
        }

        $movie = Movie::create($validated);
        $movie->genres()->sync($this->genre_ids);

        session()->flash('success', 'Thêm phim thành công!');
        return redirect()->route('admin.movies.index');
    }

    #[Layout('components.layouts.admin')]
    #[Title('Thêm phim mới')]

    public function render()
    {
        $genres = Genre::all();
        return view('livewire.admin.movies.movie-create', compact('genres'));
    }
}