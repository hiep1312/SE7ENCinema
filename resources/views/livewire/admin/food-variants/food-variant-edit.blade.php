<div>
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="container-lg mb-4">
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Chỉnh sửa biến thể: {{ $variantItem->name }}</h2>
            <a href="{{ route('admin.food_variants.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card bg-dark">
                    <div class="card-header bg-gradient text-light"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h5 class="my-1">Thông tin biến thể món ăn</h5>
                    </div>
                    <div class="card-body bg-dark">
                        <form wire:submit.prevent="updateVariant" enctype="multipart/form-data">
                            <div class="row align-items-start">
                                <div class="col-md-3 mb-3">
                                    <div class="mt-1 overflow-auto position-relative" style="max-height: 230px;">
                                        <img src="{{ asset('storage/' . ($variantItem->image ?? '404.webp')) }}"
                                            alt="Ảnh biến thể hiện tại" class="img-thumbnail" style="width: 100%;">
                                        <span
                                            class="position-absolute opacity-75 top-0 start-0 mt-2 ms-2 badge rounded bg-danger">
                                            Ảnh hiện tại
                                        </span>
                                    </div>
                                    @if ($image && $image instanceof Illuminate\Http\UploadedFile)
                                        <div class="mt-2 overflow-auto position-relative" style="max-height: 230px;">
                                            <img src="{{ $image->temporaryUrl() }}" alt="Ảnh biến thể tải lên"
                                                class="img-thumbnail" style="width: 100%;">
                                            <span
                                                class="position-absolute opacity-75 top-0 start-0 mt-2 ms-2 badge rounded bg-success">
                                                Ảnh mới
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="name" class="form-label text-light">Tên biến thể *</label>
                                                <input type="text" id="name" wire:model="name"
                                                    class="form-control bg-dark text-light border-light @error('name') is-invalid @enderror"
                                                    placeholder="VD: Size M, Màu đỏ...">
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="foodItemId" class="form-label text-light">Món ăn liên kết *</label>
                                                <select id="foodItemId" wire:model.live="foodItemId"
                                                    class="form-select bg-dark text-light border-light @error('foodItemId') is-invalid @enderror">
                                                    <option value="">--- Chọn món ăn ---</option>
                                                    @foreach ($foods as $food)
                                                        <option value="{{ $food->id }}" class="">{{ $food->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('foodItemId')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="price" class="form-label text-light">Giá *</label>
                                                <input type="number" id="price" wire:model="price"
                                                    class="form-control bg-dark text-light border-light @error('price') is-invalid @enderror"
                                                    placeholder="VD: 100000đ" min="0">
                                                @error('price')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-4">
                                                <label for="quantityVariants" class="form-label text-light">Số lượng *</label>
                                                <input type="number" id="quantity" wire:model="quantity"
                                                    class="form-control bg-dark text-light border-light @error('quantity') is-invalid @enderror"
                                                    placeholder="VD: 100" min="0">
                                                @error('quantity')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-4">
                                                <label for="quantityVariants" class="form-label text-light">Giới hạn số lượng nhập </label>
                                                <input type="number" id="limit" wire:model="limit"
                                                    class="form-control bg-dark text-light border-light @error('limit') is-invalid @enderror"
                                                    placeholder="VD: 300" min="0">
                                                @error('limit')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="status" class="form-label text-light">Trạng thái *</label>
                                                <select id="status" wire:model="status"
                                                    class="form-select bg-dark text-light border-light @error('status') is-invalid @enderror">
                                                    <option value="available">Còn hàng</option>
                                                    <option value="out_of_stock">Hết hàng</option>
                                                    <option value="hidden">Ẩn</option>
                                                </select>
                                                @error('status')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="image" class="form-label text-light">Ảnh biến thể </label>
                                                <input type="file" id="image" wire:model.live="image"
                                                    class="form-control bg-dark text-light border-light @error('image') is-invalid @enderror"
                                                    accept="image/*">
                                                @error('image')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if ($relatedVariants)
                                <div class="row mt-2 mb-4">
                                    <div class="col-12">
                                        <div class="card bg-dark border-light">
                                            <div class="card-header bg-gradient text-light"
                                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                                <h5><i class="fas fa-layer-group me-2"></i>Danh sách biến thể cùng loại</h5>
                                            </div>
                                            <div class="card-body bg-dark"
                                                style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                                                <div class="table-responsive">
                                                    <table class="table table-dark table-striped table-hover text-light border">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center text-light">Tên biến thể</th>
                                                                <th class="text-center text-light">Ảnh</th>
                                                                <th class="text-center text-light">Giá</th>
                                                                <th class="text-center text-light">Số lượng</th>
                                                                <th class="text-center text-light">Giới hạn nhập</th>
                                                                <th class="text-center text-light">Trạng thái</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse ($relatedVariants as $variant)
                                                                <tr wire:key="{{ $variant->id }}">
                                                                    <td class="text-center">
                                                                        <strong class="text-light">{{ $variant->name }}</strong>
                                                                    </td>
                                                                    <td class="d-flex justify-content-center">
                                                                        <div class="mt-1 overflow-auto d-block"
                                                                            style="max-height: 70px; width: 100px;">
                                                                            <img src="{{ asset('storage/' . ($variant->image ?? '404.webp')) }}"
                                                                                alt="Ảnh biến thể" class="rounded"
                                                                                style="width: 100%; height: auto;">
                                                                        </div>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        {{ number_format($variant->price, 0, ',', '.') }}đ</td>
                                                                    <td class="text-center">
                                                                        {{ number_format($variant->quantity_available, 0, ',', '.') }}
                                                                    </td>
                                                                    <td class="text-center">
                                                                        @if ($variant->limit)
                                                                            <span class="text-light">{{ number_format($variant->limit, 0, ',', '.') }}</span>
                                                                        @else
                                                                            <span class="text-muted">Không có giới hạn</span>
                                                                        @endif
                                                                    </td>
                                                                    <td class="text-center">
                                                                        @switch($variant->status)
                                                                            @case('available')
                                                                                <span class="badge bg-success">Còn hàng</span>
                                                                            @break

                                                                            @case('hidden')
                                                                                <span class="badge bg-warning text-dark">Ẩn</span>
                                                                            @break

                                                                            @case('out_of_stock')
                                                                                <span class="badge bg-danger">Hết hàng</span>
                                                                            @break
                                                                        @endswitch
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="6" class="text-center py-4">
                                                                        <div class="text-muted">
                                                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                                                            <p>Không có biến thể cùng loại nào</p>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Cập nhật thông tin
                                </button>
                                <a href="{{ route('admin.food_variants.index') }}" class="btn btn-outline-danger">
                                    Hủy bỏ
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
