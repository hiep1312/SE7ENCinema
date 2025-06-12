<?php

namespace App\Livewire\Admin\Genres;

use App\Models\Genre;
use App\Models\Movie;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class GenreCreate extends Component
{
    public $name;
    public $description;
    public $movie_ids = [];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:genres,name',
            'description' => 'nullable|string|max:1000',
            'movie_ids' => 'nullable|array',
            'movie_ids.*' => 'exists:movies,id',
        ];
    }

    protected $messages = [
        'name.required' => 'Vui lòng nhập tên thể loại.',
        'name.string' => 'Tên thể loại phải là chuỗi ký tự.',
        'name.max' => 'Tên thể loại không được vượt quá 255 ký tự.',
        'name.unique' => 'Tên thể loại đã tồn tại.',
        'description.max' => 'Mô tả không được vượt quá 1000 ký tự.',
        'movie_ids.array' => 'Danh sách phim không hợp lệ.',
        'movie_ids.*.exists' => 'Phim đã chọn không tồn tại.',
    ];

    public function store()
    {
        $validated = $this->validate();

        $genre = Genre::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
        ]);

        if (!empty($this->movie_ids)) {
            $genre->movies()->sync($this->movie_ids);
        }

        session()->flash('success', 'Thêm thể loại thành công!');
        return redirect()->route('admin.genres.index');
    }

    #[Layout('components.layouts.admin')]
    #[Title('Thêm thể loại mới')]

    public function render()
    {
        // Lọc phim có trạng thái 'showing' hoặc 'upcoming'
        $movies = Movie::select('id', 'title')
            ->whereIn('status', ['showing', 'upcoming'])
            ->get();
        
        return view('livewire.admin.genres.genre-create', compact('movies'));
    }
}