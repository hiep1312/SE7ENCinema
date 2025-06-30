<?php

namespace App\Livewire\Client;

use App\Models\Movie;
use App\Models\Comment;
use App\Models\Rating;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class MovieDetail extends Component
{
    public Movie $movie;
    public $tab = 'movie_info';
    public $subTab = 'overview'; // Thêm dòng này sau dòng public $tab = 'movie_info';
    public $selectedDate = null;
    public $showMoreComments = [];
    public $commentsPerPage = 5;
    public $repliesPerPage = 2;
    public $today;
    public $currentCommentPage = 1;

    // Modal states
    public $showTrailerModal = false;
    public $showRatingModal = false;

    // Rating form
    public $userRating = 0;
    public $userReview = '';

    public $ratingsPerPage = 5;
    public $currentRatingPage = 1;

    public function mount(Movie $movie)
    {
        $this->movie = $movie;
        $this->today = Carbon::now();

        // Set default selected date to first available date
        $allShowtimes = $this->movie->showtimes()
            ->where('status', 'active')
            ->orderBy('start_time')
            ->get();

        $showtimesByDay = $allShowtimes->groupBy(function($item) {
            return $item->start_time->format('Y-m-d');
        });

        $dates = array_keys($showtimesByDay->toArray());
        $this->selectedDate = $dates[0] ?? null;
    }

    public function setTab($tab)
    {
        $this->tab = $tab;
        if ($tab === 'movie_info') {
            $this->subTab = 'overview'; // Reset về overview khi chọn movie_info
        }
    }

    public function setSubTab($subTab)
    {
        $this->subTab = $subTab;
    }

    public function selectDate($date)
    {
        $this->selectedDate = $date;
    }

    public function showMore($commentId)
    {
        $this->showMoreComments[$commentId] = true;
    }

    public function showLess($commentId)
    {
        $this->showMoreComments[$commentId] = false;
    }

    // Modal methods
    public function openTrailerModal()
    {
        $this->showTrailerModal = true;
    }

    public function closeTrailerModal()
    {
        $this->showTrailerModal = false;
    }

    public function openRatingModal()
    {
        $this->showRatingModal = true;
        $this->resetRatingForm();
    }

    public function closeRatingModal()
    {
        $this->showRatingModal = false;
        $this->resetRatingForm();
    }

    public function setRating($rating)
    {
        $this->userRating = $rating;
    }

    public function resetRatingForm()
    {
        $this->userRating = 0;
        $this->userReview = '';
    }

    public function submitRating()
    {
        $this->validate([
            'userRating' => 'required|integer|min:1|max:5',
            'userReview' => 'nullable|string|max:1000',
        ]);

        // Check if user is authenticated
        if (!auth()->check()) {
            session()->flash('error', 'Bạn cần đăng nhập để đánh giá phim.');
            return;
        }

        // Check if user already rated this movie
        $existingRating = Rating::where('movie_id', $this->movie->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existingRating) {
            // Update existing rating
            $existingRating->update([
                'score' => $this->userRating,
                'review' => $this->userReview,
            ]);
            session()->flash('success', 'Đánh giá của bạn đã được cập nhật.');
        } else {
            // Create new rating
            Rating::create([
                'movie_id' => $this->movie->id,
                'user_id' => auth()->id(),
                'score' => $this->userRating,
                'review' => $this->userReview,
            ]);
            session()->flash('success', 'Cảm ơn bạn đã đánh giá phim.');
        }

        $this->closeRatingModal();
        $this->dispatch('refreshRatings');
    }

    public function getCommentsWithReplies()
    {
        // Lấy tất cả bình luận của phim này
        $allComments = $this->movie->comments()
            ->with(['user'])
            ->where('status', 'active') // Chỉ lấy bình luận active
            ->get();

        // Phân loại bình luận cha và con
        $parentComments = $allComments->whereNull('parent_comment_id');
        $childComments = $allComments->whereNotNull('parent_comment_id')->groupBy('parent_comment_id');

        // Tính số lượng replies cho mỗi bình luận cha
        $commentsWithReplyCount = $parentComments->map(function ($comment) use ($childComments) {
            $replies = $childComments->get($comment->id, collect());
            $comment->reply_count = $replies->count();
            $comment->replies = $replies->sortBy('created_at'); // Sắp xếp replies theo thời gian tăng dần
            return $comment;
        });

        // Sắp xếp theo số lượng replies giảm dần, sau đó theo thời gian tạo giảm dần
        return $commentsWithReplyCount->sortByDesc(function ($comment) {
            return [$comment->reply_count, $comment->created_at->timestamp];
        })->values();
    }

    /**
     * Tính điểm trung bình đánh giá
     * Công thức: Tổng số điểm / Tổng số lượt đánh giá
     */
    public function calculateAverageRating($ratings)
    {
        $totalRatings = $ratings->count();
        if ($totalRatings === 0) {
            return 0;
        }

        $totalScore = $ratings->sum('score');
        return round($totalScore / $totalRatings, 1);
    }

    public function setCommentPage($page)
    {
        $this->currentCommentPage = max(1, min($page, $this->getTotalCommentPages()));
    }

    public function getTotalCommentPages()
    {
        $comments = $this->getCommentsWithReplies();
        return max(1, ceil($comments->count() / $this->commentsPerPage));
    }

    public function setRatingPage($page)
    {
        $this->currentRatingPage = max(1, min($page, $this->getTotalRatingPages()));
    }

    public function getTotalRatingPages($activeRatings = null)
    {
        if ($activeRatings === null) {
            $activeRatings = $this->movie->ratings()->whereNull('deleted_at')->count();
        } else {
            $activeRatings = $activeRatings->count();
        }
        return max(1, ceil($activeRatings / $this->ratingsPerPage));
    }

    #[Layout('components.layouts.client')]
    #[Title('Chi tiết phim')]
    public function render()
    {
        $now = Carbon::now();
        $allShowtimes = $this->movie->showtimes()
            ->where('status', 'active')
            ->orderBy('start_time')
            ->get();

        // Group showtimes by day
        $showtimesByDay = $allShowtimes->groupBy(function($item) {
            return $item->start_time->format('Y-m-d');
        });

        // Lọc suất chiếu ngày hiện tại chỉ lấy sau 10 phút
        $todayKey = $now->format('Y-m-d');
        if (isset($showtimesByDay[$todayKey])) {
            $showtimesByDay[$todayKey] = $showtimesByDay[$todayKey]->filter(function($showtime) use ($now) {
                return $showtime->start_time->greaterThanOrEqualTo($now->copy()->addMinutes(10));
            })->values();
        }

        // Tính số ghế trống cho mỗi showtime
        foreach ($allShowtimes as $showtime) {
            $room = $showtime->room;
            if ($room) {
                $totalSeats = $room->seats()->where('status', 'active')->count();
                $bookedSeatIds = \App\Models\Booking::where('showtime_id', $showtime->id)
                    ->whereIn('status', ['pending', 'confirmed', 'paid'])
                    ->with('seats')
                    ->get()
                    ->flatMap(function($booking) { return $booking->seats->pluck('id'); })
                    ->unique();
                $bookedSeats = $bookedSeatIds->count();
                $showtime->available_seats = $totalSeats - $bookedSeats;
            } else {
                $showtime->available_seats = 0;
            }
        }

        // Bình luận với replies
        $comments = $this->getCommentsWithReplies();
        $totalCommentPages = $this->getTotalCommentPages();
        $start = ($this->currentCommentPage - 1) * $this->commentsPerPage;
        $visibleComments = $comments->slice($start, $this->commentsPerPage);

        // Đánh giá (bao gồm cả soft deleted để hiển thị thông báo)
        $allRatings = $this->movie->ratings()
            ->with('user')
            ->withTrashed()
            ->latest()
            ->get();

        // Phân tách đánh giá active và đánh giá bị ẩn
        $activeRatings = $allRatings->whereNull('deleted_at');
        $hiddenRatings = $allRatings->whereNotNull('deleted_at');

        // Tính toán thống kê đánh giá (chỉ tính các đánh giá không bị xóa)
        $totalRatings = $activeRatings->count();
        $totalHiddenRatings = $hiddenRatings->count();

        // Tính điểm trung bình theo công thức: Tổng điểm / Tổng số lượt đánh giá
        $avgRating = $this->calculateAverageRating($activeRatings);

        $ratingDistribution = [];

        // Tạo phân phối đánh giá từ 1-5 sao
        for ($i = 1; $i <= 5; $i++) {
            $count = $activeRatings->where('score', $i)->count();
            $ratingDistribution[$i] = [
                'count' => $count,
                'percentage' => $totalRatings > 0 ? round(($count / $totalRatings) * 100, 1) : 0
            ];
        }

        $totalRatingPages = $this->getTotalRatingPages($activeRatings);
        $startRating = ($this->currentRatingPage - 1) * $this->ratingsPerPage;
        $visibleRatings = $activeRatings->slice($startRating, $this->ratingsPerPage);

        return view('livewire.client.template.movies.movie-detail', [
            'showtimesByDay' => $showtimesByDay,
            'comments' => $comments,
            'visibleComments' => $visibleComments,
            'commentsPerPage' => $this->commentsPerPage,
            'currentCommentPage' => $this->currentCommentPage,
            'totalCommentPages' => $totalCommentPages,
            'activeRatings' => $activeRatings,
            'hiddenRatings' => $hiddenRatings,
            'avgRating' => $avgRating,
            'totalRatings' => $totalRatings,
            'totalHiddenRatings' => $totalHiddenRatings,
            'ratingDistribution' => $ratingDistribution,
            'visibleRatings' => $visibleRatings,
            'ratingsPerPage' => $this->ratingsPerPage,
            'currentRatingPage' => $this->currentRatingPage,
            'totalRatingPages' => $totalRatingPages,
        ]);
    }
}
