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
    public BookingSeat $bookingSeat;
    public $userName = '';
    public $notes = null;

    protected $rules = [
        'userName' => 'required|string|max:255',
        'notes' => 'nullable|string|max:500',
    ];

    protected $messages = [
        'userName.required' => 'Tên người dùng là bắt buộc.',
        'userName.string' => 'Tên người dùng phải là chuỗi ký tự.',
        'userName.max' => 'Tên người dùng không được vượt quá 255 ký tự.',
        'notes.string' => 'Ghi chú phải là chuỗi ký tự.',
        'notes.max' => 'Ghi chú không được vượt quá 500 ký tự.',
    ];

    public function mount(string $bookingCode, ?int $index = null) {
        $this->booking = Booking::with('showtime.movie.genres', 'showtime.room', 'user')->where('booking_code', $bookingCode)->first();
        if(is_null($this->booking)) abort(404);
        $bookingSeat = BookingSeat::with('seat', 'ticket')->where('booking_id', $this->booking->id)->get();
        $this->bookingSeat = !is_null($index) ? $bookingSeat->get(--$index, $bookingSeat->first()) : $bookingSeat->first();

        if(!$this->bookingSeat->ticket->isValidTicketOrder()) return redirect()->route('admin.tickets.index');
        $this->resetCustomInfo();
    }

    public function realtimeUpdate(){
        Ticket::with('bookingSeat.booking.showtime')->each(function (Ticket $ticket) {
            $showtime = $ticket->bookingSeat->booking->showtime;
            if($showtime->start_time->isFuture() && $ticket->status === 'canceled') $ticket->status = 'active';
            elseif($showtime->end_time->isPast() && $ticket->status === 'active') $ticket->status = 'canceled';

            $ticket->save();
        });

        $this->booking = Booking::with('showtime.movie.genres', 'showtime.room', 'user')->where('id', $this->booking->id)->first();
        $this->bookingSeat = BookingSeat::with('seat', 'ticket')->where('id', $this->bookingSeat->id)->first();
    }

    public function setCustomInfo(){
        $this->validate();

        session(['userName' => [$this->userName, now()->addHour()]]);
        Ticket::where('id', $this->bookingSeat->ticket->id)->update(['note' => $this->notes]);
        session()->flash('success', 'Thiết lập thông tin người dùng thành công!');
        $this->js("toggleSettings(document.querySelector('.settings-btn'));");

        $this->realtimeUpdate();
    }

    public function resetCustomInfo(){
        session()->forget('userName');
        $this->userName = $this->booking->user->name;
        $this->notes = $this->bookingSeat->ticket->note;
    }

    public function updateTakenStatus(){
        $this->bookingSeat->ticket->taken = true;
        $this->bookingSeat->ticket->save();
        $this->realtimeUpdate();
    }

    #[Title('Vé xem phim - SE7ENCinema')]
    #[Layout('livewire.client.ticket.layout')]
    public function render()
    {
        if(session()->has('userName') && isset(session('userName')[1]) && session('userName')[1]->isPast()) $this->resetCustomInfo();

        return view('livewire.client.ticket.index');
    }
}
