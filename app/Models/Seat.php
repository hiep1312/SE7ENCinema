<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    protected $fillable = [
        'room_id',
        'seat_row',
        'seat_number',
        'price',
        'seat_type',
        'status',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function getSeatCodeAttribute()
    {
        return $this->seat_row . $this->seat_number;
    }
}
