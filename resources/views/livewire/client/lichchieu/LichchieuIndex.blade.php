<div class="scRender dark-cinema-schedule-wrapper container">
    <!-- Header -->
    <div class="dark-schedule-header text-center mb-4">
        <h1 class="dark-schedule-main-title">
            <span class="red-dot">●</span>Phim đang chiếu
        </h1>
    </div>

    <!-- Date Tabs -->
    <div class="dark-date-tabs-wrapper mb-4">
        <ul class="nav nav-tabs" role="tablist">
            @foreach($dates as $index => $date)
                <li class="nav-item">
                    <button class="nav-link @if($activeDateTab === $index) active bg-light text-dark @else text-light @endif"
                            wire:click="selectDate('{{ $date->format('Y-m-d') }}', {{ $index }})"
                            style="border-top-left-radius: 0; border-top-right-radius: 0;">
                        <i class="fas fa-calendar me-1"></i>{{ $date->format('d-m-Y') }}
                    </button>
                </li>
            @endforeach
        </ul>
    </div>

    <!-- Age Restriction Notice -->
    <div class="dark-age-restriction-notice mb-4">
        <span class="restriction-label">Lưu ý:</span>
        <span>Khán giả dưới 13 tuổi chỉ chọn suất chiếu kết thúc trước 22h và Khán giả dưới 16 tuổi chỉ chọn suất chiếu kết thúc trước 23h.</span>
    </div>

    <!-- Movies Grid -->
    <div class="dark-movies-schedule-grid">
        @forelse($moviesWithShowtimes as $movie)
            <div class="dark-movie-schedule-card animate__animated animate__fadeInUp" style="display: flex; flex-direction: row; gap: 0; min-height: 320px;">
                <!-- Movie Poster (Left) -->
                <div class="dark-movie-poster-wrapper" style="flex: 0 0 210px; height: 320px; border-radius: 1rem 0 0 1rem; overflow: hidden; position: relative;">
                    <img
                        src="{{ $movie->poster ? (Storage::exists('public/' . $movie->poster) ? $movie->poster : asset('storage/' . $movie->poster)) : asset('storage/404.webp') }}"
                        class="dark-movie-poster"
                        alt="{{ $movie->title }}"
                        style="height: 100%; object-fit: cover; border-radius: 1rem 0 0 1rem;"
                    >
                    @if($movie->age_restriction)
                        <span class="dark-badge-age dark-badge-age-{{ strtoupper($movie->age_restriction) }}" style="top: 16px; left: 16px;">{{ $movie->age_restriction }}</span>
                    @endif
                </div>
                <!-- Movie Info (Right) -->
                <div class="dark-movie-info" style="flex: 1 1 0; padding: 2rem 2rem 2rem 2rem; display: flex; flex-direction: column; justify-content: space-around; position: relative;">
                    <!-- Format Badge moved to top right of info section -->
                    <span class="dark-format-badge" style="position: absolute; top: 10px; right: 10px;">{{ $movie->format ?? '2D' }}</span>
                    <div>
                        <div class="dark-movie-category">
                            <span class="dark-category-badge">{{ $movie->genres->first()->name ?? 'Phim' }}</span>
                            <span class="dark-duration-badge">{{ $movie->duration }} phút</span>
                        </div>
                        <h3 class="dark-movie-title" style="font-size: 1.25rem; font-weight: 700; color: #fff; margin-bottom: 0.5rem;">{{ $movie->title }}</h3>
                        <div class="dark-movie-details">

                            <div class="dark-movie-detail-item mb-3">
                                <span class="detail-label">Khởi chiếu:</span>
                                <span class="detail-value">{{ $movie->release_date->format('d/m/Y') }}</span>
                            </div>

                            @if($movie->age_restriction)
                                <div class="dark-movie-detail-item dark-age-restriction-info">
                                    <span class="detail-label">{{ $movie->age_restriction }}:</span>
                                    <span class="detail-value">
                                        @switch($movie->age_restriction)
                                            @case('P')
                                                Phim được phổ biến đến người xem ở mọi độ tuổi
                                                @break
                                            @case('K')
                                                Phim được phổ biến đến người xem dưới 13 tuổi và có người bảo hộ đi kèm
                                                @break
                                            @case('T13')
                                                Phim được phổ biến đến người xem từ đủ 13 tuổi trở lên (13+)
                                                @break
                                            @case('T16')
                                                Phim được phổ biến đến người xem từ đủ 16 tuổi trở lên (16+)
                                                @break
                                            @case('T18')
                                                Phim được phổ biến đến người xem từ đủ 18 tuổi trở lên (18+)
                                                @break
                                            @case('C')
                                                Phim cấm chiếu
                                                @break
                                        @endswitch
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="dark-showtimes-section">
                        <h4 class="dark-showtimes-title" style="margin-bottom: 0.5rem;">Lịch chiếu</h4>
                        <div class="dark-showtimes-grid">
                            @foreach($movie->showtimes as $showtime)
                                <div class="dark-showtime-item">
                                    <button
                                        {{-- wire:click="bookShowtime({{ $showtime->id }})" --}}
                                        class="dark-showtime-btn"
                                        @if($showtime->start_time->lt(now())) disabled @endif
                                    >
                                        {{ $showtime->start_time->format('H:i') }}
                                    </button>
                                    <div class="dark-seats-info">
                                        {{ $showtime->available_seats ?? 0 }} ghế trống
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="dark-no-movies-message">
                <div class="dark-no-movies-icon">
                    <i class="fa fa-film"></i>
                </div>
                <h3>Không có suất chiếu nào</h3>
                <p>Hiện tại không có suất chiếu nào cho ngày {{ Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }}</p>
                <p>Vui lòng chọn ngày khác hoặc quay lại sau.</p>
            </div>
        @endforelse
    </div>
</div>
