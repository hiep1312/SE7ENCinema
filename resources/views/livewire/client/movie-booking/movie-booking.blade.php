@assets
    @vite('resources/css/movieBooking.css')
@endassets
<div class="scRender scMovieBooking" wire:poll.6s>
    <!-- prs title wrapper Start -->
    <div style="clear: both"></div>
    <div class="prs_title_main_sec_wrapper"
        style="background-image: url('{{ $movie ? asset('storage/' . $movie->poster) : asset('client/assets/images/index_III/icon.png') }}');">
        <div class="prs_title_img_overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="prs_title_heading_wrapper">
                       <div class="prs_title_heading_wrapper_left title__Booking">
                            <h2>{{ $movie ? $movie->title : 'Movie Booking' }}</h2>
                       </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- prs title wrapper End -->
    <!-- prs video top Start -->
    <div class="prs_booking_main_div_section_wrapper">
        <div class="prs_top_video_section_wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="st_video_slider_inner_wrapper float_left"
                            style="background-image: url('{{ $movie ? asset('storage/' . $movie->poster) : asset('client/assets/images/index_III/icon.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat; position: relative;">
                            <div class="st_video_slider_overlay">
                                @if ($movie)
                                    <!-- Age Restriction Badge - Top Left -->
                                    @if ($movie->age_restriction)
                                        <div class="cinema-schedule__badge-age cinema-schedule__badge-age--{{ strtoupper($movie->age_restriction) }}"
                                            style="position: absolute; top: 20px; left: 20px; z-index: 10;">
                                            {{ $movie->age_restriction }}
                                        </div>
                                    @endif

                                    <!-- Format Badge - Top Right -->
                                    @if ($movie->format)
                                        <div class="cinema-schedule__format-badge"
                                            style="position: absolute; top: 20px; right: 20px; z-index: 10;">
                                            {{ $movie->format }}
                                        </div>
                                    @endif

                                    <!-- Center Content -->
                                    <div class="movie-booking-center-content">
                                        <!-- Play Button -->
                                        @if ($movie->trailer_url)
                                            <div class="play-button-center" wire:click="openTrailerModal">
                                                <img src="{{ asset('client/assets/images/index_III/icon.png') }}"
                                                    alt="play" class="play-icon-center">
                                            </div>
                                        @endif

                                        <!-- Movie Info -->
                                        <div class="movie-info-center">
                                            <h3 class="movie-title-center">{{ $movie->title }}</h3>
                                            <p class="movie-language-center">{{ $movie->language ?? 'Tiếng Việt' }}</p>
                                            <h4 class="movie-genres-center">
                                                @if ($movie->genres && $movie->genres->count())
                                                    {{ $movie->genres->pluck('name')->implode(' | ') }}
                                                @else
                                                    Phim
                                                @endif
                                            </h4>
                                            <h5 class="movie-formats-center">
                                                @if ($movie->format)
                                                    <span>{{ strtoupper($movie->format) }}</span>
                                                @endif
                                                @if ($movie->duration)
                                                    <span>{{ $movie->duration }} phút</span>
                                                @endif
                                            </h5>
                                        </div>
                                    </div>
                                @else
                                    <div class="movie-booking-center-content">
                                        <div class="play-button-center">
                                            <img src="{{ asset('client/assets/images/index_III/icon.png') }}"
                                                alt="play" class="play-icon-center">
                                        </div>
                                        <div class="movie-info-center">
                                            <h3 class="movie-title-center">Không có phim nào</h3>
                                            <p class="movie-language-center">Ngôn ngữ: Không có</p>
                                            <h4 class="movie-genres-center">Thể loại: Không có</h4>
                                            <h5 class="movie-formats-center">Không có</h5>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- prs video top End -->
        <!-- st slider rating wrapper Start -->
        <div class="st_slider_rating_main_wrapper float_left">
            <div class="container">
                <div class="st_calender_tabs">
                    <div class="date-tabs-container">
                        @foreach ($dates as $index => $date)
                            <div class="date-tab-item @if ($activeDateTab === $index) active @endif"
                                wire:click="selectDate('{{ $date->format('Y-m-d') }}', {{ $index }})">
                                <div class="date-tab-content">
                                    <span class="day-name">{{ ucfirst($date->translatedFormat('l')) }}</span>
                                    <span class="day-number">{{ $date->format('d') }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <!-- st slider rating wrapper End -->
        <!-- st slider sidebar wrapper Start -->
        <div class="st_slider_index_sidebar_main_wrapper st_slider_index_sidebar_main_wrapper_booking float_left">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="st_indx_slider_main_container float_left">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="tab-content">
                                        <div id="home" class="tab-pane active">
                                            <div class="st_calender_contant_main_wrapper float_left">
                                                @if ($showtimesForDate->count() > 0)
                                                    @foreach ($showtimesForDate->groupBy('room.name') as $roomName => $showtimes)
                                                        <div
                                                            class="st_calender_row_cont @if (!$loop->first) st_calender_row_cont2 @endif float_left">
                                                            <div class="st_calender_asc">
                                                                <div class="st_calen_asc_heart">
                                                                    <a href="#"><i class="fa-duotone fa-light fa-screencast"></i></a>
                                                                </div>
                                                                <div class="st_calen_asc_heart_cont">
                                                                    <div class="title">
                                                                        <h3>{{ $roomName ?? 'SE7ENCINEMA Hà Nội' }}
                                                                        </h3>
                                                                    </div>
                                                                    <ul style="list-style: none;">
                                                                        <li>
                                                                            <img src="{{ asset('client/assets/images/content/ticket.png') }}"
                                                                                alt="ticket">
                                                                        </li>
                                                                        <li>
                                                                            <img src="{{ asset('client/assets/images/content/bill.png') }}"
                                                                                alt="bill">
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <div class="st_calen_asc_tecket_time_select">
                                                                <ul>
                                                                    @foreach ($showtimes as $showtime)
                                                                        <li style="list-style: none;">
                                                                            <span>
                                                                                <h4>{{ number_format($showtime->price ?? 80000) }}
                                                                                    VNĐ</h4>
                                                                                <p class="asc_pera1">
                                                                                    {{ $showtime->room->name ?? 'Phòng chiếu' }}
                                                                                </p>
                                                                                <p
                                                                                    class="asc_pera2 @if ($showtime->available_seats <= 5) status-critical @elseif($showtime->available_seats <= 20) status-warning @else status-available @endif">
                                                                                    @if ($showtime->available_seats <= 5)
                                                                                        Sắp hết vé
                                                                                    @elseif($showtime->available_seats <= 20)
                                                                                        Còn ít vé
                                                                                    @else
                                                                                        Còn nhiều vé
                                                                                    @endif
                                                                                </p>
                                                                            </span>
                                                                            <a wire:click="bookShowtime({{ $showtime->id }})"
                                                                                style="cursor: pointer;">
                                                                                {{ $showtime->start_time->format('H:i') }}
                                                                            </a>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <!-- No Showtimes Section - Redesigned -->
                                                    <div class="no-showtimes-section">
                                                        <div class="no-showtimes-content">
                                                            <div class="no-showtimes-icon">
                                                                <i class="fa fa-calendar-times"></i>
                                                            </div>
                                                            <h3 class="no-showtimes-title">Không có suất chiếu</h3>
                                                            <p class="no-showtimes-desc">
                                                                Hiện tại không có suất chiếu nào cho ngày
                                                                <strong>{{ Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }}</strong>
                                                            </p>
                                                            <div class="no-showtimes-actions">
                                                                <p>Vui lòng chọn ngày khác hoặc quay lại sau.</p>
                                                                <a href="{{ route('client.index') }}"
                                                                    class="btn-back-home">
                                                                    <i class="fa fa-home"></i>
                                                                    Về trang chủ
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- st slider sidebar wrapper End -->
    </div>
    <!-- prs patner slider End -->
    <!-- prs Newsletter Wrapper Start -->
    {{-- <div class="prs_newsletter_wrapper">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                    <div class="prs_newsletter_text mt-2">
                        <h3>ĐĂNG KÝ NGAY CHẦN CHỪ CHI!!!</h3>
                    </div>
                </div>
                <div class="col-lg-7 col-md-7 col-sm-12 col-xs-12">
                    <div class="prs_newsletter_field">
                        <input type="text" placeholder="Enter Your Email">
                        <button type="submit">Đăng ký</button>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <!-- prs Newsletter Wrapper End -->

    <!-- Modal Trailer -->
    @if ($showTrailerModal && $movie && $movie->trailer_url)
        <div class="modal-overlay" wire:click="closeTrailerModal">
            <div class="video-container" wire:click.stop>
                <div class="video-header">
                    <div class="video-icon">
                        <i class="fa-brands fa-youtube"></i>
                    </div>
                    <div>
                        <h3 class="video-title">{{ $movie->title }} | Official Trailer</h3>
                    </div>
                </div>
                <div class="video-wrapper">
                    <div class="responsive-iframe">
                        @php
                            $videoId = '';
                            if (str_contains($movie->trailer_url, 'youtube.com/watch?v=')) {
                                $videoId = substr($movie->trailer_url, strpos($movie->trailer_url, 'v=') + 2);
                                $videoId = substr($videoId, 0, strpos($videoId, '&') ?: strlen($videoId));
                            } elseif (str_contains($movie->trailer_url, 'youtu.be/')) {
                                $videoId = substr($movie->trailer_url, strrpos($movie->trailer_url, '/') + 1);
                            }
                        @endphp
                        @if ($videoId)
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
        </div>
    @endif
</div>

@script
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('closeTrailerModal', () => {
                // Có thể thêm logic bổ sung nếu cần
            });
        });
    </script>
@endscript
