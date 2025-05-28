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

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isCanceled()
    {
        return $this->status === 'canceled';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isPast()
    {
        return $this->end_time->isPast();
    }

    public function isFuture()
    {
        return $this->start_time->isFuture();
    }

    public function isOngoing()
    {
        return $this->start_time->isPast() && $this->end_time->isFuture();
    }
}
