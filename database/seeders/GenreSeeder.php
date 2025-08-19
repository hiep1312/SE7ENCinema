<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['Thảm hoạ', 'Phim khai thác những sự kiện thiên tai hoặc thảm họa nhân tạo, thường đầy kịch tính và căng thẳng.'],
            ['Hành động', 'Phim mang tiết tấu nhanh, nhiều cảnh chiến đấu, rượt đuổi và những tình huống gay cấn.'],
            ['Chiến tranh', 'Phim tái hiện các cuộc chiến tranh, chiến dịch quân sự và số phận con người trong chiến tranh.'],
            ['Lịch sử', 'Phim dựa trên hoặc lấy cảm hứng từ các sự kiện lịch sử có thật.'],
            ['Hồi hộp', 'Phim tập trung vào việc tạo cảm giác căng thẳng và tò mò cho người xem.'],
            ['Tâm Lý', 'Phim khai thác chiều sâu nội tâm, cảm xúc và mâu thuẫn tinh thần của nhân vật.'],
            ['Thần thoại', 'Phim dựa trên các câu chuyện thần thoại, truyền thuyết hoặc yếu tố huyền ảo.'],
            ['Tình cảm', 'Phim xoay quanh tình yêu và các mối quan hệ tình cảm.'],
            ['Kinh Dị', 'Phim tạo cảm giác sợ hãi, rùng rợn, thường có yếu tố siêu nhiên hoặc máu me.'],
            ['Gia đình', 'Phim phù hợp với mọi lứa tuổi, thường xoay quanh tình cảm gia đình.'],
            ['Phiêu lưu', 'Phim kể về những chuyến hành trình khám phá, mạo hiểm và đầy thử thách.'],
            ['Hài', 'Phim mang tính giải trí, gây tiếng cười thông qua tình huống hoặc lời thoại dí dỏm.'],
            ['Hoạt hình', 'Phim được sản xuất bằng kỹ thuật hoạt hình, phù hợp nhiều đối tượng khán giả.'],
            ['Lãng mạn', 'Phim lãng mạn, ngọt ngào, tập trung vào tình yêu đôi lứa.'],
            ['Tội phạm', 'Phim xoay quanh thế giới tội phạm, điều tra và hành động của cảnh sát hoặc kẻ phạm pháp.'],
            ['Khoa Học Viễn Tưởng', 'Phim khai thác các yếu tố khoa học giả tưởng, công nghệ tương lai và khám phá vũ trụ.']
        ];

        foreach ($data as $genre) {
            Genre::create([
                'name' => mb_convert_case($genre[0], MB_CASE_TITLE_SIMPLE, 'UTF-8'),
                'description' => $genre[1],
            ]);
        }
    }
}
