@assets
    @vite('resources/css/movieDetail.css')
    <style>
        .booking-info {
            text-align: center;
            margin-bottom: 24px;
        }

        .movie-title-booking {
            font-size: 1.5rem;
            font-weight: bold;
            color: #0d47a1;
            margin-bottom: 20px;
        }

        .showtime-details {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 500;
            color: #666;
        }

        .detail-value {
            font-weight: bold;
            color: #333;
        }

        .modal-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 24px;
        }

        .btn-primary {
            display: flex;
            align-items: center;
            background: #0d47a1;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-primary:hover {
            background: #0a3d8a;
        }

        .btn-secondary {
            background: #f8f9fa;
            color: #666;
            border: 1px solid #ddd;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-secondary:hover {
            background: #e9ecef;
        }
    </style>
@endassets
<div class="scRender scMovieDetail" wire:poll.6s>
<div class="movie-detail-page ">
    <div class="movie-container">
        <!-- Movie Header -->
        <div class="movie-header-block">
            <div class="movie-header-bg" style="background-image: url('{{ asset('storage/' . $movie->poster) }}');"></div>
            <div class="movie-header">
                <div class="movie-poster">
                    <div style="position:relative; width: 100%; height: 100%;">
                        @if($movie->age_restriction)
                            <div class="age-restriction age-restriction-{{ strtoupper($movie->age_restriction) }}">{{ $movie->age_restriction }}</div>
                        @endif
                    <img src="{{ asset('storage/' . $movie->poster) }}" alt="Ảnh poster hiện tại">
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
                        <button class="btn btn-buy" wire:click="setTab('showtimes')">
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
                            @auth
                                <form wire:submit.prevent="submitComment" class="comment-form-row">
                                    <textarea wire:model.defer="newComment" rows="1" class="form-control comment-input" placeholder="Nhập bình luận của bạn..." style="resize: none;"></textarea>
                                    <button type="submit" class="btn btn-primary comment-submit-btn">Gửi bình luận</button>
                                </form>
                                @error('newComment') <div class="text-danger">{{ $message }}</div> @enderror
                            @else
                                <div class="no-data">Bạn cần <a href="{{ route('login') }}">đăng nhập</a> để bình luận.</div>
                            @endauth
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
                                    <div class="comment-row">
                                        <div class="comment-avatar-col">
                                        @if(!empty($comment->user->avatar_url))
                                            <img class="comment-avatar" src="{{ $comment->user->avatar_url }}" alt="">
                                        @else
                                            <div class="comment-avatar">
                                                <svg viewBox="0 0 24 24" width="100%" height="100%" fill="#bbb" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="8" r="5"/><path d="M4 20c0-4 4-7 8-7s8 3 8 7"/></svg>
                                            </div>
                                        @endif
                                        </div>
                                        <div class="comment-main-col">
                                            <div class="comment-user-row">
                                            <span class="comment-user">{{ $comment->user->name ?? 'Ẩn danh' }}</span>
                                            @if(str_contains(strtolower($comment->user->name ?? ''), 'love'))
                                                <span class="comment-user-icon">💕</span>
                                            @endif
                                        </div>
                                            @php
                                                $commentWords = str_word_count(strip_tags($comment->content));
                                                $showFull = $showMoreComments[$comment->id] ?? false;
                                            @endphp
                                    @if($editingComment === $comment->id)
                                                <form wire:submit.prevent="updateComment" style="margin-top:12px;">
                                            <textarea wire:model.defer="editCommentContent" rows="2" class="form-control comment-input" placeholder="Sửa bình luận..." style="resize: none;"></textarea>
                                            @error('editCommentContent') <div class="text-danger">{{ $message }}</div> @enderror
                                            <div style="margin-top:8px;">
                                                <button type="submit" class="btn-edit" style="background:none;border:none;color:#888;font-size:1rem;padding:0 12px 0 0;cursor:pointer;">Cập nhật</button>
                                                <button type="button" class="btn-delete" style="background:none;border:none;color:#888;font-size:1rem;padding:0;cursor:pointer;" wire:click="cancelEditComment">Hủy</button>
                                            </div>
                                        </form>
                                    @else
                                                <div class="comment-content">
                                                    @if($commentWords > 50 && !$showFull)
                                                        {{ \Illuminate\Support\Str::words(strip_tags($comment->content), 50, '...') }}
                                                    @else
                                                        {!! nl2br(e($comment->content)) !!}
                                                    @endif
                                                </div>
                                                @if($commentWords > 50)
                                                    <button class="btn btn-link" style="padding:0;" wire:click="{{ $showFull ? 'showLess' : 'showMore' }}({{ $comment->id }})">
                                                        {{ $showFull ? 'Ẩn bớt' : 'Xem thêm' }}
                                                    </button>
                                                @endif
                                    @endif
                                    <div class="comment-footer">
                                                <span class="comment-date">{{ $comment->created_at->format('d/m/Y') }}</span>
                                        @if($editingComment !== $comment->id && $replyingTo !== $comment->id)
                                            <button class="btn-reply" wire:click="startReply({{ $comment->id }})">Trả lời</button>
                                            @if(Auth::check() && Auth::user()->id == $comment->user_id)
                                                <button class="btn-edit" wire:click="startEditComment({{ $comment->id }})">Sửa</button>
                                            @endif
                                            @if(Auth::check() && (Auth::user()->id == $comment->user_id || Auth::user()->role == 'admin'))
                                                <button class="btn-delete" wire:click="confirmDelete({{ $comment->id }}, 'comment')">Xóa</button>
                                            @endif
                                        @endif
                                    </div>
                                    @if($replyingTo === $comment->id)
                                                <form wire:submit.prevent="submitReply" style="margin-top: 12px;">
                                            <textarea wire:model.defer="replyContent" rows="2" class="form-control comment-input" placeholder="Nhập phản hồi..." style="resize: none;"></textarea>
                                            @error('replyContent') <div class="text-danger">{{ $message }}</div> @enderror
                                            <div style="margin-top: 8px;">
                                                <button type="submit" class="btn-edit" style="background:none;border:none;color:#888;font-size:1rem;padding:0 12px 0 0;cursor:pointer;">Gửi phản hồi</button>
                                                <button type="button" class="btn-delete" style="background:none;border:none;color:#888;font-size:1rem;padding:0;cursor:pointer;" wire:click="cancelReply">Hủy</button>
                                            </div>
                                        </form>
                                    @endif
                                    @php
                                        $replies = $comment->replies;
                                        $replyCount = $replies->count();
                                        $visibleCount = $visibleReplyCount[$comment->id] ?? 3;
                                        $visibleReplies = $replies->take($visibleCount);
                                        $remaining = $replyCount - $visibleCount;
                                    @endphp
                                    @if($replyCount > 0)
                                        <div class="reply-list">
                                            @foreach($visibleReplies as $reply)
                                                <div class="reply-item">
                                                            <div class="comment-row">
                                                                <div class="comment-avatar-col">
                                                        @if(!empty($reply->user->avatar_url))
                                                            <img class="comment-avatar" src="{{ $reply->user->avatar_url }}" alt="">
                                                        @else
                                                            <div class="comment-avatar">
                                                                <svg viewBox="0 0 24 24" width="100%" height="100%" fill="#bbb" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="8" r="5"/><path d="M4 20c0-4 4-7 8-7s8 3 8 7"/></svg>
                                                            </div>
                                                        @endif
                                                                </div>
                                                                <div class="comment-main-col">
                                                                    <div class="comment-user-row">
                                                            <span class="comment-user">{{ $reply->user->name ?? 'Ẩn danh' }}</span>
                                                    </div>
                                                    @if($editingComment === $reply->id)
                                                                        <form wire:submit.prevent="updateComment" style="margin-top:12px;">
                                                            <textarea wire:model.defer="editCommentContent" rows="2" class="form-control comment-input" placeholder="Sửa phản hồi..." style="resize: none;"></textarea>
                                                            @error('editCommentContent') <div class="text-danger">{{ $message }}</div> @enderror
                                                            <div style="margin-top:8px;">
                                                                <button type="submit" class="btn-edit" style="background:none;border:none;color:#888;font-size:1rem;padding:0 12px 0 0;cursor:pointer;">Cập nhật</button>
                                                                <button type="button" class="btn-delete" style="background:none;border:none;color:#888;font-size:1rem;padding:0;cursor:pointer;" wire:click="cancelEditComment">Hủy</button>
                                                            </div>
                                                        </form>
                                                    @else
                                                        <div class="comment-content">{!! nl2br(e($reply->content)) !!}</div>
                                                    @endif
                                                    <div class="comment-footer">
                                                                        <span class="comment-date">{{ $reply->created_at->format('d/m/Y') }}</span>
                                                        @if($editingComment !== $reply->id && $replyingTo !== $comment->id)
                                                            <button class="btn-reply" wire:click="startReply({{ $comment->id }})">Trả lời</button>
                                                            @if(Auth::check() && Auth::user()->id == $reply->user_id)
                                                                <button class="btn-edit" wire:click="startEditComment({{ $reply->id }})">Sửa</button>
                                                            @endif
                                                            @if(Auth::check() && (Auth::user()->id == $reply->user_id || Auth::user()->role == 'admin'))
                                                                <button class="btn-delete" wire:click="confirmDelete({{ $reply->id }}, 'comment')">Xóa</button>
                                                            @endif
                                                        @endif
                                                                    </div>
                                                                </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                            @if($remaining > 0 || $visibleCount > 3)
                                                <div style="text-align:left; margin-top: 8px; display: flex; align-items: center; gap: 16px;">
                                                    @if($remaining > 0)
                                                        <button class="btn btn-link text-primary" style="text-decoration:none;padding:0;color:#888;" wire:click="showMoreReplies({{ $comment->id }})">
                                                            Xem thêm {{ $remaining }} câu trả lời <span style="font-size:1.2em;">&#9660;</span>
                                                        </button>
                                                    @endif
                                                    @if($visibleCount > 3)
                                                        <button class="btn btn-link text-primary" style="text-decoration:none;padding:0;color:#888;" wire:click="hideReplies({{ $comment->id }})">
                                                            Ẩn <span style="font-size:1.2em;">&#9650;</span>
                                                        </button>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                        </div>
                                    </div>
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
                        <div class="date-selector" wire:loading.class="loading">
                            @if($showtimesByDay->count() > 0)
                                <div class="d-flex flex-row gap-4 justify-content-start align-items-center" style="margin-bottom: 24px;">
                                @foreach($showtimesByDay as $date => $showtimes)
                                    @php
                                        $carbonDate = \Carbon\Carbon::parse($date);
                                        $isToday = $carbonDate->isToday();
                                        $weekday = $isToday ? 'Hôm Nay' : $carbonDate->isoFormat('dddd');
                                        $dayMonth = $carbonDate->format('d/m');
                                    @endphp
                                    <button
                                        wire:click="selectDate('{{ $date }}')"
                                        class="date-btn-custom btn d-flex flex-column justify-content-center align-items-center px-4 py-3 @if($selectedDate == $date) active @endif @if($isToday) today @endif"
                                        style="min-width: 110px; border-radius: 10px; border: none; font-weight: 500; font-size: 1.1rem;"
                                        wire:loading.attr="disabled"
                                    >
                                        <span class="date-btn-weekday" style="font-size:1.1rem; font-weight:600; @if($selectedDate == $date) color:#fff; @elseif($isToday) color:#0d47a1; @else color:#444; @endif">{{ $weekday }}</span>
                                        <span class="date-btn-day" style="font-size:1.1rem; font-weight:400; @if($selectedDate == $date) color:#fff; @elseif($isToday) color:#0d47a1; @else color:#888; @endif">{{ $dayMonth }}</span>
                                    </button>
                                @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <p class="text-muted">Không có suất chiếu nào trong tương lai</p>
                                </div>
                            @endif
                        </div>

                        <!-- Loading indicator -->
                        <div wire:loading wire:target="selectDate" class="loading-indicator">
                            <div class="spinner"></div>
                            <span>Đang tải lịch chiếu...</span>
                        </div>

                        <!-- Cinema List -->
                        <div wire:loading.remove wire:target="selectDate" class="cinema-list">
                            @if($showtimesByDay->count() > 0)
                                @php
                                    $showtimesForDate = $showtimesByDay[$selectedDate] ?? collect();
                                    $formatGroups = $showtimesForDate->groupBy(function($showtime) {
                                        return $showtime->format ?? '2D Phụ Đề Anh';
                                    });
                                @endphp

                                @forelse($formatGroups as $formatName => $formatShowtimes)
                                    <div class="cinema-chain">
                                        <div class="cinema-chain-title">{{ $formatName }}</div>
                                        <div class="showtime-grid">
                                            @foreach($formatShowtimes as $showtime)
                                                @php
                                                    $isPassed = $showtime->start_time->lte(now());
                                                    $hasNoSeats = !isset($showtime->available_seats) || $showtime->available_seats <= 0;
                                                    $isDisabled = $isPassed || $hasNoSeats;
                                                @endphp
                                                <div class="showtime-card text-center">
                                                    @if($isDisabled)
                                                        <button style="width:100%;display:block;background: #f8f9fa; color: #6c757d; cursor: not-allowed;" class="btn default" disabled>
                                                            {{ $showtime->start_time->format('H:i') }}
                                                        </button>
                                                    @else
                                                        <button style="width:100%;display:block;background: #e4e4e4;" wire:click="bookShowtime({{ $showtime->id }})" class="btn default">
                                                            {{ $showtime->start_time->format('H:i') }}
                                                        </button>
                                                    @endif
                                                    <div class="font-smaller padding-top-5">
                                                        @if($isPassed)
                                                            <span class="text-danger">Đã chiếu</span>
                                                        @elseif($hasNoSeats)
                                                            <span class="text-danger">Hết vé</span>
                                                        @else
                                                            <span class="text-success">{{ $showtime->available_seats }} ghế trống</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @empty
                                    <div class="no-showtimes">
                                        <div class="no-showtimes-icon">🎬</div>
                                        <h3>Không có suất chiếu nào</h3>
                                        <p>Không có suất chiếu nào cho ngày này. Vui lòng chọn ngày khác.</p>
                                    </div>
                                @endforelse
                            @else
                                <div class="no-showtimes">
                                    <div class="no-showtimes-icon">🎬</div>
                                    <h3>Không có suất chiếu nào</h3>
                                    <p>Không có suất chiếu nào trong tương lai cho phim này.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                @elseif($tab === 'ratings')
                    <!-- Ratings Section -->
                    <div class="ratings-section">
                        @auth
                            <!-- Form đánh giá ở đây -->
                        @else
                            <div class="no-data">Bạn cần <a href="{{ route('login') }}">đăng nhập</a> để đánh giá phim.</div>
                        @endauth
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
                                    <div class="rating-item rating-box">
                                        <div class="rating-box-header">
                                            <div class="rating-box-avatar">
                                                @if(!empty($rating->user->avatar_url))
                                                    <img class="comment-avatar" src="{{ $rating->user->avatar_url }}" alt="">
                                                @else
                                                    <div class="comment-avatar">
                                                        <svg viewBox="0 0 24 24" width="100%" height="100%" fill="#bbb" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="8" r="5"/><path d="M4 20c0-4 4-7 8-7s8 3 8 7"/></svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="rating-box-user">
                                                <span class="rating-user">{{ $rating->user->name ?? 'Ẩn danh' }}</span>
                                                <span class="rating-date">{{ $rating->created_at->diffForHumans() }}</span>
                                            </div>
                                            <div class="rating-box-actions">
                                                @if(Auth::check() && Auth::user()->id == $rating->user_id && !$rating->deleted_at)
                                                    <button class="btn btn-link text-primary" style="color: #1677ff" wire:click="editRating({{ $rating->id }})">
                                                        <i class="fas fa-edit"></i> Sửa
                                                    </button>
                                                @endif
                                                @if(Auth::check() && Auth::user()->role == 'admin' && !$rating->deleted_at)
                                                    <button class="btn btn-link text-danger" style="color: #e74c3c" wire:click="executeDeleteImmediately({{ $rating->id }}, 'rating')">Xóa</button>
                                                @endif
                                                @if(Auth::check() && Auth::user()->role == 'admin' && $rating->deleted_at)
                                                    <button class="btn btn-link text-primary" style="color: #1677ff" wire:click="restoreRating({{ $rating->id }})">Khôi phục</button>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="rating-box-stars">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span class="star @if($i <= $rating->score) @else empty @endif">★</span>
                                            @endfor
                                            <span class="rating-score">{{ $rating->score }}/5</span>
                                        </div>
                                        @if($rating->deleted_at)
                                            <div class="rating-review"><em>Nội dung đánh giá đã bị xóa</em></div>
                                        @elseif($rating->review)
                                            @php
                                                $reviewWords = str_word_count(strip_tags($rating->review));
                                                $showFullRating = $showMoreRatings[$rating->id] ?? false;
                                            @endphp
                                            <div class="rating-review">
                                                @if($reviewWords > 50 && !$showFullRating)
                                                    {{ \Illuminate\Support\Str::words(strip_tags($rating->review), 50, '...') }}
                                                @else
                                                    {{ $rating->review }}
                                                @endif
                                            </div>
                                            @if($reviewWords > 50)
                                                <button class="btn btn-link" style="padding:0;" wire:click="{{ $showFullRating ? 'showLessRating' : 'showMoreRating' }}({{ $rating->id }})">
                                                    {{ $showFullRating ? 'Ẩn bớt' : 'Xem thêm' }}
                                                </button>
                                            @endif
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

    @if($showLoginPrompt ?? false)
        <div class="modal-overlay">
            <div class="modal-content" style="max-width:400px;">
                <div class="modal-header">
                    <h3>Yêu cầu đăng nhập</h3>
                </div>
                <div class="modal-body">
                    <p>Bạn cần đăng nhập để đánh giá phim.</p>
                    <div class="modal-actions">
                        <button class="btn btn-secondary" wire:click="closeLoginPrompt">Hủy</button>
                        <button class="btn btn-primary" onclick="window.location.href='{{ route('login') }}'">Đăng nhập</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($showUpdateRatingConfirm ?? false)
        <div class="modal-overlay">
            <div class="modal-content rating-modal" style="max-width: 400px;">
                <div class="modal-header">
                    <h3>Xác nhận sửa đánh giá</h3>
                </div>
                <div class="modal-body">
                    <p>Bạn đã từng đánh giá phim này. Nếu tiếp tục, đánh giá cũ của bạn sẽ được cập nhật.<br>Bạn có muốn tiếp tục không?</p>
                    <div class="modal-actions">
                        <button class="btn btn-secondary" wire:click="cancelUpdateRating">Hủy</button>
                        <button class="btn btn-primary" wire:click="confirmUpdateRating">Đồng ý</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal xác nhận xóa --}}
    @if($showDeleteConfirm && $deleteType === 'comment')
        <div class="modal-overlay">
            <div class="modal-content" style="max-width:400px;">
                <div class="modal-header">
                    <h3>Xác nhận xóa</h3>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xóa bình luận này không?</p>
                    <div class="modal-actions">
                        <button class="btn btn-secondary" wire:click="cancelDelete">Hủy</button>
                        <button class="btn btn-danger" wire:click="executeDelete">Xóa</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal sửa đánh giá --}}
    @if($showEditRatingModal ?? false)
        <div class="modal-overlay" wire:click="closeEditRatingModal">
            <div class="modal-content rating-modal" wire:click.stop>
                <div class="modal-header">
                    <h3>Sửa đánh giá - {{ $movie->title }}</h3>
                    <button class="modal-close" wire:click="closeEditRatingModal">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="updateRating">
                        <div class="rating-input">
                            <label>Đánh giá của bạn:</label>
                            <div class="star-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button"
                                            class="star-btn @if($i <= $editUserRating) active @endif"
                                            wire:click="setEditRating({{ $i }})">
                                        ★
                                    </button>
                                @endfor
                            </div>
                        </div>
                        <div class="review-input">
                            <label for="edit-review">Nhận xét (tùy chọn):</label>
                            <textarea id="edit-review" wire:model="editUserReview" placeholder="Chia sẻ cảm nhận của bạn về bộ phim..."></textarea>
                        </div>
                        <div class="modal-actions">
                            <button type="button" class="btn btn-secondary" wire:click="closeEditRatingModal">Hủy</button>
                            <button type="submit" class="btn btn-primary" @if($editUserRating == 0) disabled @endif>Cập nhật đánh giá</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal xác nhận đặt vé --}}
    @if($showBookingConfirmModal && $selectedShowtime)
        <div class="modal-overlay" wire:click="closeBookingConfirmModal">
            <div class="modal-content" style="max-width: 500px;" wire:click.stop>
                <div class="modal-header">
                    <h3>BẠN ĐANG ĐẶT VÉ XEM PHIM</h3>
                    <button class="modal-close" wire:click="closeBookingConfirmModal">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="booking-info">
                        <div class="movie-title-booking">{{ $movie->title }}</div>

                        <div class="showtime-details">
                            <div class="detail-item">
                                <div class="detail-label">Rạp chiếu</div>
                                <div class="detail-value">{{ $selectedShowtime->room->cinema->name ?? 'Beta Thái Nguyên' }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Ngày chiếu</div>
                                <div class="detail-value">{{ $selectedShowtime->start_time->format('d/m/Y') }}</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Giờ chiếu</div>
                                <div class="detail-value">{{ $selectedShowtime->start_time->format('H:i') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-actions">
                        <button type="button" class="btn btn-secondary" wire:click="closeBookingConfirmModal">Hủy</button>
                        <button type="button" class="btn btn-primary" wire:click="confirmBooking">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 8px;">
                                <path d="M3 7V17C3 18.1046 3.89543 19 5 19H19C20.1046 19 21 18.1046 21 17V7C21 5.89543 20.1046 5 19 5H5C3.89543 5 3 5.89543 3 7Z" stroke="currentColor" stroke-width="2"/>
                                <path d="M8 5V3C8 2.44772 8.44772 2 9 2H15C15.5523 2 16 2.44772 16 3V5" stroke="currentColor" stroke-width="2"/>
                                <path d="M8 12H16" stroke="currentColor" stroke-width="2"/>
                                <path d="M8 15H12" stroke="currentColor" stroke-width="2"/>
                            </svg>
                            ĐỒNG Ý
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const textarea = document.querySelector('.comment-input');
  if (textarea) {
    textarea.addEventListener('input', function() {
      this.style.height = '38px';
      this.style.height = (this.scrollHeight) + 'px';
    });
  }
});
document.addEventListener('livewire:init', () => {
    Livewire.on('scrollToTabContent', () => {
        const tabContent = document.querySelector('.tab-content-moveek');
        if (tabContent) {
            tabContent.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});
</script>
