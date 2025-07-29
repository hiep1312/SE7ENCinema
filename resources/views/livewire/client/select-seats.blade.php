@assets
<link rel="stylesheet" href="{{ asset('client/assets/css/style.css') }}">
@endassets

<div class="scRender">
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
                        <a wire:click="goToSelectFood">Tiếp tục đặt đồ ăn</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Container cho seat layout -->
    <div id="user-seat-wrapper" wire:ignore></div>

</div>
