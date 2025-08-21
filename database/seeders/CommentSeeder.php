<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Movie;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        $userIds  = User::pluck('id');
        $movieIds = Movie::where('status', 'showing')->pluck('id');

        if ($userIds->isEmpty() || $movieIds->isEmpty()) {
            $this->command?->warn('⚠️ Thiếu users hoặc movies để seed bình luận.');
            return;
        }

        $topContents = [
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

        $replyTemplates = [
            'Chuẩn luôn, mình cũng thấy vậy.',
            'Theo mình thì {soft_contra} nhé.',
            'Đồng ý phần lớn, nhưng đoạn giữa hơi lê thê.',
            'Công nhận âm nhạc nổi bật thật.',
            'Mình thấy diễn xuất ổn, nhất là nhân vật chính.',
            'Có ai để ý cảnh {scene} không? Khá ấn tượng đó.',
            'Nếu rút ngắn ~10 phút thì nhịp tốt hơn.',
            'Xem cùng gia đình thì tuyệt vời.',
            'Công nhận cái kết plot twist ghê!',
            'Hiệu ứng tốt nhưng cảm xúc chưa tới lắm với mình.',
            'Đoạn cao trào làm mình nổi da gà.',
            'Mình thấy phần build nhân vật rất ổn.',
            'Tổng thể đáng tiền vé.',
            'Xem IMAX chắc còn phê hơn.',
            'Mọi người nghĩ bản OST thế nào?',
        ];
        $softContra = ['không hẳn vậy', 'chưa chắc', 'mình có cảm nhận khác', 'mình phân vân'];
        $scenes     = ['rượt đuổi', 'hoàng hôn', 'mở màn', 'after-credit', 'đối thoại trong mưa'];

        $getStatus = function (): string {
            $r = random_int(1, 100);
            if ($r <= 85) return 'active';
            if ($r <= 95) return 'hidden';
            return 'deleted';
        };

        foreach ($movieIds as $movieId) {
            $topCount = 1 + random_int(0, 3);

            
            $mustAuthor = $userIds->random();
            $baseTime   = Carbon::now()->subDays(random_int(0, 30))->subMinutes(random_int(0, 1440));

            $topActive = Comment::create([
                'user_id'           => $mustAuthor,
                'movie_id'          => $movieId,
                'parent_comment_id' => null,
                'reply_comment_id'  => null,
                'content'           => $this->humanize(Arr::random($topContents)),
                'status'            => 'active',
                'created_at'        => $baseTime,
                'updated_at'        => $baseTime,
            ]);

            
            for ($i = 2; $i <= $topCount; $i++) {
                $time = (clone $baseTime)->addMinutes(random_int(5, 120));
                Comment::create([
                    'user_id'           => $userIds->random(),
                    'movie_id'          => $movieId,
                    'parent_comment_id' => null,
                    'reply_comment_id'  => null,
                    'content'           => $this->humanize(Arr::random($topContents)),
                    'status'            => $getStatus(),
                    'created_at'        => $time,
                    'updated_at'        => $time,
                ]);
            }

            // Tạo reply cho bình luận gốc
            $replyMin = 1;
            $replyMax = 5;
            $replyCount = random_int($replyMin, $replyMax);

            $cursorTime     = (clone $topActive->created_at);
            $candidateUsers = $userIds->reject(fn ($id) => (int)$id === (int)$topActive->user_id)->values();

            for ($j = 1; $j <= $replyCount; $j++) {
                $cursorTime = (clone $cursorTime)->addMinutes(random_int(3, 25));

                $tpl = Arr::random($replyTemplates);
                $text = str_replace(
                    ['{soft_contra}', '{scene}'],
                    [Arr::random($softContra), Arr::random($scenes)],
                    $tpl
                );
                $tails = ['', '', ' 👍', ' 🤔', ' 😅', ' — xem ổn phết!', ' — đáng để rủ bạn đi xem!'];
                $content = $this->humanize($text . Arr::random($tails));

                Comment::create([
                    'user_id'           => $candidateUsers->random(),
                    'movie_id'          => $movieId,
                    'parent_comment_id' => $topActive->id,
                    'reply_comment_id'  => $topActive->id,
                    'content'           => $content,
                    'status'            => $getStatus(),
                    'created_at'        => $cursorTime,
                    'updated_at'        => $cursorTime,
                ]);
            }
        }
    }

    private function humanize(string $text): string
    {
        $text = trim($text);

        if ($text !== '' && mb_strtolower(mb_substr($text, 0, 1)) === mb_substr($text, 0, 1)) {
            $text = mb_strtoupper(mb_substr($text, 0, 1)) . mb_substr($text, 1);
        }

        if (!preg_match('/[.!?…]$/u', $text)) {
            $endings = ['.', '.', '.', '!', '…'];
            $text .= Arr::random($endings);
        }

        $text = preg_replace('/([^\s])(😅|🤔|👍)/u', '$1 $2', $text);

        return $text;
    }
}
