<?php

namespace Database\Seeders;

use App\Models\FoodItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class FoodItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Combo Một Người - Bắp + Nước Ngọt',
                'description' => '1 bắp rang bơ giòn thơm và 1 ly nước ngọt mát lạnh, lựa chọn lý tưởng cho 1 người.',
                'image' => 'https://www.bhdstar.vn/wp-content/uploads/2025/06/SINGLE-COMBO.jpg',
                'status' => 'activate',
            ],
            [
                'name' => 'Combo Cặp Đôi - Bắp Lớn + 2 Nước Ngọt',
                'description' => 'Bắp rang bơ size lớn và 2 ly nước ngọt mát lạnh, hoàn hảo cho 2 người cùng thưởng thức.',
                'image' => 'https://www.bhdstar.vn/wp-content/uploads/2025/06/COUPLE-COMBO.jpg',
                'status' => 'activate',
            ],
            [
                'name' => 'Combo Gia Đình – 2 Bắp Lớn + 4 Nước Ngọt',
                'description' => 'Combo lý tưởng cho gia đình: 2 bắp rang bơ lớn giòn thơm và 4 ly nước ngọt mát lạnh, thưởng thức phim thoải mái cùng người thân.',
                'image' => 'https://i.postimg.cc/PrPPdcdk/662740.png',
                'status' => 'activate',
            ],
            [
                'name' => 'Combo Ăn Vặt – Bắp + Gà Vòng Chiên + Nước ngọt',
                'description' => 'Bắp rang bơ giòn thơm, gà vòng chiên vàng rụm và nước ngọt mát lạnh, thưởng thức no nê khi xem phim.',
                'image' => 'https://i.postimg.cc/xCnBPW6J/662743.png',
                'status' => 'discontinued',
            ],
            [
                'name' => 'Bắp Rang Bơ',
                'description' => 'Bắp rang giòn thơm, phủ bơ béo ngậy, món ăn vặt kinh điển khi xem phim.',
                'image' => 'https://propercorn.com.vn/wp-content/uploads/2021/09/ng%C3%B4-b%C6%B0%E1%BB%9Bm-1-1-scaled.jpg',
                'status' => 'activate',
            ],
            [
                'name' => 'Gà Vòng Chiên',
                'description' => 'Miếng gà vòng giòn rụm, hương vị hấp dẫn, ăn là ghiền.',
                'image' => 'https://cf.shopee.vn/file/f0d345676d54270beb4c016e4049e390.webp',
                'status' => 'discontinued',
            ],
            [
                'name' => 'Xúc Xích',
                'description' => 'Xúc xích thơm ngon, đậm vị, tiện ăn khi thưởng thức phim.',
                'image' => 'https://product.hstatic.net/1000304337/product/489912982_1088029310018179_8082323552838380825_n_e3c39a5647954ffca50c4b081f7becb7.jpg',
                'status' => 'activate',
            ],
            [
                'name' => 'Bánh Bao',
                'description' => 'Bánh bao mềm mịn, nhân đậm đà, ăn no mà vẫn ngon.',
                'image' => 'https://origato.com.vn/wp-content/uploads/2023/02/banh-bao.png',
                'status' => 'activate',
            ],
            [
                'name' => 'Bánh Mì',
                'description' => 'Ổ bánh mì thơm giòn, nhân đầy ắp hương vị.',
                'image' => 'https://banhmibaduoc.vn/wp-content/uploads/2023/12/MO_9493-scaled.jpg',
                'status' => 'discontinued',
            ],
            [
                'name' => 'Snack (Bim Bim)',
                'description' => 'Đồ ăn vặt giòn tan, nhiều hương vị hấp dẫn.',
                'image' => 'https://pvmarthanoi.com.vn/wp-content/uploads/2023/06/11-1.jpg',
                'status' => 'activate',
            ],
            [
                'name' => 'Trà Trái Cây',
                'description' => 'Trà mát lạnh, kết hợp vị trái cây tươi ngon, giải khát tức thì.',
                'image' => 'https://luyutea.vn/wp-content/uploads/2024/08/HTC_1-1-1024x1024-1.jpg',
                'status' => 'activate',
            ],
            [
                'name' => 'Nước Ngọt',
                'description' => 'Đồ uống có ga mát lạnh, sảng khoái khi xem phim.',
                'image' => 'https://i.postimg.cc/52Ztm1th/496005105-1097458609082592-3377853819036947671-n.jpg',
                'status' => 'activate',
            ],
            [
                'name' => 'Nước Ép',
                'description' => 'Nước ép trái cây nguyên chất, thanh mát và tốt cho sức khỏe.',
                'image' => 'https://banhmibahuynh.vn/wp-content/uploads/2025/06/Nuoc-ep-trai-cay.png',
                'status' => 'activate',
            ],
            [
                'name' => 'Cà Phê',
                'description' => 'Cà phê thơm đậm, giúp tỉnh táo và tận hưởng trọn vẹn bộ phim.',
                'image' => 'https://suckhoedoisong.qltns.mediacdn.vn/324455921873985536/2024/12/29/ca-phe-thay-1735490234937505648296.jpg',
                'status' => 'activate',
            ]
        ];

        foreach ($data as $foodItem) {
            $dataImage = file_get_contents($foodItem['image'], false, stream_context_create([
                'http' => [
                    'method' => 'GET',
                    'ignore_errors' => true
                ],
            ]));

            $image = "foods/" . uniqid('image_') . urldecode(basename(preg_replace('/\?.*$/', '', $foodItem['image'])));
            Storage::drive('public')->put($image, $dataImage);

            FoodItem::create(array_merge($foodItem, ['image' => $image]));
        }
    }
}
