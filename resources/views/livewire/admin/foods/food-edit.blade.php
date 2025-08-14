@use('Illuminate\Http\UploadedFile')
<div class="scRender">
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show mt-2 mx-2" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session()->has('success_general'))
        <div class="alert alert-success alert-dismissible fade show mt-2 mx-2" role="alert">
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
                                <div
                                    class="col-md-3 col-xl-2 col-{{ $image && $image instanceof UploadedFile ? '12' : '6' }} d-flex d-md-block gap-2 mb-3">
                                    <div class="mt-1 food-image w-100 position-relative" style="height: auto;">
                                        @if ($foodItem->image)
                                            <img src="{{ asset('storage/' . $foodItem->image) }}"
                                                alt="Ảnh món ăn hiện tại"
                                                style="width: 100%; height: 100%; object-fit: cover; border-radius: 0;">
                                        @else
                                            <i class="fa-solid fa-burger-soda" style="font-size: 30px;"></i>
                                        @endif
                                        <span
                                            class="position-absolute opacity-75 top-0 start-0 mt-2 ms-2 badge rounded bg-danger">
                                            Ảnh hiện tại
                                        </span>
                                    </div>
                                    @if ($image && $image instanceof UploadedFile)
                                        <div class="mt-md-2 mt-1 food-image w-100 position-relative"
                                            style="height: auto;">
                                            <img src="{{ $image->temporaryUrl() }}" alt="Ảnh món ăn mới"
                                                style="width: 100%; height: 100%; object-fit: cover; border-radius: 0;">
                                            <span
                                                class="position-absolute opacity-75 top-0 start-0 mt-2 ms-2 badge rounded bg-success">
                                                Ảnh mới
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-9 col-xl-10 row align-items-start">
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

            <div class="row mt-4">
                <div class="col-12">
                    <div class="card bg-dark text-light">
                        <div class="card-header bg-gradient"
                            style="background: linear-gradient(135deg, #5b2ab5 0%, #3d1c78 100%);">
                            <h5 class="my-1">Thuộc tính và Biến thể</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <h6 class="text-light fw-bold">
                                    {{ $editingAttributeIndex !== null ? 'Chỉnh sửa thuộc tính' : 'Thêm thuộc tính mới' }}
                                </h6>
                                <div class="card bg-dark-subtle mb-4 shadow-sm border-secondary">
                                    <div class="card-body p-4">
                                        <h5 class="card-title text-light mb-3">🗂️ Thêm thuộc tính có sẵn</h5>

                                        <div class="row g-3 align-items-end mb-3">
                                            <div class="col-md position-relative p-3">
                                                <label for="select-attribute"
                                                    class="form-label text-light-emphasis fw-semibold">
                                                    1️⃣ Chọn loại thuộc tính
                                                </label>
                                                <select id="select-attribute" wire:model.live="selectedAttributeId"
                                                    class="form-select bg-dark text-light border-secondary @error('selectedAttributeId') is-invalid @enderror">
                                                    <option value="">-- Vui lòng chọn --</option>
                                                    @foreach ($availableAttributes as $attr)
                                                        <option value="{{ $attr->id }}">{{ $attr->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('selectedAttributeId')
                                                    <div class="invalid-feedback position-absolute d-block mt-1"
                                                        style="font-size: 0.875rem;">
                                                        <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            <div class="col-md-auto p-3">
                                                <button type="button" wire:click="addExistingAttribute"
                                                    class="btn btn-success w-100"
                                                    @if (empty($selectedAttributeId) || empty($selectedAttributeValueIds)) disabled @endif>
                                                    <i class="fas fa-plus-circle me-1"></i> Thêm vào món ăn
                                                </button>
                                            </div>
                                        </div>

                                        @if ($selectedAttributeId)
                                            @php
                                                $selectedAttr = $availableAttributes->find($selectedAttributeId);
                                            @endphp

                                            @if ($selectedAttr && $selectedAttr->values->count())
                                                <div>
                                                    <label
                                                        class="form-label text-light-emphasis fw-semibold d-block mb-2">
                                                        2️⃣ Chọn giá trị (có thể chọn nhiều)
                                                    </label>
                                                    <div class="d-flex flex-wrap gap-2">
                                                        @foreach ($selectedAttr->values as $value)
                                                            <input type="checkbox" class="btn-check"
                                                                id="value-{{ $value->id }}"
                                                                value="{{ $value->id }}"
                                                                wire:model.live="selectedAttributeValueIds"
                                                                autocomplete="off">
                                                            <label class="btn btn-outline-light"
                                                                for="value-{{ $value->id }}">
                                                                {{ $value->value }}
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                    @error('selectedAttributeValueIds')
                                                        <div class="invalid-feedback d-block text-warning mt-2">
                                                            <i class="fas fa-exclamation-circle me-1"></i>
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            @else
                                                <div class="alert alert-warning border-0 bg-warning-subtle mt-3 py-2"
                                                    role="alert">
                                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                                    Thuộc tính <strong>'{{ $selectedAttr->name ?? '' }}'</strong> chưa
                                                    có giá trị nào được định nghĩa.
                                                </div>
                                            @endif
                                        @endif

                                    </div>
                                </div>

                                <div class="row g-3 align-items-end p-3">
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

                            <div class="card bg-dark text-light my-4">
                                <div class="card-header bg-gradient"
                                    style="background: linear-gradient(to right, #3b82f6, #1e3a8a);">
                                    <h5 class="my-1">Gán giá trị cho tất cả biến thể</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3 align-items-end">
                                        <div class="col-md-4">
                                            <label class="form-label">Chọn mục muốn áp dụng</label>
                                            <select wire:model.live="bulkTarget"
                                                class="form-select bg-dark text-light border-secondary">
                                                <option value="">-- Chọn mục --</option>
                                                <option value="price">Giá</option>
                                                <option value="quantity">Số lượng</option>
                                                <option value="limit">Giới hạn</option>
                                                <option value="status">Trạng thái</option>
                                            </select>
                                        </div>

                                        @if ($bulkTarget === 'price')
                                            <div class="col-md-4">
                                                <label class="form-label">Giá</label>
                                                <input type="number" wire:model="bulkPrice"
                                                    class="form-control bg-dark text-light border-secondary">
                                            </div>
                                        @elseif ($bulkTarget === 'quantity')
                                            <div class="col-md-4">
                                                <label class="form-label">Số lượng</label>
                                                <input type="number" wire:model="bulkQuantity"
                                                    class="form-control bg-dark text-light border-secondary">
                                            </div>
                                        @elseif ($bulkTarget === 'limit')
                                            <div class="col-md-4">
                                                <label class="form-label">Giới hạn</label>
                                                <input type="number" wire:model="bulkLimit"
                                                    class="form-control bg-dark text-light border-secondary">
                                            </div>
                                        @elseif ($bulkTarget === 'status')
                                            <div class="col-md-4">
                                                <label class="form-label">Trạng thái</label>
                                                <select wire:model="bulkStatus"
                                                    class="form-select bg-dark text-light border-secondary">
                                                    <option value="">-- Mặc định --</option>
                                                    <option value="available">Còn hàng</option>
                                                    <option value="out_of_stock">Hết hàng</option>
                                                    <option value="hidden">Ẩn</option>
                                                </select>
                                            </div>
                                        @endif

                                        @if ($bulkTarget)
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        wire:model="bulkReplace" id="bulkReplace">
                                                    <label class="form-check-label" for="bulkReplace">
                                                        Ghi đè cả những biến thể đã điền sẵn
                                                    </label>
                                                </div>
                                                <button type="button" class="btn btn-info mt-2"
                                                    wire:click="applyBulkValues">
                                                    <i class="fas fa-magic me-1"></i> Áp dụng cho tất cả biến thể
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

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


                            @if ($showManualVariant ?? false)
                                <div class="card bg-dark text-light mt-4">
                                    <div class="card-body">
                                        <h6 class="text-light mb-3">Tạo biến thể thủ công</h6>

                                        <div class="row">
                                            @foreach ($variantAttributes as $attr)
                                                <div class="col-md-4 mb-3">
                                                    <label class="form-label">{{ $attr['name'] }}</label>
                                                    <select
                                                        wire:model.defer="manualAttributeValues.{{ $attr['name'] }}"
                                                        class="form-select bg-dark text-light border-secondary">
                                                        <option value="">-- Chọn {{ $attr['name'] }} --</option>
                                                        @foreach ($attr['values'] as $val)
                                                            <option value="{{ $val }}">{{ $val }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error("manualAttributeValues.{$attr['name']}")
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="mt-3">
                                            <button type="button" class="btn btn-success"
                                                wire:click="addManualVariant">
                                                <i class="fas fa-plus me-1"></i> Thêm biến thể
                                            </button>
                                            <button type="button" class="btn btn-secondary ms-2"
                                                wire:click="$set('showManualVariant', false)">
                                                <i class="fas fa-times me-1"></i> Hủy
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if (!empty($variantAttributes))
                                <div class="text-center my-4">
                                    <button type="button" class="btn btn-primary btn-lg me-2"
                                        wire:click="generateVariants">
                                        <span wire:loading.remove wire:target="generateVariants">
                                            <i class="fas fa-cogs me-2"></i> Tạo/Cập nhật các biến thể
                                        </span>
                                        <span wire:loading wire:target="generateVariants">
                                            <span class="spinner-border spinner-border-sm" role="status"
                                                aria-hidden="true"></span>
                                            Đang xử lý...
                                        </span>
                                    </button>

                                    <button type="button" class="btn btn-outline-warning btn-lg"
                                        wire:click="$toggle('showManualVariant')">
                                        <i class="fas fa-plus-circle me-2"></i> Thêm biến thể thủ công
                                    </button>

                                    <p class="text-muted mt-3">Bạn có thể tạo biến thể tự động từ các thuộc tính hoặc
                                        thêm thủ công từng biến thể.</p>
                                </div>
                            @endif

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
                                                    <div class="row g-3 align-items-start">
                                                        <div class="col-md-3 col-xl-2">
                                                            <h6 class="text-white">Biến thể {{ $vIndex + 1 }}</h6>
                                                            <div>
                                                                @foreach ($variant['attribute_values'] as $attr)
                                                                    <span
                                                                        class="badge bg-primary me-1 mb-1">{{ $attr['attribute'] }}:
                                                                        {{ $attr['value'] }}</span>
                                                                @endforeach
                                                            </div>
                                                            <div class="mt-2 food-image w-100 position-relative"
                                                                style="height: auto;">
                                                                @if ($variant['existing_image'])
                                                                    <img src="{{ asset('storage/' . $variant['existing_image']) }}"
                                                                        alt="Ảnh biến thể hiện tại"
                                                                        style="width: 100%; height: 100%; object-fit: cover; border-radius: 0;">
                                                                @else
                                                                    <i class="fa-solid fa-bowl-food"
                                                                        style="font-size: 30px;"></i>
                                                                @endif
                                                                <span
                                                                    class="position-absolute opacity-75 top-0 start-0 mt-2 ms-2 badge rounded bg-danger">
                                                                    Ảnh hiện tại
                                                                </span>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-9 col-xl-10 row g-3">
                                                            <div class="col-lg-4 col-sm-6">
                                                                <label class="form-label">Giá *</label>
                                                                <input type="text"
                                                                    wire:model.live="variants.{{ $vIndex }}.price"
                                                                    wire:input="formatPrice({{ $vIndex }})"
                                                                    class="form-control bg-dark text-light border-secondary @error('variants.' . $vIndex . '.price') is-invalid @enderror">
                                                                @error('variants.' . $vIndex . '.price')
                                                                    <div class="invalid-feedback">{{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                            <div class="col-lg-4 col-sm-6">
                                                                <label class="form-label">Số lượng *</label>
                                                                <input type="number"
                                                                    wire:model="variants.{{ $vIndex }}.quantity_available"
                                                                    class="form-control bg-dark text-light border-secondary @error("
                                                            variants.$vIndex.quantity_available") is-invalid
                                                            @enderror"
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
                                                                    class="form-control bg-dark text-light border-secondary @error("
                                                            variants.$vIndex.limit") is-invalid @enderror"
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
                                                                @if ($variant['image'] && $variant['image'] instanceof UploadedFile)
                                                                    <div class="mt-2 food-image position-relative"
                                                                        style="width: 100px; height: auto;">
                                                                        <img src="{{ $variant['image']->temporaryUrl() }}"
                                                                            alt="Ảnh biến thể mới"
                                                                            style="width: 100%; height: 100%; object-fit: cover; border-radius: 0;">
                                                                        <span
                                                                            class="position-absolute opacity-75 top-0 start-0 mt-2 ms-2 badge rounded bg-success">
                                                                            Ảnh mới
                                                                        </span>
                                                                    </div>
                                                                @endif
                                                                @error('variants.' . $vIndex . '.image')
                                                                    <div class="invalid-feedback">{{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                            <div class="col-lg-4 col-sm-12">
                                                                <label class="form-label">Trạng thái *</label>
                                                                <select
                                                                    wire:model="variants.{{ $vIndex }}.status"
                                                                    class="form-select bg-dark text-light border-secondary @error("
                                                            variants.$vIndex.status") is-invalid @enderror">
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
                                    @endforeach
                                </div>
                            @endif
                            <div class="d-flex justify-content-between mt-3">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Cập nhật thông tin
                                </button>
                                <a href="{{ route('admin.foods.index') }}" class="btn btn-outline-danger">
                                    Hủy bỏ
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
