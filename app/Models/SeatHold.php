<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class SeatHold extends Model
{
    use HasFactory;

    protected $fillable = [
        'showtime_id',
        'seat_id',
        'session_id',
        'user_ip',
        'held_at',
        'expires_at',
        'status'
    ];

    protected $casts = [
        'held_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function showtime()
    {
        return $this->belongsTo(Showtime::class);
    }

    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }

    public function isExpired()
    {
        return $this->expires_at <= now();
    }

    public function getRemainingTimeAttribute()
    {
        if ($this->isExpired()) {
            return 0;
        }

        return $this->expires_at->diffInSeconds(now());
    }

    public static function cleanupExpired()
    {
        return self::where('expires_at', '<', now())->delete();
    }

    public static function getActiveHolds($showtimeId, $excludeSessionId = null)
    {
        $query = self::where('showtime_id', $showtimeId)
            ->where('status', 'holding')
            ->where('expires_at', '>', now());

        if ($excludeSessionId) {
            $query->where('session_id', '!=', $excludeSessionId);
        }

        return $query->get();
    }

    public static function releaseHoldsBySession($sessionId)
    {
        return self::where('session_id', $sessionId)
            ->where('status', 'holding')
            ->update(['status' => 'released']);
    }

}
