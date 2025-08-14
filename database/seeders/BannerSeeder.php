<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Movie;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'title' => 'Bến Phà Xác Sống',
                'image' => 'https://cdn2.fptshop.com.vn/unsafe/Uploads/images/tin-tuc/176175/Originals/poster-film-5.jpg',
                'start_date' => now()->subDays(7),
                'end_date' => now()->addDays(18),
                'status' => 'active',
            ],
            [
                'title' => 'THANH GƯƠM DIỆT QUỶ: VÔ HẠN THÀNH',
                'image' => 'https://iguov8nhvyobj.vcdn.cloud/media/banner/cache/1/b58515f018eb873dafa430b6f9ae0c1e/9/8/980x448_5__21.jpg',
                'start_date' => now()->subDays(7),
                'end_date' => now()->addDays(18),
                'status' => 'active',
            ],
            [
                'title' => 'KẺ VÔ DANH 2',
                'image' => 'https://iguov8nhvyobj.vcdn.cloud/media/banner/cache/1/b58515f018eb873dafa430b6f9ae0c1e/n/b/nb2_rollingbanner_980x448.jpg',
                'start_date' => now()->subDays(4),
                'end_date' => now()->addDays(23),
                'status' => 'active',
            ],
            [
                'title' => 'TÔI CÓ BỆNH MỚI THÍCH CẬU',
                'image' => 'https://iguov8nhvyobj.vcdn.cloud/media/banner/cache/1/b58515f018eb873dafa430b6f9ae0c1e/9/8/980x448_3__29.jpg',
                'start_date' => now()->subDays(4),
                'end_date' => now()->addDays(23),
                'status' => 'inactive',
            ],
            [
                'title' => 'SHIN CẬU BÉ BÚT CHÌ: NHỮNG VŨ CÔNG SIÊU CAY KASUKABE',
                'image' => 'https://cinema.momocdn.net/img/87272568220780718-shinbeeee.jpg',
                'start_date' => now(),
                'end_date' => now()->addMonth(),
                'status' => 'active',
            ],
            [
                'title' => 'Mang Mẹ Đi Bỏ',
                'image' => 'https://i.ytimg.com/vi/5qx0CiDXp_A/maxresdefault.jpg',
                'start_date' => now(),
                'end_date' => now()->addMonth(),
                'status' => 'active',
            ],
            [
                'title' => 'Chốt Đơn',
                'image' => 'https://cdn.galaxycine.vn/media/2025/8/8/chot-don-1_1754638320654.jpg',
                'start_date' => now(),
                'end_date' => now()->addMonth(),
                'status' => 'active',
            ],
            [
                'title' => 'PHÍA SAU VẾT MÁU',
                'image' => 'https://www.bhdstar.vn/wp-content/uploads/2025/07/referenceSchemeHeadOfficeallowPlaceHoldertrueheight360ldapp-13.jpg',
                'start_date' => now()->addDays(3),
                'end_date' => now()->addDays(37),
                'status' => 'inactive',
            ],
            [
                'title' => 'THÁM TỬ LỪNG DANH CONAN: DƯ ẢNH CỦA ĐỘC NHÃN',
                'image' => 'https://i.ytimg.com/vi/u3cvoE36cp0/maxresdefault.jpg',
                'start_date' => now()->addDays(3),
                'end_date' => now()->addDays(37),
                'status' => 'active',
            ],
            [
                'title' => 'Điều Ước Cuối Cùng',
                'image' => 'https://cdn.mobilecity.vn/mobilecity-vn/images/2025/07/review-phim-dieu-uoc-cuoi-cung-anh-dai-dien.png',
                'start_date' => now()->addDays(7),
                'end_date' => now()->addDays(44),
                'status' => 'active',
            ],
            [
                'title' => 'Mưa Đỏ',
                'image' => 'https://iguov8nhvyobj.vcdn.cloud/media/catalog/product/cache/1/image/1800x/71252117777b696995f01934522c402d/6/4/640x396-muado.jpg',
                'start_date' => now()->addDays(7),
                'end_date' => now()->addDays(44),
                'status' => 'inactive',
            ]
        ];

        foreach ($data as $i => $banner) {
            $dataImage = file_get_contents($banner['image'], false, stream_context_create([
                'http' => [
                    'method' => 'GET',
                    'ignore_errors' => true
                ],
            ]));

            $image = "banners/" . uniqid('banner_') . basename($banner['image']);
            Storage::drive('public')->put($image, $dataImage);

            Banner::create(array_merge($banner, ['title' => mb_convert_case($banner['title'], MB_CASE_TITLE_SIMPLE, 'UTF-8'), 'image' => $image, 'link' => getURLPath('movies/' . Movie::whereLike('title', '%' . $banner['title'] . '%')->first()->id), 'priority' => $i > 7 ? $i+=2 : ($i > 3 ? ++$i : $i)]));
        }
    }
}
