<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'last_time_message',
    ];

    protected $casts = [
        'last_time_message' => 'datetime',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('sender_id', $userId)
            ->orWhere('receiver_id', $userId);
    }

    public function scopeWithStaff($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('sender_id', $userId)
                ->whereHas('receiver', function ($subQ) {
                    $subQ->where('role', 'admin');
                });
        })->orWhere(function ($q) use ($userId) {
            $q->where('receiver_id', $userId)
                ->whereHas('sender', function ($subQ) {
                    $subQ->where('role', 'admin');
                });
        });
    }
}
