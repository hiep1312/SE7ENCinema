<?php

namespace App\Livewire\Admin\Comments;

use App\Models\Comment;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;

class CommentDetail extends Component
{
    use WithPagination;

    public Comment $comment;
    public $showReplies = true;
    public $newReplyContent = '';
    public $replyToCommentId = null;
    public $tabCurrent = 'overview';
    public $replies = [];
    public $statusOptions = [
        'active' => 'Hoạt động',
        'hidden' => 'Đã ẩn',
        'deleted' => 'Đã xóa',
    ];

    public $totalReplies = 0;
    public $activeReplies = 0;
    public $showAllReplies = [];
    public $maxRepliesToShow = 5;

    protected $rules = [
        'newReplyContent' => 'required|string|min:1|max:1000',
    ];

    protected $messages = [
        'newReplyContent.required' => 'Nội dung trả lời không được để trống.',
        'newReplyContent.min' => 'Nội dung trả lời quá ngắn.',
        'newReplyContent.max' => 'Nội dung trả lời không được vượt quá 1000 ký tự.',
    ];

    public function mount(Comment $comment)
    {
        $this->comment = $comment->load([
            'user:id,name,avatar,email',
            'movie:id,title,poster,duration',
            'movie.genres:id,name',
            'parent.user:id,name,avatar',
            'reply.user:id,name,avatar'
        ]);
        $tab = request()->query('tab');
        $validTabs = ['overview', 'replies', 'add-reply'];
        if ($tab && in_array($tab, $validTabs)) {
            $this->tabCurrent = $tab;
        }
        $this->showAllReplies = [];
        $this->loadRepliesAndStats();
    }

    public function refreshData()
    {
        $this->comment = $this->comment->fresh([
            'user:id,name,avatar,email',
            'movie:id,title,poster,duration',
            'movie.genres:id,name',
            'parent.user:id,name,avatar',
            'reply.user:id,name,avatar'
        ]);
        
        $this->loadRepliesAndStats();
    }

    private function getRepliesTree($parentId)
    {
        $replies = Comment::with(['user:id,name,avatar,email'])
            ->where('parent_comment_id', $parentId)
            ->orderBy('created_at', 'asc')
            ->get();
        foreach ($replies as $reply) {
            $reply->children = $this->getRepliesTree($reply->id);
        }
        return $replies;
    }

    private function loadRepliesAndStats()
    {
        $this->replies = $this->getRepliesTree($this->comment->id);
        $flat = collect($this->flattenReplies($this->replies));
        $this->totalReplies = $flat->count();
        $this->activeReplies = $flat->where('status', 'active')->count();
        $this->initializeShowAllReplies($this->replies);
    }

    private function initializeShowAllReplies($replies)
    {
        foreach ($replies as $reply) {
            if (!isset($this->showAllReplies[$reply->id])) {
                $this->showAllReplies[$reply->id] = false;
            }
            if (!empty($reply->children)) {
                $this->initializeShowAllReplies($reply->children);
            }
        }
    }

    private function flattenReplies($replies)
    {
        $flat = [];
        foreach ($replies as $reply) {
            $flat[] = $reply;
            if (!empty($reply->children)) {
                $flat = array_merge($flat, $this->flattenReplies($reply->children));
            }
        }
        return $flat;
    }

    public function toggleReplies()
    {
        $this->showReplies = !$this->showReplies;
    }

    public function setReplyTo($commentId = null)
    {
        $this->replyToCommentId = $commentId;
        $this->newReplyContent = '';
    }

    public function addReply()
    {
        $this->validate();

        try {
            $parentCommentId = $this->comment->parent_comment_id ? $this->comment->parent_comment_id : $this->comment->id;
            $replyCommentId = $this->replyToCommentId ? $this->replyToCommentId : $this->comment->id;

            Comment::create([
                'user_id' => Auth::id(),
                'movie_id' => $this->comment->movie_id,
                'parent_comment_id' => $parentCommentId,
                'reply_comment_id' => $replyCommentId,
                'content' => $this->newReplyContent,
                'status' => 'active',
            ]);

            $this->loadRepliesAndStats();
            $this->newReplyContent = '';
            $this->replyToCommentId = null;

            session()->flash('success', 'Trả lời bình luận thành công!');
        } catch (\Exception $e) {
            session()->flash('error', 'Có lỗi xảy ra khi trả lời bình luận: ' . $e->getMessage());
        }
    }

    public function checkDelete(Comment $comment)
    {
        if (is_null($comment->parent_comment_id)) {
            // Bình luận cha chỉ xóa được nếu tất cả bình luận con đã 'đã xóa' hoặc 'đã ẩn'
            return !Comment::where('parent_comment_id', $comment->id)->where('status', 'active')->exists();
        }
        return true;
    }

    public function changeStatus($commentId, $status, $result = null)
    {
        $commentToChange = Comment::findOrFail($commentId);

        if ($status === 'deleted') {
            if (!$this->checkDelete($commentToChange)) {
                session()->flash('error', 'Không thể xóa bình luận này vì còn bình luận con hoạt động.');
                return;
            }
            $commentToChange->delete(); 
            session()->flash('success', 'Đã xóa bình luận thành công!');
            if ($this->comment->id == $commentId) {
                return redirect()->route('admin.comments.index');
            }
            $this->loadRepliesAndStats();
            return;
        }

        $commentToChange->status = $status;
        $commentToChange->save();
        if ($this->comment->id == $commentId) {
            $this->comment->refresh();
        }
        $this->loadRepliesAndStats();
        session()->flash('success', 'Cập nhật trạng thái thành công!');
    }

    public function deleteAllReplies($status, $result = null)
    {
        if(!$status['isConfirmed']) return;
        try {
            Comment::where('parent_comment_id', $this->comment->id)->delete();
            $this->loadRepliesAndStats();
            session()->flash('success', 'Đã xóa tất cả bình luận con thành công!');
        } catch (\Exception $e) {
            session()->flash('error', 'Có lỗi xảy ra khi xóa tất cả bình luận con: ' . $e->getMessage());
        }
    }

    public function getCommentTreeProperty()
    {
        $rootComment = $this->comment->parent_comment_id
            ? Comment::find($this->comment->parent_comment_id)
            : $this->comment;

        return $this->buildCommentTree($rootComment);
    }

    private function buildCommentTree(Comment $comment)
    {
        $tree = $comment->load([
            'user:id,name,avatar,email',
            'reply.user:id,name,avatar'
        ]);

        $replies = Comment::with([
            'user:id,name,avatar,email',
            'reply.user:id,name,avatar'
        ])
        ->where('parent_comment_id', $comment->id)
        ->orderBy('created_at', 'asc')
        ->get();

        $tree->nestedReplies = $replies;

        return $tree;
    }

    public function deleteComment($commentId)
    {
        $commentToDelete = Comment::findOrFail($commentId);
        $commentToDelete->delete(); // chỉ xóa đúng reply này
        session()->flash('success', 'Đã xóa bình luận thành công!');
        if ($this->comment->id == $commentId) {
            return redirect()->route('admin.comments.index');
        }
        $this->loadRepliesAndStats();
    }

    public function toggleShowAllReplies($parentId)
    {
        if (!$parentId) return;
        $this->showAllReplies[$parentId] = !($this->showAllReplies[$parentId] ?? false);
    }

    #[Title('Chi tiết bình luận - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.admin.comments.comment-detail');
    }
}
