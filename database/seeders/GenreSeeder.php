<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Genre;

class GenreSeeder extends Seeder
{
    public function run()
    {
        $genres = [
            'Hành động',
            'Phiêu lưu',
            'Hài',
            'Tình cảm',
            'Chính kịch',
            'Kinh dị',
            'Khoa học viễn tưởng',
            'Giả tưởng',
            'Hình sự',
            'Tài liệu',
            'Hoạt hình',
            'Gia đình',
            'Âm nhạc',
            'Lịch sử',
            'Chiến tranh',
            'Thể thao',
            'Trinh thám',
            'Viễn Tây',
            'Chính trị',
            'Kinh doanh',
            'Siêu anh hùng',
            'Kinh dị tâm lý',
            'Phép thuật',
            'Thiếu nhi',
            'Phim ngắn'
        ];

        foreach ($genres as $name) {
            Genre::updateOrCreate(['name' => $name]);
        }
    }
}
