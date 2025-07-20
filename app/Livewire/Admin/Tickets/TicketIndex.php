<?php

namespace App\Livewire\Admin\Tickets;

use App\Models\Booking;
use App\Models\Movie;
use App\Models\Ticket;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class TicketIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $takenFilter = '';
    public $statusFilter = '';
    public $movieFilter = '';

    public function resetFilters()
    {
        $this->reset(['search', 'takenFilter', 'statusFilter', 'movieFilter']);
        $this->resetPage();
    }

    public function realtimeUpdate(){
        Ticket::with('bookingSeat.booking.showtime')->each(function (Ticket $ticket) {
            $showtime = $ticket->bookingSeat->booking->showtime;
            if($showtime->start_time->isFuture() && $ticket->status === 'canceled') $ticket->status = 'active';
            elseif($showtime->end_time->isPast() && $ticket->status === 'active') $ticket->status = 'canceled';

            $ticket->save();
        });

        Booking::where('status', 'expired')
            ->with('showtime')->get()->each(function($booking){
                if($booking->showtime->start_time->addMinutes(-15) <= now() || $booking->created_at->addMinutes(30) <= now()){
                    $booking->delete();
                }
            });
    }

    #[Title('Danh sách vé phim - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        $this->realtimeUpdate();

        $query = Ticket::with(['bookingSeat.booking' => function($query){
                $query->with('user', 'showtime.room', 'showtime.movie');
            }, 'bookingSeat.seat'])
            ->when($this->search, function($query) {
                $query->where(function($subQuery){
                    $subQuery->where('note', 'like', '%' . trim($this->search) . '%')
                    ->orWhereHas('bookingSeat', function($q){
                        $q->whereHas('booking', function($q){
                            $q->where('booking_code', 'like', '%' . trim($this->search) . '%')
                            ->orWhereHas('user', fn($q) => $q->where('name', 'like', '%' . trim($this->search) . '%'))
                            ->orWhereHas('showtime', fn($q) => $q->whereHas('room', fn($q) => $q->whereLike('name', '%' . trim($this->search) . '%')));
                        })
                        ->orWhereHas('seat', fn($q) => $q->whereRaw("CONCAT(seat_row, seat_number) like ?", ['%' . trim($this->search) . '%']));
                    });
                });
            })
            ->when($this->takenFilter !== '', fn($query) => $query->where('taken', $this->takenFilter))
            ->when($this->statusFilter, fn($query) => $query->where('status', $this->statusFilter));

        $movies = Movie::select('id', 'title')->whereIn('id', (clone $query)->get()->pluck('bookingSeat.booking.showtime.movie_id')->unique())->get();
        $query->when($this->movieFilter, fn($query) => $query->whereHas('bookingSeat', function($query) {
                $query->whereHas('booking', fn($query) => $query->whereHas('showtime', fn($query) => $query->where('movie_id', $this->movieFilter)));
            }));
        $tickets = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('livewire.admin.tickets.ticket-index', compact('tickets', 'movies'));
    }
}
