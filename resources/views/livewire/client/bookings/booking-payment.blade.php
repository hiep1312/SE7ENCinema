@use('App\Models\Promotion')
@assets
    <script>
        Object.defineProperty(window, 'expiredAt', {
            configurable: false,
            enumerable: false,
            value: new Date(@json({{-- $seatHold->expires_at->toIso8601String() --}} now()->addMinutes(30)->toIso8601String())),
            writable: false
        });
    </script>
    @vite('resources/css/bookingPayment.css')
    @vite('resources/js/bookingPayment.js')
@endassets
<div class="scRender scBookingPayment" style="clear: both;" sc-root>
    <div class="booking-food-header text-light py-3">
        <div class="container-lg">
            <div class="row align-items-center g-1">
                <div class="col-sm-8">
                    @php $movie = $booking->showtime->movie; @endphp
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('storage/' . ($movie->poster ?? '404.webp')) }}" alt="Poster phim" class="booking-food-movie-poster me-3">
                        <div>
                            <h5 class="mb-1">{{ $movie->title }}</h5>
                            <p class="mb-0" style="color: #777;">
                                <i class="fas fa-calendar-alt me-2"></i>{{ mb_ucfirst($booking->showtime->start_time->translatedFormat('l, d/m/Y - H:i'), 'UTF-8') }}
                            </p>
                            <p class="mb-0" style="color: #777;">
                                <i class="fas fa-door-open me-2" style="width: 11px;"></i>Phòng chiếu: {{ $booking->showtime->room->name }}
                            </p>
                            <p class="mb-0" style="color: #777;">
                                <i class="fas fa-map-marker-alt me-2"></i>Rạp SE7ENCinema - Ghế: {{ $booking->seats->implode('seat_label', ', ') }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="text-center" wire:ignore>
                        <i class="fas fa-clock text-warning"></i>
                        <span class="text-warning">Thời gian giữ ghế:</span>
                        <div class="booking-food-countdown text-warning fw-bold fs-4" id="countdown">00:00</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-lg my-5 booking-payment-main">
        <div class="row">
            <div class="col-lg-8">
                <div class="booking-payment-section mb-4">
                    <h3 class="booking-payment-section-title mb-4">
                        <i class="fas fa-receipt text-primary me-2"></i>Thông tin đơn hàng
                    </h3>

                    <div class="booking-payment-order-card">
                        <div class="booking-payment-order-section">
                            <h6 class="booking-payment-order-subtitle">Thông tin khách hàng</h6>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="booking-payment-customer-item">
                                        <i class="fas fa-user text-primary me-2"></i>
                                        <div class="booking-payment-customer-content">
                                            <span class="booking-payment-customer-label">Họ tên</span>
                                            <span class="booking-payment-customer-value">{{ $booking->user->name }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="booking-payment-customer-item">
                                        <i class="fas fa-phone text-success me-2"></i>
                                        <div class="booking-payment-customer-content">
                                            <span class="booking-payment-customer-label">Số điện thoại</span>
                                            <span class="booking-payment-customer-value">{{ $booking->user->phone ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="booking-payment-customer-item">
                                        <i class="fas fa-envelope text-info me-2"></i>
                                        <div class="booking-payment-customer-content">
                                            <span class="booking-payment-customer-label">Email</span>
                                            <span class="booking-payment-customer-value text-truncate">{{ $booking->user->email }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Movie Info -->
                        <div class="booking-payment-order-section">
                            <h6 class="booking-payment-order-subtitle">Thông tin vé</h6>
                            <div class="booking-payment-order-item">
                                <div class="booking-payment-order-item-info">
                                    <div class="fw-bold">{{ $booking->showtime->movie->title }}</div>
                                    <div class="booking-food-summary-item small">
                                        <span>{{ mb_ucfirst($booking->showtime->start_time->translatedFormat('l, d/m/Y - H:i'), 'UTF-8') }}</span>
                                    </div>
                                    <div class="booking-food-summary-item small">
                                        <span>Rạp SE7ENCinema - {{ $booking->showtime->room->name }}</span>
                                    </div>
                                </div>
                                <div class="booking-payment-order-item-price">
                                    <span class="fw-bold">{{ number_format($booking->bookingSeats->sum('ticket_price'), 0, '.', '.') }}đ</span>
                                    <div class="text-muted small">{{ $booking->seats->count() }} vé</div>
                                </div>
                            </div>

                            <!-- Seat Details -->
                            <div class="booking-payment-seat-details">
                                @php $seats = $booking->seats->groupBy('seat_type'); @endphp
                                @if($seats->has('standard'))
                                    <div class="booking-payment-seat-detail-item">
                                        <i class="fas fa-couch me-2 text-secondary"></i>
                                        <span>Ghế thường x {{ $seats->get('standard')->count() }}</span>
                                        <span class="ms-auto">{{ number_format($seats->get('standard')->first()->price + ($booking->showtime->movie?->price ?? 0), 0, '.', '.') }}đ</span>
                                    </div>
                                @endif
                                @if($seats->has('vip'))
                                    <div class="booking-payment-seat-detail-item">
                                        <i class="fas fa-crown me-2 text-warning"></i>
                                        <span>Ghế VIP x {{ $seats->get('vip')->count() }}</span>
                                        <span class="ms-auto">{{ number_format($seats->get('vip')->first()->price + ($booking->showtime->movie?->price ?? 0), 0, '.', '.') }}đ</span>
                                    </div>
                                @endif
                                @if($seats->has('couple'))
                                    <div class="booking-payment-seat-detail-item">
                                        <i class="fas fa-heart me-2 text-danger"></i>
                                        <span>Ghế đôi x {{ $seats->get('couple')->count() }}</span>
                                        <span class="ms-auto">{{ number_format($seats->get('couple')->first()->price + ($booking->showtime->movie?->price ?? 0), 0, '.', '.') }}đ</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Food Items -->
                        <div class="booking-payment-order-section">
                            <h6 class="booking-payment-order-subtitle">Đồ ăn & thức uống</h6>
                            <div class="food-details">

                                @forelse($booking->foodOrderItems as $foodOrderItem)
                                    <div class="booking-food-summary-item">
                                        <div class="flex-grow-1">
                                            <div class="fw-bold">{{ $foodOrderItem->variant->foodItem->name }}</div>
                                            <div class="text-muted small">{{ $foodOrderItem->variant->variantAttributes?->map(fn($value, $attribute) => "$attribute: $value")->implode(', ') }}</div>
                                        </div>
                                        <div class="text-end">
                                            <span class="fw-bold">{{ number_format($foodOrderItem->quantity * $foodOrderItem->price, 0, '.', '.') }}</span>
                                            <div class="text-muted small">{{ number_format($foodOrderItem->price, 0, '.', '.') }}đ x {{ $foodOrderItem->quantity }}</div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="booking-food-back-empty">
                                        <i class="fas fa-exclamation-circle text-muted mb-3" style="font-size: 48px;"></i>
                                        <h6 class="text-muted">Chưa có món ăn nào</h6>
                                        <p class="text-muted small">Bạn có thể tiếp tục thanh toán hoặc quay lại đặt đồ ăn.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Applied Voucher -->
                        <div class="booking-payment-order-section">
                            <h6 class="booking-payment-order-subtitle">Mã giảm giá</h6>
                            <div class="booking-payment-applied-voucher">
                                <div class="booking-payment-applied-voucher-info">
                                    <div class="booking-payment-applied-voucher-code">COMBO50</div>
                                    <p class="booking-payment-applied-voucher-desc">Giảm 50% cho combo đồ ăn</p>
                                </div>
                                <div class="booking-payment-applied-voucher-amount">- 144.500&nbsp;đ</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Voucher Section -->
                <div class="booking-payment-section mb-4">
                    <h3 class="booking-payment-section-title mb-4">
                        <i class="fas fa-gift text-danger me-2"></i>Mã giảm giá
                    </h3>

                    <!-- Voucher Input -->
                    <div class="booking-payment-voucher-card">
                        <div class="booking-payment-voucher-input">
                            <div class="row">
                                <div class="col-md-8">
                                    <input type="text" class="booking-payment-input" placeholder="Nhập mã voucher..."
                                        id="voucherInput">
                                </div>
                                <div class="col-md-4">
                                    <button class="booking-payment-btn-apply" onclick="applyVoucher()">
                                        <i class="fas fa-check me-2"></i>Áp dụng
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Available Vouchers -->
                        <div class="booking-payment-available-vouchers">
                            <h6 class="mb-3">Voucher có sẵn</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="booking-payment-voucher-item" data-code="COMBO50" data-type="percentage"
                                        data-value="50" data-desc="Giảm 50% cho combo đồ ăn"
                                        onclick="selectVoucher(this)">
                                        <div class="booking-payment-voucher-code">COMBO50</div>
                                        <div class="booking-payment-voucher-desc">Giảm 50% combo</div>
                                        <div class="booking-payment-voucher-discount">50%</div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="booking-payment-voucher-item" data-code="NEWMEMBER" data-type="fixed"
                                        data-value="100000" data-desc="Giảm 100.000đ cho thành viên mới"
                                        onclick="selectVoucher(this)">
                                        <div class="booking-payment-voucher-code">NEWMEMBER</div>
                                        <div class="booking-payment-voucher-desc">Thành viên mới</div>
                                        <div class="booking-payment-voucher-discount">100.000đ</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- @if($foodItems->hasPages())
                        <div class="d-flex justify-content-center mt-2">
                            {{ $foodItems->links() }}
                        </div>
                    @endif --}}
                </div>

                <!-- Payment Methods Section -->
                <div class="booking-payment-section mb-4">
                    <h3 class="booking-payment-section-title pt-1 mb-4">
                        <i class="fas fa-credit-card text-success me-2"></i>Phương thức thanh toán
                    </h3>

                    <div class="booking-payment-methods-card" wire:ignore>
                        <div class="booking-payment-method-group">
                            <h6 class="booking-payment-method-title">Ví điện tử</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="booking-payment-method" onclick="selectPaymentMethod(this)" data-method="momo">
                                        <i class="fas fa-mobile-alt text-danger"></i>
                                        <span>MoMo</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="booking-payment-method" onclick="selectPaymentMethod(this)" data-method="vnpay">
                                        <i class="fas fa-wallet text-primary"></i>
                                        <span>VNPAY</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="booking-payment-method-group">
                            <h6 class="booking-payment-method-title">Ngân hàng</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="booking-payment-method" onclick="selectPaymentMethod(this)" data-method="atm">
                                        <i class="fas fa-credit-card text-info"></i>
                                        <span>Thẻ ATM</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="booking-payment-method" onclick="selectPaymentMethod(this)" data-method="bank">
                                        <i class="fas fa-university text-primary"></i>
                                        <span>Chuyển khoản ngân hàng</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="booking-payment-summary-card">
                    <h4 class="booking-payment-summary-title">
                        <i class="fas fa-calculator me-2"></i>Tổng thanh toán
                    </h4>

                    <div>
                        <div class="booking-payment-summary-item">
                            <span>Tiền vé:</span>
                            <span>{{ number_format($booking->bookingSeats->sum('ticket_price'), 0, '.', '.') }}đ</span>
                        </div>
                        <div class="booking-payment-summary-item">
                            <span>Đồ ăn & thức uống:</span>
                            <span>{{ number_format($booking->foodOrderItems?->reduce(fn($totalPrice, $foodOrderItem) => $totalPrice + ($foodOrderItem->quantity * $foodOrderItem->price), 0) ?? 0, 0, '.', '.') }}đ</span>
                        </div>
                        {{-- <div class="booking-payment-summary-item discount" id="discountRow">
                            <span>Giảm giá:</span>
                            <span id="discountAmount" class="text-success">-0đ</span>
                        </div> --}}
                        <hr>
                        <div class="booking-payment-summary-total fw-bold">
                            <span>Tổng cộng:</span>
                            <span class="text-danger">674.000đ</span>
                        </div>
                    </div>

                    @if($paymentSelected)
                        <div class="booking-payment-selected-method">
                            <h6>Phương thức thanh toán</h6>
                            <div class="booking-payment-method-display">
                                @switch($paymentSelected)
                                    @case('momo')
                                        <i class="fas fa-wallet text-primary"></i>
                                        <span>MoMo</span>
                                        @break
                                    @case('vnpay')
                                        <i class="fas fa-wallet text-primary"></i>
                                        <span>VNPAY</span>
                                        @break
                                    @case('atm')
                                        <i class="fas fa-wallet text-primary"></i>
                                        <span>Thẻ ATM</span>
                                        @break
                                    @case('bank')
                                        <i class="fas fa-wallet text-primary"></i>
                                        <span>Chuyển khoản ngân hàng</span>
                                        @break
                                    @default
                                @endswitch
                            </div>
                        </div>
                    @endif

                    <!-- Payment Button -->
                    <div class="booking-payment-actions">
                        <button class="booking-payment-btn-back">
                            <i class="fas fa-arrow-left me-2"></i>Quay lại
                        </button>
                        <button class="booking-payment-btn-pay" @unless($paymentSelected) disabled @endunless>
                            <i class="fas fa-lock me-2"></i>Thanh toán
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Processing Overlay -->
    <div class="booking-payment-overlay" id="paymentOverlay" style="display: none;">
        <div class="booking-payment-overlay-content">
            <div class="booking-payment-processing" id="processingState">
                <div class="booking-payment-spinner"></div>
                <h4 class="mt-3">Đang xử lý thanh toán...</h4>
                <p class="text-muted">Vui lòng không tắt trình duyệt</p>
                <div class="booking-payment-timer">
                    <span>Thời gian chờ: </span>
                    <span id="paymentTimer" class="fw-bold text-warning">02:00</span>
                </div>
            </div>

            <div class="booking-payment-timeout" id="timeoutState" style="display: none;">
                <i class="fas fa-exclamation-triangle text-warning mb-3" style="font-size: 48px;"></i>
                <h4>Hết thời gian chờ</h4>
                <p class="text-muted">Giao dịch đã hết thời gian chờ. Vui lòng thử lại.</p>
                <div class="booking-payment-timeout-actions">
                    <button class="booking-payment-btn-retry" onclick="retryPayment()">
                        <i class="fas fa-redo me-2"></i>Thanh toán lại
                    </button>
                    <button class="booking-payment-btn-cancel" onclick="cancelPayment()">
                        <i class="fas fa-times me-2"></i>Hủy giao dịch
                    </button>
                </div>
            </div>

            <div class="booking-payment-success" id="successState" style="display: none;">
                <i class="fas fa-check-circle text-success mb-3" style="font-size: 48px;"></i>
                <h4>Thanh toán thành công!</h4>
                <p class="text-muted">Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi</p>
                <button class="booking-payment-btn-continue" onclick="goToTickets()">
                    <i class="fas fa-ticket-alt me-2"></i>Xem vé của tôi
                </button>
            </div>
        </div>
    </div>
</div>
@script
<script>
    const countdownEl = document.getElementById('countdown');
    const timer = () => {
        const now = Date.now();
        const diffMs = expiredAt - now;

        if(diffMs <= 0) $wire.dispatch('reservationExpired', @json(route('client.movieBooking.movie', $booking->showtime->movie->id)));

        const diffSeconds = Math.floor(diffMs / 1000);
        const minutes = Math.floor(diffSeconds / 60);
        const seconds = diffSeconds % 60;

        const display = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        countdownEl.textContent = display;
    };
    timer();

    setInterval(timer, 1000);
</script>
@endscript
