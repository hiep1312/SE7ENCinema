<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class FoodVariant extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'food_item_id',
        'sku',
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

    public function attributeValues(){
        return $this->belongsToMany(FoodAttributeValue::class, 'food_variant_attribute_values');
    }

    public function foodOrderItems(){
        return $this->hasMany(FoodOrderItem::class);
    }

    public function variantAttributes(bool $returnRecords = false){
        $queryData = DB::table('food_variants as fv')
            ->join('food_variant_attribute_values as fvav', 'fvav.food_variant_id', '=', 'fv.id')
            ->join('food_attribute_values as fav', 'fav.id', '=', 'fvav.food_attribute_value_id')
            ->join('food_attributes as fa', 'fa.id', '=', 'fav.food_attribute_id')
            ->select($returnRecords ? ['fa.id as attribute_id', 'fa.name', 'fa.description', 'fvav.id as value_id', 'fav.value'] : [DB::raw("CONCAT('{', GROUP_CONCAT(CONCAT('\"', fa.name, '\":\"', fav.value, '\"') SEPARATOR ','), '}') as attributes")])
            ->where('fv.id', $this->id);

        if($returnRecords) return $queryData->get();
        else return collect(json_decode($queryData->groupBy('fv.id')->first()->attributes ?? '{}', true, 512, JSON_INVALID_UTF8_SUBSTITUTE) ?? []);
    }

    public static function getAttributesByVariantId(int|string $variantId, bool $returnRecords = false){
        return self::find($variantId)?->variantAttributes($returnRecords);
    }
}
