<div>
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" wire:ignore>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" wire:ignore>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="container-lg mb-4">
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Quản lý món ăn</h2>
            <div>
                @if (!$showDeleted)
                    <a href="{{ route('admin.foods.create') }}" class="btn btn-success me-2">
                        <i class="fas fa-plus me-1"></i>Thêm món ăn
                    </a>
                @endif
                <button wire:click="$toggle('showDeleted')" class="btn btn-outline-danger">
                    @if ($showDeleted)
                        <i class="fas fa-eye me-1"></i>Xem món ăn hoạt động
                    @else
                        <i class="fas fa-trash me-1"></i>Xem món ăn đã xóa
                    @endif
                </button>
            </div>
        </div>

        <div class="card bg-dark" wire:poll.5s>
            <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="row g-3">
                    <!-- Tìm kiếm -->
                    <div class="col-md-4 col-lg-3">
                        <div class="input-group">
                            <input type="text" wire:model.live.debounce.300ms="search"
                                class="form-control bg-dark text-light" placeholder="Tìm kiếm món ăn...">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                    </div>

                    <!-- Lọc theo trạng thái -->
                    @if (!$showDeleted)
                        <div class="col-md-3 col-lg-2">
                            <select wire:model.live="statusFilter" class="form-select bg-dark text-light">
                                <option value="">Tất cả trạng thái</option>
                                <option value="activate">Đang bán</option>
                                <option value="discontinued">Ngừng bán</option>
                            </select>
                        </div>

                        <!-- Lọc theo suất chiếu -->
                        <div class="col-md-3 col-lg-2">
                            <select wire:model.live="sortDateFilter" class="form-select bg-dark text-light">
                                <option value="desc">Mới nhất</option>
                                <option value="asc">Cũ nhất</option>
                            </select>
                        </div>

                        <!-- Reset filters -->
                        <div class="col-md-2">
                            <button wire:click="resetFilters" class="btn btn-outline-warning">
                                <i class="fas fa-refresh me-1"></i>Reset
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card-body bg-dark">
                <div class="table-responsive">
                    <table class="table table-dark table-striped table-hover">
                        <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <tr>
                                <th class="text-center text-light">STT</th>
                                <th class="text-center text-light">Ảnh</th>
                                <th class="text-center text-light">Tên món ăn</th>
                                <th class="text-center text-light">Mô tả</th>
                                <th class="text-center text-light">Trạng thái</th>
                                <th class="text-center text-light">Biến thể món ăn</th>
                                @if ($showDeleted)
                                    <th class="text-center text-light">Ngày xóa</th>
                                @else
                                    <th class="text-center text-light">Ngày tạo</th>
                                @endif
                                <th class="text-center text-light">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($foodItems as $food)
                                <tr wire:key="{{ $food->id }}">
                                    <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="mt-1 overflow-auto d-block text-center"
                                            style="max-height: 100px; width: 100px;">
                                            <img src="{{ asset('storage/' . ($food->image ?? '404.webp')) }}"
                                                alt="Ảnh sản phẩm {{ $food->name }}" class="rounded"
                                                style="width: 100%; height: auto;">
                                        </div>
                                    </td>
                                    <td>
                                        <strong class="text-light">{{ $food->name }}</strong>
                                        @if ($food->trashed())
                                            <span class="badge bg-danger ms-1">Đã xóa</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($food->description)
                                            <span
                                                class="text-light text-wrap">{{ Str::limit($food->description, 30, '...') }}</span>
                                        @else
                                            <span class="text-muted">Không có mô tả</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if (!$showDeleted && !$food->trashed())
                                            @switch($food->status)
                                                @case('activate')
                                                    <span class="badge bg-success">Đang bán</span>
                                                @break

                                                @case('discontinued')
                                                    <span class="badge bg-danger">Ngừng bán</span>
                                                @break
                                            @endswitch
                                        @else
                                            <span class="badge bg-secondary">Đã xóa</span>
                                        @endif
                                    </td>

                                    <td class="col-2 bg-opacity-10 border-start border-3" style="max-width: 200px;">
                                        @if ($food->variants->count() > 0)
                                            <div class="showtime-info">
                                                <!-- Tên các biến thể -->
                                                <div class="movie-title mb-1">
                                                    <i class="fa-solid fa-expand-arrows-alt me-1 text-primary"></i>
                                                    <strong class="text-primary text-wrap">
                                                        {{ Str::limit($food->variants->pluck('name')->implode(', '), 45, '...') ?? 'Không có biến thể' }}
                                                    </strong>
                                                </div>

                                                <!-- Tổng biển thể -->
                                                <div class="showtime-price mb-1">
                                                    <i class="fa-solid fa-list me-1 text-warning"></i>
                                                    <span class="text-warning">
                                                        {{ $food->variants->count() }} biến thể
                                                    </span>
                                                </div>

                                                <!-- Badge giá từ thấp đến cao -->
                                                <span class="badge bg-success">
                                                    <i class="fa-duotone fa-solid fa-money-check-dollar me-1"></i>
                                                    {{ number_format($food->variants->min('price'), 0, '.', '.') }}đ -
                                                    {{ number_format($food->variants->max('price'), 0, '.', '.') }}đ
                                                </span>

                                                <!-- Tổng số lượng sản phẩm biến thể -->
                                                <div class="time-until mt-1">
                                                    <small class="text-info">
                                                        <i class="fas fa-utensils me-1"></i>
                                                        {{ $food->variants->sum('quantity_available') }} số lượng món
                                                        ăn
                                                    </small>
                                                </div>
                                            </div>
                                        @else
                                            <div class="text-center py-2">
                                                <i class="fa-solid fa-truck-utensils fa-2x text-muted mb-2"></i>
                                                <div class="text-muted">
                                                    <strong>Không có biến thể</strong>
                                                </div>
                                                <small class="text-muted">Chưa tạo biến thể</small>
                                            </div>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        @if ($showDeleted)
                                            <span class="text-light">
                                                {{ $food->deleted_at ? $food->deleted_at->format('d/m/Y H:i') : 'N/A' }}
                                            </span>
                                        @else
                                            <span class="text-light">
                                                {{ $food->created_at ? $food->created_at->format('d/m/Y H:i') : 'N/A' }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($showDeleted)
                                            <div class="d-flex gap-3 justify-content-center">
                                                <button type="button"
                                                    wire:click.once="restoreFood({{ $food->id }})"
                                                    class="btn btn-sm btn-success" title="Khôi phục">
                                                    <i class="fas fa-undo" style="margin-right: 0"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger"
                                                    wire:sc-model="forceDeleteFood({{ $food->id }})"
                                                    wire:sc-confirm.warning="Bạn có chắc chắn muốn XÓA VĨNH VIỄN món ăn '{{ $food->name }}'? Hành động này KHÔNG THỂ HOÀN TÁC!"
                                                    title="Xóa vĩnh viễn">
                                                    <i class="fas fa-trash-alt" style="margin-right: 0"></i>
                                                </button>
                                            </div>
                                        @else
                                            <div class="d-flex gap-3 justify-content-center">
                                                <a href="{{ route('admin.foods.detail', $food->id) }}"
                                                    class="btn btn-sm btn-info" title="Xem chi tiết">
                                                    <i class="fas fa-eye" style="margin-right: 0"></i>
                                                </a>
                                                <a href="{{ route('admin.foods.edit', $food->id) }}"
                                                    class="btn btn-sm btn-warning" title="Chỉnh sửa">
                                                    <i class="fas fa-edit" style="margin-right: 0"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger"
                                                    wire:sc-model="deleteFood({{ $food->id }})"
                                                    wire:sc-confirm.warning="Bạn có chắc chắn muốn xóa món ăn '{{ $food->name }}'?"
                                                    title="Xóa">
                                                    <i class="fas fa-trash" style="margin-right: 0"></i>
                                                </button>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                                <p>
                                                    @if ($showDeleted)
                                                        Không có món ăn nào đã xóa
                                                    @else
                                                        Không có món ăn nào
                                                    @endif
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $foodItems->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
