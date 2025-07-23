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

    protected $casts = [
        'price' => 'integer',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
