<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $fillable = [
        'title', 'description', 'duration', 'release_date', 'end_date',
        'director', 'actors', 'age_restriction', 'poster',
        'trailer_url', 'format', 'price', 'status',
    ];

    protected $casts = [
        'release_date' => 'date',
        'end_date' => 'date',
    ];

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'movie_genres');
    }
}
