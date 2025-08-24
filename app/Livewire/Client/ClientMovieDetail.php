<?php

namespace App\Livewire\Client;

use App\Models\Movie;
use App\Models\Comment;
use App\Models\Rating;
use App\Models\Showtime;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Facades\Auth;

class ClientMovieDetail extends Component
{
    public Movie $movie;
    public $tab = 'movie_info';
    public $subTab = 'overview';
    public $selectedDate = null;
    public $today;

    // Pagination
    public $commentsPerPage = 5;
    public $ratingsPerPage = 5;
    public $currentCommentPage = 1;
    public $currentRatingPage = 1;

    // Modals
    public $showTrailerModal = false;
    public $showRatingModal = false;
    public $showLoginPrompt = false;
    public $showUpdateRatingConfirm = false;
    public $showDeleteConfirm = false;
    public $showEditRatingModal = false;
    public $pendingDeleteId = null;
    public $deleteType = null; // 'rating' or 'comment'

    // Forms
    public $userRating = 0;
    public $userReview = '';
    public $editUserRating = 0;
    public $editUserReview = '';
    public $editingRatingId = null;
    public $newComment = '';
    public $replyingTo = null;
    public $replyContent = '';
    public $editingComment = null;
    public $editCommentContent = '';

    // UI State
    public $visibleReplyCount = [];
    public $showMoreComments = [];
    public $showMoreRatings = [];

    // Booking modal properties
    public $showBookingConfirmModal = false;
    public $selectedShowtime = null;

    public function mount(Movie $movie)
    {
        $this->movie = $movie;
        $this->today = Carbon::now();
        $this->setDefaultSelectedDate();
    }

    // Navigation Methods
    public function setTab($tab) {
        $this->tab = $tab;
        if ($tab === 'movie_info') $this->subTab = 'overview';
        if ($tab === 'showtimes' && empty($this->selectedDate)) {
            $this->setDefaultSelectedDate();
        }

        // Dispatch event để scroll xuống tab content
        $this->dispatch('scrollToTabContent');
    }
    public function setSubTab($subTab) { $this->subTab = $subTab; }
    public function selectDate($date) {
        $this->selectedDate = $date;
        $this->dispatch('dateSelected', date: $date);
    }

    public function bookShowtime($showtimeId)
    {
        // Kiểm tra showtime còn hợp lệ không trước khi booking
        $showtime = Showtime::find($showtimeId);

        if (!$showtime || $showtime->start_time->lte(now())) {
            session()->flash('error', 'Suất chiếu này đã hết hạn!');
            return;
        }

        // Lưu thông tin suất chiếu để hiển thị trong modal
        $this->selectedShowtime = $showtime;
        $this->showBookingConfirmModal = true;
    }

    public function confirmBooking()
    {
        if ($this->selectedShowtime) {
           return redirect()->route('client.booking.select_seats', ['showtime_id' => $this->selectedShowtime->movie_id]);
        }
    }

    public function closeBookingConfirmModal()
    {
        $this->showBookingConfirmModal = false;
        $this->selectedShowtime = null;
    }

    // Modal Methods
    public function openTrailerModal() { $this->showTrailerModal = true; }
    public function closeTrailerModal() { $this->showTrailerModal = false; }
    public function openRatingModal() { $this->showRatingModal = true; $this->loadUserRating(); }
    public function closeRatingModal() { $this->showRatingModal = false; $this->resetRatingForm(); }
    public function closeLoginPrompt() { $this->showLoginPrompt = false; }

    // Rating Methods
    public function setRating($rating) { $this->userRating = $rating; }
    public function setEditRating($rating) { $this->editUserRating = $rating; }

