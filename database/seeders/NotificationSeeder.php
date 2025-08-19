<?php

namespace Database\Seeders;

use App\Models\Notification;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $notifications = [
            [
                'thumbnail' => 'https://iguov8nhvyobj.vcdn.cloud/media/wysiwyg/2025/082025/475x535_1_.jpg',
                'title'     => 'Deadpool & Wolverine chính thức khởi chiếu!',
                'content'   => 'Bom tấn Deadpool & Wolverine đã có mặt tại SE7ENCinema. Đặt vé ngay để trở thành những khán giả đầu tiên thưởng thức siêu phẩm hành động – hài này.',
                'link'      => '/movies/deadpool-wolverine',
            ],
            [
                'thumbnail' => 'https://iguov8nhvyobj.vcdn.cloud/media/banner/cache/1/b58515f018eb873dafa430b6f9ae0c1e/9/8/980x448_127.png',
                'title'     => 'Suất chiếu đặc biệt Inside Out 2',
                'content'   => 'Trở lại với những cảm xúc quen thuộc nhưng đầy mới mẻ, Inside Out 2 sẽ có suất chiếu đặc biệt vào cuối tuần này. Vé đang được bán tại SE7ENCinema.',
                'link'      => '/movies/inside-out-2',
            ],
            [
                'thumbnail' => 'https://iguov8nhvyobj.vcdn.cloud/media/banner/cache/1/b58515f018eb873dafa430b6f9ae0c1e/2/4/240x201_14_.png',
                'title'     => 'Dune: Part Two tái xuất màn ảnh rộng',
                'content'   => 'Siêu phẩm khoa học viễn tưởng Dune: Part Two đã chính thức khởi chiếu. Đặt vé ngay tại SE7ENCinema để hòa mình vào cuộc chiến trên hành tinh Arrakis.',
                'link'      => '/movies/dune-part-two',
            ],
            [
                'thumbnail' => 'https://iguov8nhvyobj.vcdn.cloud/media/banner/cache/1/b58515f018eb873dafa430b6f9ae0c1e/b/i/birthday_popcorn_box_240x201.png',
                'title'     => 'Khuyến mãi đặc biệt – Thứ 3 Vui Vẻ',
                'content'   => 'Mỗi thứ 3 hàng tuần, đồng giá vé chỉ 50.000đ cho tất cả suất chiếu 2D tại SE7ENCinema. Nhanh tay đặt vé để không bỏ lỡ!',
                'link'      => '/promotions/tuesday-fun',
            ],
            [
                'thumbnail' => 'https://iguov8nhvyobj.vcdn.cloud/media/wysiwyg/2023/thue-rap.png',
                'title'     => 'Combo bắp nước siêu hời',
                'content'   => 'Khi mua vé xem phim bất kỳ, bạn có thể sở hữu ngay combo bắp + nước chỉ với 69.000đ. Áp dụng toàn hệ thống SE7ENCinema.',
                'link'      => '/foods/combo-popcorn-drink',
            ],
        ];

        foreach ($notifications as $data) {
            Notification::create($data);
        }
    }
}
