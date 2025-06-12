<?php

namespace App\Livewire\Admin\Genres;

use App\Models\Genre;
use App\Models\Movie; // Ensure this import is present
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class GenreEdit extends Component
{
    public $genreId;
    public $name;
    public $description;
    public $movie_ids = [];

    public function mount($id)
    {
        $this->genreId = $id;
        $genre = Genre::findOrFail($id);
        $this->name = $genre->name;
        $this->description = $genre->description;
        $this->movie_ids = $genre->movies->pluck('id')->toArray();
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:genres,name,' . $this->genreId,
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

    public function update()
    {
        $validated = $this->validate();

        $genre = Genre::findOrFail($this->genreId);
        $genre->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
        ]);

        $genre->movies()->sync($this->movie_ids ?: []);

        session()->flash('success', 'Cập nhật thể loại thành công!');
        return redirect()->route('admin.genres.index');
    }

    #[Layout('components.layouts.admin')]
    #[Title('Chỉnh sửa thể loại')]

    public function render()
    {
        try {
            $movies = Movie::select('id', 'title')
                ->whereIn('status', ['showing', 'upcoming'])
                ->get();
        } catch (\Exception $e) {
            $movies = collect(); // Return empty collection if Movie class/model fails
            session()->flash('error', 'Không thể tải danh sách phim do lỗi hệ thống.');
        }

        return view('livewire.admin.genres.genre-edit', compact('movies'));
    }
}