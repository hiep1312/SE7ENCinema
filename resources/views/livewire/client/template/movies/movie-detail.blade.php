<div class="movie-detail-page">
    <div class="movie-container">
        <!-- Movie Header -->
        <div class="movie-header-block">
            <div class="movie-header-bg" style="background-image: url('{{ $movie->poster }}');"></div>
            <div class="movie-header">
                <div class="movie-poster">
                    <div style="position:relative;">
                        @if($movie->age_restriction)
                            <div class="age-restriction">{{ $movie->age_restriction }}</div>
                        @endif
                        <img src="{{ $movie->poster }}" alt="{{ $movie->title }}">
                    </div>
                </div>

                <div class="movie-info">
                    <h1 class="movie-title">{{ $movie->title }}</h1>
                    <div class="movie-actions">
                        <button class="btn btn-rating" wire:click="openRatingModal">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" fill="currentColor"/>
                            </svg>
                            Đánh giá
                        </button>
                        <button class="btn btn-trailer" wire:click="openTrailerModal">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8 5V19L19 12L8 5Z" fill="currentColor"/>
                            </svg>
                            Trailer
                        </button>
                        <button class="btn btn-buy">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3 7V17C3 18.1046 3.89543 19 5 19H19C20.1046 19 21 18.1046 21 17V7C21 5.89543 20.1046 5 19 5H5C3.89543 5 3 5.89543 3 7Z" stroke="currentColor" stroke-width="2"/>
                                <path d="M8 5V3C8 2.44772 8.44772 2 9 2H15C15.5523 2 16 2.44772 16 3V5" stroke="currentColor" stroke-width="2"/>
                                <path d="M8 12H16" stroke="currentColor" stroke-width="2"/>
                                <path d="M8 15H12" stroke="currentColor" stroke-width="2"/>
                            </svg>
                            Mua vé
                        </button>
                    </div>

                    <div class="movie-description">
                        {{ $movie->description }}
                    </div>

                    <div class="movie-details">
                        <div class="label">ĐẠO DIỄN:</div>
                        <div class="value">{{ $movie->director }}</div>
                        <div class="label">DIỄN VIÊN:</div>
                        <div class="value">{{ $movie->actors }}</div>
                        <div class="label">THỂ LOẠI:</div>
                        <div class="value">
                            @if($movie->genres && $movie->genres->count())
                                {{ $movie->genres->pluck('name')->implode(', ') }}
                            @else
                                Không rõ
                            @endif
                        </div>
                        <div class="label">THỜI LƯỢNG:</div>
                        <div class="value">{{ $movie->duration }} phút</div>
                        <div class="label">NGÔN NGỮ:</div>
                        <div class="value">{{ $movie->language ?? 'Tiếng Việt' }}</div>
                        <div class="label">NGÀY KHỞI CHIẾU:</div>
                        <div class="value">{{ $movie->release_date ? $movie->release_date->format('d/m/Y') : '' }}</div>
                    </div>

                    <div class="movie-stats">
                        <div class="stat-item">
                            <div class="stat-icon">👍</div>
                            <div class="stat-value">{{ number_format($avgRating * 20) }}%</div>
                            <div class="stat-label">Hài lòng</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon">📅</div>
                            <div class="stat-value">{{ $movie->release_date ? $movie->release_date->format('d/m/Y') : '' }}</div>
                            <div class="stat-label">Khởi chiếu</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon">⏱️</div>
                            <div class="stat-value">{{ $movie->duration }} phút</div>
                            <div class="stat-label">Thời lượng</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon">🔞</div>
                            <div class="stat-value">{{ $movie->age_restriction ?? 'T18' }}</div>
                            <div class="stat-label">Giới hạn tuổi</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Section -->
        <div class="tabs-section">
            <!-- Main Tab Navigation -->
            <div class="main-tab-nav">
                <button class="main-tab-item @if($tab === 'movie_info') active @endif" wire:click="setTab('movie_info')">
                    <span class="tab-icon">📋</span>
                    Thông tin phim
                </button>
                <button class="main-tab-item @if($tab === 'showtimes') active @endif" wire:click="setTab('showtimes')">
                    <span class="tab-icon">🎬</span>
                    Lịch chiếu
                </button>
                <button class="main-tab-item @if($tab === 'ratings') active @endif" wire:click="setTab('ratings')">
                    <span class="tab-icon">⭐</span>
                    Đánh giá
                </button>
            </div>

            <!-- Tab Content -->
            <div class="tab-content-moveek">
                @if($tab === 'movie_info')
                    <!-- Combined Movie Info Section -->
                    <div class="movie-info-combined">
                        <!-- Trailer Section -->
                        @if($movie->trailer_url)
                            <div class="trailer-section">
                                <div class="trailer-header">
                                    <h3 class="trailer-title">Trailer - {{ $movie->title }}</h3>
                                    <p class="trailer-subtitle">Xem trailer chính thức của bộ phim</p>
                                </div>
                                <div class="trailer-video-wrapper">
                                    <div class="trailer-video-inner">
                                        @php
                                            $videoId = '';
                                            if (str_contains($movie->trailer_url, 'youtube.com/watch?v=')) {
                                                $videoId = substr($movie->trailer_url, strpos($movie->trailer_url, 'v=') + 2);
                                                $videoId = substr($videoId, 0, strpos($videoId, '&') ?: strlen($videoId));
                                            } elseif (str_contains($movie->trailer_url, 'youtu.be/')) {
                                                $videoId = substr($movie->trailer_url, strrpos($movie->trailer_url, '/') + 1);
                                            }
                                        @endphp
                                        @if($videoId)
                                            <iframe src="https://www.youtube.com/embed/{{ $videoId }}" allowfullscreen></iframe>
                                        @else
                                            <video controls>
                                                <source src="{{ $movie->trailer_url }}" type="video/mp4">
                                                Trình duyệt không hỗ trợ video.
                                            </video>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Comments Section -->
                        <div class="comments-section">
                            <div class="comments-header">
                                <h3 class="comments-title">Bình luận từ khán giả ({{ $comments->count() }})</h3>
                                <p class="comments-subtitle">Chia sẻ cảm nhận của bạn về bộ phim này</p>
                            </div>
                            @php
                                $commentsPerPage = $commentsPerPage ?? 2;
                                $currentCommentPage = $currentCommentPage ?? 1;
                                $totalCommentPages = ceil($comments->count() / $commentsPerPage);
                                $start = ($currentCommentPage - 1) * $commentsPerPage;
                                $visibleComments = $comments->slice($start, $commentsPerPage);
                            @endphp
                            <div class="comments-list">
                            @forelse($visibleComments as $comment)
                                <div class="comment-item">
                                    <div class="comment-header">
                                        <div class="comment-avatar">
                                            {{ strtoupper(substr($comment->user->name ?? 'A', 0, 1)) }}
                                        </div>
                                        <div>
                                            <span class="comment-user">{{ $comment->user->name ?? 'Ẩn danh' }}</span>
                                            <span class="comment-date">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                    <div class="comment-content">{{ $comment->content }}</div>

                                    <!-- Replies -->
                                    @if($comment->replies && $comment->replies->count() > 0)
                                        <div class="reply-list">
                                            @php
                                                $visibleReplies = isset($showMoreComments[$comment->id]) && $showMoreComments[$comment->id]
                                                    ? $comment->replies
                                                    : $comment->replies->take($repliesPerPage);
                                            @endphp
                                            @foreach($visibleReplies as $reply)
                                                <div class="reply-item">
                                                    <div class="comment-header">
                                                        <div class="comment-avatar" style="width: 32px; height: 32px; font-size: 0.9rem;">
                                                            {{ strtoupper(substr($reply->user->name ?? 'A', 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <span class="comment-user">{{ $reply->user->name ?? 'Ẩn danh' }}</span>
                                                            <span class="comment-date">{{ $reply->created_at->diffForHumans() }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="comment-content">{{ $reply->content }}</div>
                                                </div>
                                            @endforeach

                                            @if($comment->replies->count() > $repliesPerPage)
                                                <div style="text-align:center; margin-top: 12px;">
                                                    @if(!isset($showMoreComments[$comment->id]) || !$showMoreComments[$comment->id])
                                                        <button wire:click="showMore({{ $comment->id }})" class="show-more-btn">
                                                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M19 9L12 16L5 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                                            Xem thêm {{ $comment->replies->count() - $repliesPerPage }} phản hồi
                                                        </button>
                                                    @else
                                                        <button wire:click="showLess({{ $comment->id }})" class="show-more-btn">
                                                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M5 15L12 8L19 15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                                            Ẩn bớt
                                                        </button>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="no-data">Chưa có bình luận nào</div>
                            @endforelse
                            </div>
                            @if($totalCommentPages > 1)
                                <div class="comment-pagination">
                                    <button class="comment-pagination-arrow" wire:click="setCommentPage({{ $currentCommentPage - 1 }})" @if($currentCommentPage == 1) disabled @endif>&#x2039;</button>
                                    @for($i = 1; $i <= $totalCommentPages; $i++)
                                        @if($i == 1 || $i == $totalCommentPages || abs($i - $currentCommentPage) <= 1)
                                            <button class="comment-pagination-btn @if($i == $currentCommentPage) active @endif" wire:click="setCommentPage({{ $i }})">{{ $i }}</button>
                                        @elseif($i == 2 && $currentCommentPage > 3)
                                            <span class="comment-pagination-ellipsis">...</span>
                                        @elseif($i == $totalCommentPages - 1 && $currentCommentPage < $totalCommentPages - 2)
                                            <span class="comment-pagination-ellipsis">...</span>
                                        @endif
                                    @endfor
                                    <button class="comment-pagination-arrow" wire:click="setCommentPage({{ $currentCommentPage + 1 }})" @if($currentCommentPage == $totalCommentPages) disabled @endif>&#x203A;</button>
                                </div>
                            @endif
                        </div>
                    </div>

                @elseif($tab === 'showtimes')
                    <!-- Showtimes Section -->
                    <div class="showtimes-section">
                        <div class="showtimes-header">
                            <h2 class="showtimes-title">Lịch chiếu</h2>
                            <p class="showtimes-subtitle">Chọn ngày bạn muốn xem phim {{ $movie->title }}.</p>
                        </div>

                        <!-- Date Selection -->
                        <div class="date-tabs-moveek" wire:loading.class="loading">
                            @foreach($showtimesByDay as $date => $showtimes)
                                <button
                                    wire:click="selectDate('{{ $date }}')"
                                    class="date-tab-moveek @if($selectedDate == $date) active @endif"
                                    wire:loading.attr="disabled"
                                >
                                    <span class="date-number-moveek">{{ \Carbon\Carbon::parse($date)->format('d') }}</span>
                                    <span class="date-day-moveek">{{ \Carbon\Carbon::parse($date)->format('m') }}/{{ \Carbon\Carbon::parse($date)->isoFormat('dd') }}</span>
                                </button>
                            @endforeach
                        </div>

                        <!-- Loading indicator -->
                        <div wire:loading wire:target="selectDate" class="loading-indicator">
                            <div class="spinner"></div>
                            <span>Đang tải lịch chiếu...</span>
                        </div>

                        <!-- Cinema List -->
                        <div wire:loading.remove wire:target="selectDate">
                            @php
                                $showtimesForDate = $showtimesByDay[$selectedDate] ?? collect();
                                $formatGroups = $showtimesForDate->groupBy(function($showtime) {
                                    return $showtime->format ?? '2D Phụ Đề Anh';
                                });
                            @endphp

                            @forelse($formatGroups as $formatName => $formatShowtimes)
                                <div class="cinema-item">
                                    <div class="cinema-name">{{ $formatName }}</div>
                                    <div class="showtime-list">
                                        @foreach($formatShowtimes as $showtime)
                                            <a href="#" class="showtime-item">
                                                <div class="showtime-time">{{ $showtime->start_time->format('H:i') }}</div>
                                                <div class="showtime-seats">{{ $showtime->available_seats }}K</div>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @empty
                                <div class="no-data">Không có suất chiếu nào cho ngày này</div>
                            @endforelse
                        </div>
                    </div>

                @elseif($tab === 'ratings')
                    <!-- Ratings Section -->
                    <div class="ratings-section">
                        @if($totalRatings > 0)
                            <!-- Rating Summary - Fixed at top -->
                            <div class="rating-summary-fixed">
                                <div class="rating-score">
                                    <div class="rating-number">{{ $avgRating }}</div>
                                    <div class="rating-stars">
                                        @php
                                            $fullStars = floor($avgRating);
                                            $hasHalfStar = ($avgRating - $fullStars) >= 0.5;
                                            $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
                                        @endphp
                                        @for($i = 0; $i < $fullStars; $i++)
                                            <span class="star">★</span>
                                        @endfor
                                        @if($hasHalfStar)
                                            <span class="star">☆</span>
                                        @endif
                                        @for($i = 0; $i < $emptyStars; $i++)
                                            <span class="star empty">☆</span>
                                        @endfor
                                    </div>
                                    <div class="rating-count">{{ $totalRatings }} đánh giá</div>
                                </div>

                                <div class="rating-distribution">
                                    @foreach($ratingDistribution as $star => $data)
                                        <div class="rating-bar-item">
                                            <div class="rating-bar-label">{{ $star }}★</div>
                                            <div class="rating-bar">
                                                <div class="rating-bar-fill" style="width: {{ $data['percentage'] }}%;"></div>
                                            </div>
                                            <div class="rating-bar-count">{{ $data['count'] }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Rating List - Scrollable -->
                            <div class="rating-list">
                                @foreach($visibleRatings as $rating)
                                    <div class="rating-item">
                                        <div class="rating-header">
                                            <div class="rating-avatar">
                                                {{ strtoupper(substr($rating->user->name ?? 'A', 0, 1)) }}
                                            </div>
                                            <div>
                                                <span class="rating-user">{{ $rating->user->name ?? 'Ẩn danh' }}</span>
                                                <span class="rating-date">{{ $rating->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                        <div class="rating-stars-user">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span class="star @if($i <= $rating->score) @else empty @endif">★</span>
                                            @endfor
                                            <span style="color:#888;font-size:1rem;margin-left:8px;">{{ $rating->score }}/5</span>
                                        </div>
                                        @if($rating->review)
                                            <div class="rating-review">{{ $rating->review }}</div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            @if($totalRatingPages > 1)
                                <div class="comment-pagination">
                                    <button class="comment-pagination-arrow" wire:click="setRatingPage({{ $currentRatingPage - 1 }})" @if($currentRatingPage == 1) disabled @endif>&#x2039;</button>
                                    @for($i = 1; $i <= $totalRatingPages; $i++)
                                        @if($i == 1 || $i == $totalRatingPages || abs($i - $currentRatingPage) <= 1)
                                            <button class="comment-pagination-btn @if($i == $currentRatingPage) active @endif" wire:click="setRatingPage({{ $i }})">{{ $i }}</button>
                                        @elseif($i == 2 && $currentRatingPage > 3)
                                            <span class="comment-pagination-ellipsis">...</span>
                                        @elseif($i == $totalRatingPages - 1 && $currentRatingPage < $totalRatingPages - 2)
                                            <span class="comment-pagination-ellipsis">...</span>
                                        @endif
                                    @endfor
                                    <button class="comment-pagination-arrow" wire:click="setRatingPage({{ $currentRatingPage + 1 }})" @if($currentRatingPage == $totalRatingPages) disabled @endif>&#x203A;</button>
                                </div>
                            @endif
                        @else
                            <div class="no-data">Chưa có đánh giá nào</div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Trailer Modal -->
    @if($showTrailerModal)
        <div class="modal-overlay" wire:click="closeTrailerModal">
            <div class="modal-content trailer-modal" wire:click.stop>
                <div class="modal-header">
                    <h3>Trailer - {{ $movie->title }}</h3>
                    <button class="modal-close" wire:click="closeTrailerModal">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
                <div class="modal-body">
                    @if($movie->trailer_url)
                        <div class="trailer-video-wrapper">
                            <div class="trailer-video-inner">
                                @php
                                    $videoId = '';
                                    if (str_contains($movie->trailer_url, 'youtube.com/watch?v=')) {
                                        $videoId = substr($movie->trailer_url, strpos($movie->trailer_url, 'v=') + 2);
                                        $videoId = substr($videoId, 0, strpos($videoId, '&') ?: strlen($videoId));
                                    } elseif (str_contains($movie->trailer_url, 'youtu.be/')) {
                                        $videoId = substr($movie->trailer_url, strrpos($movie->trailer_url, '/') + 1);
                                    }
                                @endphp
                                @if($videoId)
                                    <iframe src="https://www.youtube.com/embed/{{ $videoId }}?autoplay=1" allowfullscreen style="border-radius:16px;box-shadow:0 8px 32px rgba(0,0,0,0.3);"></iframe>
                                @else
                                    <video controls autoplay style="border-radius:16px;box-shadow:0 8px 32px rgba(0,0,0,0.3);">
                                        <source src="{{ $movie->trailer_url }}" type="video/mp4">
                                        Trình duyệt không hỗ trợ video.
                                    </video>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Rating Modal -->
    @if($showRatingModal)
        <div class="modal-overlay" wire:click="closeRatingModal">
            <div class="modal-content rating-modal" wire:click.stop>
                <div class="modal-header">
                    <h3>Đánh giá phim - {{ $movie->title }}</h3>
                    <button class="modal-close" wire:click="closeRatingModal">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="submitRating">
                        <div class="rating-input">
                            <label>Đánh giá của bạn:</label>
                            <div class="star-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button"
                                            class="star-btn @if($i <= $userRating) active @endif"
                                            wire:click="setRating({{ $i }})">
                                        ★
                                    </button>
                                @endfor
                            </div>
                        </div>
                        <div class="review-input">
                            <label for="review">Nhận xét (tùy chọn):</label>
                            <textarea id="review" wire:model="userReview" placeholder="Chia sẻ cảm nhận của bạn về bộ phim..."></textarea>
                        </div>
                        <div class="modal-actions">
                            <button type="button" class="btn btn-secondary" wire:click="closeRatingModal">Hủy</button>
                            <button type="submit" class="btn btn-primary" @if($userRating == 0) disabled @endif>Gửi đánh giá</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
