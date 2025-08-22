<?php

namespace Database\Seeders;

use App\Models\Notification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $notifications = [
            [
                'thumbnail' => 'https://iguov8nhvyobj.vcdn.cloud/media/catalog/product/cache/1/image/c5f0a1eff4c394a251036189ccddaacd/4/7/470wx700h-noise.jpg',
                'title'     => 'Tiếng Ồn Quỷ Dị chính thức khởi chiếu!',
                'content'   => 'Bom tấn Tiếng Ồn Quỷ Dị đã có mặt tại SE7ENCinema. Đặt vé ngay để trở thành những khán giả đầu tiên thưởng thức siêu phẩm hành động – hài này.',
                'link'      => getURLPath('movies/22'),
            ],
            [
                'thumbnail' => 'https://iguov8nhvyobj.vcdn.cloud/media/catalog/product/cache/1/image/c5f0a1eff4c394a251036189ccddaacd/4/7/470wx700h-bts.jpg',
                'title'     => 'Suất chiếu đặc biệt PHÍA SAU VẾT MÁU',
                'content'   => 'Trở lại với những cảm xúc quen thuộc nhưng đầy mới mẻ, PHÍA SAU VẾT MÁU sẽ có suất chiếu đặc biệt vào cuối tuần này. Vé đang được bán tại SE7ENCinema.',
                'link'      => getURLPath('movies/19'),
            ],
            [
                'thumbnail' => 'https://iguov8nhvyobj.vcdn.cloud/media/wysiwyg/2023/thue-rap.png',
                'title'     => 'SE7ENCinema ra mắt mới rất nhiều danh sách phim hot !!!',
                'content'   => 'Đặt vé ngay tại SE7ENCinema để hòa mình vào các bộ phim hấp dẫn.',
                'link'      => getURLPath('movie-list'),
            ],
            [
                'thumbnail' => 'https://iguov8nhvyobj.vcdn.cloud/media/banner/cache/1/b58515f018eb873dafa430b6f9ae0c1e/b/i/birthday_popcorn_box_240x201.png',
                'title'     => 'Khuyến mãi đặc biệt – Thứ 3 Vui Vẻ',
                'content'   => 'Mỗi thứ 3 hàng tuần, đồng giá vé chỉ 50.000đ cho tất cả suất chiếu 2D tại SE7ENCinema. Nhanh tay đặt vé để không bỏ lỡ!',
                'link'      => getURLPath('promotions'),
            ],
            [
                'thumbnail' => 'https://thaythuocvietnam.vn/thuvien/wp-content/uploads/2023/05/lien-he.png',
                'title'     => 'Giải đáp thắc mắc về chúng tôi',
                'content'   => 'Mọi thông tin liên hệ thắc mắc của quý khách xin vui lòng gửi về chúng tôi qua phần "chính sách và bảo mật"',
                'link'      => getURLPath('faq'),
            ],
        ];

        foreach ($notifications as $data) {
            $dataImage = file_get_contents($data['thumbnail'], false, stream_context_create([
                'http' => [
                    'method' => 'GET',
                    'ignore_errors' => true
                ],
            ]));

            $thumbnail = "notifications/" . uniqid('notification_') . basename($data['thumbnail']);
            Storage::disk('public')->put($thumbnail, $dataImage);

            Notification::create(array_merge($data, ['title' => mb_convert_case($data['title'], MB_CASE_TITLE_SIMPLE, 'UTF-8'), 'thumbnail' => $thumbnail, 'content' => $data['content'], 'link' => $data['link']]));
        }
    }
}
