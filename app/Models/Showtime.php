<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Showtime extends Model
{
    protected $fillable = [
        'movie_id',
        'room_id',
        'start_time',
        'end_time',
        'price',
        'status',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'price' => 'integer',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function booking()
    {
        return $this->hasOne(Booking::class);
    }

    public function isLockedForDeletion(){
        return ($this->start_time->lt(now()->addHour()) || $this->booking()->exists()) && $this->status !== "completed";
    }
}
