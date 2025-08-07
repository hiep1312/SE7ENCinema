<div class="scRender">
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="container-lg mb-4">
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Chỉnh sửa biến thể: {{ $foodItem->name }}</h2>
            <a href="{{ route('admin.food_variants.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>

        <form wire:submit.prevent="save" enctype="multipart/form-data">
            <div class="card bg-dark">
                <div class="card-header bg-gradient text-light"
                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h5 class="my-1">Thông tin biến thể</h5>
                </div>

                <div class="card-body bg-dark">
                    <div class="row">
                        {{-- Ảnh hiện tại & mới --}}
                        <div class="col-md-4 mb-4">
                            <div class="position-relative overflow-hidden rounded border" style="max-height: 250px;">
                                <img src="{{ asset('storage/' . ($variant->image ?? '404.webp')) }}"
                                    alt="Ảnh biến thể hiện tại" class="img-fluid w-100">
                                <span class="position-absolute top-0 start-0 m-2 badge bg-danger">
                                    Ảnh hiện tại
                                </span>
                            </div>

                            @if ($image && $image instanceof Illuminate\Http\UploadedFile)
                                <div class="position-relative overflow-hidden rounded border mt-3"
                                    style="max-height: 250px;">
                                    <img src="{{ $image->temporaryUrl() }}" alt="Ảnh biến thể mới"
                                        class="img-fluid w-100">
                                    <span class="position-absolute top-0 start-0 m-2 badge bg-success">
                                        Ảnh mới
                                    </span>
                                </div>
                            @endif
                        </div>

                        {{-- Phần form --}}
                        <div class="col-md-8 row">

                            <div class="col-12 mb-3">
                                <h5 class="text-light">Thuộc tính</h5>
                                @foreach ($attributeVariant as $index => $attr)
                                    <div class="row g-2 align-items-end border border-secondary rounded p-2 mb-2">
                                        <div class="col">
                                            <label class="form-label text-light">Tên thuộc tính *</label>
                                            <input readonly type="text"
                                                wire:model="attributeVariant.{{ $index }}.attribute_name"
                                                class="form-control bg-dark text-light border-secondary @error("attributeVariant.$index.attribute_name") is-invalid @enderror">
                                            @error("attributeVariant.$index.attribute_name")
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col">
                                            <label class="form-label text-light">Giá trị *</label>
                                            <input readonly type="text"
                                                wire:model="attributeVariant.{{ $index }}.value"
                                                class="form-control bg-dark text-light border-secondary @error("attributeVariant.$index.value") is-invalid @enderror">
                                            @error("attributeVariant.$index.value")
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-light">Tên sản phẩm liên kết</label>
                                <div class="border border-danger rounded px-3 py-2 text-light">
                                    {{ $name }}
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label text-light">Trạng thái <span
                                        class="text-danger">*</span></label>
                                <select wire:model.defer="status"
                                    class="form-select bg-dark text-light border-secondary @error('status') is-invalid @enderror">
                                    <option value="available">Đang bán</option>
                                    <option value="out_of_stock">Hết hàng</option>
                                    <option value="hidden">Ẩn</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label text-light">Giá <span class="text-danger">*</span></label>
                                <input type="number" wire:model.defer="price"
                                    class="form-control bg-dark text-light border-secondary @error('price') is-invalid @enderror"
                                    placeholder="VD: 25000" min="0">
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label text-light">Số lượng <span class="text-danger">*</span></label>
                                <input type="number" wire:model.defer="quantity_available"
                                    class="form-control bg-dark text-light border-secondary @error('quantity_available') is-invalid @enderror"
                                    min="0">
                                @error('quantity_available')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label text-light">Tải ảnh mới</label>
                                <input type="file" wire:model="image"
                                    class="form-control bg-dark text-light border-secondary @error('image') is-invalid @enderror"
                                    accept="image/*">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label text-light">Giới hạn</label>
                                <input type="number" wire:model.defer="limit"
                                    class="form-control bg-dark text-light border-secondary @error('limit') is-invalid @enderror"
                                    min="0">
                                @error('limit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-3">
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
