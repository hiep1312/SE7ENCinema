<div>
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="container-lg mb-4">
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Chỉnh sửa món ăn: {{ $foodItem->name }}</h2>
            <a href="{{ route('admin.foods.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>

        <!-- Form thông tin món ăn -->
        <div class="row">
            <div class="col-12">
                <div class="card bg-dark">
                    <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h5 class="my-1">Thông tin món ăn</h5>
                    </div>
                    <div class="card-body bg-dark">
                        <form wire:submit.prevent="updateFood" enctype="multipart/form-data">
                            <div class="row align-items-start">
                                <div class="col-md-3 mb-3">
                                        <div class="mt-1 overflow-auto position-relative" style="max-height: 230px;">
                                            <img src="{{ asset('storage/' . ($foodItem->image ?? '404.webp')) }}" alt="Ảnh món ăn hiện tại" class="img-thumbnail"
                                            style="width: 100%;">
                                            <span class="position-absolute opacity-75 top-0 start-0 mt-2 ms-2 badge rounded bg-danger">
                                                Ảnh hiện tại
                                            </span>
                                        </div>
                                    @if ($image && $image instanceof Illuminate\Http\UploadedFile)
                                        <div class="mt-2 overflow-auto position-relative" style="max-height: 230px;">
                                            <img src="{{ $image->temporaryUrl() }}" alt="Ảnh món ăn tải lên" class="img-thumbnail"
                                                style="width: 100%;">
                                            <span class="position-absolute opacity-75 top-0 start-0 mt-2 ms-2 badge rounded bg-success">
                                                Ảnh mới
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-9 row align-items-start">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label text-light">Tên món ăn *</label>
                                            <input type="text"
                                                id = "name"
                                                wire:model="name"
                                                class="form-control bg-dark text-light border-light @error('name') is-invalid @enderror"
                                                placeholder="VD: Bắp rang bơ">
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="status" class="form-label text-light">Trạng thái *</label>
                                            <select id="status" wire:model="status" class="form-select bg-dark text-light border-light @error('status') is-invalid @enderror">
                                                <option value="activate">Đang bán</option>
                                                <option value="discontinued">Ngừng bán</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="description" class="form-label text-light">Mô tả</label>
                                            <textarea id="description" wire:model="description" class="form-control bg-dark text-light border-light @error('description') is-invalid @enderror" placeholder="VD: Bắp rang bơ vị ngọt, size lớn, thích hợp cho 2 người"></textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="image" class="form-label text-light">Ảnh món ăn</label>
                                            <input type="file"
                                                id = "image"
                                                wire:model.live="image"
                                                class="form-control bg-dark text-light border-light @error('image') is-invalid @enderror"
                                                accept="image/*">
                                            @error('image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="quantityVariants" class="form-label text-light">Số lượng biến thể</label>
                                            <input type="number"
                                                id = "quantityVariants"
                                                wire:model.live.debounce.200ms="quantityVariants"
                                                class="form-control bg-dark text-light border-light @error('quantityVariants') is-invalid @enderror"
                                                placeholder="VD: 1, 2, ..." min="0">
                                            @error('quantityVariants')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($quantityVariants > 0)
                                <hr class="border-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="text-light">Biến thể món ăn</h5>
                                    <button type="button" wire:click="getVariantsCurrent({{ $foodItem }})" class="btn btn-outline-warning">
                                        <i class="fas fa-refresh me-1"></i>Reset
                                    </button>
                                </div>
                                <div class="row g-3 p-3">
                                    @foreach ($this->listVariants as $index => $variant)
                                        <div class="col-12 mb-4">
                                            <div class="card position-relative overflow-hidden" style="background-color: #1a1a1a; border: 1px solid #333;">
                                                <!-- Accent line -->
                                                <div class="position-absolute top-0 start-0 h-100" style="width: 4px; background: linear-gradient(to bottom, #6b7280, #374151);"></div>

                                                <button type="button"
                                                        class="btn btn-sm position-absolute delete-btn"
                                                        wire:click="removeVariant({{ $index }})"
                                                        style="top: 1rem; right: 1rem; color: #6b7280; background: transparent; border: none; border-radius: 50%; padding: 0.5rem; transition: all 0.2s ease;">
                                                        <i class="fa-solid fa-x" style="margin-right: 0"></i></button>

                                                <div class="card-body p-4">
                                                    <div class="mb-4">
                                                        <h3 class="text-white fw-semibold d-flex align-items-center gap-2" style="font-size: 1.125rem;">
                                                            <span class="d-flex align-items-center justify-content-center text-white fw-bold rounded-circle"
                                                                style="width: 2rem; height: 2rem; background: linear-gradient(to right, #6b7280, #374151); font-size: 0.875rem;">
                                                                {{ $index + 1 }}
                                                            </span>
                                                            Biến thể {{ $index + 1 }}
                                                        </h3>
                                                    </div>

                                                    <div class="row g-3 align-items-start">
                                                        @php $columnLayoutVariant = 'col-sm-6 col-md-4' @endphp
                                                        @if(isset($variant['id']) || $variant['image']) @php $columnLayoutVariant = 'col-md-6' @endphp <div class="col-md-3"> @endif
                                                        @isset($variant['id'])
                                                            <div class="mt-1 overflow-auto position-relative" style="max-height: 230px;">
                                                                <img src="{{ asset('storage/' . ($foodItem->variants->firstWhere('id', $variant['id'])->image ?? '404.webp')) }}" alt="Ảnh biến thể hiện tại" class="img-thumbnail"
                                                                    style="width: 100%">
                                                                <span class="position-absolute opacity-75 top-0 start-0 mt-2 ms-2 badge rounded bg-danger">
                                                                    Ảnh hiện tại
                                                                </span>
                                                            </div>
                                                        @endisset
                                                        @if($variant['image'] && $variant['image'] instanceof Illuminate\Http\UploadedFile)
                                                            <div class="@if(isset($variant['id'])) mt-2 @else mt-1 @endif overflow-auto position-relative" style="max-height: 230px;">
                                                                <img src="{{ $variant['image']->temporaryUrl() }}" alt="Ảnh biến thể tải lên" class="img-thumbnail"
                                                                    style="width: 100%">
                                                                <span class="position-absolute opacity-75 top-0 start-0 mt-2 ms-2 badge rounded bg-success">
                                                                    Ảnh mới
                                                                </span>
                                                            </div>
                                                        @endif
                                                        @if(isset($variant['id']) || $variant['image']) </div><div class="col-sm-9 row g-2"> @endif
                                                        <div class="{{ $columnLayoutVariant }}">
                                                            <div class="mb-3">
                                                                <label for="variants.{{ $index }}.name" class="form-label text-light">Tên biến thể *</label>
                                                                <input type="text"
                                                                    id = "variants.{{ $index }}.name"
                                                                    wire:model="variants.{{ $index }}.name"
                                                                    class="form-control bg-dark text-light border-light @error("variants.$index.name") is-invalid @enderror"
                                                                    placeholder="VD: Màu đỏ, Size M...">
                                                                @error("variants.$index.name")
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="{{ $columnLayoutVariant }}">
                                                            <div class="mb-3">
                                                                <label for="variants.{{ $index }}.price" class="form-label text-light">Giá *</label>
                                                                <input type="text"
                                                                    id = "variants.{{ $index }}.price"
                                                                    wire:model="variants.{{ $index }}.price"
                                                                    class="form-control bg-dark text-light border-light @error("variants.$index.price") is-invalid @enderror"
                                                                    placeholder="VD: 100000đ" min="0">
                                                                @error("variants.$index.price")
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="{{ $columnLayoutVariant }}">
                                                            <div class="mb-3">
                                                                <label for="variants.{{ $index }}.quantity" class="form-label text-light">Số lượng *</label>
                                                                <input type="number"
                                                                    id = "variants.{{ $index }}.quantity"
                                                                    wire:model="variants.{{ $index }}.quantity"
                                                                    class="form-control bg-dark text-light border-light @error("variants.$index.quantity") is-invalid @enderror"
                                                                    placeholder="VD: 100" min="0"
                                                                    name="temp">
                                                                @error("variants.$index.quantity")
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="{{ $columnLayoutVariant }}">
                                                            <div class="mb-3">
                                                                <label for="variants.{{ $index }}.limit" class="form-label text-light">Giới hạn số lượng nhập </label>
                                                                <input type="number"
                                                                    id = "variants.{{ $index }}.limit"
                                                                    wire:model="variants.{{ $index }}.limit"
                                                                    class="form-control bg-dark text-light border-light @error("variants.$index.limit") is-invalid @enderror"
                                                                    placeholder="VD: 300" min="0">
                                                                @error("variants.$index.limit")
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="{{ $columnLayoutVariant }}">
                                                            <div class="mb-3">
                                                                <label for="variants.{{ $index }}.status" class="form-label text-light">Trạng thái *</label>
                                                                <select id="variants.{{ $index }}.status" wire:model="variants.{{ $index }}.status" class="form-select bg-dark text-light border-light @error('variants.{{ $index }}.status') is-invalid @enderror">
                                                                    <option value="available">Còn hàng</option>
                                                                    <option value="out_of_stock">Hết hàng</option>
                                                                    <option value="hidden">Ẩn</option>
                                                                </select>
                                                                @error('variants.{{ $index }}.status')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="{{ $columnLayoutVariant }}">
                                                            <div class="mb-3">
                                                                <label for="variants.{{ $index }}.image" class="form-label text-light">Ảnh biến thể </label>
                                                                <input type="file"
                                                                    id = "variants.{{ $index }}.image"
                                                                    wire:model.live="variants.{{ $index }}.image"
                                                                    class="form-control bg-dark text-light border-light @error('variants.{{ $index }}.image') is-invalid @enderror"
                                                                    accept="image/*">
                                                                @error('variants.{{ $index }}.image')
                                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        @if(isset($variant['id']) || $variant['image']) </div> @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Cập nhật thông tin
                                </button>
                                <a href="{{ route('admin.foods.index') }}" class="btn btn-outline-danger">
                                    Hủy bỏ
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
