<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserViolation extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'user_ip',
        'violation_type',
        'violation_details',
        'occurred_at'
    ];

    protected $casts = [
        'occurred_at' => 'datetime',
    ];

    public static function countViolations($sessionId, $userIp, $type = null, $hours = 1)
    {
        $query = self::where(function ($q) use ($sessionId, $userIp) {
            $q->where('session_id', $sessionId)
                ->orWhere('user_ip', $userIp);
        })->where('occurred_at', '>=', now()->subHours($hours));

        if ($type) {
            $query->where('violation_type', $type);
        }

        return $query->count();
    }

    public static function addViolation($sessionId, $userIp, $type, $details = null)
    {
        return self::create([
            'session_id' => $sessionId,
            'user_ip' => $userIp,
            'violation_type' => $type,
            'violation_details' => $details,
            'occurred_at' => now()
        ]);
    }
}
