@assets
    @vite(['resources/css/selectfood.css'])
@endassets
<div class="scRender scSelectfood container py-5 mt-5" style="width:85%; height:auto; padding-top:9rem">
    <div class="main-header text-center py-4">
        <h1
            class="main-header__title d-inline-flex align-items-center justify-content-center gap-2 fs-2 fw-bold text-primary-emphasis mt-5">
            <i class="bi bi-camera-reels fs-3 text-warning"></i>
            Chọn đồ ăn kèm
        </h1>
        <p class="main-header__subtitle mt-2 text-secondary-emphasis lh-sm">Thưởng thức những món ăn ngon nhất cùng bộ
            phim của bạn</p>
    </div>

    <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-4 mb-5">
        @foreach ($this->foodItems() as $food)
            @php
                $prices = $food->variants->pluck('price')->sort()->values();
                $minPrice = $prices->first();
                $maxPrice = $prices->last();
            $isOutOfStock = $food->variants->sum('quantity_available') <= 0; @endphp <div class="col">
                <div class="card food-card h-100 position-relative">
                    <img src="{{ $food->image ? asset('storage/' . $food->image) : 'https://via.placeholder.com/300x200?text=No+Image' }}"
                        alt="{{ $food->name }}" class="card-img-top">

                    {{-- Badge hết hàng --}}
                    @if ($isOutOfStock)
                        <span class="position-absolute top-0 start-0 bg-danger text-white px-2 py-1 small rounded-end">
                            Hàng đã hết
                        </span>
                    @endif

                    <div class="card-body d-flex flex-column">
                        <div>
                            <h6 class="card-title">{{ $food->name }}</h6>
                            <p class="food-description">{{ $food->description }}</p>
                            <p class="food-price">
                                Giá:
                                @if ($minPrice === $maxPrice)
                                    {{ number_format($minPrice) }}₫
                                @else
                                    {{ number_format($minPrice) }}₫ - {{ number_format($maxPrice) }}₫
                                @endif
                            </p>
                        </div>

                        <button class="btn btn-select-food mt-auto" wire:click="selectFood({{ $food->id }})"
                            {{ $isOutOfStock ? 'disabled' : '' }}>
                            <i class="bi bi-plus-circle me-1"></i> Chọn món
                        </button>

                        @if ($selectedFoodId === $food->id)
                            <div class="attributes-section mt-3">
                                @php
                                    $attributes = $food->variants
                                        ->flatMap(fn($v) => $v->attributeValues)
                                        ->groupBy(fn($val) => $val->attribute->name);
                                @endphp

                                @foreach ($attributes as $attributeName => $values)
                                    <div class="mb-3">
                                        <label class="attribute-label">{{ $attributeName }}:</label>
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach ($values->unique('value') as $value)
                                                <button type="button"
                                                    wire:click="selectAttribute('{{ $attributeName }}', '{{ $value->value }}')"
                                                    class="attribute-btn {{ isset($selectedAttributes[$attributeName]) && $selectedAttributes[$attributeName] === $value->value ? 'active' : '' }}">
                                                    {{ $value->value }}
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach

                                @if ($selectedVariant)
                                    <div class="variant-info">
                                        <div class="text-success mb-2">
                                            <div><strong>SKU:</strong> {{ $selectedVariant->sku }}</div>
                                            <div><strong>Giá:</strong> {{ number_format($selectedVariant->price) }}₫
                                            </div>
                                            <div><strong>Tồn kho:</strong> {{ $selectedVariant->quantity_available }}
                                            </div>
                                        </div>

                                        @if ($selectedVariant->quantity_available > 0)
                                            <button wire:click="addToCart" class="btn btn-add-to-cart">
                                                <i class="bi bi-cart-plus me-1"></i> Thêm vào giỏ
                                            </button>
                                        @else
                                            <div class="text-danger small fw-semibold">Sản phẩm đã hết hàng</div>
                                        @endif


                                        <div class="text-danger mt-2">
                                            @error('variant')
                                                <span class="error-message">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>


    @if (count($cart) > 0)
        <div class="cart-section mb-5">
            <h3 class="cart-title">
                <i class="bi bi-cart3 me-2"></i>Giỏ hàng của bạn
            </h3>

            <div class="cart-items-container">
                @foreach ($cart as $sku => $item)
                    <div class="cart-item">
                        <div class="row align-items-center">
                            <div class="col-md-4">
                                <div class="item-name">{{ $item['name'] }}</div>
                                <div class="item-attributes">
                                    @foreach ($item['attributes'] as $k => $v)
                                        <span class="badge badge-attr">{{ $k }}: {{ $v }}</span>
                                    @endforeach
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="item-controls">
                                    <div class="quantity-controls">
                                        <button wire:click="decrement('{{ $sku }}')" class="quantity-btn">
                                            <i class="bi bi-dash">-</i>
                                        </button>
                                        <span class="quantity-display">{{ $item['quantity'] }}</span>
                                        <button wire:click="increment('{{ $sku }}')" class="quantity-btn">
                                            <i class="bi bi-plus">+</i>
                                        </button>
                                    </div>

                                    <div class="item-price">
                                        {{ number_format($item['price'] * $item['quantity']) }}₫
                                    </div>

                                    <button wire:click="remove('{{ $sku }}')" class="btn btn-remove">
                                        <i class="fas fa-trash-alt" style="margin-right: 0"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="total-section">
                <div class="total-label">Tổng tiền thanh toán</div>
                <div class="total-amount">{{ number_format($this->total) }}₫</div>
            </div>

            <div class="action-buttons">
                <button wire:click="goToCheckout" class="btn btn-checkout">
                    <i class="bi bi-credit-card me-1"></i> Thanh toán
                </button>
                <button wire:click="skipFood" class="btn btn-skip">
                    <i class="fa-solid fa-arrow-left mx-2"></i> Bỏ qua
                </button>
            </div>
        </div>
    @endif
    @if (count($cart) === 0)
        <div class="action-buttons mb-3">
            <button wire:click="skipFood" class="btn btn-skip">
                <i class="fa-solid fa-arrow-left mx-2"></i> Bỏ qua
            </button>
        </div>
    @endif


</div>
