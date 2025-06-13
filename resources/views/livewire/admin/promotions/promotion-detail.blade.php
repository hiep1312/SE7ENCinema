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
                    <div class="card-body py-5 px-5" style="font-size: 1rem;">
                        <div class="row mb-2">
                            <div class="col-5 text-warning fw-bold">Tên khuyến mãi:</div>
                            <div class="col-7 text-light">{{ $promotion->title }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-5 text-warning fw-bold">Mã khuyến mãi:</div>
                            <div class="col-7 text-light"><span class="fw-bold badge bg-primary">{{ $promotion->code }}</span></div>
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
                            <div class="col-7 text-light"><span class="fw-bold badge bg-danger">{{ $promotion->discount_type === 'percentage' ? $promotion->discount_value . '%' : number_format($promotion->discount_value, 0, '.', '.') . 'đ' }}</span></div>
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
            <!-- VOUCHER CARD MỚI ĐẸP, HIỆN ĐẠI -->
            <div>
                <div class="voucher-card position-relative overflow-hidden p-4 shadow-lg border-0"
                     style="background: linear-gradient(135deg, #fffbe7 0%, #ffe0b2 100%); border-radius: 2rem; min-width: 340px; box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);">
                    <!-- Ribbon -->
                    <div class="position-absolute top-0 start-0 px-4 py-2 fw-bold text-white"
                         style="background: linear-gradient(90deg, #ff9800 60%, #ffb300 100%); border-bottom-right-radius: 1.5rem; font-size: 1.1rem; letter-spacing: 1px; box-shadow: 0 2px 8px #ff980055;">
                        <i class="fas fa-ticket-alt me-2 fa-lg animate__animated animate__tada"></i>VOUCHER
                    </div>
                    <!-- Discount -->
                    <div class="d-flex align-items-center mb-3 mt-4">
                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                             style="width: 56px; height: 56px; background: linear-gradient(135deg, #ff9800 60%, #ffb300 100%); box-shadow: 0 2px 8px #ff980055;">
                            <i class="fas fa-gift fa-2x text-white"></i>
                        </div>
                        <div>
                            <span class="display-6 fw-bold text-gradient-orange" style="background: linear-gradient(90deg, #ff9800, #ffb300); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">{{ $promotion->discount_type === 'percentage' ? $promotion->discount_value . '%' : number_format($promotion->discount_value, 0, '.', '.') . 'đ' }}</span>
                            <span class="text-secondary ms-2 fw-medium">giảm</span>
                        </div>
                    </div>
                    <!-- Code & Info -->
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-gradient-orange text-white px-4 py-2 fs-5 fw-bold me-2 shadow-sm"
                              style="background: linear-gradient(90deg, #ff9800, #ffb300); letter-spacing: 1px; border-radius: 1rem;">{{ $promotion->code }}</span>
                        <span class="text-secondary small">Đơn tối thiểu: <b class="text-dark">{{ number_format($promotion->min_purchase, 0, '.', '.') }}đ</b></span>
                    </div>
                    <div class="mb-2 text-secondary small">HSD: <b class="text-dark">{{ $promotion->end_date->format('d/m/Y') }}</b></div>
                    <div class="mb-2 text-secondary small">Mô tả: <span class="fw-medium text-dark">{{ $promotion->description }}</span></div>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="text-secondary small">Số lượng còn: <b class="text-gradient-orange" style="background: linear-gradient(90deg, #ff9800, #ffb300); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">{{ $promotion->usage_limit - $promotion->usages->count() }}</b></span>
                        <button class="btn btn-warning fw-bold px-4 py-2 rounded-pill shadow-sm border-0"
                                style="transition: 0.2s; background: linear-gradient(90deg, #ff9800 60%, #ffb300 100%); color: #fff; letter-spacing: 1px;"
                                onmouseover="this.style.background='linear-gradient(90deg,#ffb300 60%,#ff9800 100%)'"
                                onmouseout="this.style.background='linear-gradient(90deg,#ff9800 60%,#ffb300 100%)'">
                            <i class="fas fa-save me-2"></i>Lưu voucher
                        </button>
                    </div>
                    <!-- Decorative holes -->
                    <div class="position-absolute bg-white border rounded-circle" style="left: -12px; top: 18%; width: 24px; height: 24px; box-shadow: 0 2px 8px #ff980033;"></div>
                    <div class="position-absolute bg-white border rounded-circle" style="left: -12px; bottom: 18%; width: 24px; height: 24px; box-shadow: 0 2px 8px #ff980033;"></div>
                    <div class="position-absolute bg-white border rounded-circle" style="right: -12px; top: 18%; width: 24px; height: 24px; box-shadow: 0 2px 8px #ff980033;"></div>
                    <div class="position-absolute bg-white border rounded-circle" style="right: -12px; bottom: 18%; width: 24px; height: 24px; box-shadow: 0 2px 8px #ff980033;"></div>
                </div>
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
                                    <td class="fw-bold" >{{ $user?->name ?? 'N/A' }}</td>
                                    <td>
                                        @if($user)
                                            <span class="badge bg-success">{{ $user->email }}{{ $user->phone ? ' / ' . $user->phone : '' }}</span>
                                        @else
                                            <span class="badge bg-secondary">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-info fw-bold">{{ Str::limit($foodNames->implode(', '), 30, '...') }}</span>
                                    </td>
                                    <td>{{ $usage->used_at->format('d/m/Y H:i') }}</td>
                                    <td> <span class="badge bg-danger">{{ number_format($usage->discount_amount, 0, ',', '.') }}đ</span></td>
                                    <td><span class="badge bg-success">{{ number_format($booking?->total_price ?? 0, 0, ',', '.') }}đ</span></td>
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

