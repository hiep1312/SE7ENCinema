<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodVariant extends Model
{
    protected $fillable = [
        'food_item_id',
        'name',
        'price',
        'image',
        'quantity_available',
        'limit',
        'status',
    ];

    protected $casts = [
        'price' => 'integer',
    ];

    public function foodItem()
    {
        return $this->belongsTo(FoodItem::class);
    }
}
