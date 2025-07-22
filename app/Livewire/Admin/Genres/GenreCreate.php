<?php

namespace App\Livewire\Admin\Genres;

use App\Models\Genre;
use App\Models\Movie;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class GenreCreate extends Component
{
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

    public function createGenre(){
        $this->validate();

        $genreAdded = Genre::create([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        $genreAdded->movies()->attach($this->moviesSelected);

        return redirect()->route('admin.genres.index')->with('success', 'Tạo thể loại mới thành công!');
    }

    #[Title('Tạo thể loại - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        $movies = Movie::select('id', 'title')->when($this->searchMovie, fn ($query) => $query->whereLike('title', '%' . trim($this->searchMovie) . '%'))->get();
        return view('livewire.admin.genres.genre-create', compact('movies'));
    }
}
