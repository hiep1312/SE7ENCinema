@assets
    @vite('resources/css/bookingFood.css')
@endassets
<div class="scRender scBookingFood">
    <div class="booking-food-header text-light py-3">
        <div class="container-lg">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('storage/404.webp') }}" alt="Poster phim" class="booking-food-movie-poster me-3">
                        <div>
                            <h5 class="mb-1">Spider-Man: No Way Home</h5>
                            <p class="mb-0" style="color: #777;">
                                <i class="fas fa-calendar-alt me-2"></i>Thứ 7, 25/12/2023 - 19:30
                            </p>
                            <p class="mb-0" style="color: #777;">
                                <i class="fas fa-map-marker-alt me-2"></i>SEVENCINEMA - Ghế: A5, A6
                            </p>
                            <p class="mb-1" style="color: #777;">
                                <i class="fas fa-door-open me-2"></i>Phòng chiếu: 05
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <div class="booking-food-timer">
                        <i class="fas fa-clock text-warning"></i>
                        <span class="text-warning">Thời gian giữ ghế:</span>
                        <div class="booking-food-countdown text-warning fw-bold fs-4" id="countdown">14:59</div>
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
                <li class="breadcrumb-item active" aria-current="page">Đặt đồ ăn</li>
                <li class="breadcrumb-item text-muted">Thanh toán</li>
            </ol>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="container my-4">
        <div class="row">
            <!-- Food Menu -->
            <div class="col-lg-8">
                <h3 class="booking-food-section-title mb-4">
                    <i class="fas fa-utensils me-2"></i>Chọn đồ ăn & thức uống
                </h3>

                <!-- Combo Section -->
                <div class="booking-food-category mb-5">
                    <h4 class="booking-food-category-title">
                        <i class="fas fa-star text-warning me-2"></i>Combo Đặc Biệt
                    </h4>
                    <div class="row">
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
                    </div>
                </div>

                <!-- Popcorn Section -->
                <div class="booking-food-category mb-5">
                    <h4 class="booking-food-category-title">
                        <i class="fas fa-seedling text-success me-2"></i>Bắp Rang
                    </h4>
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="booking-food-item-card">
                                <img src="/placeholder.svg?height=200&width=300" alt="Bắp rang bơ" class="booking-food-item-image">
                                <div class="booking-food-item-content">
                                    <h5 class="booking-food-item-name">Bắp Rang Bơ</h5>
                                    <p class="booking-food-item-desc">Bắp rang thơm ngon với vị bơ đậm đà</p>

                                    <!-- Size Variants -->
                                    <div class="booking-food-variant-group">
                                        <label class="booking-food-variant-label">Kích thước:</label>
                                        <div class="booking-food-variant-options">
                                            <div class="booking-food-variant active" data-type="size" data-value="S" data-price="0">
                                                <span class="booking-food-variant-name">Size S</span>
                                                <span class="booking-food-variant-price">+0đ</span>
                                            </div>
                                            <div class="booking-food-variant" data-type="size" data-value="M" data-price="20000">
                                                <span class="booking-food-variant-name">Size M</span>
                                                <span class="booking-food-variant-price">+20.000đ</span>
                                            </div>
                                            <div class="booking-food-variant" data-type="size" data-value="L" data-price="40000">
                                                <span class="booking-food-variant-name">Size L</span>
                                                <span class="booking-food-variant-price">+40.000đ</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Flavor Variants -->
                                    <div class="booking-food-variant-group">
                                        <label class="booking-food-variant-label">Hương vị:</label>
                                        <div class="booking-food-variant-options">
                                            <div class="booking-food-variant active" data-type="flavor" data-value="butter" data-price="0">
                                                <span class="booking-food-variant-name">Bơ truyền thống</span>
                                                <span class="booking-food-variant-price">+0đ</span>
                                            </div>
                                            <div class="booking-food-variant" data-type="flavor" data-value="caramel" data-price="10000">
                                                <span class="booking-food-variant-name">Caramel</span>
                                                <span class="booking-food-variant-price">+10.000đ</span>
                                            </div>
                                            <div class="booking-food-variant" data-type="flavor" data-value="cheese" data-price="15000">
                                                <span class="booking-food-variant-name">Phô mai</span>
                                                <span class="booking-food-variant-price">+15.000đ</span>
                                            </div>
                                            <div class="booking-food-variant" data-type="flavor" data-value="chocolate" data-price="15000">
                                                <span class="booking-food-variant-name">Chocolate</span>
                                                <span class="booking-food-variant-price">+15.000đ</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Extra Toppings -->
                                    <div class="booking-food-variant-group">
                                        <label class="booking-food-variant-label">Topping thêm:</label>
                                        <div class="booking-food-variant-options">
                                            <div class="booking-food-variant-checkbox" data-type="topping" data-value="extra-butter" data-price="5000">
                                                <input type="checkbox" id="extra-butter-1">
                                                <label for="extra-butter-1">
                                                    <span class="booking-food-variant-name">Bơ thêm</span>
                                                    <span class="booking-food-variant-price">+5.000đ</span>
                                                </label>
                                            </div>
                                            <div class="booking-food-variant-checkbox" data-type="topping" data-value="seasoning" data-price="3000">
                                                <input type="checkbox" id="seasoning-1">
                                                <label for="seasoning-1">
                                                    <span class="booking-food-variant-name">Gia vị đặc biệt</span>
                                                    <span class="booking-food-variant-price">+3.000đ</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Base Price Display -->
                                    <div class="booking-food-base-price">
                                        Giá cơ bản: <span class="booking-food-price-value" data-base-price="45000">45.000đ</span>
                                    </div>
                                    <div class="booking-food-final-price">
                                        Tổng giá: <span class="booking-food-final-price-value">45.000đ</span>
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
                                <img src="/placeholder.svg?height=200&width=300" alt="Bắp rang caramel" class="booking-food-item-image">
                                <div class="booking-food-item-content">
                                    <h5 class="booking-food-item-name">Bắp Rang Caramel</h5>
                                    <p class="booking-food-item-desc">Bắp rang ngọt ngào với lớp caramel thơm lừng</p>
                                    <div class="booking-food-item-variants">
                                        <div class="booking-food-variant active" data-price="55000">
                                            <span class="booking-food-variant-name">Size S</span>
                                            <span class="booking-food-variant-price">55.000đ</span>
                                        </div>
                                        <div class="booking-food-variant" data-price="75000">
                                            <span class="booking-food-variant-name">Size M</span>
                                            <span class="booking-food-variant-price">75.000đ</span>
                                        </div>
                                        <div class="booking-food-variant" data-price="95000">
                                            <span class="booking-food-variant-name">Size L</span>
                                            <span class="booking-food-variant-price">95.000đ</span>
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
                    </div>
                </div>

                <!-- Drinks Section -->
                <div class="booking-food-category mb-5">
                    <h4 class="booking-food-category-title">
                        <i class="fas fa-glass-water text-info me-2"></i>Thức Uống
                    </h4>
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="booking-food-item-card">
                                <img src="/placeholder.svg?height=200&width=300" alt="Coca Cola" class="booking-food-item-image">
                                <div class="booking-food-item-content">
                                    <h5 class="booking-food-item-name">Coca Cola</h5>
                                    <p class="booking-food-item-desc">Nước ngọt có gas sảng khoái</p>
                                    <div class="booking-food-item-variants">
                                        <div class="booking-food-variant active" data-price="25000">
                                            <span class="booking-food-variant-name">Size S</span>
                                            <span class="booking-food-variant-price">25.000đ</span>
                                        </div>
                                        <div class="booking-food-variant" data-price="35000">
                                            <span class="booking-food-variant-name">Size M</span>
                                            <span class="booking-food-variant-price">35.000đ</span>
                                        </div>
                                        <div class="booking-food-variant" data-price="45000">
                                            <span class="booking-food-variant-name">Size L</span>
                                            <span class="booking-food-variant-price">45.000đ</span>
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
                                <img src="/placeholder.svg?height=200&width=300" alt="Nước cam" class="booking-food-item-image">
                                <div class="booking-food-item-content">
                                    <h5 class="booking-food-item-name">Nước Trái Cây</h5>
                                    <p class="booking-food-item-desc">Nước trái cây tươi nguyên chất 100%</p>

                                    <!-- Size Variants -->
                                    <div class="booking-food-variant-group">
                                        <label class="booking-food-variant-label">Kích thước:</label>
                                        <div class="booking-food-variant-options">
                                            <div class="booking-food-variant active" data-type="size" data-value="S" data-price="0">
                                                <span class="booking-food-variant-name">Size S</span>
                                                <span class="booking-food-variant-price">+0đ</span>
                                            </div>
                                            <div class="booking-food-variant" data-type="size" data-value="M" data-price="10000">
                                                <span class="booking-food-variant-name">Size M</span>
                                                <span class="booking-food-variant-price">+10.000đ</span>
                                            </div>
                                            <div class="booking-food-variant" data-type="size" data-value="L" data-price="20000">
                                                <span class="booking-food-variant-name">Size L</span>
                                                <span class="booking-food-variant-price">+20.000đ</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Fruit Type Variants -->
                                    <div class="booking-food-variant-group">
                                        <label class="booking-food-variant-label">Loại trái cây:</label>
                                        <div class="booking-food-variant-options">
                                            <div class="booking-food-variant active" data-type="fruit" data-value="orange" data-price="0">
                                                <span class="booking-food-variant-name">Cam</span>
                                                <span class="booking-food-variant-price">+0đ</span>
                                            </div>
                                            <div class="booking-food-variant" data-type="fruit" data-value="apple" data-price="5000">
                                                <span class="booking-food-variant-name">Táo</span>
                                                <span class="booking-food-variant-price">+5.000đ</span>
                                            </div>
                                            <div class="booking-food-variant" data-type="fruit" data-value="mango" data-price="8000">
                                                <span class="booking-food-variant-name">Xoài</span>
                                                <span class="booking-food-variant-price">+8.000đ</span>
                                            </div>
                                            <div class="booking-food-variant" data-type="fruit" data-value="passion" data-price="12000">
                                                <span class="booking-food-variant-name">Chanh dây</span>
                                                <span class="booking-food-variant-price">+12.000đ</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Ice Level -->
                                    <div class="booking-food-variant-group">
                                        <label class="booking-food-variant-label">Mức độ đá:</label>
                                        <div class="booking-food-variant-options">
                                            <div class="booking-food-variant active" data-type="ice" data-value="normal" data-price="0">
                                                <span class="booking-food-variant-name">Bình thường</span>
                                                <span class="booking-food-variant-price">+0đ</span>
                                            </div>
                                            <div class="booking-food-variant" data-type="ice" data-value="less" data-price="0">
                                                <span class="booking-food-variant-name">Ít đá</span>
                                                <span class="booking-food-variant-price">+0đ</span>
                                            </div>
                                            <div class="booking-food-variant" data-type="ice" data-value="no" data-price="0">
                                                <span class="booking-food-variant-name">Không đá</span>
                                                <span class="booking-food-variant-price">+0đ</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Sugar Level -->
                                    <div class="booking-food-variant-group">
                                        <label class="booking-food-variant-label">Mức độ ngọt:</label>
                                        <div class="booking-food-variant-options">
                                            <div class="booking-food-variant-checkbox" data-type="sugar" data-value="extra-sweet" data-price="2000">
                                                <input type="checkbox" id="extra-sweet-2">
                                                <label for="extra-sweet-2">
                                                    <span class="booking-food-variant-name">Thêm đường</span>
                                                    <span class="booking-food-variant-price">+2.000đ</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Base Price Display -->
                                    <div class="booking-food-base-price">
                                        Giá cơ bản: <span class="booking-food-price-value" data-base-price="35000">35.000đ</span>
                                    </div>
                                    <div class="booking-food-final-price">
                                        Tổng giá: <span class="booking-food-final-price-value">35.000đ</span>
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
                    </div>
                </div>

                <!-- Snacks Section -->
                <div class="booking-food-category mb-5">
                    <h4 class="booking-food-category-title">
                        <i class="fas fa-cookie-bite text-warning me-2"></i>Đồ Ăn Vặt
                    </h4>
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="booking-food-item-card">
                                <img src="/placeholder.svg?height=200&width=300" alt="Nachos" class="booking-food-item-image">
                                <div class="booking-food-item-content">
                                    <h5 class="booking-food-item-name">Nachos Phô Mai</h5>
                                    <p class="booking-food-item-desc">Bánh tortilla giòn với sốt phô mai nóng</p>
                                    <div class="booking-food-item-variants">
                                        <div class="booking-food-variant active" data-price="65000">
                                            <span class="booking-food-variant-name">Phần thường</span>
                                            <span class="booking-food-variant-price">65.000đ</span>
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
                                <img src="/placeholder.svg?height=200&width=300" alt="Hot Dog" class="booking-food-item-image">
                                <div class="booking-food-item-content">
                                    <h5 class="booking-food-item-name">Hot Dog</h5>
                                    <p class="booking-food-item-desc">Xúc xích nướng trong bánh mì với sốt đặc biệt</p>
                                    <div class="booking-food-item-variants">
                                        <div class="booking-food-variant active" data-price="45000">
                                            <span class="booking-food-variant-name">Hot Dog thường</span>
                                            <span class="booking-food-variant-price">45.000đ</span>
                                        </div>
                                        <div class="booking-food-variant" data-price="65000">
                                            <span class="booking-food-variant-name">Hot Dog phô mai</span>
                                            <span class="booking-food-variant-price">65.000đ</span>
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
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="booking-food-summary-card sticky-top">
                    <h4 class="booking-food-summary-title">
                        <i class="fas fa-receipt me-2"></i>Tóm tắt đơn hàng
                    </h4>

                    <!-- Ticket Info -->
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

                    <!-- Food Items -->
                    <div class="booking-food-summary-section">
                        <h6 class="booking-food-summary-subtitle">Đồ ăn & thức uống</h6>
                        <div id="foodSummary" class="booking-food-summary-items">
                            <div class="booking-food-summary-empty">
                                <i class="fas fa-utensils text-muted"></i>
                                <p class="text-muted mb-0">Chưa chọn đồ ăn nào</p>
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
