<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodAttribute extends Model
{
    protected $fillable = [
        'food_item_id',
        'name',
        'description',
    ];

    public function foodItem()
    {
        return $this->belongsTo(FoodItem::class);
    }

    public function values()
    {
        return $this->hasMany(FoodAttributeValue::class);
    }
}
