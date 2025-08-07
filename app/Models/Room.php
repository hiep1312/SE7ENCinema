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
        'check_lonely',
        'check_sole',
        'check_diagonal',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'last_maintenance_date' => 'date',
        'check_lonely' => 'boolean',
        'check_sole' => 'boolean',
        'check_diagonal' => 'boolean'
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
