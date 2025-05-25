<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
     protected $fillable = [
        'booking_id',
        'note',
        'qr_code',
        'status',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