    public function submitRating()
    {
        if (!$this->checkAuth()) return;

        $this->validate([
            'userRating' => 'required|integer|min:1|max:5',
            'userReview' => 'nullable|string|max:1000',
        ]);

        $existingRating = $this->getUserRating();

        if ($existingRating) {
            // Nếu đánh giá bị soft delete, chỉ cho phép admin khôi phục hoặc user sửa số sao
            if ($existingRating->deleted_at) {
                $existingRating->update(['score' => $this->userRating]);
                session()->flash('success', 'Số sao đã được cập nhật.');
            } else {
                // Đánh giá đã tồn tại và chưa bị xóa, hiển thị form xác nhận cập nhật
                $this->showUpdateRatingConfirm = true;
                return;
            }
        } else {
            // Tạo đánh giá mới
            Rating::create([
                'movie_id' => $this->movie->id,
                'user_id' => Auth::id(),
                'score' => $this->userRating,
                'review' => $this->userReview,
            ]);
            session()->flash('success', 'Cảm ơn bạn đã đánh giá phim.');
        }

        $this->closeRatingModal();
        $this->dispatch('refreshRatings');
    }

    public function confirmUpdateRating()
    {
        $existingRating = $this->getUserRating();
        if ($existingRating && !$existingRating->deleted_at) {
            $existingRating->update([
                'score' => $this->userRating,
                'review' => $this->userReview,
            ]);
            session()->flash('success', 'Đánh giá đã được cập nhật.');
        }
        $this->showUpdateRatingConfirm = false;
        $this->closeRatingModal();
        $this->dispatch('refreshRatings');
    }

    public function cancelUpdateRating() { $this->showUpdateRatingConfirm = false; }

    // Edit Rating Methods
    public function editRating($ratingId)
    {
        if (!$this->checkAuth()) return;

        $rating = Rating::find($ratingId);
        if (!$rating || $rating->user_id !== Auth::id()) {
            session()->flash('error', 'Bạn không có quyền sửa đánh giá này.');
            return;
        }

        $this->editingRatingId = $ratingId;
        $this->editUserRating = $rating->score;
        $this->editUserReview = $rating->review ?? '';
        $this->showEditRatingModal = true;
    }

    public function closeEditRatingModal()
    {
        $this->showEditRatingModal = false;
        $this->editingRatingId = null;
        $this->editUserRating = 0;
        $this->editUserReview = '';
    }

    public function updateRating()
    {
        if (!$this->checkAuth()) return;

        $this->validate([
            'editUserRating' => 'required|integer|min:1|max:5',
            'editUserReview' => 'nullable|string|max:1000',
        ]);

        $rating = Rating::find($this->editingRatingId);
        if (!$rating || $rating->user_id !== Auth::id()) {
            session()->flash('error', 'Bạn không có quyền sửa đánh giá này.');
            return;
        }

        $rating->update([
            'score' => $this->editUserRating,
            'review' => $this->editUserReview,
        ]);

        session()->flash('success', 'Đánh giá đã được cập nhật.');
        $this->closeEditRatingModal();
        $this->dispatch('refreshRatings');
    }

    public function confirmDelete($id, $type)
    {
        // Hủy tất cả hành động khác trước khi xóa
        $this->cancelEditComment();
        $this->cancelReply();

        // Chỉ cho phép admin xóa đánh giá, user chỉ có thể xóa comment
        if ($type === 'rating' && Auth::user()->role !== 'admin') {
            session()->flash('error', 'Bạn không được phép xóa đánh giá. Chỉ có thể thay đổi đánh giá.');
            return;
        }

        $this->pendingDeleteId = $id;
        $this->deleteType = $type;
        $this->showDeleteConfirm = true;
    }

    public function executeDelete()
    {
        if (!$this->pendingDeleteId || !$this->deleteType) return;

        $user = Auth::user();
        $success = false;

        if ($this->deleteType === 'rating') {
            // Chỉ admin mới có thể xóa đánh giá
            if ($user->role === 'admin') {
            $rating = Rating::find($this->pendingDeleteId);
            if ($rating) {
                    $rating->delete();
                    $success = true;
                    session()->flash('success', 'Đánh giá đã được xóa.');
                }
            } else {
                session()->flash('error', 'Bạn không có quyền xóa đánh giá.');
            }
        } elseif ($this->deleteType === 'comment') {
            $comment = Comment::find($this->pendingDeleteId);
            if ($comment) {
                // Admin có thể xóa bất kỳ, user chỉ xóa của mình
                if ($user->role === 'admin' || $comment->user_id === $user->id) {
                    $comment->delete();
                    $success = true;
                    session()->flash('success', 'Bình luận đã được xóa.');
                }
            }
        }

        if (!$success && $this->deleteType === 'comment') {
            session()->flash('error', 'Bạn không có quyền thực hiện hành động này.');
        }

        $this->showDeleteConfirm = false;
        $this->pendingDeleteId = null;
        $this->deleteType = null;
        $this->dispatch('refreshRatings');
    }

