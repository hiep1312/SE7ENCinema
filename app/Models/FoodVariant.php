<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FoodVariant extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'food_attribute_id',
        'value',
        'price',
        'image',
        'quantity_available',
        'limit',
        'status',
    ];

    protected $casts = [
        'price' => 'integer',
    ];

    public function foodAttribute()
    {
        return $this->belongsTo(FoodAttribute::class);
    }

    public function foodOrderItems(){
        return $this->hasMany(FoodOrderItem::class);
    }
}
