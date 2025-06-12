<?php

namespace App\Livewire\Admin\Genres;

use App\Models\Genre;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class GenreShow extends Component
{
    public $genreId;

    public function mount($id)
    {
        $this->genreId = $id;
    }

    #[Layout('components.layouts.admin')]
    #[Title('Chi tiết thể loại')]

    public function render()
    {
        $genre = Genre::with('movies')->findOrFail($this->genreId);
        return view('livewire.admin.genres.genre-show', compact('genre'));
    }
}
