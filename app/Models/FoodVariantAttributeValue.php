<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodVariantAttributeValue extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'food_variant_id',
        'food_attribute_value_id',
    ];

    public function foodVariant()
    {
        return $this->belongsTo(FoodVariant::class);
    }

    public function attributeValue()
    {
        return $this->belongsTo(FoodAttributeValue::class);
    }
}
