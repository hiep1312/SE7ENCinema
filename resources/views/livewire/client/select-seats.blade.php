@assets
<link rel="stylesheet" href="{{ asset('client/assets/css/style.css') }}">
@endassets
<div class="scRender">
    <div class="st_bt_top_header_wrapper float_left mb-5">
    <div class="container container_seat">
        <div class="row">
            <!-- Cột trái: Nút back và chọn số lượng vé -->
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="st_bt_top_back_btn st_bt_top_back_btn_seatl float_left">
                    <a href="{{ url('/') }}"><i class="fas fa-long-arrow-alt-left"></i> &nbsp;Back</a>
                </div>
            </div>

            <!-- Cột giữa: Tiêu đề phim và thời gian -->
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="st_bt_top_center_heading st_bt_top_center_heading_seat_book_page float_left">
                    <h3>{{ $showtime->movie->title ?? 'Tên phim' }}</h3>
                    <div class="d-flex gap-5"><h4>Thời gian: {{ $showtime->start_time->format('d/m/Y H:i') }}</h4> <h4>Phòng chiếu: {{ $room->name }}</h4></div>
                </div>
            </div>

            <!-- Cột phải: Đóng và chuyển trang thanh toán -->
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="st_seatlay_btn float_left">
                    <a wire:click="goToSelectFood">Tiếp tục đặt đồ ăn</a>
                </div>
            </div>
        </div>
    </div>
</div>
    <div id="user-seat-wrapper"></div>

    @script
<script>
   $wire.on('seatuserdetail', function ([seats, selectedSeats = []]) {
    const wrapper = document.getElementById('user-seat-wrapper');
    try {
        wrapper.innerHTML = '';
        const dom = window.generateClientDOMSeats({
            seats,
            selectedSeats
        });
        wrapper.appendChild(dom);
        console.log('Seats generated successfully');
    } catch (error) {
        console.error('Error generating seats:', error);
    }
    console.log('generateSeats event received:', { seats, selectedSeats });
});
</script>
    @endscript
</div>
