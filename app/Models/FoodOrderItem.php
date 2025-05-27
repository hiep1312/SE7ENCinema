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

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function foodVariant()
    {
        return $this->belongsTo(FoodVariant::class);
    }
}
