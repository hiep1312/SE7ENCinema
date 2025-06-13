<div class="container-lg mb-4" wire:poll="resetPromotionDetail">
    <div class="d-flex justify-content-between align-items-center my-3">
        <h2 class="text-light">Chi tiết khuyến mãi: {{ $promotion->title }}</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.promotions.edit', $promotion->id) }}" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i>Chỉnh sửa
            </a>
            <a href="{{ route('admin.promotions.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

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

    <!-- Cards tổng kết -->
    <div class="row mb-4 g-3">
        <div class="col-md-6">
            <div class="card text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Số lượt sử dụng</h6>
                            <h3 class="mb-0">{{ $promotion->usages->count() }}</h3>
                            <small>Lần</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-ticket-alt fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Tổng tiền đã giảm</h6>
                            <h3 class="mb-0">{{ number_format($promotion->usages->sum('discount_amount'), 0, '.', '.') }}đ</h3>
                            <small>VNĐ</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-money-bill-wave fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs chuyển đổi -->
    <ul class="nav nav-tabs bg-dark mb-3" role="tablist">
        <li class="nav-item">
            <button class="nav-link @if($tabCurrent === 'overview') active bg-light text-dark @else text-light @endif" wire:click="$set('tabCurrent', 'overview')">
                <i class="fas fa-info-circle me-1"></i>Tổng quan
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link @if($tabCurrent === 'history') active bg-light text-dark @else text-light @endif" wire:click="$set('tabCurrent', 'history')">
                <i class="fas fa-history me-1"></i>Lịch sử sử dụng
            </button>
        </li>
    </ul>

    @if($tabCurrent === 'overview')
    <!-- Block thông tin chi tiết nhỏ gọn -->
    <div class="row my-4 g-3">
        <div class="col-md-8">
            <div style="border-radius: 0 1rem 1rem 1rem; box-shadow: 0 0 0 6px rgba(255,255,255,0.08);">
                <div class="card bg-dark border-light h-100"
                     style="border-radius: 0 1rem 1rem 1rem; border: 1.5px solid #fff;">
                    <div class="card-header bg-gradient text-light d-flex align-items-center"
                         style="background: linear-gradient(135deg, #232526 0%, #414345 100%); border-top-left-radius: 0;">
                        <i class="fas fa-tag me-2"></i>
                        <span>Thông tin chi tiết</span>
                    </div>
                    <div class="card-body py-3" style="font-size: 1rem;">
                        <div class="row mb-2">
                            <div class="col-5 text-warning fw-bold">Tên khuyến mãi:</div>
                            <div class="col-7 text-light">{{ $promotion->title }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-5 text-warning fw-bold">Mô tả:</div>
                            <div class="col-7 text-light">{{ $promotion->description }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-5 text-warning fw-bold">Trạng thái:</div>
                            <div class="col-7">
                                <span class="badge
                                {{ $promotion->status === 'active' ? 'bg-success' : ($promotion->status === 'expired' ? 'bg-secondary' : 'bg-danger') }}">
                                {{ $promotion->status === 'active' ? 'Hoạt động' : ($promotion->status === 'expired' ? 'Hết hạn' : 'Ngừng hoạt động') }}
                            </span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-5 text-warning fw-bold">Ngày tạo:</div>
                            <div class="col-7 text-light">{{ $promotion->created_at->format('d/m/Y') }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-5 text-warning fw-bold">Ngày bắt đầu:</div>
                            <div class="col-7 text-light">{{ $promotion->start_date->format('d/m/Y H:i') }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-5 text-warning fw-bold">Ngày kết thúc:</div>
                            <div class="col-7 text-light">{{ $promotion->end_date->format('d/m/Y H:i') }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-5 text-warning fw-bold">Loại giảm giá:</div>
                            <div class="col-7 text-light">{{ $promotion->discount_type === 'percentage' ? 'Phần trăm' : 'Cố định' }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-5 text-warning fw-bold">Giá trị khuyến mãi:</div>
                            <div class="col-7 text-light">{{ $promotion->discount_type === 'percentage' ? $promotion->discount_value . '%' : number_format($promotion->discount_value, 0, '.', '.') . 'đ' }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-5 text-warning fw-bold">Giới hạn sử dụng:</div>
                            <div class="col-7 text-light">{{ $promotion->usage_limit }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-5 text-warning fw-bold">Giá trị tối thiểu đơn hàng:</div>
                            <div class="col-7 text-light">{{ number_format($promotion->min_purchase, 0, '.', '.') }}đ</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div style="background: rgba(255,255,255,0.04); border-radius: 0 1rem 1rem 1rem; padding: 0.75rem 1rem; margin-top: 0.5rem;">
                <div class="d-flex align-items-center mb-2" style="font-size: 1rem; color: #ffc;">
                    <i class="fas fa-info-circle me-2" style="font-size: 1.1rem;"></i>
                    <span class="fw-bold">Lưu ý</span>
                </div>
                <ul class="mb-0 ps-3" style="list-style: disc; font-size: 0.93rem; color: #ffe;">
                    <li>Chỉ sửa được trạng thái, mô tả, thời gian kết thúc.</li>
                    <li>Không thể thay đổi mã khuyến mãi, loại giảm giá.</li>
                    <li>Thời gian bắt đầu không được nhỏ hơn hiện tại.</li>
                </ul>
            </div>
        </div>
    </div>
    @endif

    @if($tabCurrent === 'history')
    <!-- Lịch sử sử dụng khuyến mãi (gộp bảng đơn hàng) -->
    <div class="card bg-dark">
        <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h5 class="my-1">Lịch sử sử dụng khuyến mãi</h5>
        </div>
        <div class="card-body">
            @if($promotion->usages->isEmpty())
                <div class="text-center text-muted">
                    <i class="fas fa-inbox fa-3x mb-3"></i>
                    <p>Chưa có ai sử dụng khuyến mãi này.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-dark table-striped text-center align-middle">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Mã đơn hàng</th>
                                <th>Tên khách hàng</th>
                                <th>Email / SĐT</th>
                                <th>Tên món ăn</th>
                                <th>Ngày sử dụng</th>
                                <th>Số tiền đã giảm</th>
                                <th>Tổng tiền đơn hàng</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                           @foreach($promotion->usages as $index => $usage)
                                @php
                                    $booking = $usage->booking;
                                    $user = $booking?->user;
                                    $foodNames = $booking?->foodOrderItems->map(fn($item) => $item->variant?->foodItem?->name)->filter()->unique()->values();
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $booking?->booking_code ?? 'N/A' }}</td>
                                    <td>{{ $user?->name ?? 'N/A' }}</td>
                                    <td>
                                        @if($user)
                                            <span class="badge bg-success">{{ $user->email }}{{ $user->phone ? ' / ' . $user->phone : '' }}</span>
                                        @else
                                            <span class="badge bg-secondary">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-info">{{ Str::limit($foodNames->implode(', '), 30, '...') }}</span>
                                    </td>
                                    <td>{{ $usage->used_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ number_format($usage->discount_amount, 0, ',', '.') }}đ</td>
                                    <td>{{ number_format($booking?->total_price ?? 0, 0, ',', '.') }}đ</td>
                                    <td>
                                        <a href="#" class="btn btn-info btn-sm" title="Xem chi tiết"><i class="fas fa-eye"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
    @endif
</div>

