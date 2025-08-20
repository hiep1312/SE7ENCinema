<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class UserViolation extends Model
{
    const SEAT_TIMEOUT = 'seat_timeout';
    const PAYMENT_TIMEOUT = 'payment_timeout';

    protected $fillable = [
        'user_id',
        'violation_type',
        'violation_details',
        'occurred_at',
    ];

    protected $casts = [
        'occurred_at' => 'datetime'
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function countViolations(User|int $user, string|null $type = null, Carbon|int|string $start = 1, Carbon|string|null $end = null): int
    {
        $query = self::where('user_id', is_int($user) ? $user : $user->id)
            ->when($type, fn($q) => $q->where('violation_type', $type));

        if(is_int($start)) $query->where('occurred_at', '>=', now()->subHours($start));
        else $query->where('occurred_at', '>=', is_string($start) ? Carbon::parse($start) : $start);

        if(!is_null($end)) $query->where('occurred_at', '<=', is_string($end) ? Carbon::parse($end) : $end);

        return $query->count();
    }

    public static function addViolation(User|int $user, string $type, ?string $details = null): bool
    {
        return self::create([
            'user_id' => is_int($user) ? $user : $user->id,
            'violation_type' => $type,
            'violation_details' => $details,
            'occurred_at' => now(),
        ]);
    }
}
