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
        $data = [
            [
                'food' => 'Bắp Rang Bơ',
                'attributes' => [
                    ['Size', 'Kích thước hộp bắp (S = nhỏ, M = vừa, L = lớn)'],
                    ['Vị', 'Hương vị của bắp rang bơ (Bơ, Phô mai, Mặn, Caramel, Socola...)'],
                ],
            ],
            [
                'food' => 'Gà Vòng Chiên',
                'attributes' => [
                    ['Size', 'Kích thước phần gà vòng chiên (S, M, L)'],
                    ['Vị', 'Hương vị tẩm ướp gà (Truyền thống, Cay, Phô mai...)'],
                    ['Sốt', 'Loại sốt chấm kèm (Tương ớt, Tương cà, Mayonnaise, BBQ...)']
                ],
            ],
            [
                'food' => 'Xúc Xích',
                'attributes' => [
                    ['Vị', 'Loại xúc xích (Thường, Phô mai, Cay, Xông khói...)'],
                    ['Sốt', 'Loại sốt ăn kèm (Tương ớt, Tương cà, Mayonnaise, BBQ...)']
                ],
            ],
            [
                'food' => 'Bánh Bao',
                'attributes' => [
                    ['Nhân', 'Loại nhân bên trong bánh bao (Thịt heo, Đậu đỏ, Xá xíu, Phô mai, Chay...)'],
                    ['Sốt', 'Loại sốt ăn kèm (Tương ớt, Tương cà, Mayonnaise, BBQ, Xì dầu...)']
                ],
            ],
            [
                'food' => 'Bánh Mì',
                'attributes' => [
                    ['Nhân', 'Nhân chính của bánh mì (Thịt heo, Xá xíu, Trứng, Patê...)'],
                    ['Topping', 'Nguyên liệu kèm thêm (Nộm, Dưa leo, Rau thơm, Phô mai...)'],
                    ['Sốt', 'Loại sốt ăn kèm (Tương ớt, Tương cà, Mayonnaise, BBQ, Mù tạt...)']
                ],
            ],
            [
                'food' => 'Snack (Bim Bim)',
                'attributes' => [
                    ['Size', 'Trọng lượng gói snack (S = nhỏ, M = vừa, L = lớn)'],
                    ['Vị', 'Hương vị của snack (Rong biển, Phô mai, Bò nướng, Sườn nướng, Cay...)']
                ],
            ],
            [
                'food' => 'Trà Trái Cây',
                'attributes' => [
                    ['Size', 'Kích thước ly trà (S, M, L)'],
                    ['Vị', 'Loại trà/trái cây (Đào, Vải, Dâu tây, Chanh, Cam...)'],
                    ['Topping', 'Nguyên liệu thêm (Trân châu, Thạch rau câu, Bánh flan...)'],
                    ['Đá', 'Mức độ đá trong ly (Không, Ít, Vừa, Nhiều)'],
                    ['Đường', 'Mức độ ngọt (30%, 50%, 70%, 100%)']
                ],
            ],
            [
                'food' => 'Nước Ngọt',
                'attributes' => [
                    ['Size', 'Kích thước ly nước ngọt (S, M, L)'],
                    ['Loại', 'Loại nước ngọt (Coca Cola, Pepsi, 7Up, Fanta, Sting...)'],
                    ['Đá', 'Mức độ đá trong ly (Không, Ít, Vừa, Nhiều)']
                ],
            ],
            [
                'food' => 'Nước Ép',
                'attributes' => [
                    ['Size', 'Kích thước ly nước ép (S, M, L)'],
                    ['Vị', 'Loại nước ép (Cam, Táo, Dứa, Dưa hấu, Chanh leo...)'],
                    ['Topping', 'Nguyên liệu thêm (Trân châu, Thạch rau câu, Pudding...)'],
                    ['Đá', 'Mức độ đá trong ly (Không, Ít, Vừa, Nhiều)'],
                    ['Đường', 'Mức độ ngọt (0%, 30%, 50%, 70%, 100%)']
                ],
            ],
            [
                'food' => 'Cà Phê',
                'attributes' => [
                    ['Size', 'Kích thước ly cà phê (S, M, L)'],
                    ['Loại', 'Kiểu cà phê (Đen, Sữa, Latte, Cappuccino...)'],
                    ['Nhiệt độ', 'Cách pha (Nóng hoặc Đá (lạnh))'],
                    ['Đường', 'Mức độ ngọt (0%, 30%, 50%, 70%, 100%)']
                ],
            ]
        ];

        foreach ($data as $foodAttribute) {
            $food_item_id = FoodItem::where('name', $foodAttribute['food'])->first()->id;

            foreach ($foodAttribute['attributes'] as [$name, $description]) {
                FoodAttribute::create(compact('food_item_id', 'name', 'description'));
            }
        }
    }
}
