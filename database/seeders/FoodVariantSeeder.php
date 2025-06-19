<?php

namespace Database\Seeders;

use App\Models\FoodAttribute;
use App\Models\FoodItem;
use App\Models\FoodVariant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class FoodVariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $attributes = FoodAttribute::all();

        $popcornPresets = [
            'Size' => ['S', 'M', 'L', 'XL'],
            'Vị' => ['Ngọt', 'Mặn', 'Caramel', 'Phô mai', 'Rong biển'],
            'Kiểu gói' => ['Hộp giấy', 'Ly nhựa', 'Túi giấy', 'Hộp nhựa'],
        ];
        $drinkPresets = [
            'Size' => ['S', 'M', 'L', 'XL'],
            'Đá' => ['Có đá', 'Ít đá', 'Không đá'],
            'Hương vị' => ['Đào', 'Chanh', 'Dâu', 'Xoài'],
        ];

        foreach ($attributes as $attribute) {
            $values = $popcornPresets[$attribute->name] ?? $drinkPresets[$attribute->name] ?? null;

            if (!$values) continue;

            shuffle($values);

            foreach (array_slice($values, 0, rand(1, 3)) as $value) {
                FoodVariant::create([
                    'food_attribute_id' => $attribute->id,
                    'value' => $value,
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
