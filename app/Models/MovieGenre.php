<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovieGenre extends Model
{
    protected $fillable = ['movie_id', 'genre_id'];

    public $timestamps = false;
}
