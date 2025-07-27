<?php

namespace App\Livewire\Admin\Bookings;

use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\Ticket;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use SE7ENCinema\scAlert;

class BookingDetail extends Component
{
    use WithPagination, scAlert;
    public $booking;
    public $tabCurrent = 'overview';

    public function mount(int $booking){
        $this->booking = Booking::with('showtime.movie', 'showtime.room', 'user', 'seats', 'promotionUsage', 'foodOrderItems.variant.foodItem', 'foodOrderItems.variant.attributeValues.attribute')->findOrFail($booking);

        $this->cleanupBookingsAndUpdateData(['isConfirmed' => true]);
    }

    public function cleanupBookingsAndUpdateData(?array $status = null){
        if($this->booking->status === 'expired' && ($this->booking->showtime->start_time->addMinutes(-15) <= now() || $this->booking->created_at->addMinutes(30) <= now())){
            if(is_array($status) && isset($status['isConfirmed'])):
                $this->booking->delete();
                return redirect()->route('admin.bookings.index');
            endif;

            $this->scAlert('Đơn hàng này đã bị xóa do hết hạn giữ!', 'Đơn hàng đã bị hệ thống tự động xóa vì đã quá thời gian giữ. Bạn sẽ được chuyển hướng về danh sách đơn hàng!', 'info', 'cleanupBookingsAndSyncShowtimes');
            session()->flash('deleteExpired', true);
        }

        $showtime = $this->booking->showtime;
        $startTime = $showtime->start_time;
        $endTime = $showtime->end_time;
        if($endTime->isPast()) $showtime->status = 'completed';
        elseif(($startTime->isFuture() || $endTime->isFuture()) && $showtime->status === 'completed') $showtime->status = 'active';
        $showtime->save();

        Ticket::with('bookingSeat.booking.showtime')->each(function (Ticket $ticket) {
            $showtime = $ticket->bookingSeat->booking->showtime;
            if($showtime->start_time->isFuture() && $ticket->status === 'canceled') $ticket->status = 'active';
            elseif($showtime->end_time->isPast() && $ticket->status === 'active') $ticket->status = 'canceled';

            $ticket->save();
        });
    }

    #[Title('Chi tiết đơn hàng - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        $tickets = BookingSeat::where('booking_id', $this->booking->id)->with('ticket')->get()->map(fn($bookingSeat) => $bookingSeat->ticket);

        return view('livewire.admin.bookings.booking-detail', compact('tickets'));
    }
}
