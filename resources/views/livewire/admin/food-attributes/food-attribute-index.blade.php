<div class="scRender">
    <div class="container-lg mb-4">
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Quản lý Thuộc tính</h2>
            <div>
                <button wire:click="toggleCreateForm" class="btn btn-success">
                    <i class="fas fa-plus"></i> Thêm thuộc tính mới
                </button>
            </div>
        </div>

        @if ($showCreateForm)
            <div class="card bg-dark mb-4">
                <div class="card-header bg-gradient text-light">
                    <h5 class="mb-0"> Thêm Thuộc tính mới</h5>
                </div>
                <div class="card-body bg-dark">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="text" wire:model="newName" id="newName"
                                    class="form-control bg-dark text-light border-secondary"
                                    placeholder="Tên thuộc tính">
                                <label for="newName">Tên thuộc tính</label>
                            </div>
                            @error('newName')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="text" wire:model="newDescription" id="newDescription"
                                    class="form-control bg-dark text-light border-secondary" placeholder="Mô tả">
                                <label for="newDescription">Mô tả</label>
                            </div>
                            @error('newDescription')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="text" wire:model="newValues" id="newValues"
                                    class="form-control bg-dark text-light border-secondary"
                                    placeholder="Giá trị mặc định">
                                <label for="newValues">Giá trị mặc định (phân cách phẩy)</label>
                            </div>
                            @error('newValues')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-3 text-end">
                        <button wire:click="create" class="btn btn-success">
                            <i class="fas fa-check me-1"></i> Lưu
                        </button>
                        <button wire:click="toggleCreateForm" class="btn btn-outline-light">❌ Huỷ</button>
                    </div>
                </div>
            </div>
        @endif

        <div class="card bg-dark" wire:poll.6s>
            <div class="card-header bg-gradient text-light"
                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="row g-3">
                    <div class="col-md-4 col-lg-3">
                        <div class="input-group">
                            <input type="text" wire:model.live.debounce.300ms="search"
                                class="form-control bg-dark text-light" placeholder="Tìm kiếm món ăn...">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body bg-dark">
                <div class="table-responsive">
                    <table class="table table-dark table-striped table-hover">
                        <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <tr>
                                <th class="text-center text-light">STT</th>
                                <th class="text-center text-light">Thuộc tính</th>
                                <th class="text-center text-light">Mô tả</th>
                                <th class="text-center text-light">Giá trị</th>
                                <th class="text-center text-light">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($attributes as $attribute)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $attribute->name }}</td>
                                    <td>{{ $attribute->description }}</td>
                                    <td>
                                        @if ($attribute->values->count())
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach ($attribute->values as $value)
                                                    <span class="badge bg-info">{{ $value->value }}</span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-muted fst-italic">Chưa có</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <button wire:click="editAttribute({{ $attribute->id }})"
                                                class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <button wire:click="toggleExpand({{ $attribute->id }})"
                                                class="btn btn-sm btn-info">
                                                <i class="fas fa-cogs"></i>
                                            </button>

                                            <button wire:click="deleteAttribute({{ $attribute->id }})"
                                                onclick="return confirm('Bạn có chắc chắn muốn xoá thuộc tính này?')"
                                                class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                @if ($editingAttributeId === $attribute->id)
                                    <tr>
                                        <td colspan="5">
                                            <div class="p-3 bg-dark border border-secondary rounded-3">
                                                <div class="row g-2">
                                                    <div class="col-md-5">
                                                        <div class="form-floating">
                                                            <input wire:model="editingAttributeName"
                                                                id="editName{{ $attribute->id }}"
                                                                class="form-control bg-dark text-light border-secondary"
                                                                placeholder="Tên thuộc tính">
                                                            <label for="editName{{ $attribute->id }}">Tên thuộc
                                                                tính</label>
                                                        </div>
                                                        @error('editingAttributeName')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="form-floating">
                                                            <input wire:model="editingAttributeDescription"
                                                                id="editDesc{{ $attribute->id }}"
                                                                class="form-control bg-dark text-light border-secondary"
                                                                placeholder="Mô tả">
                                                            <label for="editDesc{{ $attribute->id }}">Mô tả</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 text-end">
                                                        <button wire:click="saveAttribute"
                                                            class="btn btn-success btn-sm">
                                                            <i class="fas fa-check me-1"></i> Lưu
                                                        </button>
                                                        <button wire:click="resetAttributeEdit"
                                                            class="btn btn-outline-light btn-sm">❌ Huỷ</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endif

                                @if ($expandedAttributeId === $attribute->id)
                                    <tr>
                                        <td colspan="5">
                                            <div class="p-3 bg-dark border border-secondary rounded-3">
                                                <h6 class="mb-3 text-light">Quản lý Giá trị</h6>
                                                @forelse ($attribute->values as $value)
                                                    <div
                                                        class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="badge bg-primary">{{ $value->value }}</span>
                                                        <div class="d-flex gap-2">
                                                            <button wire:click="editValue({{ $value->id }})"
                                                                class="btn btn-sm btn-warning">
                                                                <i class="fas fa-edit"></i>
                                                            </button>

                                                            <button wire:click="deleteValue({{ $value->id }})"
                                                                onclick="return confirm('Bạn có chắc chắn muốn xoá giá trị này?')"
                                                                class="btn btn-sm btn-danger">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <div class="text-muted fst-italic">Chưa có giá trị.</div>
                                                @endforelse

                                                <div class="form-floating mt-3">
                                                    <input wire:model="editingValue" id="editingValueInput"
                                                        class="form-control bg-dark text-light border-secondary"
                                                        placeholder="Giá trị mới">
                                                    <label for="editingValueInput">Nhập giá trị mới...</label>
                                                </div>
                                                <div class="mt-2">
                                                    <button wire:click="saveValue({{ $attribute->id }})"
                                                        class="btn btn-success btn-sm">
                                                        {!! $editingValueId ? '💾 Cập nhật' : '<i class="fas fa-plus"></i> Thêm' !!}
                                                    </button>
                                                    <button wire:click="resetValueEdit"
                                                        class="btn btn-outline-light btn-sm">❌ Huỷ</button>
                                                </div>
                                                @error('editingValue')
                                                    <div><small class="text-danger">{{ $message }}</small></div>
                                                @enderror
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted fst-italic">
                                        Không có thuộc tính nào.
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
