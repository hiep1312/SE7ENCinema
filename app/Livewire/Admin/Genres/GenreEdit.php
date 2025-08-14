<?php

namespace App\Livewire\Admin\Genres;

use App\Models\Genre;
use App\Models\Movie;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class GenreEdit extends Component
{
    public $genreItem;
    public $name = '';
    public $description = null;

    /* Genre */
    public $searchMovie = '';
    public $moviesSelected = [];

    /* Tab */
    public $tabCurrent = 'movies';

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',

        'moviesSelected.*' => 'integer|exists:movies,id',
    ];

    protected $messages = [
        'name.required' => 'Tên thể loại là bắt buộc.',
        'name.string' => 'Tên thể loại phải là một chuỗi ký tự.',
        'name.max' => 'Tên thể loại không được vượt quá 255 ký tự.',
        'description.string' => 'Mô tả phải là một chuỗi ký tự.',

        'moviesSelected.*.integer' => 'ID phim không hợp lệ.',
        'moviesSelected.*.exists' => 'Một hoặc nhiều phim đã chọn không tồn tại.',
    ];

    public function mount(Genre $genre){
        $this->genreItem = $genre;
        $this->fill($genre->only('name', 'description'));

        $this->moviesSelected = $genre->movies()->pluck('movies.id')->toArray();
    }

    public function updateGenre(){
        $this->validate();

        $this->genreItem->update([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        $this->genreItem->movies()->sync($this->moviesSelected);

        return redirect()->route('admin.genres.index')->with('success', 'Cập nhật thể loại thành công!');
    }

    #[Title('Chỉnh sửa thể loại - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        $movies = Movie::select('id', 'title')->when($this->searchMovie, fn ($query) => $query->whereLike('title', '%' . trim($this->searchMovie) . '%'))->get();
        $totalMovies = Movie::count();
        return view('livewire.admin.genres.genre-edit', compact('movies', 'totalMovies'));
    }
}
