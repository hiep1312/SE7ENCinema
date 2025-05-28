<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use SoftDeletes;
<<<<<<< HEAD
=======

>>>>>>> f21ed9a42dc4e4b506b77e88bd44eabf817ce152
    protected $fillable = [
        'name',
        'capacity',
        'status',
        'last_maintenance_date',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'last_maintenance_date' => 'date',
        'deleted_at' => 'datetime',
    ];

    public function seats()
    {
        return $this->hasMany(Seat::class);
    }

    public function showtimes()
    {
        return $this->hasMany(Showtime::class);
    }

    /**
     * Kiểm tra xem phòng có suất chiếu đang hoạt động không
     */
    public function hasActiveShowtimes()
    {
        return $this->showtimes()
            ->where('start_time', '>=', now())
            ->where('status', 'active')
            ->exists();
    }

    /**
     * Kiểm tra xem phòng có suất chiếu trong tương lai không
     */
    public function hasFutureShowtimes()
    {
        return $this->showtimes()
            ->where('start_time', '>', now())
            ->where('status', 'active')
            ->exists();
    }

    /**
     * Lấy suất chiếu tiếp theo
     */
    public function getNextShowtimeAttribute()
    {
        return $this->showtimes()
            ->with(['movie' => function($query) {
                $query->withTrashed();
            }])
            ->where('start_time', '>=', now())
            ->where('status', 'active')
            ->orderBy('start_time', 'asc')
            ->first();
    }

    /**
     * Kiểm tra có thể chỉnh sửa không
     */
    public function canEdit()
    {
        return !$this->hasActiveShowtimes();
    }

    /**
     * Kiểm tra có thể xóa không
     */
    public function canDelete()
    {
        return !$this->hasFutureShowtimes();
    }
}
