<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::pluck('id');
        $movies = Movie::where('status', 'showing')->pluck('id');

        $contents = [
            'Phim này thật sự tuyệt vời, diễn viên diễn rất tự nhiên.',
            'Cốt truyện hấp dẫn nhưng một số cảnh hơi dài.',
            'Âm nhạc rất hay, kết hợp hoàn hảo với cảnh quay.',
            'Mình thích nhân vật chính, rất cảm xúc và chân thật.',
            'Hiệu ứng hình ảnh đẹp nhưng nội dung hơi nhạt.',
            'Phim này khiến mình cười không ngớt, quá hài hước!',
            'Một bộ phim đáng để xem cùng gia đình.',
            'Diễn viên phụ xuất sắc, tạo điểm nhấn cho phim.',
            'Cốt truyện xoay quanh tình bạn rất cảm động.',
            'Phim kết thúc bất ngờ, không đoán trước được.',
            'Âm thanh và ánh sáng được phối hợp rất chuyên nghiệp.',
            'Một trải nghiệm điện ảnh đáng nhớ.',
            'Phim có nhiều cảnh quay đẹp nhưng hơi dài dòng.',
            'Diễn viên chính có biểu cảm quá tốt.',
            'Mình muốn xem lại lần nữa vì quá hay.',
            'Cốt truyện phức tạp nhưng hấp dẫn.',
            'Phim mang nhiều thông điệp ý nghĩa.',
            'Một bộ phim hành động đầy kịch tính.',
            'Hài hước, vui nhộn nhưng vẫn có chiều sâu.',
            'Kết thúc phim thật sự khiến mình bất ngờ.',
        ];

        $topLevelComments = [];

        $getStatus = function() {
            $r = rand(1, 100);
            if ($r <= 85) return 'active';
            if ($r <= 95) return 'hidden';
            return 'deleted';
        };

        foreach ($contents as $content) {
            $comment = Comment::create([
                'user_id' => $users->random(),
                'movie_id' => $movies->random(),
                'parent_comment_id' => null,
                'reply_comment_id' => null,
                'content' => $content,
                'status' => $getStatus(),
            ]);
            $topLevelComments[] = $comment;
        }

        foreach (Arr::shuffle($topLevelComments) as $top) {
            $previous = null;

            foreach (range(1, 5) as $depth) {
                $reply = Comment::create([
                    'user_id' => $users->random(),
                    'movie_id' => $top->movie_id,
                    'parent_comment_id' => $top->id,
                    'reply_comment_id' => $previous?->id ?? $top->id,
                    'content' => $contents[array_rand($contents)],
                    'status' => $getStatus(),
                ]);
                $previous = $reply;
            }
        }
    }
}
