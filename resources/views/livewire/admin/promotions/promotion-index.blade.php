<div>
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" wire:ignore role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" wire:ignore role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="container-lg mb-4" wire:poll.7s>
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Quản lý khuyến mãi</h2>
            <div>
                <a href="{{ route('admin.promotions.create') }}" class="btn btn-success me-2">
                    <i class="fas fa-plus me-1"></i>Thêm khuyến mãi
                </a>
            </div>
        </div>

        <div class="card bg-dark">
            <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="row g-3">
                    <div class="col-md-4 col-lg-3">
                        <div class="input-group">
                            <input type="text" wire:model.live.debounce.300ms="search" class="form-control bg-dark text-light" placeholder="Tìm kiếm khuyến mãi...">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-2">
                        <select wire:model.live="statusFilter" class="form-select bg-dark text-light">
                            <option value="">Tất cả trạng thái</option>
                            <option value="active">Hoạt động</option>
                            <option value="inactive">Ngừng hoạt động</option>
                            <option value="expired">Hết hạn</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-lg-2">
                        <select wire:model.live="bookingFilter" class="form-select bg-dark text-light">
                            <option value="">Tất cả KM áp dụng</option>
                            <option value="has_booking">Có khách hàng áp dụng</option>
                            <option value="no_booking">Chưa có khách hàng áp dụng</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-lg-2">
                        <select wire:model.live="discountTypeFilter" class="form-select bg-dark text-light">
                            <option value="">Tất cả loại giảm giá</option>
                            <option value="percentage">Phần trăm (%)</option>
                            <option value="fixed_amount">Cố định (VNĐ)</option>
                        </select>
                    </div>
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
                                <th class="text-light">Tiêu đề</th>
                                <th class="text-light">Mã khuyến mãi</th>
                                {{-- <th class="text-light">Mô tả</th> --}}
                                <th class="text-center text-light">Giảm giá</th>
                                <th class="text-center text-light">Ngày bắt đầu</th>
                                <th class="text-center text-light">Ngày kết thúc</th>
                                <th class="text-center text-light">Khuyến mãi được áp dụng</th>
                                <th class="text-center text-light">Ngày tạo</th>
                                <th class="text-center text-light">Trạng thái</th>
                                <th class="text-center text-light">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($promotions as $promotion)
                                <tr>
                                    <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                    <td><strong class="text-light">{{ Str::limit($promotion->title, 10) }}</strong></td>
                                    <td><strong class="text-light">{{ $promotion->code }}</strong></td>
                                    {{-- <td>{{ Str::limit($promotion->description, 30) }}</td> --}}
                                    <td class="text-center">
                                        @if($promotion->discount_type === 'percentage')
                                            <span class="badge bg-primary">{{ $promotion->discount_value }}%</span>
                                        @else
                                            <span class="badge bg-danger">{{ number_format($promotion->discount_value, 0, '.', '.') }}đ</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ ($promotion->start_date)->format('d/m/Y H:i') }}</td>
                                    <td class="text-center">{{ ($promotion->end_date)->format('d/m/Y H:i') }}</td>
                                    <td class="bg-opacity-10 border-start border-3" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        @if($promotion->usages->count() > 0)
                                            <div class="showtime-info">
                                                <!-- Tên các user -->
                                                <div class="movie-title mb-1">
                                                    <i class="fas fa-user-friends me-1 text-primary"></i>
                                                    <strong class="text-primary text-wrap">
                                                        {{ Str::limit($promotion->usages->pluck('booking.user.name')->map(fn($n) => $n ?? 'Khách đã xóa')->implode(', '), 45, '...') }}
                                                    </strong>
                                                </div>
                                                <!-- Mã đơn hàng -->
                                                <div class="showtime-schedule mb-1">
                                                    <i class="fas fa-ticket-alt me-1 text-info"></i>
                                                    <span class="text-info text-wrap">
                                                        {{ Str::limit($promotion->usages->pluck('booking.booking_code')->map(fn($c) => $c ?? 'N/A')->implode(', '), 45, '...') }}
                                                    </span>
                                                </div>
                                                <!-- Tổng số lượt sử dụng -->
                                                <div class="showtime-price mb-1">
                                                    <i class="fas fa-users me-1 text-warning"></i>
                                                    <span class="text-warning">
                                                        {{ $promotion->usages->count() }} lượt sử dụng
                                                    </span>
                                                </div>
                                                <!-- Tổng số tiền đã giảm -->
                                                <span class="badge bg-success">
                                                    <i class="fas fa-money-bill me-1"></i>
                                                    {{ number_format($promotion->usages->sum('discount_amount'), 0, '.', '.') }}đ tổng giảm
                                                </span>
                                            </div>
                                        @else
                                            <div class="text-center py-2">
                                                <i class="fas fa-calendar-times fa-2x text-muted mb-2"></i>
                                                <div class="text-muted" style="font-size: 12px;">
                                                    <strong>Không có khách hàng áp dụng</strong>
                                                </div>
                                                <small class="text-muted" style="font-size: 10px;">Chưa có khách hàng áp dụng</small>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ Carbon\Carbon::parse($promotion->created_at)->format('d/m/Y') }}</td>
                                    <td class="text-center">
                                        <span class="badge
                                            {{ $promotion->status === 'active' ? 'bg-success' : ($promotion->status === 'expired' ? 'bg-secondary' : 'bg-danger') }}">
                                            {{ $promotion->status === 'active' ? 'Hoạt động' : ($promotion->status === 'expired' ? 'Hết hạn' : 'Ngừng hoạt động') }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-3 justify-content-center">
                                            <a href="{{ route('admin.promotions.detail', $promotion->id) }}" class="btn btn-sm btn-info" title="Xem chi tiết">
                                                <i style="margin-right: 0px" class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.promotions.edit', $promotion->id) }}" class="btn btn-sm btn-warning" title="Chỉnh sửa">
                                                <i style="margin-right: 0px" class="fas fa-edit"></i>
                                            </a>
                                            <button wire:sc-model="deletePromotion({{ $promotion->id }})"
                                                    wire:sc-confirm.warning="Bạn có chắc chắn muốn xóa khuyến mãi '{{ $promotion->code }}'? Hành động này KHÔNG THỂ HOÀN TÁC!"
                                                    class="btn btn-sm btn-danger" title="Xóa">
                                                <i style="margin-right: 0px" class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <p>Không có khuyến mãi nào</p>
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
