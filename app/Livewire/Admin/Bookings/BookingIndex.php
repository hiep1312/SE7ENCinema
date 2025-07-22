<?php

namespace App\Livewire\Admin\Bookings;

use App\Models\Booking;
use App\Models\Showtime;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class BookingIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $paymentMethodFilter = '';
    public $priceFilter = null;
    public $rangePrice = [];

    public function mount(){
        $orders = Booking::all();
        $this->priceFilter = $this->rangePrice = [$orders->min('total_price'), $orders->max('total_price')];
        $this->js('updateSlider');
    }

    public function resetFilters()
    {
        $this->reset(['search', 'statusFilter', 'paymentMethodFilter']);
        $this->priceFilter = $this->rangePrice;
        $this->js('resetSlider');
        $this->resetPage();
    }

    public function cleanupBookingsAndSyncShowtimes(){
        Booking::where('status', 'expired')
            ->with('showtime')->get()->each(function($booking){
                if($booking->showtime->start_time->addMinutes(-15) <= now() || $booking->created_at->addMinutes(30) <= now()){
                    $booking->delete();
                }
            });

        Showtime::all()->each(function ($showtime) {
            $startTime = $showtime->start_time;
            $endTime = $showtime->end_time;
            if($endTime->isPast()) $showtime->status = 'completed';
            elseif(($startTime->isFuture() || $endTime->isFuture()) && $showtime->status === 'completed') $showtime->status = 'active';
            $showtime->save();
        });
    }

    #[Title('Danh sách đơn hàng - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        $this->cleanupBookingsAndSyncShowtimes();

        $query = Booking::with('user', 'showtime.room', 'showtime.movie', 'seats', 'foodOrderItems', 'promotionUsages')
            ->when($this->search, function($query) {
                $query->where(function($subQuery){
                    $subQuery->where('booking_code', 'like', '%' . trim($this->search) . '%')
                        ->orWhereHas('user', fn($q) => $q->where('name', 'like', '%' . trim($this->search) . '%'))
                        ->orWhereHas('showtime', function($q){
                            $q->whereHas('movie', fn($q) => $q->where('title', 'like', '%' . trim($this->search) . '%'))
                                ->orWhereHas('room', fn($q) => $q->where('name', 'like', '%' . trim($this->search) . '%'));
                        });
                });
            })
            ->when($this->statusFilter, fn($query) => $query->where('status', $this->statusFilter))
            ->when($this->paymentMethodFilter, fn($query) => $query->where('payment_method', $this->paymentMethodFilter))
            ->when($this->priceFilter, fn($query) => $query->whereBetween('total_price', $this->priceFilter));

        $bookings = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('livewire.admin.bookings.booking-index', compact('bookings'));
    }
}
