<div class="container py-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="card-title h3 mb-4 border-bottom pb-2">Chỉnh sửa món ăn {{ $name }}</h2>

            {{-- Hiển thị lỗi --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Hiển thị thông báo thành công --}}
            @if (session()->has('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif



            {{-- Form chỉnh sửa thông tin món ăn --}}
            <form wire:submit.prevent="update" enctype="multipart/form-data">
                <div class="row mb-4">
                    {{-- Ảnh món ăn --}}
                    <div class="col-md-4 text-center mb-3">

                        @if ($existingImagePath)
                            <img width="200px" src="{{ asset('storage/' . $existingImagePath) }}" alt="Hiện tại">
                        @endif

                        <input type="file" class="form-control" wire:model="image">
                        @if ($image)
                            <div class="form-text">Chọn ảnh mới để thay thế ảnh hiện tại.</div>
                            <img width="100px" src="{{ $image->temporaryUrl() }}" alt="Preview">
                        @endif

                    </div>

                    {{-- Thông tin món ăn --}}
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label">Tên món ăn</label>
                            <input type="text" class="form-control" wire:model="name">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mô tả</label>
                            <textarea class="form-control" wire:model="description" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Trạng thái</label>
                            <select class="form-select" wire:model="status">
                                <option value="activate">Hoạt động</option>
                                <option value="discontinued">Không hoạt động</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Biến thể món ăn --}}

                <div class="mb-3">
                    <label class="form-label">Biến thể món ăn</label>

                    @foreach ($variants as $index => $variant)
                        <div class="card p-3 mb-2">
                            <div class="row">
                                <div class="col">
                                    @if (isset($variant['existing_image']) && is_string($variant['existing_image']))
                                        <div class="mb-2">Ảnh cũ:</div>
                                        <img src="{{ asset('storage/' . $variant['existing_image']) }}" width="100"
                                            class="img-thumbnail mb-2">
                                    @endif
                                    @if (isset($variant['image']) && is_object($variant['image']))
                                        <div class="mb-2">Ảnh mới (chưa lưu):</div>
                                        <img src="{{ $variant['image']->temporaryUrl() }}" width="100"
                                            class="img-thumbnail mb-2">
                                    @endif

                                </div>
                                <div class="col">
                                    <input type="text" class="form-control" placeholder="Tên biến thể"
                                        wire:model="variants.{{ $index }}.name">
                                </div>
                                <div class="col">
                                    <input type="number" class="form-control" placeholder="Giá"
                                        wire:model="variants.{{ $index }}.price">
                                </div>
                                <div class="col">
                                    <input type="number" class="form-control" placeholder="Số lượng"
                                        wire:model="variants.{{ $index }}.quantity_available">
                                </div>
                                <div class="col">
                                    <input type="number" class="form-control" placeholder="Giới hạn số lượng"
                                        wire:model="variants.{{ $index }}.limit">
                                </div>
                                <div class="col">
                                    <select class="form-select" wire:model="variants.{{ $index }}.status">
                                        <option value="available">Hoạt động</option>
                                        <option value="out_of_stock">Không hoạt động</option>
                                        <option value="hidden">Ẩn</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <input type="file" class="form-control" placeholder="ảnh"
                                        wire:model="variants.{{ $index }}.image">
                                </div>
                                <div class="col-auto">
                                    @if (isset($variant['id']) && $variant['id'])
                                        <button x-data
                                            @click.prevent="if (confirm('Bạn có chắc muốn xoá luôn biến thể này không?')) { $wire.deleteVariant({{ $index }}) }"
                                            class="btn btn-danger">
                                            Xoá Biến thể
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-danger"
                                            wire:click.prevent="removeVariant({{ $index }})">Xoá</button>
                                    @endif

                                </div>
                            </div>
                        </div>
                    @endforeach
                    <button type="button" class="btn btn-outline-primary" wire:click.prevent="addVariant">+ Thêm biến
                        thể</button>

                </div>


                {{-- Nút hành động --}}
                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-success">💾 Lưu thay đổi</button>
                    <a href="{{ route('admin.food.list') }}" class="btn btn-secondary">← Quay lại danh sách</a>
                </div>
            </form>
        </div>
    </div>
</div>
