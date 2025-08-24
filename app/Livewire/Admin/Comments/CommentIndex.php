<?php

namespace App\Livewire\Admin\Comments;

use App\Models\Comment;
use App\Models\Movie;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\DB;

class CommentIndex extends Component
{
    use WithPagination;

    public $movieId;
    public $search = '';
    public $statusFilter = '';
    public $selectedMovie;
    public $statusOptions = [
        'active' => 'Hoạt động',
        'hidden' => 'Đã ẩn',
        'deleted' => 'Đã xóa',
    ];

    public function mount()
    {
        if ($this->movieId) {
            $this->selectedMovie = Movie::find($this->movieId);
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingMovieId()
    {
        $this->resetPage();
        $this->selectedMovie = $this->movieId ? Movie::find($this->movieId) : null;
    }

    private function checkDelete(Comment $comment)
    {
        if (is_null($comment->parent_comment_id)) {
            // Bình luận cha chỉ xóa được nếu tất cả bình luận con đã 'đã xóa' hoặc 'đã ẩn'
            return !Comment::where('parent_comment_id', $comment->id)->where('status', 'active')->exists();
        }
        return true;
    }

    public function changeCommentStatus($commentId, $status, $result = null)
    {
        $comment = Comment::findOrFail($commentId);
        if (!in_array($status, array_keys($this->statusOptions))) {
            session()->flash('error', 'Trạng thái không hợp lệ.');
            return;
        }

        if ($status === 'deleted') {
            if (!$this->checkDelete($comment)) {
                session()->flash('error', 'Không thể xóa bình luận này vì còn bình luận con hoạt động.');
                return;
            }
            $comment->delete(); // Hard delete
            session()->flash('success', 'Đã xóa bình luận thành công!');
            return;
        }

        $comment->status = $status;
        $comment->save();
        session()->flash('success', 'Cập nhật trạng thái bình luận thành công!');
    }

    public function resetFilters()
    {
        $this->reset(['search', 'statusFilter', 'movieId']);
        $this->resetPage();
    }

    public function deleteComment($status, $commentId)
    {
        if(!$status['isConfirmed']) return;
        $comment = Comment::findOrFail($commentId);
        if (!$this->checkDelete($comment)) {
            session()->flash('error', 'Không thể xóa bình luận này vì còn bình luận con hoạt động.');
            return;
        }
        $comment->delete();
        session()->flash('success', 'Đã xóa bình luận thành công!');
        $this->resetPage();
    }

    #[Title('Danh sách bình luận - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        $commentsQuery = Comment::with([
            'user:id,name,avatar,email',
            'movie:id,title,poster,duration',
            'movie.genres:id,name',
            'parent.user:id,name',
            'reply.user:id,name'
        ])
        ->when($this->movieId, function ($query) {
            return $query->where('movie_id', $this->movieId);
        })
        ->when($this->search, function ($query, $search) {
            return $query->where(function ($q) use ($search) {
                $q->where('content', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', '%' . $search . '%');
                    });
            });
        })
        ->when($this->statusFilter, function ($query) {
            return $query->where('status', $this->statusFilter);
        })
        ->whereNull('parent_comment_id');

        $comments = $commentsQuery->orderBy('created_at', 'desc')->paginate(10);

        $commentIds = $comments->pluck('id');
        if ($commentIds->isNotEmpty()) {
            $replyCounts = Comment::whereIn('parent_comment_id', $commentIds)
                ->select('parent_comment_id', DB::raw('count(*) as total'))
                ->groupBy('parent_comment_id')
                ->pluck('total', 'parent_comment_id');

            $comments->getCollection()->each(function ($comment) use ($replyCounts) {
                $comment->replies_count = $replyCounts->get($comment->id, 0);
            });
        }

        $movies = Movie::select('id', 'title')->orderBy('title')->get();

        return view('livewire.admin.comments.comment-index', compact('comments', 'movies'));
    }
}
