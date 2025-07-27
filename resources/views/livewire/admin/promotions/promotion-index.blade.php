<div class="scRender">
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show mt-2 mx-2" role="alert" wire:ignore>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show mt-2 mx-2" role="alert" wire:ignore>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="container-lg mb-4">
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Quản lý mã giảm giá</h2>
            <div>
                <a href="{{ route('admin.promotions.create') }}" class="btn btn-success me-2">
                    <i class="fas fa-plus me-1"></i>Thêm mã giảm giá
                </a>
            </div>
        </div>

        <div class="card bg-dark" wire:poll.6s>
            <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="row g-3">
                    <!-- Tìm kiếm -->
                    <div class="col-md-4 col-lg-3">
                        <div class="input-group">
                            <input type="text" wire:model.live.debounce.300ms="search"
                                   class="form-control bg-dark text-light"
                                   placeholder="Tìm kiếm mã giảm giá...">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                    </div>

                    <!-- Lọc theo trạng thái -->
                    <div class="col-md-3 col-lg-2">
                        <select wire:model.live="statusFilter" class="form-select bg-dark text-light">
                            <option value="">Tất cả trạng thái</option>
                            <option value="active">Hoạt động</option>
                            <option value="inactive">Ngừng hoạt động</option>
                            <option value="expired">Đã hết hạn</option>
                        </select>
                    </div>

                    <!-- Lọc theo suất chiếu -->
                    <div class="col-md-3 col-lg-2">
                        <select wire:model.live="usageFilter" class="form-select bg-dark text-light">
                            <option value="">Tất cả TH sử dụng</option>
                            <option value="1">Đã có người sử dụng</option>
                            <option value="0">Chưa có người sử dụng</option>
                        </select>
                    </div>

                    <!-- Lọc theo loại giảm giá -->
                    <div class="col-md-3 col-lg-2">
                        <select wire:model.live="discountTypeFilter" class="form-select bg-dark text-light">
                            <option value="">Tất cả loại giảm giá</option>
                            <option value="fixed_amount">Cố định (VNĐ)</option>
                            <option value="percentage">Phần trăm (%)</option>
                        </select>
                    </div>

                    <!-- Reset filters -->
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
                                <th class="text-center text-light">Mã giảm giá</th>
                                <th class="text-center text-light">Thời gian sử dụng</th>
                                <th class="text-center text-light">Giảm giá</th>
                                <th class="text-center text-light">Sử dụng</th>
                                <th class="text-center text-light">Trạng thái</th>
                                <th class="text-center text-light">Ngày tạo</th>
                                <th class="text-center text-light">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($promotions as $promotion)
                                <tr>
                                    <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                    <td style="max-width: 300px;">
                                        <strong class="text-light">{{ $promotion->code }}</strong>
                                        <div class="movie-genre text-wrap lh-base" style="margin-bottom: 0; margin-top: 3px;">
                                            <i class="fa-solid fa-ticket-perforated me-1"></i>
                                            {{ $promotion->title }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="mb-1">
                                            <small style="color: #34c759;">
                                                <i class="fas fa-play me-1"></i>
                                                {{ $promotion->start_date->format('Y-m-d H:i') }}
                                            </small>
                                        </div>
                                        <div>
                                            <small style="color: #ff4d4f;">
                                                <i class="fas fa-stop me-1"></i>
                                                {{ $promotion->end_date->format('Y-m-d H:i') }}
                                            </small>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-light">
                                            {{ number_format($promotion->discount_value, 0, '.', '.') . ($promotion->discount_type === 'percentage' ? '%' : 'đ') }}
                                        </span>
                                        <small class="text-muted d-block" style="margin-top: 5px !important; font-size: 12px">
                                            ĐHTT: {{ number_format($promotion->min_purchase, 0, '.', '.') ?? 0 }}đ
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        @switch($promotion->usages_count)
                                            @case($promotion->usage_limit) <span class="badge bg-success"> @break
                                            @case(0) <span class="badge bg-danger"> @break
                                            @default <span class="badge bg-primary"> @break
                                        @endswitch
                                        {{ $promotion->usages_count }} / {{ $promotion->usage_limit ?? "Vô hạn" }} <i class="fas fa-user"></i> đã sử dụng</span>
                                    </td>
                                    <td class="text-center">
                                        @switch($promotion->status)
                                            @case('active')
                                                <span class="badge-clean-base badge-clean-green">Hoạt động</span>
                                                @break
                                            @case('inactive')
                                                <span class="badge-clean-base badge-clean-red">Ngừng hoạt động</span>
                                                @break
                                            @case('expired')
                                                <span class="badge-clean-base badge-clean-orange">Đã hết hạn</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td class="text-center">
                                        <span class="text-light">
                                            {{ $promotion->created_at ? $promotion->created_at->format('d/m/Y H:i') : 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2 justify-content-center">
                                            <a href="{{ route('admin.promotions.detail', $promotion->id) }}"
                                                class="btn btn-sm btn-info"
                                                title="Xem chi tiết">
                                                <i class="fas fa-eye" style="margin-right: 0"></i>
                                            </a>
                                            <a href="{{ route('admin.promotions.edit', $promotion->id) }}"
                                                class="btn btn-sm btn-warning"
                                                title="Chỉnh sửa">
                                                <i class="fas fa-edit" style="margin-right: 0"></i>
                                            </a>
                                            @if($promotion->usages()->exists() && $promotion->status !== "expired")
                                                <button type="button"
                                                        class="btn btn-sm btn-danger"
                                                        wire:sc-alert.error="Không thể xóa mã giảm giá đã có người sử dụng và chưa hết hạn!"
                                                        wire:sc-model
                                                        title="Xóa">
                                                    <i class="fas fa-trash" style="margin-right: 0"></i>
                                                </button>
                                            @else
                                                <button type="button"
                                                        class="btn btn-sm btn-danger"
                                                        wire:sc-model="deletePromotion({{ $promotion->id }})"
                                                        wire:sc-confirm.warning="Bạn có chắc chắn muốn xóa mã giảm giá '{{ $promotion->code }}'? Hành động này KHÔNG THỂ HOÀN TÁC!"
                                                        title="Xóa">
                                                    <i class="fas fa-trash" style="margin-right: 0"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <p>Không có mã giảm giá nào</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $promotions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
