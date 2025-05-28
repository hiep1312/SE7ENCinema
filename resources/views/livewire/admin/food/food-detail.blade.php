<div class="container py-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="card-title h3 mb-4 border-bottom pb-2">Chi tiết món ăn</h2>

            {{-- Thông tin món ăn --}}
            <div class="row mb-4">
                {{-- Ảnh món ăn --}}
                @if ($foodItem->image)
                    <div class="col-md-4 text-center">
                        <img width="200px" src="{{ asset('storage/' . $foodItem->image) }}" alt="{{ $foodItem->name }}"
                            class="img-fluid rounded border">
                    </div>
                @endif

                {{-- Thông tin cơ bản --}}
                <div class="col-md-8">
                    <h4 class="mb-3">{{ $foodItem->name }}</h4>
                    <p class="text-muted">{{ $foodItem->description }}</p>
                    <input type="text" id="status" class="form-control mb-3"
                        value="{{ $foodItem->status === 'activate' ? 'Hoạt động' : 'Không hoạt động' }}" readonly>
                </div>
            </div>

            {{-- Biến thể món ăn --}}
            <h5 class="mb-3">Các kích cỡ món ăn</h5>

            @if ($foodItem->variants && $foodItem->variants->count())
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Ảnh</th>
                                <th scope="col">Tên size</th>
                                <th scope="col">Giá</th>
                                <th scope="col">Số lượng có sẵn</th>
                                <th scope="col">Số lượng giới hạn</th>
                                <th scope="col">Trạng thái biến thể</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($foodItem->variants as $variant)
                                <tr>
                                    <td><img width="100px" src="{{ asset('storage/' . $variant->image) }}"
                                            alt="{{ $variant->name }}" class="img-fluid rounded border">
                                    </td>
                                    <td>{{ $variant->name }}</td>
                                    <td>{{ number_format($variant->price, 0, ',', '.') }}₫</td>
                                    <td>{{ $variant->quantity_available }}</td>
                                    <td>{{ $variant->limit }}</td>
                                    <td>{{ $variant->status }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted fst-italic">Món ăn này chưa có kích cỡ nào được định nghĩa.</p>
            @endif

            {{-- Nút quay lại --}}
            <div class="mt-4">
                <a href="{{ route('admin.food.edit', $foodItem->id) }}" class="btn btn-warning">Sửa</a>
                <a href="{{ route('admin.food.list') }}" class="btn btn-secondary">
                    ← Quay lại danh sách
                </a>
            </div>
        </div>
    </div>
</div>
