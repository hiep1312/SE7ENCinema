<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class SeatHold extends Model
{
    protected $fillable = [
        'showtime_id',
        'seat_id',
        'user_id',
        'held_at',
        'expires_at',
        'status'
    ];

    protected $casts = [
        'held_at' => 'datetime',
        'expires_at' => 'datetime'
    ];

    public $timestamps = false;

    public function showtime()
    {
        return $this->belongsTo(Showtime::class);
    }

    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function cleanupExpired(): bool
    {
        return self::where('expires_at', '<', now())->delete();
    }

    public static function getActiveHolds(Showtime|int $showtime, User|Collection|int|array|null $excludeUser = null): Collection
    {
        $query = self::where('showtime_id', is_int($showtime) ? $showtime : $showtime->id)
            ->where('status', 'holding')
            ->where('expires_at', '>', now());

        if(is_int($excludeUser) || is_a($excludeUser, User::class)) $query->where('user_id', '!=', $excludeUser);
        elseif(is_array($excludeUser) || is_a($excludeUser, Collection::class)) $query->whereNotIn('user_id', $excludeUser);

        return $query->get();
    }

    public static function releaseHoldsByUser(User|int $user): bool
    {
        return self::where('user_id', is_int($user) ? $user : $user->id)->update(['status' => 'released']);
    }
}
