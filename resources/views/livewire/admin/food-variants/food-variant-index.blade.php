<div>
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="container-lg mb-4" wire:poll.5s>
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Quản lý biến thể món ăn</h2>
            <div>
                @if (!$showDeleted)
                    <a href="{{ route('admin.foods_variants.create') }}" class="btn btn-success me-2">
                        <i class="fas fa-plus me-1"></i>Thêm món ăn
                    </a>
                @endif
                <button wire:click="$toggle('showDeleted')" class="btn btn-outline-danger">
                    @if ($showDeleted)
                        <i class="fas fa-eye me-1"></i>Xem món biến thể hoạt động
                    @else
                        <i class="fas fa-trash me-1"></i>Xem biến thể đã xóa
                    @endif
                </button>
            </div>
        </div>

        <div class="card bg-dark">
            <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="row g-3">
                    <div class="col-md-4 col-lg-3">
                        <div class="input-group">
                            <input type="text" wire:model.live.debounce.300ms="search"
                                class="form-control bg-dark text-light" placeholder="Tìm kiếm biến thể...">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                    </div>
                    @if (!$showDeleted)
                        <div class="col-md-3 col-lg-2">
                            <select wire:model.live="statusFilter" class="form-select bg-dark text-light">
                                <option value="">Tất cả trạng thái</option>
                                <option value="available">Còn hàng</option>
                                <option value="out_of_stock">Hết hàng</option>
                                <option value="hidden">Ẩn</option>
                            </select>
                        </div>
                    @endif
                    @if (!$showDeleted)
                        <div class="col-md-3 col-lg-2">
                            <select wire:model.live="sortDateFilter" class="form-select bg-dark text-light">
                                <option value="desc">Mới nhất</option>
                                <option value="asc">Cũ nhất</option>
                            </select>
                        </div>
                    @endif
                    <div class="col-md-2">
                        <button wire:click="resetFilters" class="btn btn-outline-warning">
                            <i class="fas fa-refresh me-1"></i>Reset
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body bg-dark">
                <div class="table-responsive">
                    <table class="table table-dark table-striped table-hover">
                        <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <tr>
                                <th class="text-center text-light">STT</th>
                                <th class="text-center text-light">Ảnh</th>
                                <th class="text-center text-light">Tên biến thể</th>
                                <th class="text-center text-light">Món ăn</th>
                                <th class="text-center text-light">Giá</th>
                                <th class="text-center text-light">Số lượng</th>
                                <th class="text-center text-light">Trạng thái</th>
                                <th class="text-center text-light">Ngày tạo</th>
                                <th class="text-center text-light">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($foodVariants as $variant)
                                <tr wire:key="{{ $variant->id }}">
                                    <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                    <td class="text-center">
                                        <img src="{{ asset('storage/' . ($variant->image ?? '404.webp')) }}"
                                            class="rounded" style="width: 60px; height: auto;">
                                    </td>
                                    <td class="text-light">
                                        <strong>{{ $variant->name }}</strong>
                                        @if ($variant->trashed())
                                            <span class="badge bg-danger ms-1">Đã xóa</span>
                                        @endif

                                    </td>

                                    <td class="text-info">{{ $variant->foodItem->name ?? '---' }}</td>
                                    <td class="text-warning">{{ number_format($variant->price) }}đ</td>
                                    <td class="text-light">{{ $variant->quantity_available }}</td>
                                    <td class="text-center">
                                        @if (!$showDeleted && !$variant->trashed())
                                            @switch($variant->status)
                                                @case('available')
                                                    <span class="badge bg-success">Còn hàng</span>
                                                @break

                                                @case('out_of_stock')
                                                    <span class="badge bg-danger">Hết hàng</span>
                                                @break

                                                @case('hidden')
                                                    <span class="badge bg-secondary">Ẩn</span>
                                                @break
                                            @endswitch
                                        @else
                                            <span class="badge bg-secondary">Đã xóa</span>
                                        @endif

                                    </td>
                                    <td class="text-light">{{ $variant->created_at->format('d/m/Y H:i') }}</td>
                                    @if ($showDeleted)
                                        <td class="text-center">
                                            <div class="d-flex gap-2 justify-content-center">
                                                <button type="button"
                                                    wire:click.once="restoreVariant({{ $variant->id }})"
                                                    class="btn btn-sm btn-success" title="Khôi phục">
                                                    <i class="fas fa-undo" style="margin-right: 0"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger"
                                                    wire:sc-model="forceDeleteVariant({{ $variant->id }})"
                                                    wire:sc-confirm.warning="Bạn có chắc chắn muốn XÓA VĨNH VIỄN biến thể '{{ $variant->name }}'? Hành động này KHÔNG THỂ HOÀN TÁC!"
                                                    title="Xóa vĩnh viễn">
                                                    <i class="fas fa-trash-alt" style="margin-right: 0"></i>
                                                </button>

                                            </div>
                                        </td>
                                    @else
                                        <td class="text-center">
                                            <div class="d-flex gap-2 justify-content-center">
                                                <a href="{{ route('admin.foods_variants.detail', $variant->id) }}"
                                                    class="btn btn-sm btn-info" title="Xem chi tiết">
                                                    <i class="fas fa-eye" style="margin-right: 0"></i>
                                                </a>
                                                <a href="{{ route('admin.foods_variants.edit', $variant->id) }}"
                                                    class="btn btn-sm btn-warning" title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger"
                                                    wire:sc-model="deleteVariant({{ $variant->id }})"
                                                    wire:sc-confirm.warning="Bạn có chắc chắn muốn xóa biến thể '{{ $variant->name }}'?">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                                <p>Không có biến thể nào</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $foodVariants->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
