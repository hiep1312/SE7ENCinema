<div>
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
                                            <label class="form-check-label text-light ms-4 fs-5" for="productVariants">
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

                                    <div class="d-flex align-items-center gap-2">

                                        <select class="form-select" wire:model.live="bulkAction"
                                            style="width: auto;">
                                            <option value="">-- Chọn thao tác --</option>
                                            <option value="price">Giá</option>
                                            <option value="quantity">Số lượng</option>
                                            <option value="quantity_limit">Giới hạn số lượng</option>
                                            <option value="image">Ảnh</option>
                                            <option value="status">Trạng thái</option>
                                        </select>

                                        @if ($bulkAction)
                                            <div style="min-width: 180px;">
                                                @if ($bulkAction === 'price')
                                                    <input type="number" wire:model="bulkValue" class="form-control"
                                                        placeholder="Nhập giá">
                                                @elseif ($bulkAction === 'quantity')
                                                    <input type="number" wire:model="bulkValue" class="form-control"
                                                        placeholder="Nhập số lượng">
                                                @elseif ($bulkAction === 'quantity_limit')
                                                    <input type="number" wire:model="bulkValue" class="form-control"
                                                        placeholder="Nhập giới hạn số lượng">
                                                @elseif ($bulkAction === 'image')
                                                    <input type="file" wire:model="bulkImage"
                                                        class="form-control">
                                                @elseif ($bulkAction === 'status')
                                                    <select wire:model="bulkValue" class="form-select">
                                                        <option value="">-- Chọn trạng thái --</option>
                                                        <option value="available">Có sẵn</option>
                                                        <option value="out_of_stock">Hết hàng</option>
                                                        <option value="hidden">Không hiển thị</option>
                                                    </select>
                                                @endif
                                            </div>
                                            <button type="button" wire:click="applyBulkAction"
                                                class="btn btn-success flex-shrink-0">
                                                Áp dụng
                                            </button>
                                        @endif

                                        <div class="btn-group">
                                            <button type="button" wire:click="generateVariantsFromAttributes"
                                                class="btn btn-primary"
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
                                </div>


                                @if ($variantCreateMode === 'manual')

                                    <div class="card bg-dark border-secondary mb-4">
                                        <div class="card-body">
                                            <h6 class="text-light mb-3">Tạo biến thể thủ công</h6>

                                            @foreach ($variantAttributes as $attr)
                                                <div class="mb-3">
                                                    <label class="form-label text-light">{{ $attr['name'] }}</label>
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

                                            <button class="btn btn-primary" type="button"
                                                wire:click="addManualVariant">
                                                Thêm biến thể
                                            </button>
                                            @if ($errors->has('manualAttributeValues'))
                                                <div class="text-danger mt-2">
                                                    {{ $errors->first('manualAttributeValues') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                @endif
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
                            <i class="fas fa-times-circle me-1"></i> Hủy bỏ
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
