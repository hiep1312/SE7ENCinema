<?php
namespace App\Livewire\Client;

use Livewire\Component;
use App\Models\Movie;
use App\Models\Showtime;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\View\View;
class SelectMovieShowtime extends Component
{
    public $movies;
    public $selectedMovieId = null;
    public $showtimes = [];
    public $selectedShowtimeId = null;

    public function mount()
    {
        $this->movies = Movie::where('status', 'showing')->get();
    }

    public function updatedSelectedMovieId($movieId)
    {
        $this->showtimes = Showtime::where('movie_id', $movieId)
            ->where('status', 'completed')
            ->orderBy('start_time')
            ->get();
        $this->selectedShowtimeId = null;
    }

    public function goToSelectSeats()
    {
        if (!$this->selectedShowtimeId) {
            session()->flash('error', 'Vui lòng chọn giờ chiếu.');
            return;
        }
        return redirect()->route('booking.select_seats', ['showtime_id' => $this->selectedShowtimeId]);
    }

    #[Title('Danh sách phim - SE7ENCinema')]
    #[Layout('components.layouts.client')]
    public function render()
    {
        return view('livewire.client.select-movie-showtime', [
            'movies' => $this->movies,
            'showtimes' => $this->showtimes,
        ]);
    }
}
