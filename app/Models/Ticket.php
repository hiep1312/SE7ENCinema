<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'booking_seat_id',
        'note',
        'qr_code',
        'taken',
        'taken_at',
        'checkin_at',
        'status',
    ];

    protected $casts = [
        'taken' => 'boolean',
        'taken_at' => 'datetime',
        'checkin_at' => 'datetime',
    ];

    public function bookingSeat()
    {
        return $this->belongsTo(BookingSeat::class);
    }

    public function isValidTicketOrder(){
        return $this->bookingSeat->booking->status === "paid";
    }

    protected function getCurrentIndexAttribute(){
        return BookingSeat::where('booking_id', $this->bookingSeat->booking_id)->get()->search(function($bookingSeat) {
            return $bookingSeat->id === $this->booking_seat_id;
        }) + 1;
    }

    protected function getBookingAttribute(){
        return $this->bookingSeat->booking;
    }
}
