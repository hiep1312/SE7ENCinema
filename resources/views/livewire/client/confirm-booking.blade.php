<div>
    <h2>Xác nhận đơn hàng - Mã booking: {{ $booking->booking_code }}</h2>

    <h3>Thông tin suất chiếu</h3>
    <p>Phim: {{ $booking->showtime->movie->title ?? '' }}</p>
    <p>Giờ chiếu: {{ $booking->showtime->start_time->format('d/m/Y H:i') }}</p>

    <h3>Ghế đã chọn</h3>
    <ul>
        @foreach ($booking->bookingSeats as $bs)
            <li>{{ $bs->seat->seat_row }}{{ $bs->seat->seat_number }} - Giá: {{ number_format($bs->seat->price) }} VNĐ</li>
        @endforeach
    </ul>

    <h3>Đồ ăn đã chọn</h3>

    <h3>Tổng tiền: {{ number_format($booking->total_price) }} VNĐ</h3>

    @if(session()->has('error'))
        <div style="color:red;">{{ session('error') }}</div>
    @endif
    @if(session()->has('success'))
        <div style="color:green;">{{ session('success') }}</div>
    @endif

    <h3>Chọn phương thức thanh toán</h3>
    <button wire:click="pay('credit_card')">Thẻ tín dụng</button>
    <button wire:click="pay('bank_transfer')">Chuyển khoản</button>
    <button wire:click="pay('e_wallet')">Ví điện tử</button>
    <button wire:click="pay('cash')">Thanh toán tiền mặt</button>
</div>
