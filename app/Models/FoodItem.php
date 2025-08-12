<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class FoodItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'image',
        'status',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function attributes()
    {
        return $this->hasMany(FoodAttribute::class);
    }

    public function variants()
    {
        return $this->hasMany(FoodVariant::class);
    }

    public function getAllVariants(bool $attributeGroups = false, string ...$columns)
    {
        $base = empty($columns) ? ['fv.*'] : $columns;
        $columns = array_merge($base, $attributeGroups ? ['fa.name as attribute_name', 'fav.value as attribute_value'] : [DB::raw("CONCAT('{', GROUP_CONCAT(CONCAT('\"', fa.name, '\":\"', fav.value, '\"') SEPARATOR ','), '}') as attributes")]);
        $queryData = DB::table('food_variants as fv')
            ->join('food_variant_attribute_values as fvav', 'fvav.food_variant_id', '=', 'fv.id')
            ->join('food_attribute_values as fav', 'fav.id', '=', 'fvav.food_attribute_value_id')
            ->join('food_attributes as fa', 'fa.id', '=', 'fav.food_attribute_id')
            ->join("food_items as ft", 'ft.id', '=', 'fv.food_item_id')
            ->select($columns)->where("ft.id", $this->id);

        if($attributeGroups) return $queryData->get()->groupBy(['attribute_name', 'attribute_value']);
        else return $queryData->groupBy('fv.id')->get()->map(function ($variant) {
                $variant->attributes = collect(json_decode($variant->attributes ?? '{}', true, 512, JSON_INVALID_UTF8_SUBSTITUTE) ?? []);
                return $variant;
            });
    }

    public static function getVariantsByFoodId(int|string $foodId, bool $attributeGroups = false, string ...$columns)
    {
        return self::find($foodId)?->getAllVariants($attributeGroups, $columns);
    }
}
