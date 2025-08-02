<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BannedSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'user_ip',
        'banned_at',
        'banned_until',
        'reason'
    ];

    protected $casts = [
        'banned_at' => 'datetime',
        'banned_until' => 'datetime',
    ];

    public static function isBanned($sessionId, $userIp)
    {
        return self::where(function ($q) use ($sessionId, $userIp) {
            $q->where('session_id', $sessionId)
                ->orWhere('user_ip', $userIp);
        })->where('banned_until', '>', now())
            ->exists();
    }

    public static function banUser($sessionId, $userIp, $reason, $hours = 24)
    {
        return self::create([
            'session_id' => $sessionId,
            'user_ip' => $userIp,
            'banned_at' => now(),
            'banned_until' => now()->addHours($hours),
            'reason' => $reason
        ]);
    }

    public static function getBanInfo($sessionId, $userIp)
    {
        return self::where(function ($q) use ($sessionId, $userIp) {
            $q->where('session_id', $sessionId)
                ->orWhere('user_ip', $userIp);
        })->where('banned_until', '>', now())
            ->first();
    }
}
