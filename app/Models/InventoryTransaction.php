<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    protected $fillable = [
        'food_item_id',
        'quantity',
        'transaction_type',
        'transaction_date',
        'note',
        'staff_id',
    ];

    public function foodItem() {
        return $this->belongsTo(FoodItem::class);
    }

    public function staff() {
        return $this->belongsTo(User::class, 'staff_id');
    }
}
