<?php

namespace Database\Seeders;

use App\Models\FoodAttribute;
use App\Models\FoodItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FoodAttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $foodItems = FoodItem::all();

        $popcornPresets = ['Size', 'Vị', 'Kiểu gói'];
        $drinkPresets = ['Đá', 'Hương vị', 'Loại đồ uống'];

        foreach ($foodItems as $foodItem) {
            $attributeSource = fake()->randomElement([$popcornPresets, $drinkPresets]);

            foreach(range(0, rand(1, 2)) as $i){
                FoodAttribute::create([
                    'food_item_id' => $foodItem->id,
                    'name' => $attributeSource[$i],
                    'description' => fake()->words(2, true),
                ]);
            }
        }
    }
}
