<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingSeat extends Model
{
    public $timestamps = false;

    protected $fillable = ['booking_id', 'seat_id'];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }
    public $timestamps = false;

    public function booking() {
        return $this->belongsTo(Booking::class);
    }

    public function seat() {
        return $this->belongsTo(Seat::class);
    }

    public function ticket() {
        return $this->hasOne(Ticket::class);
    }
}
