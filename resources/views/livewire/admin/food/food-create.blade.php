<div class="container mt-4">
    <h2 class="mb-4 text-primary">Thêm Món Ăn Mới</h2>

    <form wire:submit.prevent="save" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="name" class="form-label">Tên món</label>
            <input type="text" id="name" class="form-control @error('name') is-invalid @enderror"
                wire:model="name" />
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Mô tả</label>
            <textarea id="description" class="form-control @error('description') is-invalid @enderror" wire:model="description"></textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Ảnh món ăn</label>
            <input type="file" id="image" class="form-control @error('image') is-invalid @enderror"
                wire:model.live="photo" />
            @if ($photo)
                <div class="mb-3">
                    <label class="form-label">Xem trước ảnh món ăn:</label>
                    <img src="{{ $photo->temporaryUrl() }}" alt="Xem trước ảnh" class="img-thumbnail"
                        style="max-height: 150px; margin-top: 30px;">
                </div>
            @endif
            @error('image')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Trạng thái</label>
            <select id="status" class="form-select @error('status') is-invalid @enderror" wire:model="status">
                <option value="activate">Hoạt động</option>
                <option value="discontinued">Không hoạt động</option>
            </select>
            @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Biến thể món ăn</label>

            @foreach ($variants as $index => $variant)
                <div class="card p-3 mb-2">
                    <div class="row">
                        <div class="col">
                            <input type="text"
                                class="form-control @error("variants.$index.name") is-invalid @enderror"
                                placeholder="Tên biến thể" wire:model="variants.{{ $index }}.name">
                            @error("variants.$index.name")
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col">
                            <input type="number"
                                class="form-control @error("variants.$index.price") is-invalid @enderror"
                                placeholder="Giá" wire:model="variants.{{ $index }}.price">
                            @error("variants.$index.price")
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col">
                            <input type="number"
                                class="form-control @error("variants.$index.quantity") is-invalid @enderror"
                                placeholder="Số lượng" wire:model="variants.{{ $index }}.quantity">
                            @error("variants.$index.quantity")
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col">
                            <input type="number"
                                class="form-control @error("variants.$index.limit") is-invalid @enderror"
                                placeholder="Giới hạn số lượng" wire:model="variants.{{ $index }}.limit">
                            @error("variants.$index.limit")
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col">
                            <select class="form-select" wire:model="variants.{{ $index }}.status">
                                <option value="available">Hoạt động</option>
                                <option value="out_of_stock">Không hoạt động</option>
                                <option value="hidden">Ẩn</option>
                            </select>
                        </div>
                        <div class="col">
                            <input type="file"
                                class="form-control @error("variants.$index.image") is-invalid @enderror"
                                placeholder="ảnh" wire:model="variants.{{ $index }}.image">
                            @error("variants.$index.image")
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                            @if (isset($variant['image']) && is_object($variant['image']))
                                <img src="{{ $variant['image']->temporaryUrl() }}" alt="Xem trước ảnh biến thể"
                                    class="img-thumbnail mt-2" style="max-height: 120px;">
                            @endif
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-danger"
                                wire:click.prevent="removeVariant({{ $index }})">Xoá</button>
                        </div>
                    </div>
                </div>
            @endforeach

            <button type="button" class="btn btn-outline-primary" wire:click.prevent="addVariant">+ Thêm biến
                thể</button>
        </div>



        <button type="submit" class="btn btn-primary">Lưu</button>
        <a href="{{ route('admin.food.list') }}" class="btn btn-secondary ms-2">Hủy</a>
    </form>
</div>
