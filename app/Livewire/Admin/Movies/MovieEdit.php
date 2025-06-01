<?php

namespace App\Livewire\Admin\Movies;

use App\Models\Genre;
use App\Models\Movie;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

class MovieEdit extends Component
{
    use WithFileUploads;

    public Movie $movie;
    public $title;
    public $description;
    public $duration;
    public $release_date;
    public $end_date;
    public $director;
    public $actors;
    public $age_restriction;
    public $poster;
    public $trailer_url;
    public $format;
    public $price;
    public $genre_ids = [];

    public function mount(Movie $movie)
    {
        $this->movie = $movie;
        $this->title = $movie->title;
        $this->description = $movie->description;
        $this->duration = $movie->duration;
        $this->release_date = $movie->release_date->format('Y-m-d');
        $this->end_date = optional($movie->end_date)->format('Y-m-d');
        $this->director = $movie->director;
        $this->actors = $movie->actors;
        $this->age_restriction = $movie->age_restriction;
        $this->trailer_url = $movie->trailer_url;
        $this->format = $movie->format;
        $this->price = $movie->price;
        $this->genre_ids = $movie->genres->pluck('id')->toArray();
    }

    protected function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'duration' => 'required|integer|min:1',
            'release_date' => 'required|date|after_or_equal:' . now()->format('Y-m-d'),
            'end_date' => 'nullable|date|after_or_equal:release_date',
            'director' => 'nullable|string|max:255',
            'actors' => 'nullable|string|max:1000',
            'age_restriction' => 'required|in:P,K,T13,T16,T18,C',
            'poster' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'trailer_url' => 'nullable|url',
            'format' => 'required|in:2D,3D,4DX,IMAX',
            'price' => 'required|integer|min:0',
            'genre_ids' => 'required|array|min:1',
            'genre_ids.*' => 'exists:genres,id',
        ];
    }

    protected $messages = [
        'title.required' => 'Vui lòng nhập tên phim.',
        'title.max' => 'Tên phim không được vượt quá 255 ký tự.',
        'description.max' => 'Mô tả không được vượt quá 1000 ký tự.',
        'duration.required' => 'Vui lòng nhập thời lượng phim.',
        'duration.integer' => 'Thời lượng phải là số nguyên.',
        'duration.min' => 'Thời lượng phải lớn hơn hoặc bằng 1 phút.',
        'release_date.required' => 'Vui lòng nhập ngày phát hành.',
        'release_date.date' => 'Ngày phát hành không hợp lệ.',
        'release_date.after_or_equal' => 'Ngày phát hành phải là hôm nay hoặc trong tương lai.',
        'end_date.date' => 'Ngày kết thúc không hợp lệ.',
        'end_date.after_or_equal' => 'Ngày kết thúc phải cùng hoặc sau ngày phát hành.',
        'director.max' => 'Tên đạo diễn không được vượt quá 255 ký tự.',
        'actors.max' => 'Danh sách diễn viên không được vượt quá 1000 ký tự.',
        'age_restriction.required' => 'Vui lòng chọn độ tuổi hạn chế.',
        'age_restriction.in' => 'Độ tuổi hạn chế không hợp lệ.',
        'poster.image' => 'Poster phải là file ảnh.',
        'poster.mimes' => 'Poster phải là ảnh định dạng jpeg, png hoặc jpg.',
        'poster.max' => 'Kích thước poster tối đa là 2MB.',
        'trailer_url.url' => 'Link trailer không hợp lệ.',
        'format.required' => 'Vui lòng chọn định dạng.',
        'format.in' => 'Định dạng không hợp lệ.',
        'price.required' => 'Vui lòng nhập giá vé.',
        'price.integer' => 'Giá vé phải là số nguyên.',
        'price.min' => 'Giá vé phải lớn hơn hoặc bằng 0.',
        'genre_ids.required' => 'Vui lòng chọn ít nhất 1 thể loại.',
        'genre_ids.array' => 'Thể loại không hợp lệ.',
        'genre_ids.min' => 'Phải chọn ít nhất 1 thể loại.',
        'genre_ids.*.exists' => 'Thể loại chọn không tồn tại.',
    ];

    public function update()
    {
        $validated = $this->validate();

        if ($this->poster) {
            $validated['poster'] = $this->poster->store('posters', 'public');
        } else {
            unset($validated['poster']);
        }

        $this->movie->update($validated);
        $this->movie->genres()->sync($this->genre_ids);

        session()->flash('success', 'Cập nhật thành công!');
        return redirect()->route('admin.index');
    }

    #[Layout('components.layouts.admin')]
    #[Title('Quản lý phim')]

    public function render()
    {
        $genres = Genre::all();
        return view('livewire.admin.movies.movie-edit', compact('genres'));
    }
}