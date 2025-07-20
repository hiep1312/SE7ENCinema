<div class="scRender container py-5">
    <h1 class="mb-4">🔒 Thanh toán</h1>
    <h2>{{ $booking_id }}</h2>
    <!-- Chi tiết ghế đã đặt -->
    <div class="mb-3">
        <h4>🎟️ Ghế đã chọn:</h4>
        @if (count($seats) > 0)
            <p>
                @foreach ($seats as $seat)
                    <span class="badge bg-primary">{{ $seat }}</span>
                @endforeach
            </p>
        @else
            <p>Không có ghế nào!</p>
        @endif
        <p class="fw-bold">Tổng tiền ghế: {{ number_format($seat_total) }}₫</p>
    </div>


    <!-- Chi tiết món ăn -->
    <div class="mb-4">
        <h4>🍿 Món ăn đã chọn</h4>
        @if (count($cart) > 0)
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th>Món</th>
                        <th>Thuộc tính</th>
                        <th>Số lượng</th>
                        <th>Giá</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cart as $item)
                        <tr>
                            <td>{{ $item['name'] }}</td>
                            <td>
                                @foreach ($item['attributes'] as $key => $value)
                                    <span class="badge bg-secondary">{{ $key }}: {{ $value }}</span>
                                @endforeach
                            </td>
                            <td>{{ $item['quantity'] }}</td>
                            <td>{{ number_format($item['price'] * $item['quantity']) }}₫</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <p class="fw-bold">Tiền đồ ăn: {{ number_format($food_total) }}₫</p>
        @else
            <p>Không có món ăn nào được chọn.</p>
        @endif
    </div>

    <!-- Tổng cộng -->
    <h4 class="fw-bold">💰 Tổng cộng: {{ number_format($total_amount) }}₫</h4>

    <!-- Nút thanh toán -->
    <div class="mt-4">
        <button wire:click="payAtCounter" class="btn btn-secondary me-2">
            Thanh toán tại quầy
        </button>
        <button wire:click="redirectToVnpay" class="btn btn-primary">
            Thanh toán bằng VNPay
        </button>
    </div>
</div>
