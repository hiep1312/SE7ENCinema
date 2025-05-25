<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodItem extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image',
        'status',
    ];

    public function variants()
    {
        return $this->hasMany(FoodVariant::class);
    }
}
