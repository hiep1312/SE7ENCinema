@use('App\Models\Promotion')
@assets
    @vite('resources/css/bookingPayment.css')
@endassets
<div class="scRender scBookingPayment">
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

    <!-- Breadcrumb -->
    <div class="container mt-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Chọn ghế</a></li>
                <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Đặt đồ ăn</a></li>
                <li class="breadcrumb-item active" aria-current="page">Mã giảm giá</li>
                <li class="breadcrumb-item text-muted">Thanh toán</li>
            </ol>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="container my-4">
        <div class="row">
            <div class="col-lg-8">
                <h3 class="booking-voucher-section-title mb-4">
                    <i class="fas fa-gift text-danger me-2"></i>Mã giảm giá
                </h3>

                <div class="booking-voucher-input-card mb-4">
                    <div class="booking-voucher-input-header">
                        <h5 class="mb-0">
                            <i class="fas fa-ticket-alt text-danger me-1"></i>
                            Nhập mã giảm giá
                        </h5>
                        <small class="text-muted">Nhập mã giảm giá để nhận ưu đãi cho đơn hàng của bạn</small>
                    </div>
                    <div class="booking-voucher-input-body">
                        <div class="row">
                            <div class="col-md-8">
                                <input type="text" class="booking-voucher-input"
                                    placeholder="Nhập mã giảm giá..." wire:model.live.debounce.300ms="seachPromotion">
                            </div>
                            <div class="col-md-4">
                                <button class="booking-voucher-btn-apply" wire:click="applyPromotion">
                                    <i class="fas fa-check me-2"></i>Áp dụng
                                </button></div>
                        </div>
                        @if($selectedPromotionId && ($selectedPromotion = Promotion::where('id', $selectedPromotionId)->first()))
                            <div class="booking-voucher-applied-message">
                                <div class="booking-voucher-success-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="booking-voucher-success-content">
                                    <strong>Voucher đã được áp dụng!</strong>
                                    <p class="mb-0 mt-1">{{ $selectedPromotion->title }}</p>
                                </div>
                                <button class="booking-voucher-remove-btn">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="booking-voucher-available-card">
                    <div class="booking-voucher-available-header">
                        <h5 class="mb-0">
                            <i class="fas fa-star text-warning"></i>
                            Mã giảm giá có sẵn
                        </h5>
                        <small class="text-muted">Chọn mã giảm giá phù hợp với đơn hàng của bạn</small>
                    </div>
                    <div class="booking-voucher-available-body">
                        @forelse($promotions as $promotion)
                            @php $isQualified = $loop->index == 3 ? false : true; @endphp
                            <div class="booking-voucher-card {{ $isQualified ? ($selectedPromotionId === $promotion->id ? 'selected' : '') : 'disabled' }}" wire:key="{{ $promotion->id }}" onclick="this.querySelector('.booking-voucher-select-btn').click()">
                                <div class="booking-voucher-card-content">
                                    <div class="booking-voucher-card-left">
                                        <div class="booking-voucher-card-header">
                                            <span class="booking-voucher-code">{{ $promotion->code }}</span>
                                            <span class="booking-voucher-discount-badge {{ $promotion->discount_type }}">
                                                {!! $promotion->discount_type === 'percentage' ? '<i class="fas fa-percent me-1"></i>' : '' !!}{{ number_format($promotion->discount_value, 0, '.', '.') . ($promotion->discount_type === 'percentage' ? '%' : 'đ') }}
                                            </span>
                                        </div>
                                        <h6 class="booking-voucher-title">{{ $promotion->title }}</h6>
                                        <p class="booking-voucher-desc">{{ Str::limit($promotion->description, 80, '...') }}</p>
                                        <div class="booking-voucher-conditions">
                                            <span class="booking-voucher-condition">
                                                <i class="fas fa-shopping-cart me-1"></i>Đơn tối thiểu: {{ number_format($promotion->min_purchase, 0, '.', '.') }}đ
                                            </span>
                                            <span class="booking-voucher-condition">
                                                <i class="fas fa-clock me-1"></i>HSD: {{ $promotion->end_date->format('d/m/Y H:i') }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="booking-voucher-card-right">
                                        <button class="booking-voucher-select-btn {{ $isQualified ? '' : 'disabled' }}" wire:click="$set('selectedPromotionId', {{ $selectedPromotionId === $promotion->id ? 'null' : $promotion->id }})">
                                            @if($isQualified)
                                                @if($selectedPromotionId === $promotion->id)
                                                    <i class="fas fa-check me-1"></i>Đã chọn
                                                @else
                                                    <i class="fas fa-plus me-1"></i>Chọn
                                                @endif
                                            @else
                                                <i class="fas fa-lock me-1"></i>Không đủ điều kiện
                                            @endif
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-info"></div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="booking-voucher-summary-card sticky-top">
                    <h4 class="booking-voucher-summary-title">
                        <i class="fas fa-receipt me-2"></i>Tóm tắt đơn hàng
                    </h4>

                    <!-- Ticket Info -->
                    <div class="booking-voucher-summary-section">
                        <h6 class="booking-voucher-summary-subtitle">Thông tin vé</h6>
                        <div class="booking-voucher-summary-item">
                            <span>Spider-Man: No Way Home</span>
                            <span>4 vé</span>
                        </div>
                        <div class="booking-voucher-summary-item small">
                            <span>Thứ 7, 25/12/2023 - 19:30</span>
                            <span></span>
                        </div>
                        <div class="booking-voucher-summary-item small">
                            <span>Rạp CGV Vincom Center - Phòng 05</span>
                            <span></span>
                        </div>

                        <!-- Chi tiết từng loại ghế -->
                        <div class="booking-voucher-seat-details">
                            <div class="booking-voucher-summary-item">
                                <div>
                                    <i class="fas fa-couch me-2 text-secondary"></i>
                                    <span>Ghế thường (A5)</span>
                                </div>
                                <span>75.000đ</span>
                            </div>
                            <div class="booking-voucher-summary-item">
                                <div>
                                    <i class="fas fa-crown me-2 text-warning"></i>
                                    <span>Ghế VIP (B3)</span>
                                </div>
                                <span>120.000đ</span>
                            </div>
                            <div class="booking-voucher-summary-item">
                                <div>
                                    <i class="fas fa-heart me-2 text-danger"></i>
                                    <span>Ghế đôi (C1-C2)</span>
                                </div>
                                <span>180.000đ</span>
                            </div>
                            <div class="booking-voucher-summary-item fw-bold border-top pt-2">
                                <span>Tổng tiền vé:</span>
                                <span class="text-primary">375.000đ</span>
                            </div>
                        </div>
                    </div>

                    <!-- Food Items -->
                    <div class="booking-voucher-summary-section">
                        <h6 class="booking-voucher-summary-subtitle">Đồ ăn & thức uống</h6>
                        <div id="foodSummary" class="booking-voucher-summary-items">
                            <div class="booking-voucher-summary-item">
                                <div>
                                    <div class="fw-bold">Combo Couple x1</div>
                                    <div class="text-muted small">Combo Couple</div>
                                </div>
                                <span class="fw-bold">159.000đ</span>
                            </div>
                            <div class="booking-voucher-summary-item">
                                <div>
                                    <div class="fw-bold">Bắp Rang Bơ x2</div>
                                    <div class="text-muted small">Size M, Bơ truyền thống</div>
                                </div>
                                <span class="fw-bold">130.000đ</span>
                            </div>
                        </div>
                    </div>

                    <!-- Voucher Applied -->
                    <div class="booking-voucher-summary-section" id="voucherSummarySection" style="display: none;">
                        <h6 class="booking-voucher-summary-subtitle">Mã giảm giá</h6>
                        <div class="booking-voucher-applied-summary" id="appliedVoucherSummary">
                            <!-- Voucher info will be inserted here -->
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="booking-voucher-summary-total">
                        <div class="d-flex justify-content-between">
                            <span>Tạm tính:</span>
                            <span id="subtotal">664.000đ</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Phí dịch vụ:</span>
                            <span id="serviceFee">10.000đ</span>
                        </div>
                        <div class="d-flex justify-content-between text-success" id="discountRow" style="display: none;">
                            <span>Giảm giá:</span>
                            <span id="discountAmount">-0đ</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold fs-5">
                            <span>Tổng cộng:</span>
                            <span id="total" class="text-danger">674.000đ</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="booking-voucher-summary-actions">
                        <button class="booking-voucher-btn-back" onclick="goBack()">
                            <i class="fas fa-arrow-left me-2"></i>Quay lại
                        </button>
                        <button class="booking-voucher-btn-next" onclick="goNext()">
                            Tiếp theo
                            <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
