<div class="scRender container py-4">
    <div class="row row-cols-1 row-cols-md-3 g-4">
        @foreach ($this->foodItems() as $food)
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $food->name }}</h5>
                        <p class="card-text">{{ $food->description }}</p>

                        <button class="btn btn-primary btn-sm" wire:click="selectFood({{ $food->id }})">
                            Chọn món
                        </button>

                        @if ($selectedFoodId === $food->id)
                            <div class="mt-3 border-top pt-3">
                                @php
                                    $attributes = $food->variants
                                        ->flatMap(fn($v) => $v->attributeValues)
                                        ->groupBy(fn($val) => $val->attribute->name);
                                @endphp

                                @foreach ($attributes as $attributeName => $values)
                                    <div class="mb-2">
                                        <strong>{{ $attributeName }}:</strong><br>
                                        @foreach ($values->unique('value') as $value)
                                            <button
                                                wire:click="selectAttribute('{{ $attributeName }}', '{{ $value->value }}')"
                                                class="btn btn-outline-secondary btn-sm mb-1
                                                    {{ isset($selectedAttributes[$attributeName]) && $selectedAttributes[$attributeName] === $value->value ? 'active' : '' }}">
                                                {{ $value->value }}
                                            </button>
                                        @endforeach
                                    </div>
                                @endforeach

                                @if ($selectedVariant)
                                    <div class="alert alert-success p-2">
                                        SKU: {{ $selectedVariant->sku }} <br>
                                        Giá: {{ number_format($selectedVariant->price) }}₫
                                    </div>
                                    <button wire:click="addToCart" class="btn btn-success btn-sm">
                                        Thêm vào giỏ
                                    </button>
                                    @if ($errors->has('variant'))
                                        <div class="alert alert-danger mt-2">
                                            {{ $errors->first('variant') }}
                                        </div>
                                    @endif
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if (count($cart) > 0)
        <div class="mt-4 p-3 border rounded shadow-sm bg-light">
            <h5>🛒 Giỏ hàng</h5>
            <table class="table table-sm align-middle">
                <thead>
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
                                    <button wire:click="decrement('{{ $sku }}')"
                                        class="btn btn-outline-secondary">-</button>
                                    <button class="btn btn-light">{{ $item['quantity'] }}</button>
                                    <button wire:click="increment('{{ $sku }}')"
                                        class="btn btn-outline-secondary">+</button>
                                </div>
                            </td>
                            <td>{{ number_format($item['price'] * $item['quantity']) }}₫</td>
                            <td><button wire:click="remove('{{ $sku }}')"
                                    class="btn btn-danger btn-sm">X</button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="fw-bold">Tổng: {{ number_format($this->total) }}₫</div>
        </div>

        @if (count($cart) > 0)
            <button wire:click="goToCheckout" class="btn btn-primary mt-2">
                Đi đến thanh toán
            </button>
        @endif

    @endif
</div>
