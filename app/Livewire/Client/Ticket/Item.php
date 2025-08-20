<?php

namespace App\Livewire\Client\Ticket;

use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\Ticket;
use Livewire\Component;

class Item extends Component
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

    public function mount(Booking $booking, BookingSeat $bookingSeat) {
        $this->booking = $booking->load('showtime.movie.genres', 'showtime.room', 'user');
        $this->bookingSeat = $bookingSeat->load('seat', 'ticket');

        $this->resetCustomInfo(false);
    }

    public function realtimeUpdate(){
        $this->booking = Booking::with('showtime.movie.genres', 'showtime.room', 'user')->where('id', $this->booking->id)->first();
        $this->bookingSeat = BookingSeat::with('seat', 'ticket')->where('id', $this->bookingSeat->id)->first();
    }

    public function setCustomInfo(){
        $this->validate();

        session(["userName{$this->bookingSeat->id}" => [$this->userName, now()->addHour()]]);
        Ticket::where('id', $this->bookingSeat->ticket->id)->update(['note' => empty($this->notes) ? null : $this->notes]);
        session()->flash('success', 'Thiết lập thông tin người dùng thành công!');
        $this->js("toggleSettings({$this->bookingSeat->id});");

        $this->realtimeUpdate();
    }

    public function resetCustomInfo(bool $clearSession = true){
        $clearSession && session()->forget("userName{$this->bookingSeat->id}");
        $this->userName = $this->booking->user->name;
        $this->notes = $this->bookingSeat->ticket->note;
    }

    public function updateTakenStatus(){
        $this->bookingSeat->ticket->taken = true;
        $this->bookingSeat->ticket->save();
        $this->realtimeUpdate();
    }

    public function render()
    {
        if(session()->has("userName{$this->bookingSeat->id}") && isset(session("userName{$this->bookingSeat->id}")[1]) && session("userName{$this->bookingSeat->id}")[1]->isPast()) $this->resetCustomInfo();

        return view('livewire.client.ticket.item');
    }
}
