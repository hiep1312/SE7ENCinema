<?php

namespace App\Livewire\Admin\Movies;

use App\Models\Movie;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class MovieShow extends Component
{
    public Movie $movie;

    public function mount(Movie $movie)
    {
        $this->movie = $movie->load('genres');
    }

    #[Layout('components.layouts.admin')]
    #[Title('Quản lý phim')]

    public function render()
    {
        return view('livewire.admin.movies.movie-show');
    }
}