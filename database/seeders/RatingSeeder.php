<?php

namespace Database\Seeders;

use App\Models\Movie;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Database\Seeder;

class RatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sampleReviews = [
            'Phim quá hay, cốt truyện lôi cuốn từ đầu đến cuối.',
            'Âm thanh, hình ảnh trong rạp cực kỳ sống động.',
            'Nội dung có chỗ hơi dài dòng nhưng tổng thể rất đáng xem.',
            'Diễn viên diễn xuất rất tròn vai, xem cực cuốn.',
            'Phim mang lại nhiều cảm xúc, chắc chắn sẽ xem lại lần 2.',
            'Hài hước, giải trí tốt, cả gia đình đều thích.',
            'Một số cảnh hơi gượng nhưng hiệu ứng thì quá đỉnh.',
            'Cốt truyện đơn giản nhưng giàu ý nghĩa, dễ tiếp cận.',
            'Đúng chuẩn bom tấn màn ảnh rộng, rạp xem cực đã.',
            'Tưởng không hay nhưng lại hay không tưởng.',
        ];

        $users = User::all();
        $movies = Movie::where('status', 'showing')->get();

        foreach ($movies as $movie) {
            $reviewCount = rand(2, 3);

            for ($i = 0; $i < $reviewCount; $i++) {
                Rating::create([
                    'user_id' => $users->random()->id,
                    'movie_id' => $movie->id,
                    'score' => rand(3, 5),
                    'review' => $sampleReviews[array_rand($sampleReviews)],
                ]);
            }
        }
    }
}
