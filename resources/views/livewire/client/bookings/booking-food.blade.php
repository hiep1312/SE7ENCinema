@assets
    @vite('resources/css/bookingFood.css')
@endassets
<div class="scRender scBookingFood" style="clear: both;">
    <div class="booking-food-header text-light py-3">
        <div class="container-lg">
            <div class="row align-items-center g-1">
                <div class="col-sm-8">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('storage/404.webp') }}" alt="Poster phim" class="booking-food-movie-poster me-3">
                        <div>
                            <h5 class="mb-1">Spider-Man: No Way Home</h5>
                            <p class="mb-0" style="color: #777;">
                                <i class="fas fa-calendar-alt me-2"></i>Thứ 7, 25/12/2023 - 19:30
                            </p>
                            <p class="mb-0" style="color: #777;">
                                <i class="fas fa-door-open me-2" style="width: 11px;"></i>Phòng chiếu: 05
                            </p>
                            <p class="mb-0" style="color: #777;">
                                <i class="fas fa-map-marker-alt me-2"></i>SEVENCINEMA - Ghế: A5, A6
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="booking-food-timer">
                        <i class="fas fa-clock text-warning"></i>
                        <span class="text-warning">Thời gian giữ ghế:</span>
                        <div class="booking-food-countdown text-warning fw-bold fs-4" id="countdown">14:59</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-lg my-5 booking-food-main">
        <div class="row">
            <div class="col-lg-8">
                <h3 class="booking-food-section-title mb-4">
                    <i class="fas fa-utensils me-2"></i>Chọn đồ ăn & thức uống
                </h3>

                <div class="booking-food-category mb-5">
                    <h4 class="booking-food-category-title">
                        <i class="fas fa-star text-warning me-2"></i>Combo Đặc Biệt
                    </h4>
                    {{-- <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="booking-food-item-card">
                                <img src="/placeholder.svg?height=200&width=300" alt="Combo 1" class="booking-food-item-image">
                                <div class="booking-food-item-content">
                                    <h5 class="booking-food-item-name">Combo Couple</h5>
                                    <p class="booking-food-item-desc">2 Bắp rang bơ lớn + 2 Nước ngọt lớn</p>
                                    <div class="booking-food-item-variants">
                                        <div class="booking-food-variant active" data-price="159000">
                                            <span class="booking-food-variant-name">Combo Couple</span>
                                            <span class="booking-food-variant-price">159.000đ</span>
                                        </div>
                                    </div>
                                    <div class="booking-food-item-controls">
                                        <button class="booking-food-btn-minus" onclick="decreaseQuantity(this)">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <span class="booking-food-quantity">0</span>
                                        <button class="booking-food-btn-plus" onclick="increaseQuantity(this)">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="booking-food-item-card">
                                <img src="/placeholder.svg?height=200&width=300" alt="Combo 2" class="booking-food-item-image">
                                <div class="booking-food-item-content">
                                    <h5 class="booking-food-item-name">Combo Family</h5>
                                    <p class="booking-food-item-desc">3 Bắp rang bơ lớn + 3 Nước ngọt lớn + 1 Kẹo</p>
                                    <div class="booking-food-item-variants">
                                        <div class="booking-food-variant active" data-price="229000">
                                            <span class="booking-food-variant-name">Combo Family</span>
                                            <span class="booking-food-variant-price">229.000đ</span>
                                        </div>
                                    </div>
                                    <div class="booking-food-item-controls">
                                        <button class="booking-food-btn-minus" onclick="decreaseQuantity(this)">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <span class="booking-food-quantity">0</span>
                                        <button class="booking-food-btn-plus" onclick="increaseQuantity(this)">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </div>

                <!-- Popcorn Section -->
                <div class="booking-food-category mb-5">
                    <h4 class="booking-food-category-title">
                        <i class="fas fa-seedling text-success me-2"></i>Đồ Ăn & Thức Uống
                    </h4>
                    <div class="row">
                        @forelse($foodItems as $foodItem)
                            <div class="col-sm-6 mb-4">
                                <div class="booking-food-item-card">
                                    <img src="{{ asset('storage/404.webp') }}" alt="Bắp rang bơ" class="booking-food-item-image">
                                    <div class="booking-food-item-content">
                                        <h5 class="booking-food-item-name">{{ $foodItem->name }}</h5>
                                        <p class="booking-food-item-desc">{{ Str::limit($foodItem->description, 100, '...') }}</p>

                                        @php $foodVariants = $foodItem->getAllVariants(true); @endphp
                                        {{-- @foreach($foodItem->)
                                            <div class="booking-food-variant-group">
                                                <label class="booking-food-variant-label">{{ $foodAttribute->name }}:</label>
                                                <div class="booking-food-variant-options">
                                                @foreach ($foodAttribute->values as $foodAttributeValue)
                                                    <div class="booking-food-variant">{{ $foodAttributeValue->value }}</div>
                                                @endforeach
                                                </div>
                                            </div>
                                        @endforeach --}}

                                        <div class="booking-food-base-price">
                                            Giá cơ bản: <span class="booking-food-price-value" data-base-price="45000"></span>
                                        </div>
                                        <div class="booking-food-final-price">
                                            Tổng giá: <span class="booking-food-final-price-value">45.000đ</span>
                                        </div>

                                        <div class="booking-food-item-controls mt-3">
                                            <button class="booking-food-btn-minus">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <span class="booking-food-quantity">0</span>
                                            <button class="booking-food-btn-plus">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                            <button class="booking-food-add-more-btn" onclick="scrollToFoodMenu()">
                                                <i class="fas fa-plus me-1"></i>Thêm
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty

                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="booking-food-summary-card">
                    <h4 class="booking-food-summary-title">
                        <i class="fas fa-receipt me-2"></i>Tóm tắt đơn hàng
                    </h4>

                    <div class="booking-food-summary-section">
                        <h6 class="booking-food-summary-subtitle">Thông tin vé</h6>
                        <div class="booking-food-summary-item">
                            <span>Spider-Man: No Way Home</span>
                            <span>4 vé</span>
                        </div>
                        <div class="booking-food-summary-item small">
                            <span>Thứ 7, 25/12/2023 - 19:30</span>
                            <span></span>
                        </div>
                        <div class="booking-food-summary-item small">
                            <span>Rạp CGV Vincom Center - Phòng 05</span>
                            <span></span>
                        </div>

                        <!-- Chi tiết từng loại ghế -->
                        <div class="booking-food-seat-details">
                            <div class="booking-food-summary-item">
                                <div>
                                    <i class="fas fa-couch me-2 text-secondary"></i>
                                    <span>Ghế thường (A5)</span>
                                </div>
                                <span>75.000đ</span>
                            </div>
                            <div class="booking-food-summary-item">
                                <div>
                                    <i class="fas fa-crown me-2 text-warning"></i>
                                    <span>Ghế VIP (B3)</span>
                                </div>
                                <span>120.000đ</span>
                            </div>
                            <div class="booking-food-summary-item">
                                <div>
                                    <i class="fas fa-heart me-2 text-danger"></i>
                                    <span>Ghế đôi (C1-C2)</span>
                                </div>
                                <span>180.000đ</span>
                            </div>
                            <div class="booking-food-summary-item fw-bold border-top pt-2">
                                <span>Tổng tiền vé:</span>
                                <span class="text-primary">375.000đ</span>
                            </div>
                        </div>
                    </div>

                    <div class="booking-food-summary-section">
                        <h6 class="booking-food-summary-subtitle">Đồ ăn & thức uống</h6>
                        <div id="foodSummary" class="booking-food-summary-items">
                            {{-- <div class="booking-food-summary-empty">
                                <i class="fas fa-utensils text-muted"></i>
                                <p class="text-muted mb-0">Chưa chọn đồ ăn nào</p>
                            </div> --}}

                            <div class="booking-food-summary-item">
                                <div class="flex-grow-1">
                                    <div class="fw-bold">Bắp Rang Caramel</div>
                                    <div class="text-muted small">Size S</div>
                                    <div class="booking-food-summary-item-controls">
                                        <div class="booking-food-quantity-control">
                                            <button class="booking-food-summary-btn-minus" onclick="updateCartFromSummary('Bắp Rang Caramel-eyJ1bmRlZmluZWQiOnsibmFtZSI6IlNpemUgUyIsInByaWNlIjo1NTAwMH19', 0)" disabled="">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <span class="booking-food-summary-quantity">1</span>
                                            <button class="booking-food-summary-btn-plus" onclick="updateCartFromSummary('Bắp Rang Caramel-eyJ1bmRlZmluZWQiOnsibmFtZSI6IlNpemUgUyIsInByaWNlIjo1NTAwMH19', 2)">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                        <button class="booking-food-remove-btn" onclick="removeFromCart('Bắp Rang Caramel-eyJ1bmRlZmluZWQiOnsibmFtZSI6IlNpemUgUyIsInByaWNlIjo1NTAwMH19')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <span class="fw-bold">55.000&nbsp;đ</span>
                                    <div class="text-muted small">55.000&nbsp;đ x 1</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="booking-food-summary-total">
                        <div class="d-flex justify-content-between">
                            <span>Tạm tính:</span>
                            <span id="subtotal">170.000đ</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Phí dịch vụ:</span>
                            <span>10.000đ</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold fs-5">
                            <span>Tổng cộng:</span>
                            <span id="total" class="text-danger">180.000đ</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="booking-food-summary-actions">
                        <button class="booking-food-btn-back" onclick="goBack()">
                            <i class="fas fa-arrow-left me-2"></i>Quay lại
                        </button>
                        <button class="booking-food-btn-next" onclick="goNext()">
                            Tiếp theo
                            <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
