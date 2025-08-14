@assets
    <link rel="stylesheet" href="{{ asset('client/assets/css/style.css') }}">
@endassets

<div class="scRender" wire:poll.5000ms="refreshSeatStatus">

    @if ($isBanned && $banInfo)
        <div class="container mt-5">
            <div class="row justify-content-center mt-5">
                <div class="col-md-12">
                    <div class="alert alert-danger shadow rounded-4 p-4 text-center mt-5 pt-5" role="alert"
                        style="background: url('https://static.vecteezy.com/system/resources/previews/048/724/727/non_2x/cyber-security-hacking-concept-attention-warning-attacker-alert-sign-and-computer-security-protection-notify-danger-technology-background-free-vector.jpg');  background-size: cover; color: white; height: 50vh;">
                        <div class="mb-3 fs-3 pt-4">
                            <i class="fa-solid fa-circle-exclamation fa-3x mb-2"></i>
                        </div>
                        <h4 class="alert-heading fw-bold mb-2 fs-3">{{ $banInfo['reason'] }}</h4>
                        <p class="mb-3 fs-3">{{ $banInfo['details'] }}</p>

                        @if (isset($banInfo['banned_until']) && $banInfo['banned_until'])
                            <div class="bg-dark bg-opacity-25 rounded-3 py-2 px-3 mb-3 d-inline-block">
                                <small class="d-block">
                                    <strong>Khóa đến:</strong>
                                    {{ \Carbon\Carbon::parse($banInfo['banned_until'])->format('d/m/Y H:i:s') }}
                                </small>
                                <small class="text-light-emphasis">
                                    (Còn {{ \Carbon\Carbon::parse($banInfo['banned_until'])->diffForHumans() }})
                                </small>
                            </div>
                        @endif

                        @if (isset($banInfo['violation_count']) && $banInfo['violation_count'])
                            <div class="bg-dark bg-opacity-25 rounded-3 py-2 px-3 mb-3 d-inline-block">
                                <small>
                                    <strong>Số lần vi phạm:</strong> {{ $banInfo['violation_count'] }} lần
                                </small>
                            </div>
                        @endif

                        <div class="mt-3">
                            <a href="{{ url('/') }}"
                                class="btn btn-light text-danger fw-semibold px-4 py-2 rounded-pill shadow-sm border-0 fs-3 p-3"
                                style="transition: 0.3s;">
                                <i class="fa-solid fa-house me-2"></i> Về trang chủ
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @else

    <div class="st_bt_top_header_wrapper float_left mb-5">
        <div class="container container_seat">
            <div class="row">
                <!-- Cột trái: Nút back -->
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="st_bt_top_back_btn st_bt_top_back_btn_seatl float_left">
                        <a href="{{ url('/') }}"><i class="fas fa-long-arrow-alt-left"></i> &nbsp;Back</a>
                    </div>
                </div>

                <!-- Cột giữa: Thông tin phim -->
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="st_bt_top_center_heading st_bt_top_center_heading_seat_book_page float_left">
                        <h3>{{ $showtime->movie->title ?? 'Tên phim' }}</h3>
                        <div class="d-flex gap-5">
                            <h4>Thời gian: {{ $showtime->start_time->format('d/m/Y H:i') }}</h4>
                            <h4>Phòng chiếu: {{ $room->name }}</h4>
                        </div>
                    </div>
                </div>

                <!-- Cột phải: Nút tiếp tục -->
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="st_seatlay_btn float_left">
                        <a wire:click="goToSelectFood" class="text-decoration-none w-100 {{ empty($selectedSeats) ? 'disabled' : '' }}">
                            Tiếp tục đặt đồ ăn
                        </a>
                    </div>
                </div>
            </div>
            <div id="seat-countdown" class="seat-countdown-container text-center mb-3" wire:ignore></div>
        </div>
    </div>

    <div id="user-seat-wrapper" wire:ignore></div>

    @endif
</div>
