@assets
    @vite('resources/css/showtimeIndex.css')
@endassets
<div class="scRender cinema-schedule container scShowtimeIndex" wire:poll="loadMoviesForDate">
    <!-- Header -->
    <div style="clear: both"></div>
    <div class="cinema-schedule__header text-center mb-4">
        <h1 class="cinema-schedule__main-title">
            <span class="cinema-schedule__main-title-dot">●</span>Phim đang chiếu
        </h1>
    </div>

    <!-- Date Tabs -->
    <div class="cinema-schedule__date-tabs mb-4">
        <ul class="cinema-schedule__date-tabs-list nav nav-tabs" role="tablist">
            @foreach($dates as $index => $date)
                <li class="cinema-schedule__date-tab-item nav-item">
                    <button class="cinema-schedule__date-tab nav-link @if($activeDateTab === $index) cinema-schedule__date-tab--active bg-light text-dark @else text-light @endif"
                            wire:click="selectDate('{{ $date->format('Y-m-d') }}', {{ $index }})"
                            style="border-top-left-radius: 0; border-top-right-radius: 0;">
                        <i class="cinema-schedule__date-tab-icon fas fa-calendar me-1"></i>{{ $date->format('d-m-Y') }}
                    </button>
                </li>
            @endforeach
        </ul>
    </div>

    <!-- Age Restriction Notice -->
    <div class="cinema-schedule__age-notice mb-4">
        <span class="cinema-schedule__age-notice-label">Lưu ý:</span>
        <span>Khán giả dưới 13 tuổi chỉ chọn suất chiếu kết thúc trước 22h và Khán giả dưới 16 tuổi chỉ chọn suất chiếu kết thúc trước 23h.</span>
    </div>

    <!-- Movies Grid -->
    <div class="cinema-schedule__movies-grid">
        @forelse($moviesWithShowtimes as $movie)
            <div class="cinema-schedule__movie-card cinema-schedule__animate-fadeInUp" style="display: flex; flex-direction: row; gap: 0; min-height: 320px;">
                <!-- Movie Poster (Left) -->
                <div class="cinema-schedule__movie-poster-wrapper" style="flex: 0 0 210px; height: 100%; border-radius: 1rem 0 0 1rem; overflow: hidden; position: relative;">
                    <img
                        src="{{ $movie->poster ? (Storage::exists('public/' . $movie->poster) ? $movie->poster : asset('storage/' . $movie->poster)) : asset('storage/404.webp') }}"
                        class="cinema-schedule__movie-poster"
                        alt="{{ $movie->title }}"
                        style="height: 100%; object-fit: cover; border-radius: 1rem 0 0 1rem;"
                    >
                    @if($movie->age_restriction)
                        <span class="cinema-schedule__badge-age cinema-schedule__badge-age--{{ strtoupper($movie->age_restriction) }}" style="top: 16px; left: 16px;">{{ $movie->age_restriction }}</span>
                    @endif
                </div>
                <!-- Movie Info (Right) -->
                <div class="cinema-schedule__movie-info" style="flex: 1 1 0; padding: 2rem 2rem 1em 2rem; display: flex; flex-direction: column; position: relative;">
                    <!-- Format Badge moved to top right of info section -->
                    <span class="cinema-schedule__format-badge" style="position: absolute; top: 10px; right: 10px;">{{ $movie->format ?? '2D' }}</span>
                    <div>
                        <div class="cinema-schedule__movie-category">
                            <span class="cinema-schedule__category-badge">{{ $movie->genres->first()->name ?? 'Phim' }}</span>
                            <span class="cinema-schedule__duration-badge">{{ $movie->duration }} phút</span>
                        </div>
                        <h3 class="cinema-schedule__movie-title" style="font-size: 1.25rem; font-weight: 700; color: #fff; margin-bottom: 0.5rem;">{{ $movie->title }}</h3>
                        <div class="cinema-schedule__movie-details">
                            <div class="cinema-schedule__movie-detail-item mb-3">
                                <span class="cinema-schedule__detail-label">Khởi chiếu:</span>
                                <span class="cinema-schedule__detail-value">{{ $movie->release_date->format('d/m/Y') }}</span>
                            </div>
                            <div class="cinema-schedule__movie-detail-item mb-3">
                                <span class="cinema-schedule__detail-label">Tên phim:</span>
                                <span class="cinema-schedule__detail-value">{{ $movie->title }}</span>
                            </div>
                            <div class="cinema-schedule__movie-detail-item mb-3">
                                <span class="cinema-schedule__detail-label">Đạo diễn:</span>
                                <span class="cinema-schedule__detail-value">{{ $movie->directors }}</span>
                            </div>
                            <div class="cinema-schedule__movie-detail-item mb-3">
                                <span class="cinema-schedule__detail-label">Diễn viên:</span>
                                <span class="cinema-schedule__detail-value">{{ $movie->actors }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="cinema-schedule__showtimes-section">
                        <h4 class="cinema-schedule__showtimes-title" style="margin-bottom: 0.5rem;">Lịch chiếu</h4>
                        <div class="cinema-schedule__showtimes-grid">
                            @forelse($movie->showtimes as $showtime)
                                @php
                                    $isPassed = $showtime->start_time->lte(now());
                                    $hasNoSeats = !isset($showtime->available_seats) || $showtime->available_seats <= 0;
                                    $isDisabled = $isPassed || $hasNoSeats;
                                @endphp
                                <div class="cinema-schedule__showtime-item">
                                    <button
                                        wire:click="bookShowtime({{ $showtime->id }})"
                                        class="cinema-schedule__showtime-btn @if($isDisabled) cinema-schedule__showtime-btn--disabled @endif"
                                        @if($isDisabled) disabled @endif
                                        title="@if($isPassed) Suất chiếu đã qua @elseif($hasNoSeats) Hết vé @else Còn {{ $showtime->available_seats }} ghế @endif"
                                    >
                                        {{ $showtime->start_time->format('H:i') }}
                                    </button>
                                    <div class="cinema-schedule__seats-info">
                                        @if($isPassed)
                                            <span class="text-muted">Đã chiếu</span>
                                        @elseif($hasNoSeats)
                                            <span class="text-danger">Hết vé</span>
                                        @else
                                            {{ $showtime->available_seats }} ghế trống
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-muted">
                                    <em>Không có suất chiếu hợp lệ</em>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="cinema-schedule__no-movies-message">
                <div class="cinema-schedule__no-movies-icon">
                    <i class="fa fa-film"></i>
                </div>
                <h3 class="cinema-schedule__no-movies-message-title">Không có suất chiếu nào</h3>
                <p class="cinema-schedule__no-movies-message-desc">Hiện tại không có suất chiếu nào cho ngày {{ Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }}</p>
                <p class="cinema-schedule__no-movies-message-desc">Vui lòng chọn ngày khác hoặc quay lại sau.</p>
            </div>
        @endforelse
    </div>
</div>
