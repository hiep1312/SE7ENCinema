<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodAttributeValue extends Model
{
    protected $fillable = [
        'food_attribute_id',
        'value',
    ];

    public function attribute()
    {
        return $this->belongsTo(FoodAttribute::class, 'food_attribute_id');
    }

    public function variants()
    {
        return $this->belongsToMany(FoodVariant::class, 'food_variant_attribute_values');
    }
}
