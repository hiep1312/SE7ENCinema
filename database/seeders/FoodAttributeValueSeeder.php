<?php

namespace Database\Seeders;

use App\Models\FoodAttribute;
use App\Models\FoodAttributeValue;
use App\Models\FoodItem;
use Illuminate\Database\Seeder;

class FoodAttributeValueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Dữ liệu cho từng món ăn
        $data = [
            [
                'food' => 'Bắp Rang Bơ',
                'attributeValues' => [
                    'Size' => ['S', 'M', 'L'],
                    'Vị' => ['Nguyên bản (Bơ)', 'Phô mai', 'Mặn', 'Caramel', 'Socola', 'Matcha'],
                ],
            ],
            [
                'food' => 'Gà Vòng Chiên',
                'attributeValues' => [
                    'Size' => ['S', 'M', 'L'],
                    'Vị' => ['Nguyên bản', 'Cay', 'Phô mai'],
                    'Sốt' => ['Tương cà', 'Tương ớt', 'Mayonnaise', 'BBQ', 'Cà chua', 'Mù tạt']
                ]
            ],
            [
                'food' => 'Xúc Xích',
                'attributeValues' => [
                    'Vị' => ['Nguyên bản', 'Cay', 'Phô mai', 'Xông khói'],
                    'Sốt' => ['Không sốt', 'Tương cà', 'Tương ớt', 'Mayonnaise', 'BBQ', 'Mù tạt']
                ]
            ],
            [
                'food' => 'Bánh Bao',
                'attributeValues' => [
                    'Nhân' => ['Nguyên bản (Thịt heo)', 'Đậu đỏ', 'Xá xíu', 'Phô mai', 'Chay'],
                    'Sốt' => ['Không sốt', 'Mayonnaise', 'BBQ', 'Xì dầu']
                ]
            ],
            [
                'food' => 'Bánh Mì',
                'attributeValues' => [
                    'Nhân' => ['Thịt heo', 'Xá xíu', 'Trứng', 'Chay', 'Patê'],
                    'Topping' => ['Nộm', 'Dưa leo', 'Rau thơm', 'Phô mai'],
                    'Sốt' => ['Không sốt', 'Tương ớt', 'Tương cà', 'Mayonnaise', 'BBQ']
                ]
            ],
            [
                'food' => 'Snack (Bim Bim)',
                'attributeValues' => [
                    'Size' => ['S', 'M', 'L'],
                    'Vị' => ['Rong biển', 'Phô mai', 'Gà nướng', 'Bò nướng', 'Sườn nướng', 'Tôm', 'Cay', 'Rau quả']
                ]
            ],
            [
                'food' => 'Trà Trái Cây',
                'attributeValues' => [
                    'Size' => ['S', 'M', 'L'],
                    'Vị' => ['Đào', 'Vải', 'Dâu tây', 'Chanh', 'Cam', 'Táo', 'Bưởi', 'Ổi'],
                    'Topping' => ['Trân châu đen', 'Trân châu trắng', 'Thạch rau câu', 'Thạch phô mai', 'Thạch trân châu', 'Bánh flan'],
                    'Đá' => ['Không đá', 'Ít đá', 'Vừa đá', 'Nhiều đá'],
                    'Đường' => ['Đường 30%', 'Đường 50%', 'Đường 70%', 'Đường 100%'],
                ]
            ],
            [
                'food' => 'Nước Ngọt',
                'attributeValues' => [
                    'Size' => ['S', 'M', 'L'],
                    'Loại' => ['Coca Cola', 'Pepsi', '7Up', 'Sprite', 'Mirinda', 'Sting', 'Fanta'],
                    'Đá' => ['Không đá', 'Ít đá', 'Vừa đá', 'Nhiều đá'],
                ]
            ],
            [
                'food' => 'Nước Ép',
                'attributeValues' => [
                    'Size' => ['S', 'M', 'L'],
                    'Vị' => ['Đào', 'Vải', 'Dâu tây', 'Chanh leo', 'Cam', 'Táo', 'Dứa', 'Dưa hấu'],
                    'Topping' => ['Trân châu đen', 'Trân châu trắng', 'Thạch rau câu', 'Thạch phô mai', 'Thạch trân châu', 'Pudding'],
                    'Đá' => ['Không đá', 'Ít đá', 'Vừa đá', 'Nhiều đá'],
                    'Đường' => ['Không đường', 'Đường 30%', 'Đường 50%', 'Đường 70%', 'Đường 100%'],
                ]
            ],
            [
                'food' => 'Cà Phê',
                'attributeValues' => [
                    'Size' => ['S', 'M', 'L'],
                    'Loại' => ['Cà phê đen', 'Cà phê sữa', 'Latte', 'Cappuccino', 'Mocha'],
                    'Nhiệt độ' => ['Nóng', 'Đá (Lạnh)'],
                    'Đường' => ['Không đường', 'Đường 30%', 'Đường 50%', 'Đường 70%', 'Đường 100%'],
                ]
            ],
        ];

        // Chèn dữ liệu cho các món cụ thể
        foreach($data as $attributeValue) {
            $foodId = FoodItem::where('name', $attributeValue['food'])->first()->id;
            foreach($attributeValue['attributeValues'] as $name => $values) {
                $food_attribute_id = FoodAttribute::where('food_item_id', $foodId)
                    ->where('name', $name)
                    ->first()->id;

                FoodAttributeValue::insert(
                    array_map(fn($value) => ['food_attribute_id' => $food_attribute_id, 'value' => $value], $values)
                );
            }
        }

        // =============================
        // Thêm dữ liệu cho Attribute chung (food_item_id = null)
        // =============================
        $globalData = [
            'Size' => ['S', 'M', 'L'],
            'Vị' => ['Nguyên bản', 'Phô mai', 'Cay', 'Caramel', 'Socola', 'Matcha', 'Mặn'],
            'Sốt' => ['Không sốt', 'Tương ớt', 'Tương cà', 'Mayonnaise', 'BBQ', 'Mù tạt', 'Xì dầu'],
            'Topping' => ['Trân châu đen', 'Trân châu trắng', 'Thạch rau câu', 'Phô mai', 'Bánh flan', 'Pudding'],
            'Đá' => ['Không đá', 'Ít đá', 'Vừa đá', 'Nhiều đá'],
            'Đường' => ['Không đường', 'Đường 30%', 'Đường 50%', 'Đường 70%', 'Đường 100%'],
        ];

        foreach ($globalData as $name => $values) {
            $attribute = FoodAttribute::whereNull('food_item_id')->where('name', $name)->first();
            if ($attribute) {
                FoodAttributeValue::insert(
                    array_map(fn($value) => ['food_attribute_id' => $attribute->id, 'value' => $value], $values)
                );
            }
        }
    }
}
