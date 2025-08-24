<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'last_time_message',
        // thêm các fields khác nếu có
    ];

    protected $casts = [
        'last_time_message' => 'datetime',
    ];

    /**
     * Relationship với User (sender)
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Relationship với User (receiver)
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * Relationship với Messages (nếu có bảng messages)
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Scope để lấy conversations của một user cụ thể
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('sender_id', $userId)
            ->orWhere('receiver_id', $userId);
    }

    /**
     * Scope để lấy conversations với staff (cho user thường)
     */
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
