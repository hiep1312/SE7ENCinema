<?php

namespace App\Livewire\Admin\Movies;

use App\Models\Genre;
use App\Models\Movie;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

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
    public $delete_poster = false; // Added for poster deletion

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
            'price' => 'required|integer|min:0|max:1000000', // Added max
            'genre_ids' => 'required|array|min:1',
            'genre_ids.*' => 'exists:genres,id',
        ];
    }

    protected $messages = [
        // Existing messages...
        'price.max' => 'Giá vé không được vượt quá 1,000,000 VNĐ.',
    ];

    public function update()
    {
        $validated = $this->validate();

        try {
            if ($this->delete_poster && $this->movie->poster) {
                Storage::disk('public')->delete($this->movie->poster);
                $validated['poster'] = null;
            } elseif ($this->poster) {
                $validated['poster'] = $this->poster->store('posters', 'public');
            } else {
                unset($validated['poster']);
            }

            $this->movie->update($validated);
            $this->movie->genres()->sync($this->genre_ids);

            session()->flash('success', 'Cập nhật thành công!');
            return redirect()->route('admin.movies.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Cập nhật thất bại: ' . $e->getMessage());
        }
    }

    #[Layout('components.layouts.admin')]
    #[Title('Quản lý phim')]

    public function render()
    {
        $genres = Genre::all();
        return view('livewire.admin.movies.movie-edit', compact('genres'));
    }
}