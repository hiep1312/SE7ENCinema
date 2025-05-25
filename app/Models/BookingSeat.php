<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingSeat extends Model
{
    protected $fillable = [
        'seat_id',
        'booking_id',
    ];

    public $timestamps = false;
}
