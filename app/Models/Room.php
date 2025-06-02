<?php

namespace App\Models;

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
    ];

    protected $casts = [
        'capacity' => 'integer',
        'last_maintenance_date' => 'date',
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

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
