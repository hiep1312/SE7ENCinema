<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodOrderItem extends Model
{
    protected $fillable = [
        'booking_id',
        'food_variant_id',
        'quantity',
        'price',
    ];

    protected $casts = [
        'price' => 'integer',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function variant()
    {
        return $this->belongsTo(FoodVariant::class, 'food_variant_id');
    }
}
