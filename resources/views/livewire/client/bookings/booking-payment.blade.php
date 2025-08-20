@use('App\Models\Promotion')
@assets
    @vite('resources/css/bookingPayment.css')
@endassets
<div class="scRender scBookingPayment">
    <div style="clear: both"></div>
    <div class="booking-voucher-header bg-dark text-white py-3">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        <img src="/placeholder.svg?height=80&width=60" alt="Movie Poster" class="booking-voucher-movie-poster me-3">
                        <div>
                            <h5 class="mb-1">Spider-Man: No Way Home</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1 text-muted">
                                        <i class="fas fa-calendar-alt me-2"></i>Thứ 7, 25/12/2023 - 19:30
                                    </p>
                                    <p class="mb-0 text-muted">
                                        <i class="fas fa-building me-2"></i>Rạp CGV Vincom Center
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1 text-muted">
                                        <i class="fas fa-door-open me-2"></i>Phòng chiếu: 05
                                    </p>
                                    <div class="booking-voucher-seat-info">
                                        <div class="booking-voucher-seat-item">
                                            <i class="fas fa-couch me-1"></i>
                                            <span class="booking-voucher-seat-type">Ghế thường:</span>
                                            <span class="booking-voucher-seat-numbers">A5</span>
                                        </div>
                                        <div class="booking-voucher-seat-item">
                                            <i class="fas fa-crown me-1 text-warning"></i>
                                            <span class="booking-voucher-seat-type">Ghế VIP:</span>
                                            <span class="booking-voucher-seat-numbers">B3</span>
                                        </div>
                                        <div class="booking-voucher-seat-item">
                                            <i class="fas fa-heart me-1 text-danger"></i>
                                            <span class="booking-voucher-seat-type">Ghế đôi:</span>
                                            <span class="booking-voucher-seat-numbers">C1-C2</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <div class="booking-voucher-timer">
                        <i class="fas fa-clock text-warning me-2"></i>
                        <span class="text-warning">Thời gian giữ ghế:</span>
                        <div class="booking-voucher-countdown text-warning fw-bold fs-4" id="countdown">14:59</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container my-4">
        <div class="row">
            <!-- Left Column -->
            <div class="col-lg-8">
                <!-- Order Summary Section -->
                <div class="booking-payment-section mb-4">
                    <h3 class="booking-payment-section-title mb-4">
                        <i class="fas fa-receipt text-primary me-2"></i>Thông tin đơn hàng
                    </h3>

                    <div class="booking-payment-order-card">
                        <div class="booking-payment-order-section">
                            <h6 class="booking-payment-order-subtitle">Thông tin khách hàng</h6>
                            <div class="booking-payment-customer-info">
                                <div class="booking-payment-customer-item">
                                    <i class="fas fa-user text-primary me-2"></i>
                                    <span class="booking-payment-customer-label">Họ tên:</span>
                                    <span class="booking-payment-customer-value">Nguyễn Văn An</span>
                                </div>
                                <div class="booking-payment-customer-item">
                                    <i class="fas fa-phone text-success me-2"></i>
                                    <span class="booking-payment-customer-label">Số điện thoại:</span>
                                    <span class="booking-payment-customer-value">0901234567</span>
                                </div>
                                <div class="booking-payment-customer-item">
                                    <i class="fas fa-envelope text-info me-2"></i>
                                    <span class="booking-payment-customer-label">Email:</span>
                                    <span class="booking-payment-customer-value">nguyenvanan@email.com</span>
                                </div>
                            </div>
                        </div>

                        <!-- Movie Info -->
                        <div class="booking-payment-order-section">
                            <h6 class="booking-payment-order-subtitle">Thông tin vé</h6>

                            <div class="booking-payment-order-item">
                                <div class="booking-payment-order-item-info">
                                    <div class="fw-bold">Spider-Man: No Way Home</div>
                                    <div class="text-muted small">Thứ 7, 25/12/2023 - 19:30</div>
                                    <div class="text-muted small">Rạp CGV Vincom Center - Phòng 05</div>
                                </div>
                                <div class="booking-payment-order-item-price">
                                    <span class="fw-bold">375.000đ</span>
                                    <div class="text-muted small">4 vé</div>
                                </div>
                            </div>

                            <!-- Seat Details -->
                            <div class="booking-payment-seat-details">
                                <div class="booking-payment-seat-detail-item">
                                    <i class="fas fa-couch me-2 text-secondary"></i>
                                    <span>Ghế thường (A5)</span>
                                    <span class="ms-auto">75.000đ</span>
                                </div>
                                <div class="booking-payment-seat-detail-item">
                                    <i class="fas fa-crown me-2 text-warning"></i>
                                    <span>Ghế VIP (B3)</span>
                                    <span class="ms-auto">120.000đ</span>
                                </div>
                                <div class="booking-payment-seat-detail-item">
                                    <i class="fas fa-heart me-2 text-danger"></i>
                                    <span>Ghế đôi (C1-C2)</span>
                                    <span class="ms-auto">180.000đ</span>
                                </div>
                            </div>
                        </div>

                        <!-- Food Items -->
                        <div class="booking-payment-order-section">
                            <h6 class="booking-payment-order-subtitle">Đồ ăn & thức uống</h6>
                            <div id="foodOrderSummary">
                                <div class="booking-payment-order-item">
                                    <div class="booking-payment-order-item-info">
                                        <div class="fw-bold">Combo Couple x1</div>
                                        <div class="text-muted small">Combo Couple</div>
                                    </div>
                                    <div class="booking-payment-order-item-price">
                                        <span class="fw-bold">159.000đ</span>
                                    </div>
                                </div>
                                <div class="booking-payment-order-item">
                                    <div class="booking-payment-order-item-info">
                                        <div class="fw-bold">Bắp Rang Bơ x2</div>
                                        <div class="text-muted small">Size M, Bơ truyền thống</div>
                                    </div>
                                    <div class="booking-payment-order-item-price">
                                        <span class="fw-bold">130.000đ</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Applied Voucher -->
                        <div class="booking-payment-order-section" id="appliedVoucherSection" style="display: none;">
                            <h6 class="booking-payment-order-subtitle">Mã giảm giá</h6>
                            <div class="booking-payment-applied-voucher" id="appliedVoucherDisplay">
                                <!-- Will be populated by JavaScript -->
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
                                    <input type="text" class="booking-payment-input"
                                        placeholder="Nhập mã voucher..." id="voucherInput">
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
                                    <div class="booking-payment-voucher-item" data-code="COMBO50" data-type="percentage" data-value="50" data-desc="Giảm 50% cho combo đồ ăn" onclick="selectVoucher(this)">
                                        <div class="booking-payment-voucher-code">COMBO50</div>
                                        <div class="booking-payment-voucher-desc">Giảm 50% combo</div>
                                        <div class="booking-payment-voucher-discount">50%</div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="booking-payment-voucher-item" data-code="NEWMEMBER" data-type="fixed" data-value="100000" data-desc="Giảm 100.000đ cho thành viên mới" onclick="selectVoucher(this)">
                                        <div class="booking-payment-voucher-code">NEWMEMBER</div>
                                        <div class="booking-payment-voucher-desc">Thành viên mới</div>
                                        <div class="booking-payment-voucher-discount">100.000đ</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Methods Section -->
                <div class="booking-payment-section mb-4">
                    <h3 class="booking-payment-section-title mb-4">
                        <i class="fas fa-credit-card text-success me-2"></i>Phương thức thanh toán
                    </h3>

                    <div class="booking-payment-methods-card">
                        <!-- Credit/Debit Cards -->
                        <div class="booking-payment-method-group">
                            <h6 class="booking-payment-method-title">Thẻ tín dụng/ghi nợ</h6>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="booking-payment-method" data-method="visa" onclick="selectPaymentMethod(this)">
                                        <i class="fab fa-cc-visa text-primary"></i>
                                        <span>Visa</span>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="booking-payment-method" data-method="mastercard" onclick="selectPaymentMethod(this)">
                                        <i class="fab fa-cc-mastercard text-warning"></i>
                                        <span>Mastercard</span>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="booking-payment-method" data-method="jcb" onclick="selectPaymentMethod(this)">
                                        <i class="fab fa-cc-jcb text-info"></i>
                                        <span>JCB</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- E-Wallets -->
                        <div class="booking-payment-method-group">
                            <h6 class="booking-payment-method-title">Ví điện tử</h6>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="booking-payment-method" data-method="momo" onclick="selectPaymentMethod(this)">
                                        <i class="fas fa-mobile-alt text-danger"></i>
                                        <span>MoMo</span>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="booking-payment-method" data-method="zalopay" onclick="selectPaymentMethod(this)">
                                        <i class="fas fa-wallet text-primary"></i>
                                        <span>ZaloPay</span>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="booking-payment-method" data-method="vnpay" onclick="selectPaymentMethod(this)">
                                        <i class="fas fa-university text-success"></i>
                                        <span>VNPay</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Banking -->
                        <div class="booking-payment-method-group">
                            <h6 class="booking-payment-method-title">Ngân hàng</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="booking-payment-method" data-method="atm" onclick="selectPaymentMethod(this)">
                                        <i class="fas fa-credit-card text-info"></i>
                                        <span>Thẻ ATM nội địa</span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="booking-payment-method" data-method="banking" onclick="selectPaymentMethod(this)">
                                        <i class="fas fa-university text-primary"></i>
                                        <span>Internet Banking</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="booking-voucher-summary-card">
                    <h4 class="booking-voucher-summary-title">
                        <i class="fas fa-calculator me-2"></i>Tổng thanh toán
                    </h4>

                    <!-- Ticket Info -->
                    <div class="booking-payment-summary-breakdown">
                        <div class="booking-payment-summary-item">
                            <span>Tiền vé:</span>
                            <span>375.000đ</span>
                        </div>
                        <div class="booking-payment-summary-item">
                            <span>Đồ ăn & thức uống:</span>
                            <span id="foodTotal">289.000đ</span>
                        </div>
                        <div class="booking-payment-summary-item">
                            <span>Phí dịch vụ:</span>
                            <span id="serviceFee">10.000đ</span>
                        </div>
                        <div class="booking-payment-summary-item discount" id="discountRow" style="display: none;">
                            <span>Giảm giá:</span>
                            <span id="discountAmount" class="text-success">-0đ</span>
                        </div>
                        <hr>
                        <div class="booking-payment-summary-total">
                            <span>Tổng cộng:</span>
                            <span id="finalTotal" class="text-danger">674.000đ</span>
                        </div>
                    </div>

                    <!-- Selected Payment Method -->
                    <div class="booking-payment-selected-method" id="selectedMethodDisplay" style="display: none;">
                        <h6>Phương thức thanh toán</h6>
                        <div class="booking-payment-method-display" id="selectedMethodInfo">

                        </div>
                    </div>

                    <!-- Payment Button -->
                    <div class="booking-payment-actions">
                        <button class="booking-payment-btn-back" onclick="goBack()">
                            <i class="fas fa-arrow-left me-2"></i>Quay lại
                        </button>
                        <button class="booking-payment-btn-pay" id="paymentBtn" onclick="processPayment()" disabled>
                            <i class="fas fa-lock me-2"></i>Thanh toán
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
