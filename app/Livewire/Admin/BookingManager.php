<?php

namespace App\Livewire\Admin;
use Livewire\Component;
use App\Models\Booking;

class BookingManager extends Component
{
        public $statusFilter = 'pending'; // Lọc theo trạng thái

        public function mount()
        {
            // Mặc định hiển thị booking chưa thanh toán
            $this->statusFilter = 'pending';
        }

        public function updatedStatusFilter($value)
        {
            // Khi thay đổi trạng thái lọc
            $this->statusFilter = $value;
        }

        public function markPaid($bookingId)
        {
            $booking = Booking::find($bookingId);
            if ($booking) {
                $booking->status = 'paid';
                $booking->save();
                session()->flash('success', 'Đã đánh dấu thanh toán cho booking #' . $booking->booking_code);
            }
        }

        public function render()
        {
            $bookings = Booking::where('status', $this->statusFilter)
                ->with('user', 'showtime.movie')
                ->orderBy('created_at', 'desc')
                ->get();

            return view('livewire.admin.booking-manager', compact('bookings'));
        }
}

