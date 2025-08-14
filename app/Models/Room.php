<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'capacity',
        'status',
        'last_maintenance_date',
        'seat_algorithms'
    ];

    protected $casts = [
        'capacity' => 'integer',
        'last_maintenance_date' => 'date'
    ];

    protected function seatAlgorithms(): Attribute
    {
        return Attribute::make(
            get: fn(string $algorithm) => json_decode($algorithm, true, 512, JSON_INVALID_UTF8_SUBSTITUTE),
        );
    }

    public function seats()
    {
        return $this->hasMany(Seat::class);
    }

    public function showtimes()
    {
        return $this->hasMany(Showtime::class);
    }

    public function hasActiveShowtimes()
    {
        return $this->showtimes()
            ->where('start_time', '>=', now())
            ->where('status', 'active')
            ->exists();
    }
}
