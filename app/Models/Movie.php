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

    /**
     * Kiểm tra xem phim có suất chiếu đang hoạt động không
     */
    public function hasActiveShowtimes()
    {
        return $this->showtimes()
            ->where('end_time', '>=', now())
            ->where('status', 'active')
            ->exists();
    }

    /**
     * Kiểm tra xem phim có suất chiếu trong tương lai không
     */
    public function hasFutureShowtimes()
    {
        return $this->showtimes()
            ->where('start_time', '>', now())
            ->where('status', 'active')
            ->exists();
    }

    /**
     * Lấy thời lượng phim dưới dạng giờ:phút
     */
    public function getFormattedDurationAttribute()
    {
        $hours = floor($this->duration / 60);
        $minutes = $this->duration % 60;

        if ($hours > 0) {
            return $hours . 'h ' . $minutes . 'm';
        }

        return $minutes . 'm';
    }

    /**
     * Kiểm tra phim có đang chiếu không
     */
    public function isShowing()
    {
        return $this->status === 'showing';
    }

    /**
     * Kiểm tra phim có sắp chiếu không
     */
    public function isComingSoon()
    {
        return $this->status === 'coming_soon';
    }
}
