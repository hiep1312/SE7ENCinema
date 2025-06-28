<?php

namespace Database\Seeders;

use App\Models\FoodAttributeValue;
use App\Models\FoodVariantAttributeValue;
use Illuminate\Database\Seeder;

class FoodVariantAttributeValueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(...$variant): void
    {
        [$combo, $foodVariantId] = $variant;

        foreach ($combo as $pair) {
            [$attributeName, $attributeValue] = $pair;

            $valueModel = FoodAttributeValue::where('value', $attributeValue)
                ->whereHas('attribute', function ($query) use ($attributeName, $foodVariantId) {
                    $query->where('name', $attributeName)
                        ->where('food_item_id', $foodVariantId);
                })->first();

            if ($valueModel){
                FoodVariantAttributeValue::create([
                    'food_variant_id' => $foodVariantId,
                    'food_attribute_value_id' => $valueModel->id,
                ]);
            }
        }
    }
}
