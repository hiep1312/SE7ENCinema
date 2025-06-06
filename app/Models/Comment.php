<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'user_id', 'movie_id', 'parent_comment_id',
        'reply_comment_id', 'content', 'status'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function movie() {
        return $this->belongsTo(Movie::class);
    }

    public function parent() {
        return $this->belongsTo(Comment::class, 'parent_comment_id');
    }

    public function reply() {
        return $this->belongsTo(Comment::class, 'reply_comment_id');
    }
}
