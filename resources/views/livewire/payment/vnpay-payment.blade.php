<div class="scRender container py-5">
    <style>
        .card-checkout {
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            padding: 24px;
            background-color: #fff;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .countdown-timer {
            font-weight: 600;
            color: #d63384;
        }

        .badge-seat {
            font-size: 0.9rem;
            margin-right: 6px;
        }

        .total-final {
            font-size: 1.3rem;
            font-weight: bold;
        }

        .btn-checkout {
            min-width: 220px;
            padding: 10px 18px;
            font-weight: 500;
        }

        .table td,
        .table th {
            vertical-align: middle;
        }

        .highlight-price {
            color: #198754;
            font-weight: 600;
        }
    </style>

    <div class="alert alert-warning d-flex justify-content-between align-items-center shadow-sm">
        <div>
            ⏰ <strong>Vui lòng hoàn tất thanh toán trong:</strong>
            <span id="countdown" class="countdown-timer"></span>
        </div>
        <button wire:click="testdeletesession" class="btn btn-sm btn-outline-danger">🧹 Xoá phiên</button>
    </div>

    <div class="card-checkout mt-4">
        <h2 class="mb-3 text-primary fw-bold"><i class="bi bi-lock-fill"></i> Thanh toán</h2>
        <h5 class="mb-4 text-dark">🎫 Mã đặt vé: <strong>{{ $booking_id }}</strong></h5>

        <!-- Ghế -->
        <div class="mb-4">
            <div class="section-title">🪑 Ghế đã chọn</div>
            @if (count($seats) > 0)
                <p>
                    @foreach ($seats as $seat)
                        <span class="badge bg-primary badge-seat">{{ $seat }}</span>
                    @endforeach
                </p>
            @else
                <p class="text-muted">Không có ghế nào!</p>
            @endif
            <p class="mb-0">Tổng tiền ghế: <span class="highlight-price">{{ number_format($seat_total) }}₫</span></p>
        </div>

        <!-- Món ăn -->
        <div class="mb-4">
            <div class="section-title">🍿 Món ăn đã chọn</div>
            @if (count($cart) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle small">
                        <thead class="table-light">
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
                </div>
                <p class="mb-0">Tiền đồ ăn: <span class="highlight-price">{{ number_format($food_total) }}₫</span></p>
            @else
                <p class="text-muted">Không có món ăn nào được chọn.</p>
            @endif
        </div>

        <!-- Tổng cộng -->
        <div class="border-top pt-3 mb-4">
            <div class="total-final text-danger">
                🧾 Tổng cộng: {{ number_format($total_amount) }}₫
            </div>
        </div>

        <!-- Nút -->
        <div class="d-flex flex-wrap gap-3">
            <button wire:click="redirectToVnpay" class="btn btn-primary btn-checkout">
                🏦 Thanh toán qua VNPay
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let deadline = {!! json_encode($payment_deadline) !!};

        function updateCountdown() {
            let now = new Date().getTime();
            let distance = deadline - now;

            if (distance < 0) {
                document.getElementById("countdown").innerHTML = "Đã hết thời gian!";
                return;
            }

            let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            let seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById("countdown").innerHTML = `${minutes} phút ${seconds} giây`;

            setTimeout(updateCountdown, 1000);
        }

        updateCountdown();
    });
</script>