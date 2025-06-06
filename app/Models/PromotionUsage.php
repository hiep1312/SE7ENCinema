<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromotionUsage extends Model
{
     protected $fillable = [
        'promotion_id', 'booking_id', 'discount_amount', 'used_at'
    ];

    protected $casts = [
        'used_at' => 'datetime'
    ];

    public function promotion() {
        return $this->belongsTo(Promotion::class);
    }

    public function booking() {
        return $this->belongsTo(Booking::class);
    }
}
