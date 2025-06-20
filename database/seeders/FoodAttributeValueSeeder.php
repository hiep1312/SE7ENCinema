<?php

namespace Database\Seeders;

use App\Models\FoodAttribute;
use App\Models\FoodAttributeValue;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class FoodAttributeValueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $valueOptions = [
            'Size' => ['S', 'M', 'L'],
            'Vị' => ['Mặn', 'Ngọt', 'Phô mai', 'Bơ'],
            'Kiểu gói' => ['Hộp giấy', 'Túi nhựa', 'Túi giấy', 'Ly nhựa'],
            'Đá' => ['Không đá', 'Ít đá', 'Vừa đá', 'Nhiều đá'],
            'Hương vị' => ['Dâu', 'Cam', 'Xoài', 'Chanh dây', 'Kiwi'],
            'Loại đồ uống' => ['Trà sữa', 'Trà trái cây', 'Nước ngọt', 'Nước khoáng']
        ];

        $attributes = FoodAttribute::all();

        foreach ($attributes as $attribute) {
            $values = Arr::shuffle($valueOptions[$attribute->name] ?? fake()->words(4));

            foreach (range(0, rand(1, 2)) as $i) {
                FoodAttributeValue::create([
                    'food_attribute_id' => $attribute->id,
                    'value' => $values[$i],
                ]);
            }
        }
    }
}
