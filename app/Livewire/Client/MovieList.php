<?php

namespace App\Livewire\Client;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Movie;
use App\Models\Genre;
use Illuminate\Support\Carbon;
 use Illuminate\Support\Str;
use Livewire\Attributes\Url;

class MovieList extends Component
{
    use WithPagination;

    public $tabCurrent = 'showing';
    public $genreFilter = '';
    public $search = '';
    public $perPage = 9; 

    public function updateStatusMovies(){
        Movie::all()->each(function($movie){
            $releaseDate = Carbon::parse($movie->release_date);
            $endDate = !$movie->end_date ?: Carbon::parse($movie->end_date);
            if(is_object($endDate) && $endDate->isPast()) $movie->status = 'ended';
            else if($releaseDate->isFuture()) $movie->status = 'coming_soon';
            else $movie->status = 'showing';
            $movie->save();
        });
    }

    public function render(){
        $this->updateStatusMovies();

        $query = Movie::query()->where('status', $this->tabCurrent)
            ->when($this->genreFilter, function ($query) {
                $query->whereHas('genres', function ($q) {
                    $q->where('id', $this->genreFilter);
                });
            })
            ->when($this->search, fn($query) => $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                ->orWhere('description', 'like', '%' . $this->search . '%');
            }));


        $genres = Genre::with('movies')->whereHas('movies', function ($q) use ($query) {
            $q->whereIn('movies.id', $query->get('id'));
        })->get();

        $topMoviesQuery = Movie::with('showtimes')
            ->withCount([
                'showtimes as paid_booking_count' => function ($query) {
                    $query->whereHas('booking', function ($q) {
                        $q->where('status', 'paid');
                    });
                }
            ])
            ->whereHas('showtimes', function ($query) {
                $query->whereHas('booking', function ($q) {
                    $q->where('status', 'paid');
                });
            })->where('status', 'showing')
            ->when($this->genreFilter, function ($query) {
                $query->whereHas('genres', function ($q) {
                    $q->where('id', $this->genreFilter);
                });
            })->orderBy('paid_booking_count', 'desc');



        $topEventMovie = Movie::withAvg('ratings', 'score')->orderByDesc('ratings_avg_score')->first() ?? $topMoviesQuery->first();
        $movies = $query->orderBy('created_at', 'desc')->paginate(10);
        $topMovies = $topMoviesQuery->paginate(10);

        return view('livewire.client.movie-list', compact('movies', 'genres', 'topMovies', 'topEventMovie'));
    }
}
