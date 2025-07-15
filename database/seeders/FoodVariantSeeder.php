<?php

namespace Database\Seeders;

use App\Models\FoodAttribute;
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
        $gerenateCombinations = function (array $attributes, array $prefix = []) use (&$gerenateCombinations) {
            if(empty($attributes)) return [$prefix];

            $attribute = array_shift($attributes);
            $combinations = [];

            foreach ($attribute['values'] as $value) {
                $newPrefix = array_merge($prefix, [
                    [$attribute['name'], $value['value']]
                ]);
                $combinations = array_merge(
                    $combinations,
                    $gerenateCombinations($attributes, $newPrefix)
                );
            }

            return $combinations;
        };

        $items = FoodItem::all()->toArray();

        foreach (Arr::shuffle($items) as $item) {
            $attributes = FoodAttribute::select('id', 'name')->with('values')->where('food_item_id', $item['id'])->get()->toArray();
            if (empty($attributes)) continue;

            $combinations = $gerenateCombinations($attributes);

            foreach ($combinations as $combo) {
                $parts = [$item['name']];

                foreach ($combo as $part) array_push($parts, ...$part);

                $sku = implode('-', array_map(function ($part) {
                    $ascii = Str::ascii($part);
                    return strtolower(str_replace(' ', '-', trim($ascii)));
                }, $parts));

                $foodVariant = FoodVariant::create([
                    'food_item_id' => $item['id'],
                    'sku' => $sku,
                    'price' => fake()->numberBetween(20000, 100000),
                    'image' => fake()->imageUrl(300, 450, 'food'),
                    'quantity_available' => fake()->numberBetween(10, 100),
                    'limit' => fake()->numberBetween(10, 100),
                    'status' => fake()->randomElement(['available', 'out_of_stock', 'hidden']),
                ]);

                $this->call(FoodVariantAttributeValueSeeder::class, false, [$combo, $foodVariant->id]);
            }
        }
    }
}
