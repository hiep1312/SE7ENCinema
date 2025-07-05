{{-- resources/views/livewire/admin/foods/edit-food.blade.php --}}
<div>
    {{-- Session Messages --}}
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session()->has('success_general'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success_general') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif


    <div class="container-lg mb-4">
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Chỉnh sửa món ăn: {{ $foodItem->name }}</h2>
            <a href="{{ route('admin.foods.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>

        {{-- Form --}}
        <form wire:submit.prevent="save" enctype="multipart/form-data">
            <div class="row">
                <div class="col-12">
                    <div class="card bg-dark text-light">
                        <div class="card-header bg-gradient"
                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <h5 class="my-1">Thông tin chung</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                {{-- Cột ảnh --}}
                                <div class="col-md-4 mb-4">
                                    <label class="form-label">Ảnh đại diện món ăn</label>
                                    <div class="position-relative overflow-hidden rounded border mb-3"
                                        style="height: 250px;">
                                        <img src="{{ asset('storage/' . ($foodItem->image ?? 'images/404.webp')) }}"
                                            alt="Ảnh món ăn hiện tại" class="img-fluid w-100 h-100"
                                            style="object-fit: cover;">
                                        <span class="position-absolute top-0 start-0 m-2 badge bg-danger">Ảnh hiện
                                            tại</span>
                                    </div>

                                    @if ($image && $image instanceof Illuminate\Http\UploadedFile)
                                        <div class="position-relative overflow-hidden rounded border mt-3"
                                            style="height: 250px;">
                                            <img src="{{ $image->temporaryUrl() }}" alt="Ảnh món ăn mới"
                                                class="img-fluid w-100 h-100" style="object-fit: cover;">
                                            <span class="position-absolute top-0 start-0 m-2 badge bg-success">Ảnh
                                                mới</span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Cột form thông tin --}}
                                <div class="col-md-8">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Tên món ăn *</label>
                                            <input type="text" wire:model="name"
                                                class="form-control bg-dark text-light border-secondary @error('name') is-invalid @enderror">
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Trạng thái *</label>
                                            <select wire:model="status"
                                                class="form-select bg-dark text-light border-secondary @error('status') is-invalid @enderror">
                                                <option value="activate">Đang bán</option>
                                                <option value="discontinued">Ngừng bán</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label">Mô tả</label>
                                            <textarea wire:model="description"
                                                class="form-control bg-dark text-light border-secondary @error('description') is-invalid @enderror" rows="4"></textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label">Tải ảnh mới (thay thế ảnh đại diện)</label>
                                            <input type="file" wire:model.live="image"
                                                class="form-control bg-dark text-light border-secondary @error('image') is-invalid @enderror"
                                                accept="image/*">
                                            @error('image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Thuộc tính và Biến thể --}}
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card bg-dark text-light">
                        <div class="card-header bg-gradient"
                            style="background: linear-gradient(135deg, #5b2ab5 0%, #3d1c78 100%);">
                            <h5 class="my-1">Thuộc tính và Biến thể</h5>
                        </div>
                        <div class="card-body">
                            {{-- Phần quản lý thuộc tính --}}
                            <div class="mb-4 p-3 border rounded" style="border-color: #4a5568 !important;">
                                <h6 class="text-primary fw-bold">
                                    {{ $editingAttributeIndex !== null ? 'Chỉnh sửa thuộc tính' : 'Thêm thuộc tính mới' }}
                                </h6>
                                <div class="row g-3 align-items-end p-3">
                                    {{-- Input cho thuộc tính mới --}}
                                    <div class="col-md-5 mb-3 mb-md-0 position-relative">
                                        <label class="form-label">Tên thuộc tính (ví dụ: Kích thước)</label>
                                        <input type="text"
                                            class="form-control bg-dark text-light border-secondary @error('newAttributeName') is-invalid @enderror"
                                            wire:model.live="newAttributeName" placeholder="Nhập tên thuộc tính">
                                        @error('newAttributeName')
                                            <div class="invalid-feedback"
                                                style="position: absolute; top: 100%; left: 0; margin-left: 10px;">
                                                {{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-5 mb-3 mb-md-0 position-relative">
                                        <label class="form-label">Giá trị (phân cách bởi dấu phẩy)</label>
                                        <input type="text"
                                            class="form-control bg-dark text-light border-secondary @error('newAttributeValues') is-invalid @enderror"
                                            wire:model.live="newAttributeValues" placeholder="ví dụ: Nhỏ, Vừa, Lớn">
                                        @error('newAttributeValues')
                                            <div class="invalid-feedback"
                                                style="position: absolute; top: 100%; left: 0; margin-left: 10px;">
                                                {{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-success w-100"
                                            wire:click="addOrUpdateAttribute">
                                            @if ($editingAttributeIndex !== null)
                                                <i class="fas fa-save"></i> Cập nhật
                                            @else
                                                <i class="fas fa-plus"></i> Thêm
                                            @endif
                                        </button>
                                        @if ($editingAttributeIndex !== null)
                                            <button type="button" class="btn btn-secondary w-100 mt-2"
                                                wire:click="cancelEditAttribute">
                                                <i class="fas fa-times"></i> Hủy
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Thông báo thành công/lỗi cho thuộc tính --}}
                            @if (session()->has('attribute_error'))
                                <div class="alert alert-danger">{{ session('attribute_error') }}</div>
                            @endif
                            @if (session()->has('attribute_success'))
                                <div class="alert alert-success">{{ session('attribute_success') }}</div>
                            @endif

                            {{-- Danh sách các thuộc tính đã thêm --}}
                            <h6 class="text-light mt-4">Danh sách thuộc tính</h6>
                            @if (empty($variantAttributes))
                                <p class="text-muted text-center">Chưa có thuộc tính nào.</p>
                            @else
                                <div class="list-group">
                                    @foreach ($variantAttributes as $index => $attribute)
                                        <div
                                            class="list-group-item bg-secondary text-white border-primary mb-2 rounded d-flex justify-content-between align-items-center flex-wrap">
                                            <div>
                                                <strong class="text-warning">{{ $attribute['name'] }}:</strong>
                                                <span>{{ implode(', ', $attribute['values']) }}</span>
                                            </div>
                                            <div class="btn-group mt-2 mt-md-0">
                                                <button type="button"
                                                    wire:click="editAttribute({{ $index }})"
                                                    class="btn btn-sm btn-info" title="Sửa"><i
                                                        class="fas fa-pencil-alt"></i></button>
                                                <button type="button"
                                                    wire:click="removeAttribute({{ $index }})"
                                                    class="btn btn-sm btn-danger" title="Xóa"
                                                    wire:confirm="Bạn có chắc muốn xóa thuộc tính này?"><i
                                                        class="fas fa-trash-alt"></i></button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Nút tạo biến thể --}}
                            @if (!empty($variantAttributes))
                                <div class="text-center my-4">
                                    <button type="button" class="btn btn-primary btn-lg"
                                        wire:click="generateVariants">
                                        <span wire:loading.remove wire:target="generateVariants">
                                            <i class="fas fa-cogs me-2"></i>Tạo/Cập nhật các biến thể
                                        </span>
                                        <span wire:loading wire:target="generateVariants">
                                            <span class="spinner-border spinner-border-sm" role="status"
                                                aria-hidden="true"></span>
                                            Đang xử lý...
                                        </span>
                                    </button>
                                    <p class="text-muted mt-2">Bấm vào đây để tạo các phiên bản món ăn từ các thuộc
                                        tính trên.</p>
                                </div>
                            @endif

                            {{-- Danh sách các biến thể để chỉnh sửa --}}
                            @if (!empty($variants))
                                <hr class="border-light my-4">
                                <h5 class="text-light mb-3">Chỉnh sửa các Biến thể</h5>
                                <div class="row g-4">
                                    @foreach ($variants as $vIndex => $variant)
                                        <div class="col-12">
                                            <div class="card position-relative overflow-hidden"
                                                style="background-color: #2d3748; border: 1px solid #4a5568;">
                                                <div class="position-absolute top-0 start-0 h-100"
                                                    style="width: 5px; background: linear-gradient(to bottom, #6b7280, #374151);">
                                                </div>
                                                <button type="button"
                                                    class="btn-close btn-close-white position-absolute"
                                                    wire:click="removeVariant({{ $vIndex }})"
                                                    style="top: .5rem; right: .5rem;"
                                                    title="Xóa biến thể này"></button>

                                                <div class="card-body">
                                                    <div class="row g-3">
                                                        <div class="col-lg-3 col-md-4">
                                                            {{-- *** CHANGED: Hiển thị thuộc tính dạng read-only *** --}}
                                                            <h6 class="text-white">Biến thể #{{ $vIndex + 1 }}</h6>
                                                            <div>
                                                                @foreach ($variant['attribute_values'] as $attr)
                                                                    <span
                                                                        class="badge bg-info me-1 mb-1">{{ $attr['attribute'] }}:
                                                                        {{ $attr['value'] }}</span>
                                                                @endforeach
                                                            </div>

                                                            {{-- Hiển thị ảnh biến thể --}}
                                                            <div class="mt-3">
                                                                @if (!empty($variant['existing_image']))
                                                                    <div class="mb-2 position-relative">
                                                                        <img src="{{ asset('storage/' . $variant['existing_image']) }}"
                                                                            alt="Ảnh hiện tại"
                                                                            class="img-thumbnail bg-dark border-secondary"
                                                                            style="width: 100%; height: 120px; object-fit: cover;">
                                                                        <span
                                                                            class="position-absolute top-0 start-0 m-1 badge bg-danger">Hiện
                                                                            tại</span>
                                                                    </div>
                                                                @endif
                                                                @if (isset($variant['image']) && $variant['image'] instanceof Illuminate\Http\UploadedFile)
                                                                    <div class="position-relative">
                                                                        <img src="{{ $variant['image']->temporaryUrl() }}"
                                                                            alt="Ảnh mới"
                                                                            class="img-thumbnail bg-dark border-secondary"
                                                                            style="width: 100%; height: 120px; object-fit: cover;">
                                                                        <span
                                                                            class="position-absolute top-0 start-0 m-1 badge bg-success">Ảnh
                                                                            mới</span>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-9 col-md-8">
                                                            <div class="row g-3">
                                                                <div class="col-lg-4 col-sm-6">
                                                                    <label class="form-label">Giá *</label>
                                                                    <input type="number"
                                                                        wire:model="variants.{{ $vIndex }}.price"
                                                                        class="form-control bg-dark text-light border-secondary @error("variants.$vIndex.price") is-invalid @enderror"
                                                                        min="0">
                                                                    @error("variants.$vIndex.price")
                                                                        <div class="invalid-feedback">{{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-lg-4 col-sm-6">
                                                                    <label class="form-label">Số lượng *</label>
                                                                    <input type="number"
                                                                        wire:model="variants.{{ $vIndex }}.quantity_available"
                                                                        class="form-control bg-dark text-light border-secondary @error("variants.$vIndex.quantity_available") is-invalid @enderror"
                                                                        min="0">
                                                                    @error("variants.$vIndex.quantity_available")
                                                                        <div class="invalid-feedback">{{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-lg-4 col-sm-6">
                                                                    <label class="form-label">Giới hạn</label>
                                                                    <input type="number"
                                                                        wire:model="variants.{{ $vIndex }}.limit"
                                                                        class="form-control bg-dark text-light border-secondary @error("variants.$vIndex.limit") is-invalid @enderror"
                                                                        min="0">
                                                                    @error("variants.$vIndex.limit")
                                                                        <div class="invalid-feedback">{{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-lg-8 col-sm-6">
                                                                    <label class="form-label">Ảnh biến thể (tùy
                                                                        chọn)</label>
                                                                    <input type="file"
                                                                        wire:model.live="variants.{{ $vIndex }}.image"
                                                                        class="form-control bg-dark text-light border-secondary @error('variants.' . $vIndex . '.image') is-invalid @enderror"
                                                                        accept="image/*">
                                                                    @error('variants.' . $vIndex . '.image')
                                                                        <div class="invalid-feedback">{{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-lg-4 col-sm-12">
                                                                    <label class="form-label">Trạng thái *</label>
                                                                    <select
                                                                        wire:model="variants.{{ $vIndex }}.status"
                                                                        class="form-select bg-dark text-light border-secondary @error("variants.$vIndex.status") is-invalid @enderror">
                                                                        <option value="available">Còn hàng</option>
                                                                        <option value="out_of_stock">Hết hàng</option>
                                                                        <option value="hidden">Ẩn</option>
                                                                    </select>
                                                                    @error("variants.$vIndex.status")
                                                                        <div class="invalid-feedback">{{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Nút submit chính --}}
            <div class="mt-4 d-flex justify-content-end">
                <a href="{{ route('admin.foods.index') }}" class="btn btn-outline-secondary me-3">Hủy bỏ</a>
                <button type="submit" class="btn btn-lg btn-success">
                    <span wire:loading.remove wire:target="save">
                        <i class="fas fa-save me-2"></i>Lưu tất cả thay đổi
                    </span>
                    <span wire:loading wire:target="save">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Đang lưu...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
