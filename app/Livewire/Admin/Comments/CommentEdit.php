<?php

namespace App\Livewire\Admin\Comments;

use App\Models\Comment;
use App\Models\Movie;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

class CommentEdit extends Component
{
    public Comment $comment;
    public $content;
    public $status;
    public $movieId;
    public $parentCommentId;
    public $replyCommentId;
    public $selectedMovie;

    // Child comment properties
    public $childCommentCount = 0;
    public $childComments = [];

    public $statusOptions = [
        'active' => 'Hoạt động',
        'hidden' => 'Đã ẩn',
        'deleted' => 'Đã xóa',
    ];

    public function __construct()
    {
        $this->statusOptions = [
            'active' => 'Hoạt động',
            'hidden' => 'Đã ẩn',
            'deleted' => 'Đã xóa',
        ];
    }

    protected function rules()
    {
        $rules = [
            'content' => 'required|string|min:1|max:1000',
            'status' => 'required|in:' . implode(',', array_keys($this->statusOptions)),
            'movieId' => 'required|exists:movies,id',
            'parentCommentId' => 'nullable|exists:comments,id',
            'replyCommentId' => 'nullable|exists:comments,id',
            'childCommentCount' => 'nullable|integer|min:0|max:10',
        ];

        // Thêm rules cho các bình luận con
        foreach ($this->childComments as $index => $childComment) {
            $rules["childComments.{$index}.content"] = 'required|string|min:1|max:1000';
            $rules["childComments.{$index}.status"] = 'required|in:active,hidden';
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
            'replyCommentId.exists' => 'Bình luận được trả lời không tồn tại.',
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

    public function mount(Comment $comment)
    {
        $this->comment = $comment;
        $this->content = $comment->content;
        $this->status = $comment->status;
        $this->movieId = $comment->movie_id;
        $this->parentCommentId = $comment->parent_comment_id;
        $this->replyCommentId = $comment->reply_comment_id;
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
        $this->childCommentCount = count($this->childComments);
    }

    public function clearChildForms()
    {
        $this->childComments = [];
        $this->childCommentCount = 0;
        $this->resetErrorBag(['childComments']);
    }

    public function updatedMovieId()
    {
        if ($this->movieId) {
            $this->selectedMovie = Movie::find($this->movieId);
        } else {
            $this->selectedMovie = null;
        }
    }

    public function updateComment()
    {
        $this->validate([
            'content' => 'required|string|min:1|max:1000',
            'status' => 'required|in:' . implode(',', array_keys($this->statusOptions)),
            'movieId' => 'required|exists:movies,id',
            'parentCommentId' => 'nullable|exists:comments,id',
            'replyCommentId' => 'nullable|exists:comments,id',
        ]);

        // Additional validation for reply comment
        if ($this->replyCommentId && $this->parentCommentId) {
            $replyComment = Comment::find($this->replyCommentId);
            if (!$replyComment || ($replyComment->parent_comment_id != $this->parentCommentId && $replyComment->id != $this->parentCommentId)) {
                $this->addError('replyCommentId', 'Bình luận trả lời không thuộc cùng chuỗi bình luận.');
                return;
            }
        }

        // Nếu không có replyCommentId hợp lệ thì set null
        $replyCommentId = $this->replyCommentId ?: null;

        // Nếu là bình luận cha và muốn chuyển trạng thái thành 'đã xóa', kiểm tra các bình luận con
        if ($this->status === 'deleted' && is_null($this->comment->parent_comment_id)) {
            $activeChildren = Comment::where('parent_comment_id', $this->comment->id)->where('status', 'active')->count();
            if ($activeChildren > 0) {
                $this->addError('status', 'Không thể chuyển trạng thái thành "đã xóa" khi còn bình luận con hoạt động.');
                return;
            }
        }

        try {
            $this->comment->update([
                'content' => $this->content,
                'status' => $this->status,
                'movie_id' => $this->movieId,
                'parent_comment_id' => $this->parentCommentId,
                'reply_comment_id' => $replyCommentId,
            ]);

            if (!empty($this->childComments)) {
                $parentId = $this->comment->parent_comment_id ?: $this->comment->id;
                foreach ($this->childComments as $childData) {
                    if (!empty($childData['content'])) {
                        Comment::create([
                            'content' => $childData['content'],
                            'status' => $childData['status'],
                            'movie_id' => $this->movieId,
                            'user_id' => rand(1, 20), // Random từ 1 đến 20 user
                            'parent_comment_id' => $parentId,
                            'reply_comment_id' => null,
                        ]);
                    }
                }
            }

            session()->flash('success', 'Cập nhật bình luận thành công!');
            return redirect()->route('admin.comments.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Có lỗi xảy ra khi cập nhật bình luận: ' . $e->getMessage());
        }
    }

    public function saveChildComments()
    {
        // Validate child comments
        $this->validate();

        if (empty($this->childComments)) {
            $this->addError('childComments', 'Không có bình luận con nào để lưu.');
            return;
        }

        try {
            DB::beginTransaction();

            $savedCount = 0;
            // Xác định parent ID - nếu comment hiện tại là bình luận con thì dùng parent của nó,
            // nếu là bình luận gốc thì dùng chính nó
            $parentId = $this->comment->parent_comment_id ?: $this->comment->id;

            foreach ($this->childComments as $childData) {
                if (!empty($childData['content'])) {
                    Comment::create([
                        'content' => $childData['content'],
                        'status' => $childData['status'],
                        'movie_id' => $this->movieId,
                        'user_id' => rand(1, 20), // Random từ 1 đến 20 user
                        'parent_comment_id' => $parentId,
                        'reply_comment_id' => null,
                    ]);
                    $savedCount++;
                }
            }

            DB::commit();

            if ($savedCount > 0) {
                session()->flash('success', "Đã thêm thành công {$savedCount} bình luận con!");
                $this->clearChildForms();
            } else {
                $this->addError('childComments', 'Không có bình luận con hợp lệ nào để lưu.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Có lỗi xảy ra khi lưu bình luận con: ' . $e->getMessage());
        }
    }

    // Hàm kiểm tra điều kiện xóa (đồng bộ với CommentCreate)
    public function checkDelete(Comment $comment)
    {
        if (is_null($comment->parent_comment_id)) {
            return !Comment::where('parent_comment_id', $comment->id)->exists();
        }
        return true;
    }

    #[Title('Chỉnh sửa bình luận - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        $movies = Movie::select('id', 'title', 'poster', 'description')->orderBy('title')->get();
        $selectedMovie = null;
        if ($this->movieId) {
            $selectedMovie = $movies->where('id', $this->movieId)->first();
        }

        // Get potential parent comments for the selected movie (excluding current comment và loại bỏ deleted)
        $parentComments = Comment::with('user')
            ->where('movie_id', $this->movieId)
            ->where('id', '!=', $this->comment->id)
            ->whereNull('parent_comment_id')
            ->where('status', '!=', 'deleted') // Chỉ loại bỏ deleted, giữ lại hidden
            ->orderBy('created_at', 'desc')
            ->get();

        // Get potential reply comments (from same parent thread)
        $replyComments = collect();
        if ($this->parentCommentId) {
            $replyComments = Comment::with('user')
                ->where('parent_comment_id', $this->parentCommentId)
                ->where('id', '!=', $this->comment->id)
                ->where('status', '!=', 'deleted') // Chỉ loại bỏ deleted
                ->orderBy('created_at', 'asc')
                ->get();
        }

        return view('livewire.admin.comments.comment-edit', compact('movies', 'parentComments', 'replyComments', 'selectedMovie'));
    }
}