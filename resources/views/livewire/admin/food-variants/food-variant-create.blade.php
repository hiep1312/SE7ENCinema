<div class="scRender">
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show mt-2 mx-2" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @error('generatedVariants')
        <div class="alert alert-danger d-block mb-3">
            {{ $message }}
        </div>
    @enderror

    <div class="container-lg mb-4">
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Thêm biến thể mới</h2>
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
                        <form wire:submit.prevent="createVariant" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="foodItemId" class="form-label text-light">Món ăn liên kết *</label>
                                        <select id="foodItemId" wire:model="foodItemId"
                                            class="form-select bg-dark text-light border-light @error('foodItemId') is-invalid @enderror">
                                            <option value="">--- Chọn món ăn ---</option>
                                            @foreach ($foods as $food)
                                                <option value="{{ $food->id }}">{{ $food->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('foodItemId')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

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
                                                    {{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-5 mb-3 mb-md-0 position-relative">
                                            <label class="form-label text-light">Giá trị (phân cách bằng dấu
                                                "|")</label>
                                            <input type="text" wire:model.defer="newAttributeValues"
                                                class="form-control bg-dark text-light border-secondary @error('newAttributeValues') is-invalid @enderror"
                                                placeholder="VD: Lớn|Nhỏ|Đỏ|Xanh">
                                            @error('newAttributeValues')
                                                <div class="invalid-feedback"
                                                    style="position: absolute; top: 100%; left: 0; margin-left: 10px;">
                                                    {{ $message }}</div>
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
                                                <button type="button" wire:click="resetEditing"
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
                                                        <button type="button" wire:click="moveUp({{ $index }})"
                                                            class="btn btn-sm btn-outline-light"
                                                            @if ($index == 0) disabled @endif
                                                            title="Di chuyển lên">
                                                            <i class="fas fa-arrow-up"></i>
                                                        </button>
                                                        <button type="button"
                                                            wire:click="moveDown({{ $index }})"
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
                                        <p class="text-light-50">Chưa có thuộc tính nào được thêm. Hãy nhập thông tin
                                            thuộc tính ở trên để bắt đầu.</p>
                                    @endif
                                @elseif ($variantTab === 'variants')
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="text-light mb-0">Quản lý các biến thể sản phẩm:</h6>
                                        <div>
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

                                    @if ($variantCreateMode === 'manual')

                                        <div class="card bg-dark border-secondary mb-4">
                                            <div class="card-body">
                                                <h6 class="text-light mb-3">Tạo biến thể thủ công</h6>

                                                @foreach ($variantAttributes as $attr)
                                                    <div class="mb-3">
                                                        <label
                                                            class="form-label text-light">{{ $attr['name'] }}</label>
                                                        <select
                                                            wire:model.defer="manualAttributeValues.{{ $attr['name'] }}"
                                                            class="form-select bg-dark text-light border-secondary">
                                                            <option value="">-- Chọn {{ $attr['name'] }} --
                                                            </option>
                                                            @foreach ($attr['values'] as $val)
                                                                <option value="{{ $val }}">
                                                                    {{ $val }}
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
                                                                @if ($variant['image'])
                                                                    <div class="col-md-3 col-xl-2 col-6 mb-3">
                                                                        <div class="mt-1 food-image w-100"
                                                                            style="height: auto;">
                                                                            <img src="{{ $variant['image']->temporaryUrl() }}"
                                                                                alt="Ảnh biến thể mới"
                                                                                style="width: 100%; height: 100%; object-fit: cover; border-radius: 0;">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-9 col-xl-10 row">
                                                                @endif
                                                                <div class="col-md-4 mb-3">
                                                                    <label class="form-label">Giá <span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="text" x-data
                                                                        x-on:input="
                                                                          let raw = $el.value.replace(/\D/g, '');
                                                                          $el.value = raw.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                                                                          $wire.set('generatedVariants.{{ $index }}.price', raw);
                                                                            "
                                                                        value="{{ isset($generatedVariants[$index]['price']) && is_numeric($generatedVariants[$index]['price'])
                                                                            ? number_format((float) $generatedVariants[$index]['price'], 0, ',', '.')
                                                                            : '' }}"
                                                                        class="form-control bg-dark text-light border-secondary @error('generatedVariants.' . $index . '.price') is-invalid @enderror"
                                                                        placeholder="Giá biến thể">

                                                                    @error('generatedVariants.' . $index . '.price')
                                                                        <div class="invalid-feedback d-block">
                                                                            {{ $message }}</div>
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
                                                                            {{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-md-4 mb-3">
                                                                    <label class="form-label">Giới hạn số lượng <span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="number"
                                                                        wire:model.defer="generatedVariants.{{ $index }}.limit"
                                                                        class="form-control bg-dark text-light border-secondary @error('generatedVariants.' . $index . '.limit') is-invalid @enderror"
                                                                        placeholder="Giới hạn">
                                                                    @error('generatedVariants.' . $index . '.limit')
                                                                        <div class="invalid-feedback d-block">
                                                                            {{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label class="form-label">Ảnh biến thể</label>
                                                                    <input type="file"
                                                                        wire:model="generatedVariants.{{ $index }}.image"
                                                                        class="form-control bg-dark text-light border-secondary @error('generatedVariants.' . $index . '.image') is-invalid @enderror"
                                                                        accept="image/*">
                                                                    @error('generatedVariants.' . $index . '.image')
                                                                        <div class="invalid-feedback d-block">
                                                                            {{ $message }}</div>
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
                                                                    @error('generatedVariants.' . $index . '.status')
                                                                        <div class="invalid-feedback d-block">
                                                                            {{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                                @if ($variant['image'])
                                                            </div>
                                            @endif
                                        </div>
                                        <div class="d-flex justify-content-end mt-3">
                                            <button type="button"
                                                wire:click="removeGeneratedVariant({{ $index }})"
                                                class="btn btn-outline-danger btn-sm">
                                                <i class="fas fa-times-circle me-1"></i> Xóa biến
                                                thể này
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
        <div class="d-flex justify-content-between mt-3">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Tạo biến thể
            </button>
            <a href="{{ route('admin.food_variants.index') }}" class="btn btn-outline-danger">
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
