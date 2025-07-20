<?php

namespace App\Livewire\Admin\Comments;

use App\Models\Comment;
use App\Models\Movie;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

class CommentCreate extends Component
{
    public $content;
    public $status = 'active';
    public $movieId;
    public $parentCommentId;
    public $selectedMovie;
    public $parentComment;
    public $childComments = [];
    public $childCommentCount = 0;
    public $statusOptions = [
        'active' => 'Hoạt động',
        'hidden' => 'Đã ẩn',
    ];

    public function __construct() {
        $this->statusOptions = [
            'active' => 'Hoạt động',
            'hidden' => 'Đã ẩn',
        ];
    }

    protected function rules()
    {
        $rules = [
            'content' => 'required|string|min:1|max:1000',
            'status' => 'required|in:' . implode(',', array_keys($this->statusOptions)),
            'movieId' => 'required|exists:movies,id',
            'parentCommentId' => 'nullable|exists:comments,id',
            'childCommentCount' => 'nullable|integer|min:0|max:10',
        ];

        // Thêm rules cho các bình luận con
        foreach ($this->childComments as $index => $childComment) {
            $rules["childComments.{$index}.content"] = 'required|string|min:1|max:1000';
            $rules["childComments.{$index}.status"] = 'required|in:' . implode(',', array_keys($this->statusOptions));
        }

        return $rules;
    }

    protected function messages()
    {
        $messages = [
            'content.required' => 'Nội dung bình luận không được để trống.',
            'content.min' => 'Nội dung bình luận quá ngắn.',
            'content.max' => 'Nội dung bình luận không được vượt quá 1000 ký tự.',
            'status.required' => 'Vui lòng chọn trạng thái.',
            'status.in' => 'Trạng thái không hợp lệ.',
            'movieId.required' => 'Vui lòng chọn phim.',
            'movieId.exists' => 'Phim không tồn tại.',
            'parentCommentId.exists' => 'Bình luận cha không tồn tại.',
            'childCommentCount.integer' => 'Số lượng bình luận con phải là số nguyên.',
            'childCommentCount.min' => 'Số lượng bình luận con không được âm.',
            'childCommentCount.max' => 'Số lượng bình luận con tối đa là 10.',
        ];

        // Thêm messages cho các bình luận con
        foreach ($this->childComments as $index => $childComment) {
            $messages["childComments.{$index}.content.required"] = "Nội dung bình luận con #" . ($index + 1) . " không được để trống.";
            $messages["childComments.{$index}.content.min"] = "Nội dung bình luận con #" . ($index + 1) . " quá ngắn.";
            $messages["childComments.{$index}.content.max"] = "Nội dung bình luận con #" . ($index + 1) . " không được vượt quá 1000 ký tự.";
            $messages["childComments.{$index}.status.required"] = "Vui lòng chọn trạng thái cho bình luận con #" . ($index + 1) . ".";
            $messages["childComments.{$index}.status.in"] = "Trạng thái bình luận con #" . ($index + 1) . " không hợp lệ.";
        }

        return $messages;
    }

    public function mount($movieId = null)
    {
        if ($movieId) {
            $this->movieId = $movieId;
        }
    }

    public function generateChildComments()
    {
        $count = (int) $this->childCommentCount;

        if ($count < 0) $count = 0;
        if ($count > 10) $count = 10;

        $this->childComments = [];

        for ($i = 0; $i < $count; $i++) {
            $this->childComments[] = [
                'content' => '',
                'status' => 'active'
            ];
        }
    }

    public function removeChildComment($index)
    {
        unset($this->childComments[$index]);
        $this->childComments = array_values($this->childComments); // Reindex array
    }

    public function updatedMovieId()
    {
        if ($this->movieId) {
            $this->selectedMovie = Movie::find($this->movieId);
        } else {
            $this->selectedMovie = null;
        }
    }

    public function updatedParentCommentId()
    {
        if ($this->parentCommentId) {
            $this->parentComment = Comment::with('user')->find($this->parentCommentId);
        } else {
            $this->parentComment = null;
        }
    }

    public function createComment()
    {
        $this->validate();

        // Kiểm tra trạng thái bình luận cha nếu có (chỉ cấm deleted, cho phép hidden)
        if ($this->parentCommentId) {
            $parent = Comment::find($this->parentCommentId);
            if ($parent && $parent->status === 'deleted') {
                $this->addError('parentCommentId', 'Bình luận cha này đã bị xóa. Bạn không thể tạo bình luận con!');
                return;
            }
        }

        try {
            DB::beginTransaction();

            // Tạo bình luận chính
            $mainComment = Comment::create([
                'user_id' => rand(1, 20), // Random từ 1 đến 20 user
                'content' => $this->content,
                'status' => $this->status,
                'movie_id' => $this->movieId,
                'parent_comment_id' => $this->parentCommentId,
                'reply_comment_id' => null,
            ]);

            // Tạo các bình luận con nếu có
            if (!empty($this->childComments)) {
                $parentId = $this->parentCommentId ?: $mainComment->id;

                foreach ($this->childComments as $childData) {
                    Comment::create([
                        'user_id' => rand(1, 20), // Random từ 1 đến 20 user
                        'content' => $childData['content'],
                        'status' => $childData['status'],
                        'movie_id' => $this->movieId,
                        'parent_comment_id' => $parentId,
                        'reply_comment_id' => null,
                    ]);
                }
            }

            DB::commit();

            $totalComments = count($this->childComments) + 1;
            session()->flash('success', "Tạo thành công {$totalComments} bình luận!");

            return redirect()->route('admin.comments.index', ['movie_id' => $this->movieId]);

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Có lỗi xảy ra khi tạo bình luận: ' . $e->getMessage());
        }
    }

    public function resetForm()
    {
        $this->reset(['content', 'status', 'parentCommentId', 'childComments', 'childCommentCount']);
        $this->resetErrorBag();
    }

    // Hàm kiểm tra điều kiện xóa (đồng bộ với các file khác)
    public function checkDelete($comment)
    {
        if (is_null($comment->parent_comment_id)) {
            return !Comment::where('parent_comment_id', $comment->id)->exists();
        }
        return true;
    }

    public function addChildComment()
    {
        $this->childComments[] = [
            'content' => '',
            'status' => 'active',
        ];
        $this->childCommentCount = count($this->childComments);
    }

    #[Title('Tạo bình luận mới - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        $movies = Movie::select('id', 'title', 'poster', 'description')->orderBy('title')->get();
        $selectedMovie = null;
        if ($this->movieId) {
            $selectedMovie = $movies->where('id', $this->movieId)->first();
        }

        // Get potential parent comments for the selected movie (loại bỏ deleted, giữ lại hidden)
        $parentComments = collect();
        if ($this->movieId) {
            $parentComments = Comment::with('user')
                ->where('movie_id', $this->movieId)
                ->whereNull('parent_comment_id')
                ->where('status', '!=', 'deleted') // Chỉ loại bỏ deleted
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('livewire.admin.comments.comment-create', compact('movies', 'parentComments', 'selectedMovie'));
    }
}
