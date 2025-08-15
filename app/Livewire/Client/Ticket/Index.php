<?php

namespace App\Livewire\Client\Ticket;

use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\Ticket;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class Index extends Component
{
    public ?Booking $booking;
    public ?BookingSeat $bookingSeat = null;
    public $statusFilter = '';
    public $takenFilter = '';

    public function mount(string $bookingCode, ?int $index = null) {
        $this->booking = Booking::where('booking_code', $bookingCode)->first();
        if(is_null($this->booking) || $this->booking->status !== 'paid') abort(404);

        if(is_int($index)){
            $bookingSeat = BookingSeat::where('booking_id', $this->booking->id)->get();
            $this->bookingSeat = $bookingSeat->get(--$index, $bookingSeat->first());
        }

        $this->resetCustomInfoAll();
    }

    public function resetCustomInfoAll(){
        foreach (session()->all() as $key => $value) {
            if (str_starts_with($key, 'userName')) {
                session()->forget($key);
            }
        }
    }

    public function realtimeUpdate(){
        Ticket::with('bookingSeat.booking.showtime')->each(function (Ticket $ticket) {
            $showtime = $ticket->bookingSeat->booking->showtime;
            if($showtime->start_time->isFuture() && $ticket->status === 'canceled') $ticket->status = 'active';
            elseif($showtime->end_time->isPast() && $ticket->status === 'active') $ticket->status = 'canceled';

            $ticket->save();
        });
    }

    #[Title('Vé xem phim - SE7ENCinema')]
    #[Layout('livewire.client.ticket.layout')]
    public function render()
    {
        $bookingSeatsOrigin = BookingSeat::with('ticket')
            ->where('booking_id', $this->booking->id);

        $bookingSeats = (clone $bookingSeatsOrigin)->when($this->statusFilter, function ($query) {
                $query->whereHas('ticket', function ($q) {
                    $q->where('status', $this->statusFilter);
                });
            })
            ->when($this->takenFilter !== '', function ($query) {
                $query->whereHas('ticket', function ($q) {
                    $q->where('taken', $this->takenFilter);
                });
            })
            ->get();

        return view('livewire.client.ticket.index', compact('bookingSeats') + ['bookingSeatsOrigin' => $bookingSeatsOrigin->get()]);
    }
}
