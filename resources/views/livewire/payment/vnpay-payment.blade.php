<div class="scRender container py-5">
    <style>
        :root {
            --primary-red: #FF4757;
            --primary-dark: #2C3E50;
            --secondary-dark: #34495E;
            --accent-blue: #3742FA;
            --success-green: #2ED573;
            --warning-orange: #FFA502;
            --text-light: #BDC3C7;
            --text-dark: #2C3E50;
            --bg-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --card-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            --border-radius: 20px;
        }

        body {
            background: var(--bg-gradient);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Cinema Header */
        .cinema-header {
            background: linear-gradient(135deg, #FF4757 0%, #FF3742 100%);
            color: white;
            padding: 30px;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
            text-align: center;
            box-shadow: var(--card-shadow);
            margin-bottom: -5px;
        }

        .cinema-logo {
            font-size: 2.5rem;
            font-weight: 900;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .cinema-tagline {
            font-size: 1.1rem;
            opacity: 0.9;
            font-weight: 300;
        }

        /* Countdown Alert */
        .countdown-alert {
            background: linear-gradient(135deg, #FF6B6B, #FF4757);
            border: none;
            border-radius: 15px;
            color: white;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 10px 25px rgba(255, 71, 87, 0.3);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.02); }
            100% { transform: scale(1); }
        }

        .countdown-timer {
            font-size: 1.3rem;
            font-weight: 700;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
        }

        /* Main Card */
        .card-checkout {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            padding: 40px;
            margin-bottom: 30px;
        }

        .section-header {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid #F8F9FA;
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
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0;
        }

        /* Booking Info */
        .booking-info {
            background: linear-gradient(135deg, #F8F9FA, #E9ECEF);
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            border-left: 5px solid var(--primary-red);
        }

        .booking-id {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-red);
            margin: 0;
        }

        /* Seats Display */
        .seats-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }

        .badge-seat {
            background: linear-gradient(135deg, var(--accent-blue), #5352ED) !important;
            color: white;
            padding: 10px 15px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 5px 15px rgba(55, 66, 250, 0.3);
            transition: all 0.3s ease;
            border: none;
        }

        .badge-seat:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(55, 66, 250, 0.4);
        }

        /* Food Table */
        .food-table {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }

        .food-table table {
            margin: 0;
        }

        .food-table thead {
            background: linear-gradient(135deg, var(--secondary-dark), var(--primary-dark));
            color: white;
        }

        .food-table th {
            padding: 15px;
            font-weight: 600;
            border: none;
            color: white;
        }

        .food-table td {
            padding: 15px;
            border-top: 1px solid #E9ECEF;
            vertical-align: middle;
        }

        .food-table tbody tr:hover {
            background-color: #F8F9FA;
            transition: all 0.3s ease;
        }

        .attribute-badge {
            background: var(--text-light) !important;
            color: var(--text-dark) !important;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
            margin-right: 5px;
        }

        /* Price Highlights */
        .highlight-price {
            color: var(--success-green) !important;
            font-weight: 700;
            font-size: 1.1rem;
        }

        /* Total Section */
        .total-section {
            background: linear-gradient(135deg, #F8F9FA, #E9ECEF);
            padding: 30px;
            border-radius: 15px;
            margin: 30px 0;
            text-align: center;
            border: 2px dashed var(--success-green);
        }

        .total-final {
            font-size: 2.5rem;
            font-weight: 900;
            color: var(--success-green) !important;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
            margin: 0;
        }

        /* Payment Buttons */
        .btn-checkout {
            background: linear-gradient(135deg, #00D4FF, #0099CC) !important;
            color: white !important;
            border: none !important;
            padding: 15px 40px;
            font-size: 1.2rem;
            font-weight: 600;
            border-radius: 25px;
            box-shadow: 0 10px 25px rgba(0, 212, 255, 0.3);
            transition: all 0.3s ease;
            min-width: 250px;
        }

        .btn-checkout:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0, 212, 255, 0.4);
            color: white !important;
        }

        .btn-clear {
            background: linear-gradient(135deg, #FF6B6B, #FF4757) !important;
            color: white !important;
            border: none !important;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .btn-clear:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 75, 87, 0.3);
            color: white !important;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px;
            color: var(--text-light);
            background: #F8F9FA;
            border-radius: 15px;
            margin-bottom: 20px;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .card-checkout {
                padding: 25px;
            }
            
            .cinema-header {
                padding: 20px;
            }
            
            .cinema-logo {
                font-size: 2rem;
            }
            
            .total-final {
                font-size: 2rem;
            }
            
            .btn-checkout {
                width: 100%;
            }
        }

        /* Animations */
        .fade-in {
            animation: fadeIn 0.8s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <!-- Cinema Header -->
    <div class="cinema-header fade-in">
        <div class="cinema-logo">SE7EN CINEMA</div>
        <div class="cinema-tagline">Trải nghiệm điện ảnh đỉnh cao</div>
    </div>

    <!-- Countdown Alert -->
    <div class="alert countdown-alert fade-in" role="alert">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <i class="bi bi-clock-fill me-2"></i>
                <strong>Vui lòng hoàn tất thanh toán trong:</strong>
                <div id="countdown" class="countdown-timer mt-1"></div>
            </div>
            <button wire:click="testdeletesession" class="btn btn-clear">
                <i class="bi bi-trash3-fill me-1"></i> Xóa phiên
            </button>
        </div>
    </div>

    <!-- Main Checkout Card -->
    <div class="card-checkout fade-in">
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
            <h2 class="total-final">{{ number_format($total_amount) }}₫</h2>
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