<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'thumbnail', 'title', 'content', 'link',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_notifications')->withPivot('is_read')->withTimestamps();
    }
}
