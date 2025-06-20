<?php

namespace Database\Seeders;

use App\Models\FoodAttribute;
use App\Models\FoodAttributeValue;
use App\Models\FoodItem;
use App\Models\FoodVariant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class FoodVariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = FoodItem::all()->toArray();

        foreach (Arr::shuffle($items) as $item) {
            $attributes = FoodAttribute::select('id', 'name')->where('food_item_id', $item['id'])->get();
            if ($attributes->isEmpty()) continue;

            foreach (Arr::shuffle($attributes->toArray()) as $attribute) {
                $attributeValues = FoodAttributeValue::select('value')->where('food_attribute_id', $attribute['id'])->get()->toArray();
                if(empty($attributeValues)) continue;

                foreach (Arr::shuffle($attributeValues) as $attributeValue) {
                    $parts = [$item['name'], $attribute['name'], $attributeValue['value']];

                    $sku = implode('-', array_map(function ($part) {
                        $ascii = Str::ascii($part);
                        return str_replace(' ', '-', trim($ascii));
                    }, $parts));

                    FoodVariant::create([
                        'food_item_id' => $item['id'],
                        'sku' => strtolower($sku),
                        'price' => fake()->numberBetween(20000, 100000),
                        'image' => fake()->imageUrl(300, 450, 'food'),
                        'quantity_available' => fake()->numberBetween(10, 100),
                        'limit' => fake()->numberBetween(10, 100),
                        'status' => fake()->randomElement(['available', 'out_of_stock', 'hidden']),
                    ]);
                }
            }
        }
    }
}
