<div class="scRender container py-4">

    <style>
        .card.food-card {
            transition: all 0.3s ease-in-out;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .card.food-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }

        .food-card img {
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            object-fit: cover;
            height: 200px;
        }

        .food-card .card-body {
            padding: 1rem 1.2rem;
        }

        .food-card .card-title {
            font-weight: 600;
            font-size: 1.1rem;
        }

        .food-card .card-text {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .btn-select-food {
            border-radius: 20px;
            font-size: 0.85rem;
            padding: 0.4rem 1.1rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-select-food i {
            font-size: 0.95rem;
        }
    </style>

    <h3 class="mb-4 fw-bold text-primary">🍿 Chọn đồ ăn kèm</h3>

    <div class="row row-cols-1 row-cols-md-3 g-4">
        @foreach ($this->foodItems() as $food)
            <div class="col">
                <div class="card h-100 border-0 food-card">
                    @if ($food->image)
                        <img src="{{ asset('storage/' . $food->image) }}" class="card-img-top" alt="{{ $food->name }}">
                    @else
                        <img src="https://via.placeholder.com/300x200?text=No+Image" class="card-img-top" alt="No image">
                    @endif

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title text-primary">{{ $food->name }}</h5>
                        <p class="card-text">{{ $food->description }}</p>

                        <button class="btn btn-outline-primary btn-select-food mt-auto" wire:click="selectFood({{ $food->id }})">
                            <i class="bi bi-plus-circle"></i> Chọn món
                        </button>

                        @if ($selectedFoodId === $food->id)
                            <div class="mt-3 pt-3 border-top">
                                @php
                                    $attributes = $food->variants
                                        ->flatMap(fn($v) => $v->attributeValues)
                                        ->groupBy(fn($val) => $val->attribute->name);
                                @endphp

                                @foreach ($attributes as $attributeName => $values)
                                    <div class="mb-2">
                                        <label class="form-label fw-bold">{{ $attributeName }}:</label><br>
                                        <div class="btn-group flex-wrap" role="group">
                                            @foreach ($values->unique('value') as $value)
                                                <button type="button"
                                                    wire:click="selectAttribute('{{ $attributeName }}', '{{ $value->value }}')"
                                                    class="btn btn-sm btn-outline-secondary me-1 mb-1
                                                        {{ isset($selectedAttributes[$attributeName]) && $selectedAttributes[$attributeName] === $value->value ? 'active' : '' }}">
                                                    {{ $value->value }}
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach

                                @if ($selectedVariant)
                                    <div class="alert alert-success py-2 px-3 mt-2 small">
                                        <div><strong>SKU:</strong> {{ $selectedVariant->sku }}</div>
                                        <div><strong>Giá:</strong> {{ number_format($selectedVariant->price) }}₫</div>
                                    </div>
                                    <button wire:click="addToCart" class="btn btn-success btn-sm">
                                        ➕ Thêm vào giỏ
                                    </button>
                                @endif

                                @if ($errors->has('variant'))
                                    <div class="alert alert-danger mt-2">
                                        {{ $errors->first('variant') }}
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
        <div class="mt-5 p-4 border rounded shadow-sm bg-white">
            <h4 class="fw-bold text-success mb-3">🛒 Giỏ hàng</h4>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle small">
                    <thead class="table-light">
                        <tr>
                            <th>Món</th>
                            <th>Thuộc tính</th>
                            <th>Số lượng</th>
                            <th>Giá</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cart as $sku => $item)
                            <tr>
                                <td>{{ $item['name'] }}</td>
                                <td>
                                    @foreach ($item['attributes'] as $k => $v)
                                        <span class="badge bg-secondary">{{ $k }}: {{ $v }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button wire:click="decrement('{{ $sku }}')" class="btn btn-outline-secondary">-</button>
                                        <button class="btn btn-light" disabled>{{ $item['quantity'] }}</button>
                                        <button wire:click="increment('{{ $sku }}')" class="btn btn-outline-secondary">+</button>
                                    </div>
                                </td>
                                <td>{{ number_format($item['price'] * $item['quantity']) }}₫</td>
                                <td>
                                    <button wire:click="remove('{{ $sku }}')" class="btn btn-sm btn-danger">🗑️</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="fw-bold fs-5 text-end mt-3">
                Tổng tiền đồ ăn: <span class="text-danger">{{ number_format($this->total) }}₫</span>
            </div>
            <div class="d-flex justify-content-end gap-2 mt-3">
                <button wire:click="goToCheckout" class="btn btn-primary">✅ Tiến hành thanh toán</button>
            </div>
        </div>
    @endif
    <button wire:click="skipFood" class="btn btn-outline-secondary">⏭️ Bỏ qua</button>
</div>