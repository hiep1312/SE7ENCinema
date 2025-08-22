@assets
    <script>
        Object.defineProperty(window, 'expiredAt', {
            configurable: false,
            enumerable: false,
            value: new Date(@json($seatHold->expires_at->toIso8601String())),
            writable: false
        });
    </script>
    @vite('resources/css/bookingFood.css')
    @vite('resources/js/foodSelection.js')
@endassets
<div class="scRender scBookingFood" style="clear: both;" wire:poll.6s sc-root>
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
                    <div class="booking-food-timer" wire:ignore>
                        <i class="fas fa-clock text-warning"></i>
                        <span class="text-warning">Thời gian giữ ghế:</span>
                        <div class="booking-food-countdown text-warning fw-bold fs-4" id="countdown">00:00</div>
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
                    </div> --}}
                </div>

                <!-- Popcorn Section -->
                <div class="booking-food-category mb-5">
                    <h4 class="booking-food-category-title">
                        <i class="fas fa-seedling text-success me-2"></i>Đồ Ăn & Thức Uống
                    </h4>
                    <div class="row">
                        @forelse($foodItems as $foodItem)
                            <div class="col-sm-6 mb-4" wire:key="food-item-{{ $foodItem->id }}">
                                <div class="booking-food-item-card" data-food-id="{{ $foodItem->id }}" wire:ignore.self>
                                    <div class="booking-food-card-inner">
                                        <div class="booking-food-card-front">
                                            <img src="{{ asset("storage/" . ($foodItem->image ?? '404.webp')) }}" alt="Ảnh đồ ăn & thức uống {{ $foodItem->name }}" class="booking-food-item-image">
                                            <div class="booking-food-item-content">
                                                <h5 class="booking-food-item-name">{{ $foodItem->name }}</h5>
                                                <p class="booking-food-item-desc">{{ $foodItem->description }}</p>

                                                @foreach($foodItem->availableAttributes as $foodAttribute => $foodAttributeValues)
                                                    <div class="booking-food-variant-group" data-attribute="{{ $foodAttribute }}" wire:key="{{ "$foodItem->id - $foodAttribute" }}" wire:ignore.self>
                                                        <label class="booking-food-variant-label text-start">{{ $foodAttribute }}:</label>
                                                        <div class="booking-food-variant-options">
                                                            @foreach ($foodAttributeValues as $foodAttributeValue)
                                                                <div class="booking-food-variant" data-value="{{ $foodAttributeValue }}"
                                                                    onclick="selectVariant(event)" wire:key="{{ "$foodItem->id - $foodAttribute - $foodAttributeValue" }}"
                                                                    wire:ignore.self>{{ $foodAttributeValue }}</div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endforeach

                                                <div class="booking-food-base-price">
                                                    Giá tiền: <span class="booking-food-price-value" wire:ignore>{{ number_format($foodItem->variantsData->min('price'), 0, '.', '.') }}đ - {{ number_format($foodItem->variantsData->max('price'), 0, '.', '.') }}đ</span>
                                                </div>

                                                <button class="booking-food-auto-flip-btn" onclick="toggleTempCartItem(this, true)" wire:ignore>
                                                    <i class="fas fa-arrow-right"></i>
                                                    Chọn số lượng
                                                </button>
                                            </div>
                                        </div>
                                        <div class="booking-food-card-back">
                                            <img src="{{ asset("storage/" . ($foodItem->image ?? '404.webp')) }}" alt="Ảnh đồ ăn & thức uống {{ $foodItem->name }}" class="booking-food-item-image">
                                            <div class="booking-food-item-content">
                                                <h5 class="booking-food-item-name">{{ $foodItem->name }}</h5>
                                                <p class="booking-food-item-desc">{{ $foodItem->description }}</p>

                                                @if($cartTempVariantId && $cartTempVariant?->food_item_id === $foodItem->id)
                                                    <div class="booking-food-back-variants">
                                                        <h6>Lựa chọn của bạn:</h6>
                                                        <div class="booking-food-variants-summary">
                                                            @foreach($cartTempVariant->variantAttributes as $attribute => $value)
                                                                <div class="booking-food-variant-summary">
                                                                    <span class="variant-label">{{ $attribute }}:</span>
                                                                    <span class="variant-value">{{ $value }}</span>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>

                                                    <div class="booking-food-back-price-info">
                                                        <div class="booking-food-back-unit-price">
                                                            Giá gốc: <span class="booking-food-back-original-price">{{ number_format($cartTempVariant->price, 0, '.', '.') }}đ</span>
                                                        </div>
                                                        <div class="booking-food-back-total-price">
                                                            Tổng tiền: <span class="booking-food-back-final-price" x-text="(($wire.cartTempVariantQuantity || 1) * @json($cartTempVariant->price)).toLocaleString('vi').concat('đ')"></span>
                                                        </div>
                                                    </div>

                                                    @php $availableQuantity = array_key_exists($cartTempVariantId, $carts) ? $carts[$cartTempVariantId][0] : 0; @endphp
                                                    <div class="booking-food-quantity-section">
                                                        <span class="booking-food-quantity-label">Số lượng:</span>
                                                        <div class="booking-food-back-quantity-controls">
                                                            <button class="booking-food-back-btn-minus" @click="$wire.$set('cartTempVariantQuantity', $wire.cartTempVariantQuantity <= 1 ? 1 : --$wire.cartTempVariantQuantity)" :disabled="$wire.cartTempVariantQuantity <= 1">
                                                                <i class="fas fa-minus"></i>
                                                            </button>
                                                            <span class="booking-food-back-quantity" x-text="$wire.cartTempVariantQuantity"></span>
                                                            <button class="booking-food-back-btn-plus" @click="$wire.$set('cartTempVariantQuantity', $wire.cartTempVariantQuantity >= @json($cartTempVariant->quantity_available) ? $wire.cartTempVariantQuantity : ++$wire.cartTempVariantQuantity)" :disabled="$wire.cartTempVariantQuantity >= @json($cartTempVariant->quantity_available - $availableQuantity)">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <!-- Action Buttons -->
                                                    <div class="booking-food-back-actions">
                                                        <button class="booking-food-btn-back-to-variants" onclick="toggleTempCartItem(this, false)">
                                                            <i class="fas fa-arrow-left"></i>
                                                            Quay lại
                                                        </button>
                                                        <button class="booking-food-btn-add-to-cart" @click="$wire.addToCart()" wire:loading.attr="disabled" wire:target="addToCart" :disabled="$wire.cartTempVariantQuantity > @json($cartTempVariant->quantity_available - $availableQuantity) || $wire.cartTempVariantQuantity === 0">
                                                            <div wire:loading.remove wire:target="addToCart">
                                                                <i class="fas fa-plus"></i>
                                                                Thêm vào giỏ
                                                            </div>
                                                            <div wire:loading wire:target="addToCart">
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <div class="spinner"></div>
                                                                    Đang thêm vào giỏ...
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </div>
                                                @else
                                                    <div class="booking-food-back-loading">
                                                        <div class="booking-food-loading-spinner"></div>
                                                        <p class="text-muted mt-3">Đang tải thông tin...</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="booking-food-back-empty">
                                <i class="fas fa-exclamation-circle text-muted mb-3" style="font-size: 48px;"></i>
                                <h6 class="text-muted">Chưa có món ăn nào</h6>
                                <p class="text-muted small">Bạn có thể tiếp tục đặt vé hoặc quay lại sau.</p>
                            </div>
                        @endforelse
                    </div>
                    @if($foodItems->hasPages())
                        <div class="d-flex justify-content-center mt-2">
                            {{ $foodItems->links() }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-4">
                <div class="booking-food-summary-card">
                    <h4 class="booking-food-summary-title">
                        <i class="fas fa-receipt me-2"></i>Tóm tắt đơn hàng
                    </h4>

                    <div class="booking-food-summary-section" wire:ignore>
                        <h6 class="booking-food-summary-subtitle">Thông tin vé</h6>
                        <div class="booking-food-summary-item">
                            <span>{{ $booking->showtime->movie->title }}</span>
                            <span>{{ $booking->seats->count() }} vé</span>
                        </div>
                        <div class="booking-food-summary-item small">
                            <span>{{ mb_ucfirst($booking->showtime->start_time->translatedFormat('l, d/m/Y - H:i'), 'UTF-8') }}</span>
                        </div>
                        <div class="booking-food-summary-item small">
                            <span>Rạp SE7ENCinema - {{ $booking->showtime->room->name }}</span>
                            <span></span>
                        </div>

                        <!-- Chi tiết từng loại ghế -->
                        <div class="booking-food-seat-details">
                            @php $seats = $booking->seats->groupBy('seat_type'); @endphp
                            @if($seats->has('standard'))
                                <div class="booking-food-summary-item">
                                    <div>
                                        <i class="fas fa-couch me-2 text-secondary"></i>
                                        <span>Ghế thường x {{ $seats->get('standard')->count() }}</span>
                                    </div>
                                    <span>{{ number_format($seats->get('standard')->first()->price + ($booking->showtime->movie?->price ?? 0), 0, '.', '.') }}đ</span>
                                </div>
                            @endif
                            @if($seats->has('vip'))
                                <div class="booking-food-summary-item">
                                    <div>
                                        <i class="fas fa-crown me-2 text-warning"></i>
                                        <span>Ghế VIP x {{ $seats->get('vip')->count() }}</span>
                                    </div>
                                    <span>{{ number_format($seats->get('vip')->first()->price + ($booking->showtime->movie?->price ?? 0), 0, '.', '.') }}đ</span>
                                </div>
                            @endif
                            @if($seats->has('couple'))
                                <div class="booking-food-summary-item">
                                    <div>
                                        <i class="fas fa-heart me-2 text-danger"></i>
                                        <span>Ghế đôi x {{ $seats->get('couple')->count() }}</span>
                                    </div>
                                    <span>{{ number_format($seats->get('couple')->first()->price + ($booking->showtime->movie?->price ?? 0), 0, '.', '.') }}đ</span>
                                </div>
                            @endif
                            <div class="booking-food-summary-item fw-bold border-top pt-2" style="margin-bottom: 0;">
                                <span>Tổng tiền vé:</span>
                                <span class="text-primary">{{ number_format($booking->bookingSeats->sum('ticket_price'), 0, '.', '.') }}đ</span>
                            </div>
                        </div>
                    </div>

                    <div class="booking-food-summary-section">
                        <h6 class="booking-food-summary-subtitle">Đồ ăn & thức uống</h6>
                        <div class="booking-food-summary-items">
                            @if(count($carts) > 0)
                                @foreach ($carts as $variantId => [$quantity, $price, $variant])
                                    <div class="booking-food-summary-item" wire:key="cart-variant-{{ $variantId }}">
                                        <div class="flex-grow-1">
                                            <div class="fw-bold">{{ $variant->foodItem->name }}</div>
                                            <div class="text-muted small">{{ $variant->variantAttributes?->map(fn($value, $attribute) => "$attribute: $value")->implode(', ') }}</div>
                                            <div class="booking-food-summary-item-controls">
                                                <div class="booking-food-quantity-control">
                                                    <button class="booking-food-summary-btn-minus" @click="$wire.carts[@json($variantId)]?.[0] && $wire.carts[@json($variantId)][0]--" :disabled="$wire.carts[@json($variantId)]?.[0] <= 1">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                    <span class="booking-food-summary-quantity" x-text="$wire.carts[@json($variantId)]?.[0]"></span>
                                                    <button class="booking-food-summary-btn-plus" @click="$wire.carts[@json($variantId)]?.[0] && $wire.carts[@json($variantId)][0]++" :disabled="$wire.carts[@json($variantId)]?.[0] >= @json($variant->quantity_available)">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                                <button class="booking-food-remove-btn" @click="delete $wire.carts[@json($variantId)]; Object.keys($wire.carts).length === 0 && $wire.$refresh()" onclick="this.closest('.booking-food-summary-item').style.display = 'none'">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <span class="fw-bold" x-text="($wire.carts[@json($variantId)]?.[0] * @json($variant->price)).toLocaleString('vi').concat('đ')"></span>
                                            <div class="text-muted small">{{ number_format($variant->price, 0, '.', '.') }}đ x <span x-text="$wire.carts[@json($variantId)]?.[0]"></span></div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="booking-food-summary-empty">
                                    <i class="fas fa-utensils text-muted"></i>
                                    <p class="text-muted mb-0">Chưa chọn đồ ăn nào</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="booking-food-summary-total" wire:ignore>
                        <div class="d-flex justify-content-between fw-bold fs-5">
                            <span>Tổng cộng:</span>
                            <span id="total" class="text-danger" x-text="(Object.values($wire.carts).reduce((total, [quantity, price]) => total + (quantity * (price ?? 0)), 0) + @json($booking->bookingSeats->sum('ticket_price'))).toLocaleString('vi').concat('đ')"></span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="booking-food-summary-actions">
                        <a class="booking-food-btn-back" href="{{ route('client.booking.select_seats', $booking->showtime_id) }}">
                            <i class="fas fa-arrow-left me-2"></i>Quay lại
                        </a>
                        <button class="booking-food-btn-next" wire:click="nextStep">
                            Tiếp theo
                            <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
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
