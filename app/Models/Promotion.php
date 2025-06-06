<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $fillable = [
        'title', 'description', 'start_date', 'end_date',
        'discount_type', 'discount_value', 'code',
        'usage_limit', 'min_purchase', 'status'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function usages() {
        return $this->hasMany(PromotionUsage::class);
    }
}
