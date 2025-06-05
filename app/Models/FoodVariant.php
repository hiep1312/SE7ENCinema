<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FoodVariant extends Model
{
    use SoftDeletes;
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

    public function foodOrderItems(){
        return $this->hasMany(FoodOrderItem::class);
    }
}
