<?php

namespace App\Livewire\Client;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Movie;
use App\Models\Genre;
use Illuminate\Support\Carbon;

class MovieList extends Component
{
    use WithPagination;

    public $tabCurrent = 'showing';
    public $genreFilter = '';
    public $search = '';
    public $perPage = 9;

    public function updateStatusMovies()
    {
        Movie::all()->each(function($movie){
            $releaseDate = Carbon::parse($movie->release_date);
            $endDate = !$movie->end_date ?: Carbon::parse($movie->end_date);
            if(is_object($endDate) && $endDate->isPast()) $movie->status = 'ended';
            else if($releaseDate->isFuture()) $movie->status = 'coming_soon';
            else $movie->status = 'showing';
            $movie->save();
        });
    }

    public function gotoPage($page)
    {
        $this->setPage($page);
    }

    public function render()
    {
        $this->updateStatusMovies();

        $query = Movie::query()->where('status', $this->tabCurrent)
            ->when($this->genreFilter, function ($query) {
                $query->whereHas('genres', function ($q) {
                    $q->where('genres.id', $this->genreFilter);
                });
            })
            ->when($this->search, fn($query) => $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                ->orWhere('description', 'like', '%' . $this->search . '%');
            }));

        $genres = Genre::with('movies')->whereHas('movies', function ($q) use ($query) {
            $q->whereIn('movies.id', $query->get('id'));
        })->get();

        $topMovies = Movie::with(['showtimes', 'ratings'])
            ->withCount([
                'showtimes as booking_count' => function ($query) {
                    $query->whereHas('booking', function ($q) {
                        $q->where('status', 'paid');
                    });
                }
            ])
            ->withAvg('ratings', 'score')
            ->where('status', 'showing')
            ->having('booking_count', '>', 0)
            ->orderByDesc('booking_count')
            ->orderByDesc('ratings_avg_score')
            ->limit(7)
            ->get();

        // Top Event Movie
        $topEventMovie = Movie::withAvg('ratings', 'score')
            ->where('status', 'showing')
            ->where('age_restriction', '!=', 'C')
            ->orderByDesc('ratings_avg_score')
            ->first() ?? $topMovies->first();
        $movies = $query->where('age_restriction', '!=', 'C')->orderBy('created_at', 'desc')->paginate(20);

        return view('livewire.client.movie-list', compact(
            'movies',
            'genres',
            'topMovies',
            'topEventMovie'
        ));
    }
}
