<div class="scRender">
    {{-- Hiển thị thông báo lỗi chung từ session --}}
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Hiển thị thông báo thành công từ session --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @error('generatedVariants')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror


    <div class="container-lg mb-4">
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Thêm món ăn mới</h2>
            <a href="{{ route('admin.foods.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>

        <div class="row">
            <div class="col-12">
                <form wire:submit.prevent="createFood" enctype="multipart/form-data">
                    <div class="card bg-dark">
                        <div class="card-header bg-gradient text-light"
                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <h5 class="my-1">Thông tin món ăn</h5>
                        </div>
                        <div class="card-body bg-dark">
                            <div class="row">
                                @if ($image)
                                    <div class="col-md-3 col-5 mb-3">
                                        <div class="mt-1 overflow-hidden rounded"
                                            style="max-height: 230px; width: 100%;">
                                            <img src="{{ $image->temporaryUrl() }}" alt="Ảnh món ăn tải lên"
                                                class="img-fluid" style="object-fit: cover; height: 100%; width: 100%;">
                                        </div>
                                    </div>
                                @endif
                                <div class="@if ($image) col-md-9 @else col-12 @endif row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label text-light">Tên món ăn <span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="name" wire:model.defer="name"
                                            class="form-control bg-dark text-light border-secondary @error('name') is-invalid @enderror"
                                            placeholder="VD: Bắp rang bơ">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="status" class="form-label text-light">Trạng thái <span
                                                class="text-danger">*</span></label>
                                        <select id="status" wire:model.defer="status"
                                            class="form-select bg-dark text-light border-secondary @error('status') is-invalid @enderror">
                                            <option value="activate">Đang bán</option>
                                            <option value="discontinued">Ngừng bán</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="description" class="form-label text-light">Mô tả</label>
                                        <textarea id="description" wire:model.defer="description"
                                            class="form-control bg-dark text-light border-secondary @error('description') is-invalid @enderror"
                                            placeholder="VD: Bắp rang bơ vị ngọt, size lớn, thích hợp cho 2 người" rows="3"></textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="image" class="form-label text-light">Ảnh món ăn</label>
                                        <input type="file" id="image" wire:model="image"
                                            class="form-control bg-dark text-light border-secondary @error('image') is-invalid @enderror"
                                            accept="image/*">
                                        @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 d-flex align-items-end mb-3">
                                        <div class="form-check form-switch p-0 d-flex align-items-center ps-5">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                id="productVariants" wire:model.live.debounce.200ms="productVariants"
                                                style="transform: scale(1.5);">
                                            <label class="form-check-label text-light ms-1 fs-6" for="productVariants">
                                                Sản phẩm có biến thể
                                            </label>
                                            @error('productVariants')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if (!$productVariants)
                        <div class="card bg-dark mt-3">
                            <div class="card-header bg-gradient text-light"
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5 class="my-1">Thông tin giá & kho cơ bản</h5>
                            </div>
                            <div class="card-body row">
                                <div class="col-md-4 mb-3">
                                    <label for="basePrice" class="form-label text-light">Giá <span
                                            class="text-danger">*</span></label>
                                    <input type="number" id="basePrice" wire:model.defer="basePrice"
                                        class="form-control bg-dark text-light border-secondary @error('basePrice') is-invalid @enderror"
                                        placeholder="VD: 35000">
                                    @error('basePrice')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="baseQuantity" class="form-label text-light">Số lượng tồn kho <span
                                            class="text-danger">*</span></label>
                                    <input type="number" id="baseQuantity" wire:model.defer="baseQuantity"
                                        class="form-control bg-dark text-light border-secondary @error('baseQuantity') is-invalid @enderror"
                                        placeholder="VD: 100">
                                    @error('baseQuantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="baseLimit" class="form-label text-light">Giới hạn số lượng
                                        <span class="text-danger">*</span></label>
                                    <input type="number" id="baseLimit" wire:model.defer="baseLimit"
                                        class="form-control bg-dark text-light border-secondary @error('baseLimit') is-invalid @enderror"
                                        placeholder="VD: 5">
                                    @error('baseLimit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="baseStatus" class="form-label text-light">Trạng thái kho hàng <span
                                            class="text-danger">*</span></label>
                                    <select id="baseStatus" wire:model.defer="baseStatus"
                                        class="form-select bg-dark text-light border-secondary @error('baseStatus') is-invalid @enderror">
                                        <option value="available">Còn hàng</option>
                                        <option value="out_of_stock">Hết hàng</option>
                                        <option value="hidden">Ngừng bán</option>
                                    </select>
                                    @error('baseStatus')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($productVariants)
                        <ul class="nav nav-tabs bg-dark mt-4" role="tablist">
                            <li class="nav-item">
                                <button type="button"
                                    class="nav-link @if ($variantTab === 'attributes') active bg-primary text-white @else text-light @endif"
                                    wire:click="$set('variantTab', 'attributes')">
                                    <i class="fas fa-tags me-1"></i> Quản lý thuộc tính
                                </button>
                            </li>
                            <li class="nav-item">
                                <button type="button"
                                    class="nav-link @if ($variantTab === 'variants') active bg-primary text-white @else text-light @endif"
                                    wire:click="$set('variantTab', 'variants')"
                                    @if (empty($variantAttributes)) disabled @endif>
                                    <i class="fas fa-boxes me-1"></i> Quản lý biến thể
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content tab-manager p-3 border border-secondary border-top-0 bg-dark">
                            @if ($variantTab === 'attributes')
                                <h6 class="text-light mb-3">Thêm/Sửa thuộc tính cho món ăn:</h6>
                                {{-- Bắt đầu: Phần chọn thuộc tính sẵn có đã được làm đẹp --}}
                                <div class="card bg-dark-subtle mb-4 shadow-sm border-secondary">
                                    <div class="card-body p-4">
                                        <h5 class="card-title text-light mb-3">🗂️ Thêm thuộc tính có sẵn</h5>

                                        {{-- Hàng chứa Dropdown chọn thuộc tính và Nút Thêm --}}
                                        <div class="row g-3 align-items-end mb-3">
                                            {{-- Dropdown chọn thuộc tính --}}
                                            <div class="col-md">
                                                <label for="select-attribute"
                                                    class="form-label text-light-emphasis fw-semibold">1. Chọn loại
                                                    thuộc tính</label>
                                                {{-- Sử dụng wire:model.live để giao diện phản hồi ngay lập tức --}}
                                                <select id="select-attribute" wire:model.live="selectedAttributeId"
                                                    class="form-select bg-dark text-light border-secondary">
                                                    <option value="">-- Vui lòng chọn --</option>
                                                    @foreach ($availableAttributes as $attr)
                                                        <option value="{{ $attr->id }}">{{ $attr->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            {{-- Nút Thêm thuộc tính vào món ăn --}}
                                            <div class="col-md-auto">
                                                {{-- Nút sẽ bị vô hiệu hóa nếu chưa chọn đủ thông tin, cải thiện UX --}}
                                                <button type="button" wire:click="addExistingAttribute"
                                                    class="btn btn-success w-100"
                                                    @if (empty($selectedAttributeId) || empty($selectedAttributeValueIds)) disabled @endif>
                                                    <i class="fas fa-plus-circle me-1"></i> Thêm vào món ăn
                                                </button>
                                            </div>
                                        </div>

                                        {{-- Vùng hiển thị các giá trị của thuộc tính đã chọn (hiển thị khi có selectedAttributeId) --}}
                                        @if ($selectedAttributeId)
                                            @php
                                                // Tìm thuộc tính được chọn để lấy các giá trị của nó
                                                $selectedAttr = $availableAttributes->find($selectedAttributeId);
                                            @endphp

                                            @if ($selectedAttr && $selectedAttr->values->count() > 0)
                                                <div>
                                                    <label
                                                        class="form-label text-light-emphasis fw-semibold d-block mb-2">2.
                                                        Chọn giá trị (có thể chọn nhiều)</label>

                                                    {{-- Sử dụng Flexbox để các tag tự động xuống hàng một cách đẹp mắt --}}
                                                    <div class="d-flex flex-wrap gap-2">
                                                        @foreach ($selectedAttr->values as $value)
                                                            {{-- Sử dụng "btn-check": một kỹ thuật hiện đại để tạo checkbox dạng button/tag --}}
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
                                                </div>
                                            @else
                                                {{-- Thông báo rõ ràng hơn khi thuộc tính không có giá trị --}}
                                                <div class="alert alert-warning border-0 bg-warning-subtle mt-3 py-2"
                                                    role="alert">
                                                    <i class="fas fa-exclamation-triangle me-2"></i> Thuộc tính
                                                    <strong>'{{ $selectedAttr->name ?? '' }}'</strong> chưa có giá trị
                                                    nào được định nghĩa.
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                {{-- Kết thúc: Phần chọn thuộc tính sẵn có --}}

                                <div class="row mb-3 align-items-end">
                                    <div class="col-md-5 mb-3 mb-md-0 position-relative">
                                        <label class="form-label text-light">Tên thuộc tính</label>
                                        <input type="text" wire:model.defer="newAttributeName"
                                            class="form-control bg-dark text-light border-secondary @error('newAttributeName') is-invalid @enderror"
                                            placeholder="VD: Kích thước, Màu sắc">
                                        @error('newAttributeName')
                                            <div class="invalid-feedback"
                                                style="position: absolute; top: 100%; left: 0; margin-left: 10px;">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-5 mb-3 mb-md-0 position-relative">
                                        <label class="form-label text-light">Giá trị (phân cách bằng dấu "|")</label>
                                        <input type="text" wire:model.defer="newAttributeValues"
                                            class="form-control bg-dark text-light border-secondary @error('newAttributeValues') is-invalid @enderror"
                                            placeholder="VD: Lớn|Nhỏ|Đỏ|Xanh">
                                        @error('newAttributeValues')
                                            <div class="invalid-feedback"
                                                style="position: absolute; top: 100%; left: 0; margin-left: 10px;">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-2 text-md-end">
                                        @if ($editingIndex === null)
                                            <button type="button" wire:click="addAttribute"
                                                class="btn btn-success w-100">
                                                <i class="fas fa-plus-circle me-1"></i> Thêm
                                            </button>
                                        @else
                                            <button type="button" wire:click="updateAttribute"
                                                class="btn btn-warning w-100">
                                                <i class="fas fa-edit me-1"></i> Cập nhật
                                            </button>
                                            <button type="button"
                                                wire:click="$set('editingIndex', null); $set('newAttributeName', ''); $set('newAttributeValues', '');"
                                                class="btn btn-secondary w-100 mt-2">
                                                <i class="fas fa-undo me-1"></i> Hủy
                                            </button>
                                        @endif
                                    </div>
                                </div>

                                <hr class="border-secondary my-4">

                                <h6 class="text-light mb-3">Danh sách thuộc tính đã tạo:</h6>
                                @if (count($variantAttributes) > 0)
                                    <div class="list-group">
                                        @foreach ($variantAttributes as $index => $attribute)
                                            <div
                                                class="list-group-item bg-secondary text-white border-primary mb-2 rounded d-flex justify-content-between align-items-center flex-wrap">
                                                <div class="py-2">
                                                    <strong
                                                        class="text-warning me-2">{{ $attribute['name'] }}:</strong>
                                                    <span
                                                        class="d-block d-md-inline-block">{{ implode(', ', $attribute['values']) }}</span>
                                                </div>
                                                <div class="btn-group my-2 my-md-0" role="group">
                                                    <button type="button"
                                                        wire:click="editAttribute({{ $index }})"
                                                        class="btn btn-sm btn-info" title="Sửa thuộc tính">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </button>
                                                    <button type="button"
                                                        wire:click="removeAttribute({{ $index }})"
                                                        class="btn btn-sm btn-danger" title="Xóa thuộc tính">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                    <button type="button"
                                                        wire:click="moveUp({{ $index }}, 'up')"
                                                        class="btn btn-sm btn-outline-light"
                                                        @if ($index == 0) disabled @endif
                                                        title="Di chuyển lên">
                                                        <i class="fas fa-arrow-up"></i>
                                                    </button>
                                                    <button type="button"
                                                        wire:click="moveDown({{ $index }}, 'down')"
                                                        class="btn btn-sm btn-outline-light"
                                                        @if ($index == count($variantAttributes) - 1) disabled @endif
                                                        title="Di chuyển xuống">
                                                        <i class="fas fa-arrow-down"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-light-50">Chưa có thuộc tính nào được thêm. Hãy nhập thông tin thuộc
                                        tính ở trên để bắt đầu.</p>
                                @endif
                            @elseif ($variantTab === 'variants')
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="text-light mb-0">Quản lý các biến thể sản phẩm:</h6>

                                    <div class="d-flex align-items-center">
                                        <button type="button" wire:click="generateVariantsFromAttributes"
                                            class="btn btn-primary me-2"
                                            @if (empty($variantAttributes)) disabled @endif>
                                            <i class="fas fa-sync-alt me-1"></i> Tái tạo biến thể
                                        </button>

                                        <button type="button" wire:click="$set('variantCreateMode', 'manual')"
                                            class="btn btn-primary"
                                            @if (empty($variantAttributes)) disabled @endif>
                                            <i class="fas fa-plus me-1"></i> Tạo biến thể thủ công
                                        </button>
                                    </div>
                                </div>


                                @if ($variantCreateMode === 'manual')
                                    <div class="card bg-dark border-secondary mb-4">
                                        <div class="card-body">
                                            <h6 class="text-light mb-3">Tạo biến thể thủ công</h6>

                                            <div class="row">
                                                @foreach ($variantAttributes as $attr)
                                                    <div class="col-md-4 mb-3">
                                                        <label class="form-label">{{ $attr['name'] }}</label>
                                                        <select
                                                            wire:model.defer="manualAttributeValues.{{ $attr['name'] }}"
                                                            class="form-select bg-dark text-light border-secondary @error("manualAttributeValues.{$attr['name']}") is-invalid @enderror">
                                                            <option value="">-- Chọn {{ $attr['name'] }} --
                                                            </option>
                                                            @foreach ($attr['values'] as $val)
                                                                <option value="{{ $val }}">
                                                                    {{ $val }}</option>
                                                            @endforeach
                                                        </select>
                                                        @error("manualAttributeValues.{$attr['name']}")
                                                            <div class="invalid-feedback d-block mt-1">
                                                                <i class="fas fa-exclamation-circle me-1"></i>
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                @endforeach
                                            </div>

                                            <div class="mt-3 d-flex justify-content-start gap-2">
                                                <button type="button" class="btn btn-success"
                                                    wire:click="addManualVariant">
                                                    <i class="fas fa-plus me-1"></i> Thêm biến thể
                                                </button>
                                                <button type="button" class="btn btn-secondary"
                                                    wire:click="$set('variantCreateMode', null)">
                                                    <i class="fas fa-times me-1"></i> Huỷ
                                                </button>
                                            </div>
                                            @error('manualAttributeValues')
                                                <div class="alert alert-danger mt-2">
                                                    <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                                                </div>
                                            @enderror

                                        </div>
                                    </div>
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
                                                <select wire:model.live="bulkAction"
                                                    class="form-select bg-dark text-light border-secondary">
                                                    <option value="">-- Chọn thao tác --</option>
                                                    <option value="price">Giá</option>
                                                    <option value="quantity">Số lượng</option>
                                                    <option value="quantity_limit">Giới hạn</option>
                                                    <option value="status">Trạng thái</option>
                                                </select>
                                            </div>

                                            @if ($bulkAction === 'price' || $bulkAction === 'quantity' || $bulkAction === 'quantity_limit')
                                                <div class="col-md-4">
                                                    <label class="form-label">
                                                        {{ $bulkAction === 'price' ? 'Giá' : ($bulkAction === 'quantity' ? 'Số lượng' : 'Giới hạn') }}
                                                    </label>
                                                    <input type="number" wire:model="bulkValue"
                                                        class="form-control bg-dark text-light border-secondary">
                                                </div>
                                            @elseif ($bulkAction === 'status')
                                                <div class="col-md-4">
                                                    <label class="form-label">Trạng thái</label>
                                                    <select wire:model="bulkValue"
                                                        class="form-select bg-dark text-light border-secondary">
                                                        <option value="">-- Chọn trạng thái --</option>
                                                        <option value="available">Còn hàng</option>
                                                        <option value="out_of_stock">Hết hàng</option>
                                                        <option value="hidden">Ẩn</option>
                                                    </select>
                                                </div>
                                            @endif

                                            @if ($bulkAction)
                                                <div class="col-md-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            wire:model="applyToAll" id="applyToAllCheckbox">
                                                        <label class="form-check-label" for="applyToAllCheckbox">
                                                            Ghi đè cả những biến thể đã điền sẵn
                                                        </label>
                                                    </div>
                                                    <button type="button" class="btn btn-info mt-2"
                                                        wire:click="applyBulkAction">
                                                        <i class="fas fa-magic me-1"></i> Áp dụng cho tất cả biến thể
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>


                                @if (count($generatedVariants))
                                    <div class="accordion" id="variantsAccordion" x-data="{ expandedVariants: @entangle('expandedVariants') }">
                                        @foreach ($generatedVariants as $index => $variant)
                                            <div class="accordion-item bg-dark border-secondary mb-2"
                                                wire:key="variant-{{ $index }}">
                                                <h2 class="accordion-header" id="heading-{{ $index }}">
                                                    <button class="accordion-button bg-secondary text-white"
                                                        type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#collapse-{{ $index }}"
                                                        :aria-expanded="expandedVariants[{{ $index }}] ? 'true' : 'false'"
                                                        aria-controls="collapse-{{ $index }}"
                                                        @click="expandedVariants[{{ $index }}] = !expandedVariants[{{ $index }}]">
                                                        <strong>
                                                            @foreach ($variant['attribute_values'] as $pair)
                                                                {{ $pair['attribute'] }}: <span
                                                                    class="text-info">{{ $pair['value'] }}</span>{{ !$loop->last ? ' | ' : '' }}
                                                            @endforeach
                                                        </strong>
                                                    </button>
                                                </h2>
                                                <div id="collapse-{{ $index }}"
                                                    class="accordion-collapse collapse"
                                                    :class="{ 'show': expandedVariants[{{ $index }}] }"
                                                    aria-labelledby="heading-{{ $index }}"
                                                    data-bs-parent="#variantsAccordion" wire:ignore.self>
                                                    <div class="accordion-body text-light">
                                                        <div class="row">
                                                            <div class="col-md-4 mb-3">
                                                                <label class="form-label">Giá <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="number"
                                                                    wire:model.defer="generatedVariants.{{ $index }}.price"
                                                                    class="form-control bg-dark text-light border-secondary @error('generatedVariants.' . $index . '.price') is-invalid @enderror"
                                                                    placeholder="Giá biến thể">
                                                                @error('generatedVariants.' . $index . '.price')
                                                                    <div class="invalid-feedback d-block">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                <label class="form-label">Số lượng tồn kho <span
                                                                        class="text-danger">*</span></label>
                                                                <input type="number"
                                                                    wire:model.defer="generatedVariants.{{ $index }}.quantity"
                                                                    class="form-control bg-dark text-light border-secondary @error('generatedVariants.' . $index . '.quantity') is-invalid @enderror"
                                                                    placeholder="Số lượng">
                                                                @error('generatedVariants.' . $index . '.quantity')
                                                                    <div class="invalid-feedback d-block">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                            <div class="col-md-4 mb-3">
                                                                <label class="form-label">Giới hạn số lượng
                                                                    <span class="text-danger">*</span></label>
                                                                <input type="number"
                                                                    wire:model.defer="generatedVariants.{{ $index }}.limit"
                                                                    class="form-control bg-dark text-light border-secondary @error('generatedVariants.' . $index . '.limit') is-invalid @enderror"
                                                                    placeholder="Giới hạn">
                                                                @error('generatedVariants.' . $index . '.limit')
                                                                    <div class="invalid-feedback d-block">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Ảnh biến thể</label>
                                                                <input type="file"
                                                                    wire:model="generatedVariants.{{ $index }}.image"
                                                                    class="form-control bg-dark text-light border-secondary @error('generatedVariants.' . $index . '.image') is-invalid @enderror"
                                                                    accept="image/*">
                                                                @if (isset($variant['image']) && is_object($variant['image']))
                                                                    <img src="{{ $variant['image']->temporaryUrl() }}"
                                                                        class="img-thumbnail mt-2"
                                                                        style="max-height: 100px;">
                                                                @elseif(isset($variant['image_path']) && !is_object($variant['image_path']))
                                                                    <img src="{{ asset('storage/' . $variant['image_path']) }}"
                                                                        class="img-thumbnail mt-2"
                                                                        style="max-height: 100px;">
                                                                @endif
                                                                @error('generatedVariants.' . $index . '.image')
                                                                    <div class="invalid-feedback d-block">
                                                                        {{ $message }}
                                                                    </div>
                                                                @enderror
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label">Trạng thái <span
                                                                        class="text-danger">*</span></label>
                                                                <select
                                                                    wire:model.defer="generatedVariants.{{ $index }}.status"
                                                                    class="form-select bg-dark text-light border-secondary @error('generatedVariants.' . $index . '.status') is-invalid @enderror">
                                                                    <option value="available">Có sẵn</option>
                                                                    <option value="out_of_stock">Hết hàng</option>
                                                                    <option value="hidden">Không hiển thị</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex justify-content-end mt-3">
                                                            <button type="button"
                                                                wire:click="removeGeneratedVariant({{ $index }})"
                                                                class="btn btn-outline-danger btn-sm">
                                                                <i class="fas fa-times-circle me-1"></i> Xóa biến thể
                                                                này
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-light-50">Vui lòng thêm thuộc tính ở tab "Quản lý thuộc tính" và
                                        nhấn "Tái tạo biến thể" để sinh ra các biến thể.</p>
                                @endif
                            @endif

                        </div>
                    @endif


                    <div class="d-flex justify-content-between mt-4">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Tạo món ăn
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
