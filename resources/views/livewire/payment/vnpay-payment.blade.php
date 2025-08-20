<div class="scRender container py-5">
   <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap');

    :root {
        --primary-red: #ff4d4f;
        --primary-dark: #1e272e;
        --secondary-dark: #353b48;
        --accent-blue: #4b7bec;
        --success-green: #2ed573;
        --warning-orange: #ffa502;
        --text-light: #dcdde1;
        --text-dark: #2f3542;
        --card-shadow: 0 8px 25px rgba(0, 0, 0, 0.07);
        --border-radius: 24px;
    }

    body {
        font-family: 'Inter', sans-serif;
        background: linear-gradient(to bottom right, #f5f6fa, #dcdde1);
    }

   .cinema-header {
    background: linear-gradient(135deg, #1e1e2f, #2f3542);
    color: white;
    padding: 40px 30px;
    border-radius: 24px 24px 0 0;
    text-align: center;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    margin-top: 9rem;
}

    .cinema-logo {
        font-size: 2.8rem;
        font-weight: 900;
        text-shadow: 2px 2px 6px rgba(0,0,0,0.15);
    }

    .cinema-tagline {
        font-size: 1.1rem;
        font-weight: 400;
        opacity: 0.9;
    }

    .countdown-alert {
         background: linear-gradient(135deg, #1e1e2f, #2f3542);
        color: white;
        padding: 20px;
        border-radius: 0 0 24px 24px !important;
        animation: pulse 2s infinite;
        box-shadow: 0 10px 20px rgba(255, 71, 87, 0.25);
    }

    .countdown-timer {
        font-size: 1.3rem;
        font-weight: bold;
        color: white !important;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.02); }
    }

    .card-checkout {
        background: white;
        border-radius: var(--border-radius);
        padding: 40px;
        box-shadow: var(--card-shadow);
    }

    .section-header {
        display: flex;
        align-items: center;
        margin-bottom: 25px;
        border-bottom: 2px solid #f1f2f6;
        padding-bottom: 12px;
    }

    .section-icon {
        font-size: 2rem;
        margin-right: 15px;
        padding: 12px;
        border-radius: 12px;
        color: white;
    }

    .seats-icon { background: var(--accent-blue); }
    .food-icon { background: var(--warning-orange); }
    .total-icon { background: var(--success-green); }

    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-dark);
    }

    .booking-info {
        background: linear-gradient(135deg, #f1f2f6, #dcdde1);
        padding: 25px;
        border-radius: 18px;
        border-left: 6px solid var(--primary-red);
        margin-bottom: 30px;
    }

    .booking-id {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-red);
    }

    .seats-container {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .badge-seat {
        background: linear-gradient(to right, #4b7bec, #3867d6);
        color: white;
        padding: 10px 18px;
        border-radius: 24px;
        font-weight: 600;
        font-size: 0.95rem;
        transition: 0.2s;
        cursor: pointer;
    }

    .badge-seat:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(75, 123, 236, 0.4);
    }

    .food-table {
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }

    .food-table thead {
        background: linear-gradient(to right, #2f3542, #57606f);
        color: white;
    }

    .food-table th, .food-table td {
        padding: 15px;
    }

    .attribute-badge {
        background: #ced6e0;
        color: #2f3542;
        font-size: 0.8rem;
        padding: 5px 10px;
        border-radius: 12px;
    }

    .highlight-price {
        color: var(--success-green);
        font-weight: 700;
        font-size: 1.1rem;
    }

    .total-section {
        background: #f1f2f6;
        padding: 30px;
        border-radius: 20px;
        border: 2px dashed var(--success-green);
        text-align: center;
    }

    .total-final {
        font-size: 2.5rem;
        font-weight: 900;
        color: var(--success-green);
    }

    .btn-checkout {
        background: linear-gradient(135deg, #00d2ff, #3a7bd5);
        color: white;
        border: none;
        padding: 15px 35px;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: 30px;
        transition: 0.3s;
        min-width: 240px;
    }

    .btn-checkout:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 30px rgba(0, 210, 255, 0.4);
    }

    .btn-clear {
        background: linear-gradient(135deg, #ff6b6b, #ff4757);
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 20px;
        font-size: 0.9rem;
        transition: 0.3s;
    }

    .btn-clear:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 20px rgba(255, 75, 87, 0.3);
    }

    .empty-state {
        padding: 40px;
        border-radius: 20px;
        text-align: center;
        background: #f1f2f6;
        color: #a4b0be;
    }

    .empty-state i {
        font-size: 3rem;
        opacity: 0.5;
    }

    /* Fade-in Animation */
    .fade-in {
        animation: fadeInUp 0.6s ease-in-out both;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 768px) {
        .cinema-header {
            padding: 25px 15px;
        }

        .cinema-logo {
            font-size: 2rem;
        }

        .card-checkout {
            padding: 25px 20px;
        }

        .btn-checkout {
            width: 100%;
        }

        .total-final {
            font-size: 2rem;
        }
    }
</style>


    <!-- Cinema Header -->
   <div class="cinema-header fade-in">
    <div class="cinema-logo">SE7EN CINEMA</div>
    <div class="cinema-tagline">Trải nghiệm điện ảnh đỉnh cao</div>
</div>

<div class="alert countdown-alert fade-in" role="alert">
    <div class="d-flex justify-content-between align-items-center">
        <div class="text-light">
            <i class="bi bi-clock-fill me-2"></i>
            <strong>Vui lòng hoàn tất thanh toán trong:</strong>
            <div id="countdown" class="countdown-timer mt-1"></div>
        </div>
        <button wire:click="testdeletesession" class="btn btn-clear text-light">
            <i class="bi bi-trash3-fill me-1"></i> Xóa phiên
        </button>
    </div>
</div>
    <!-- Main Checkout Card -->
    <div class="card-checkout fade-in mb-5">
        <!-- Booking Information -->
        <div class="booking-info">
            <div class="d-flex align-items-center">
                <i class="bi bi-ticket-perforated-fill me-3" style="font-size: 2rem; color: var(--primary-red);"></i>
                <div>
                    <h3 class="booking-id">Mã đặt vé: {{ $booking_id }}</h3>
                    <p class="mb-0 text-muted">Thanh toán an toàn với SE7EN Cinema</p>
                </div>
            </div>
        </div>

        <!-- Seats Section -->
        <div class="section-header">
            <div class="section-icon seats-icon">
                <i class="bi bi-person-workspace"></i>
            </div>
            <div>
                <h3 class="section-title">Ghế đã chọn</h3>
                <p class="mb-0 text-muted">Vị trí ghế trong rạp</p>
            </div>
        </div>

        @if (count($seats) > 0)
            <div class="seats-container">
                @foreach ($seats as $seat)
                    <span class="badge badge-seat">{{ $seat }}</span>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="bi bi-x-circle"></i>
                <p class="mb-0">Không có ghế nào được chọn!</p>
            </div>
        @endif
        <p class="mb-0">Tổng tiền ghế: <span class="highlight-price">{{ number_format($seat_total) }}₫</span></p>

        <hr class="my-4">

        <!-- Food Section -->
        <div class="section-header">
            <div class="section-icon food-icon">
                <i class="bi bi-cup-straw"></i>
            </div>
            <div>
                <h3 class="section-title">Món ăn đã chọn</h3>
                <p class="mb-0 text-muted">Combo và đồ uống</p>
            </div>
        </div>

        @if (count($cart) > 0)
            <div class="food-table">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
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
                                    <td><strong>{{ $item['name'] }}</strong></td>
                                    <td>
                                        @foreach ($item['attributes'] as $key => $value)
                                            <span class="badge attribute-badge">{{ $key }}: {{ $value }}</span>
                                        @endforeach
                                    </td>
                                    <td><strong>{{ $item['quantity'] }}</strong></td>
                                    <td><span class="highlight-price">{{ number_format($item['price'] * $item['quantity']) }}₫</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <p class="mb-0">Tiền đồ ăn: <span class="highlight-price">{{ number_format($food_total) }}₫</span></p>
        @else
            <div class="empty-state">
                <i class="bi bi-cup-straw"></i>
                <p class="mb-0">Không có món ăn nào được chọn.</p>
            </div>
        @endif

        <!-- Total Section -->
        <div class="total-section">
            <div class="section-header justify-content-center border-0 pb-0 mb-3">
                <div class="section-icon total-icon">
                    <i class="bi bi-receipt"></i>
                </div>
                <h3 class="section-title">Tổng thanh toán</h3>
            </div>
            <h2 class="total-final">{{ number_format($total_amount, 0, '.', '.') }}₫</h2>
            <p class="text-muted mb-0">Đã bao gồm VAT và phí dịch vụ</p>
        </div>

        <!-- Payment Section -->
        <div class="text-center">
            <button wire:click="redirectToVnpay" class="btn btn-checkout">
                <i class="bi bi-credit-card-2-front-fill me-2"></i>
                Thanh toán qua VNPay
            </button>
            <p class="mt-3 text-muted small">
                <i class="bi bi-shield-check me-1"></i>
                Giao dịch được bảo mật với công nghệ SSL 256-bit
            </p>
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
                document.getElementById("countdown").style.color = "#FF4757";
                return;
            }

            let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            let seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById("countdown").innerHTML = `${minutes} phút ${seconds} giây`;

            setTimeout(updateCountdown, 1000);
        }

        updateCountdown();

        // Add animation delays
        const elements = document.querySelectorAll('.fade-in');
        elements.forEach((el, index) => {
            el.style.animationDelay = `${index * 0.1}s`;
        });

        // Add click effects for seat badges
        document.querySelectorAll('.badge-seat').forEach(badge => {
            badge.addEventListener('click', function() {
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 150);
            });
        });
    });
</script>
