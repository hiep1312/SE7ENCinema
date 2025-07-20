<div class="scRender">
    {{-- ALERT --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" wire:ignore>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="container mb-4">
        <h2 class="text-light mb-4">📋 Quản lý Thuộc tính & Giá trị</h2>

        <div class="card bg-dark border-0 shadow-sm">
            {{-- HEADER --}}
            <div class="card-header border-0 cool-gradient">
                <div class="row align-items-center">
                    <div class="col-md-4 col-lg-3">
                        <div class="input-group">
                            <span class="input-group-text bg-dark border-0 text-light">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" wire:model.live.debounce.300ms="search"
                                class="form-control bg-dark border-0 text-light" placeholder="Tìm kiếm thuộc tính...">
                        </div>
                    </div>
                </div>
            </div>

            {{-- BODY --}}
            <div class="card-body">
                {{-- FORM THÊM --}}
                {{-- Nút Thêm mới --}}
                <div class="mb-3">
                    <button wire:click="toggleCreateForm" class="btn btn-success">
                        <i class="fas fa-plus"></i> Thêm thuộc tính mới
                    </button>
                </div>

                {{-- FORM THÊM --}}
                @if ($showCreateForm)
                    <div class="card mb-4" style="background-color: #4a4a4a;">
                        <div class="card-header">
                            <h5 class="mb-0">➕ Thêm Thuộc tính mới</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <input type="text" wire:model.defer="newName" class="form-control"
                                        placeholder="Tên thuộc tính">
                                    @error('newName')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <input type="text" wire:model.defer="newDescription" class="form-control"
                                        placeholder="Mô tả">
                                    @error('newDescription')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <input type="text" wire:model.defer="newValues" class="form-control"
                                        placeholder="Giá trị mặc định (phân cách phẩy)">
                                    @error('newValues')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="mt-3 text-end">
                                <button wire:click="create" class="btn btn-success">
                                    <i class="fas fa-check me-1"></i> Lưu
                                </button>
                                <button wire:click="toggleCreateForm" class="btn btn-outline-light">
                                    ❌ Huỷ
                                </button>
                            </div>
                        </div>
                    </div>
                @endif


                {{-- BẢNG --}}
                <div class="table-responsive">
                    <table class="table table-dark table-striped table-hover">
                        <thead class="cool-gradient" >
                            <tr>
                                <th>#</th>
                                <th>Thuộc tính</th>
                                <th>Mô tả</th>
                                <th>Giá trị</th>
                                <th class="text-end">Hành động</th>
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
                                        <div class="gap-2 justify-content-center">
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

                                {{-- FORM EDIT --}}
                                @if ($editingAttributeId === $attribute->id)
                                    <tr>
                                        <td colspan="5">
                                            <div class="p-3" style="background-color: #4a4a4a;">
                                                {{-- màu xám đậm --}}
                                                <div class="row g-2">
                                                    <div class="col-md-5">
                                                        <input wire:model.defer="editingAttributeName"
                                                            class="form-control" placeholder="Tên thuộc tính">
                                                        @error('editingAttributeName')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-5">
                                                        <input wire:model.defer="editingAttributeDescription"
                                                            class="form-control" placeholder="Mô tả">
                                                    </div>
                                                    <div class="col-md-2 text-end">
                                                        <button wire:click="saveAttribute"
                                                            class="btn btn-success btn-sm">💾 Lưu</button>
                                                        <button wire:click="resetAttributeEdit"
                                                            class="btn btn-outline-light btn-sm">❌ Huỷ</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endif

                                {{-- FORM GIÁ TRỊ --}}
                                @if ($expandedAttributeId === $attribute->id)
                                    <tr>
                                        <td colspan="5">
                                            <div class="p-3" style="background-color: #4a4a4a;">
                                                {{-- xám đậm --}}
                                                <h6 class="mb-3">🔧 Quản lý Giá trị</h6>
                                                {{-- List --}}
                                                @forelse ($attribute->values as $value)
                                                    <div class="d-flex justify-content-between mb-2">
                                                        <span>{{ $value->value }}</span>
                                                        <div class="gap-2 justify-content-center">
                                                            <button wire:click="editValue({{ $value->id }})"
                                                                class="btn btn-sm btn-warning"><i
                                                                    class="fas fa-edit"></i></button>
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

                                                {{-- Input --}}
                                                <div class="input-group mt-3">
                                                    <input wire:model.defer="editingValue"
                                                        class="form-control bg-dark text-light"
                                                        placeholder="Nhập giá trị mới...">
                                                    <button wire:click="saveValue({{ $attribute->id }})"
                                                        class="btn btn-success btn-sm">
                                                        {{ $editingValueId ? '💾 Cập nhật' : '➕ Thêm' }}
                                                    </button>
                                                    <button wire:click="resetValueEdit"
                                                        class="btn btn-outline-light btn-sm">Huỷ</button>
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
