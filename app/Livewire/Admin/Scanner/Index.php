<?php

namespace App\Livewire\Admin\Scanner;

use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\Ticket;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

class Index extends Component
{
    public string $type;

    #[On('dataQR')]
    public function handleDataQR(mixed ...$result){
        [$data, ] = $result;
        $isInternalQRData = true;
        $options = [];

        if($this->type === "bookings"){
            $scanModel = Booking::where('booking_code', $data)->first();
            $scanModel && ($options = [
                'urlPrint' => route('client.ticket', $scanModel->booking_code),
                'urlDetail' => route('admin.bookings.detail', $scanModel->id),
                'taken' => BookingSeat::with('ticket')->where('booking_id', $scanModel->id)->whereDoesntHave('ticket', function ($query) {
                    $query->where('taken', false);
                })->exists(),
            ]);
        }else {
            $scanModel = Ticket::with('bookingSeat.booking.showtime')->where('qr_code', $data)->first();
            if($scanModel):
                $options = [
                    'used' => $scanModel->status === "used",
                    'expired' => $scanModel->bookingSeat->booking->showtime->start_time->isPast(),
                    'urlDetail' => route('admin.bookings.detail', $scanModel->bookingSeat->booking->id),
                ];

                ($options['used'] || $options['expired']) || $scanModel->update(['status' => 'used']);
            endif;
        }
        $scanModel || ($isInternalQRData = false);

        $this->js('openResultPopup', $isInternalQRData ? $this->type : "other", $data, $options);
    }

    #[Title('Quét QR - SE7ENCinema')]
    #[Layout('livewire.admin.scanner.layout')]
    public function render()
    {
        return view('livewire.admin.scanner.index');
    }
}
