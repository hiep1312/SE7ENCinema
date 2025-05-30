<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FoodItem extends Model
{
    use SoftDeletes;
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
