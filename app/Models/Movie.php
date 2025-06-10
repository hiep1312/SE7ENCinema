<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Movie extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'title',
        'description',
        'duration',
        'release_date',
        'end_date',
        'director',
        'actors',
        'age_restriction',
        'poster',
        'trailer_url',
        'format',
        'price',
        'status',
    ];

    protected $casts = [
        'release_date' => 'date',
        'end_date' => 'date',
        'duration' => 'integer',
        'price' => 'integer',
        'deleted_at' => 'datetime',
    ];

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'movie_genres');
    }

    public function showtimes()
    {
        return $this->hasMany(Showtime::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
}
