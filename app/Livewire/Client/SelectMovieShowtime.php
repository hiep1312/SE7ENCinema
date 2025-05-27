<?php
namespace App\Livewire\Client;

use Livewire\Component;
use App\Models\Movie;
use App\Models\Showtime;
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
            ->where('status', 'active')
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

    public function render()
    {
        return view('livewire.client.select-movie-showtime')->layout('client');;
    }
}