    // Xóa đánh giá ngay lập tức (không xác nhận)
    public function executeDeleteImmediately($id, $type)
    {
        if ($type === 'rating' && Auth::check() && Auth::user()->role === 'admin') {
            $rating = Rating::find($id);
            if ($rating) {
                $rating->delete();
                session()->flash('success', 'Đánh giá đã được xóa.');
                $this->dispatch('refreshRatings');
            }
        }
        // Nếu là comment thì vẫn dùng confirm modal như cũ
    }

    public function cancelDelete()
    {
        $this->showDeleteConfirm = false;
        $this->pendingDeleteId = null;
        $this->deleteType = null;
    }

    // Admin restore rating
    public function restoreRating($id)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            session()->flash('error', 'Bạn không có quyền khôi phục đánh giá.');
            return;
        }

        $rating = Rating::withTrashed()->find($id);
        if ($rating) {
            $rating->restore();
            session()->flash('success', 'Đánh giá đã được khôi phục.');
            $this->dispatch('refreshRatings');
        }
    }

    // Comment Methods
    public function submitComment()
    {
        if (!$this->checkAuth()) return;

        $this->validate(['newComment' => 'required|string|max:1000']);

        Comment::create([
            'user_id' => Auth::id(),
            'movie_id' => $this->movie->id,
            'content' => $this->newComment,
            'status' => 'active',
        ]);

        $this->newComment = '';
        session()->flash('success', 'Bình luận đã được gửi.');
    }

    public function startEditComment($commentId)
    {
        // Hủy tất cả hành động khác trước khi bắt đầu sửa
        $this->cancelReply();

        $comment = Comment::find($commentId);
        if ($comment && ($comment->user_id === Auth::id() || Auth::user()->role === 'admin')) {
            $this->editingComment = $commentId;
            $this->editCommentContent = $comment->content;
        }
    }

    public function updateComment()
    {
        $this->validate(['editCommentContent' => 'required|string|max:1000']);

        $comment = Comment::find($this->editingComment);
        if ($comment && ($comment->user_id === Auth::id() || Auth::user()->role === 'admin')) {
            $comment->update(['content' => $this->editCommentContent]);
            session()->flash('success', 'Bình luận đã được cập nhật.');
        }

        $this->cancelEditComment();
    }

    public function cancelEditComment()
    {
        $this->editingComment = null;
        $this->editCommentContent = '';
    }

    // Reply Methods
    public function startReply($commentId) {
        // Hủy tất cả hành động khác trước khi bắt đầu trả lời
        $this->cancelEditComment();
        $this->replyingTo = $commentId;
        $this->replyContent = '';
    }
    public function cancelReply() { $this->replyingTo = null; $this->replyContent = ''; }

    public function submitReply()
    {
        if (!$this->checkAuth()) return;

        $this->validate(['replyContent' => 'required|string|max:1000']);

        // Tìm comment gốc để làm parent
        $targetComment = Comment::find($this->replyingTo);
        $parentCommentId = $targetComment->parent_comment_id ?? $targetComment->id;

        Comment::create([
            'user_id' => Auth::id(),
            'movie_id' => $this->movie->id,
            'content' => $this->replyContent,
            'parent_comment_id' => $parentCommentId,
            'status' => 'active',
        ]);

        $this->cancelReply();
        session()->flash('success', 'Phản hồi đã được gửi.');
    }

    // UI Helper Methods
    public function showMore($commentId) { $this->showMoreComments[$commentId] = true; }
    public function showLess($commentId) { $this->showMoreComments[$commentId] = false; }
    public function showMoreReplies($commentId) { $this->visibleReplyCount[$commentId] = ($this->visibleReplyCount[$commentId] ?? 3) + 3; }
    public function hideReplies($commentId) { $this->visibleReplyCount[$commentId] = 3; }
    public function showMoreRating($ratingId) { $this->showMoreRatings[$ratingId] = true; }
    public function showLessRating($ratingId) { $this->showMoreRatings[$ratingId] = false; }

    // Pagination Methods
    public function setCommentPage($page) { $this->currentCommentPage = max(1, min($page, $this->getTotalCommentPages())); }
    public function setRatingPage($page) { $this->currentRatingPage = max(1, min($page, $this->getTotalRatingPages())); }

    // Helper Methods
    private function checkAuth()
    {
        if (!Auth::check()) {
            $this->showLoginPrompt = true;
            return false;
        }
        return true;
    }

    private function getUserRating()
    {
        return Rating::withTrashed()
            ->where('movie_id', $this->movie->id)
            ->where('user_id', Auth::id())
            ->first();
    }

    private function loadUserRating()
    {
        if (!Auth::check()) return;

        $rating = $this->getUserRating();
        if ($rating) {
            $this->userRating = $rating->score;
            $this->userReview = $rating->deleted_at ? '' : $rating->review;
        }
    }

    private function resetRatingForm()
    {
        $this->userRating = 0;
        $this->userReview = '';
    }

    private function setDefaultSelectedDate()
    {
        $now = Carbon::now();

        $allShowtimes = $this->movie->showtimes()
            ->where('status', 'active')
            ->orderBy('start_time')
            ->get();

        // Lọc suất chiếu: chỉ lấy những suất chiếu chưa bắt đầu
        $validShowtimes = $allShowtimes->filter(function($showtime) use ($now) {
            return $showtime->start_time->gt($now);
        });

        $showtimesByDay = $validShowtimes->groupBy(function($item) {
            return $item->start_time->format('Y-m-d');
        });

        $dates = array_keys($showtimesByDay->toArray());
        $this->selectedDate = $dates[0] ?? null;
    }



    private function getCommentsWithReplies()
    {
        $allComments = $this->movie->comments()
            ->with(['user:id,name,avatar,email'])
            ->where('status', 'active')
            ->get();

        $parentComments = $allComments->whereNull('parent_comment_id');
        $childComments = $allComments->whereNotNull('parent_comment_id')->groupBy('parent_comment_id');

        $commentsWithReplyCount = $parentComments->map(function ($comment) use ($childComments, $allComments) {
            $replies = $childComments->get($comment->id, collect());
            
            // Thêm nested replies cho mỗi reply
            $repliesWithNested = $replies->map(function ($reply) use ($allComments) {
                $nestedReplies = $allComments->where('parent_comment_id', $reply->id);
                $reply->nested_replies = $nestedReplies->sortBy('created_at');
                $reply->nested_reply_count = $nestedReplies->count();
                return $reply;
            });
            
            $comment->reply_count = $replies->count();
            $comment->replies = $repliesWithNested->sortBy('created_at');
            return $comment;
        });

        // Sắp xếp comment cha theo thời gian tạo mới nhất lên trên cùng
        return $commentsWithReplyCount->sortByDesc(function ($comment) {
            return $comment->created_at->timestamp;
        })->values();
    }

    private function calculateAverageRating($ratings)
    {
        $totalRatings = $ratings->count();
        return $totalRatings === 0 ? 0 : round($ratings->sum('score') / $totalRatings, 1);
    }

    private function getTotalCommentPages()
    {
        return max(1, ceil($this->getCommentsWithReplies()->count() / $this->commentsPerPage));
    }

    private function getTotalRatingPages()
    {
        $totalRatings = $this->movie->ratings()->withTrashed()->count();
        return max(1, ceil($totalRatings / $this->ratingsPerPage));
    }

    #[Layout('components.layouts.client')]
    #[Title('Chi tiết phim')]
    public function render()
    {
        $now = Carbon::now();

        // Lấy tất cả suất chiếu active
        $allShowtimes = $this->movie->showtimes()
            ->where('status', 'active')
            ->orderBy('start_time')
            ->get();

        // Lọc suất chiếu: chỉ lấy những suất chiếu chưa bắt đầu
        $validShowtimes = $allShowtimes->filter(function($showtime) use ($now) {
            return $showtime->start_time->gt($now);
        });

        // Group showtimes by day
        $showtimesByDay = $validShowtimes->groupBy(function($item) {
            return $item->start_time->format('Y-m-d');
        });

        // Tính available seats
        foreach ($validShowtimes as $showtime) {
            $room = $showtime->room;
            if ($room) {
                $totalSeats = $room->seats()->where('status', 'active')->count();
                $bookedSeats = \App\Models\Booking::where('showtime_id', $showtime->id)
                    ->where('status', 'paid')
                    ->withCount('seats')
                    ->get()
                    ->sum('seats_count');
                $showtime->available_seats = max(0, $totalSeats - $bookedSeats);
            } else {
                $showtime->available_seats = 0;
            }
        }

        // Lọc thêm để chỉ giữ lại những suất chiếu có ghế trống
        $showtimesByDay = $showtimesByDay->map(function($showtimes) {
            return $showtimes->filter(function($showtime) {
                return $showtime->available_seats > 0;
            })->values();
        })->filter(function($showtimes) {
            return $showtimes->count() > 0;
        });

        // Set default selected date nếu chưa có
        if (empty($this->selectedDate) && !$showtimesByDay->isEmpty()) {
            $this->selectedDate = $showtimesByDay->keys()->first();
        }

        // Comments
        $comments = $this->getCommentsWithReplies();
        $totalCommentPages = $this->getTotalCommentPages();
        $start = ($this->currentCommentPage - 1) * $this->commentsPerPage;
        $visibleComments = $comments->slice($start, $this->commentsPerPage);

        // Ratings
        $allRatings = $this->movie->ratings()->with('user:id,name,avatar,email')->withTrashed()->latest()->get();
        $activeRatings = $allRatings->whereNull('deleted_at');
        $hiddenRatings = $allRatings->whereNotNull('deleted_at');

        $avgRating = $this->calculateAverageRating($allRatings);
        $totalRatings = $allRatings->count();

        // Rating distribution
        $ratingDistribution = [];
        for ($i = 1; $i <= 5; $i++) {
            $count = $allRatings->where('score', $i)->count();
            $ratingDistribution[$i] = [
                'count' => $count,
                'percentage' => $totalRatings > 0 ? round(($count / $totalRatings) * 100, 1) : 0
            ];
        }

        $totalRatingPages = $this->getTotalRatingPages();
        $startRating = ($this->currentRatingPage - 1) * $this->ratingsPerPage;
        $visibleRatings = $allRatings->slice($startRating, $this->ratingsPerPage);

        // Đưa đánh giá của bản thân lên đầu nếu có
        $userRatingModel = null;
        if (Auth::check()) {
            $userRatingModel = $allRatings->first(function($r) {
                return $r->user_id === Auth::id();
            });
            if ($userRatingModel) {
                $visibleRatings = $visibleRatings->reject(function($r) use ($userRatingModel) {
                    return $r->id === $userRatingModel->id;
                });
                $visibleRatings = $visibleRatings->prepend($userRatingModel);
            }
        }

        return view('livewire.client.template.movies.movie_detail', [
            'showtimesByDay' => $showtimesByDay,
            'comments' => $comments,
            'visibleComments' => $visibleComments,
            'totalCommentPages' => $totalCommentPages,
            'activeRatings' => $activeRatings,
            'hiddenRatings' => $hiddenRatings,
            'avgRating' => $avgRating,
            'totalRatings' => $totalRatings,
            'totalHiddenRatings' => $hiddenRatings->count(),
            'ratingDistribution' => $ratingDistribution,
            'visibleRatings' => $visibleRatings,
            'totalRatingPages' => $totalRatingPages,
        ]);
    }
}
