<div class="scRender">
    <div class="container-lg mb-4" wire:poll>
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Chi tiết mã giảm giá: {{ $promotion->code }}</h2>
            <div>
                <a href="{{ route('admin.promotions.edit', $promotion->id) }}"
                   class="btn btn-warning me-2">
                    <i class="fas fa-edit me-1"></i>Chỉnh sửa
                </a>
                <a href="{{ route('admin.promotions.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Quay lại
                </a>
            </div>
        </div>

        @if($promotion->usages()->exists() && $promotion->status !== "expired")
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Mã giảm giá đã có người sử dụng và chưa hết hạn. Một số chức năng sẽ bị hạn chế.
            </div>
        @endif

        <div class="row mb-4 g-3">
            <div class="col-md-6">
                <div class="card text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Số người đã sử dụng</h6>
                                <h3 class="mb-0">{{ $promotion->usages->count() }}</h3>
                                <small>Người</small>
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
                                <h6 class="card-title">Tổng số tiền đã giảm</h6>
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

        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs bg-dark" role="tablist">
            <li class="nav-item">
                <button class="nav-link @if($tabCurrent === 'overview') active bg-light text-dark @else text-light @endif"
                        wire:click="$set('tabCurrent', 'overview')"
                        style="border-top-right-radius: 0;">
                    <i class="fas fa-info-circle me-1"></i>Tổng quan
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link @if($tabCurrent === 'history') active bg-light text-dark @else text-light @endif"
                        wire:click="$set('tabCurrent', 'history')"
                        style="border-top-left-radius: 0;">
                    <i class="fas fa-history me-1"></i>Lịch sử sử dụng
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content mt-1">
            <!-- Overview Tab -->
            @if($tabCurrent === 'overview')
                <div class="row">
                    <div class="col-lg-6 col-xl-8">
                        <div class="card bg-dark border-light">
                            <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5><i class="fas fa-info me-2"></i>Thông tin chi tiết</h5>
                            </div>
                            <div class="card-body bg-dark" style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                                <div class="table-responsive">
                                    <table class="table table-borderless text-light">
                                        <tr>
                                            <td><strong class="text-warning">Mã giảm giá:</strong></td>
                                            <td><strong>{{ $promotion->code }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td><strong class="text-warning">Tiêu đề:</strong></td>
                                            <td>{{ $promotion->title }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong class="text-warning">Mô tả:</strong></td>
                                            <td class="text-wrap text-muted lh-base text-start">{{ $promotion->description ?? 'Không có mô tả' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong class="text-warning">Giảm giá:</strong></td>
                                            <td>{{ number_format($promotion->discount_value, 0, '.', '.') . ($promotion->discount_type === 'percentage' ? '%' : 'đ') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong class="text-warning">Thời gian bắt đầu:</strong></td>
                                            <td>
                                                <span style="color: #34c759;"><i class="fas fa-play me-1"></i>{{ $promotion->start_date->format('Y-m-d H:i') }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong class="text-warning">Thời gian kết thúc:</strong></td>
                                            <td>
                                                <span style="color: #ff4d4f;"><i class="fas fa-stop me-1"></i>{{ $promotion->end_date->format('Y-m-d H:i') }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong class="text-warning">Giới hạn sử dụng:</strong></td>
                                            <td>{{ $promotion->usage_limit ?? "Vô hạn" }}
                                                @if($promotion->usage_limit) <span class="text-muted">(còn {{ $promotion->usage_limit - $promotion->usages->count() }} lượt)</span> @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong class="text-warning">Giá trị đơn hàng tối thiểu:</strong></td>
                                            <td>
                                                <span class="text-muted">{{ number_format($promotion->min_purchase, 0, '.', '.') ?? 0 }}đ</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong class="text-warning">Trạng thái:</strong></td>
                                            <td>
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
                                        </tr>
                                        <tr>
                                            <td><strong class="text-warning">Ngày tạo:</strong></td>
                                            <td>{{ $promotion->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xl-4 mt-4 mt-lg-0">
                        <div class="voucher-card-modern w-100 position-relative">
                            <div class="voucher-ribbon position-absolute top-0 start-0 px-3 py-1 fw-bold text-light">
                                <i class="fas fa-ticket-alt me-2 fa-lg"></i>VOUCHER
                            </div>
                            <div class="voucher-hole rounded-circle" style="left: -8px; top: 18%;"></div>
                            <div class="voucher-hole rounded-circle" style="left: -8px; bottom: 18%;"></div>
                            <div class="voucher-hole rounded-circle" style="right: -8px; top: 18%;"></div>
                            <div class="voucher-hole rounded-circle" style="right: -8px; bottom: 18%;"></div>
                            <div class="d-flex align-items-center mb-2 mt-3">
                                <div class="voucher-icon rounded-circle d-flex align-items-center justify-content-center me-2">
                                    <i class="fas fa-gift text-white"></i>
                                </div>
                                <span class="fw-bold text-gradient-orange">Giảm {{ number_format($promotion->discount_value, 0, '.', '.') . ($promotion->discount_type === 'percentage' ? '%' : 'đ') }}</span>
                            </div>
                            <div class="my-2">
                                <span class="voucher-code-modern text-light text-center w-100 px-3 py-1 fw-bold">{{ $promotion->code }}</span>
                            </div>
                            <div class="d-flex flex-column gap-1">
                                <small class="text-secondary">
                                    Tiêu đề: <b class="text-dark">{{ $promotion->title }}</b></b>
                                </small>
                                <small class="text-secondary">
                                    Đơn tối thiểu: <b class="text-dark">{{ number_format($promotion->min_purchase, 0, '.', '.') ?? 0 }}đ</b>
                                </small>
                                <small class="text-secondary">
                                    HSD: <b class="text-dark">{{ $promotion->end_date->format('d/m/Y H:i') }}</b>
                                </small>
                                <small class="text-secondary">
                                    Số lượng còn lại: <b class="text-dark">{{ isset($promotion->usage_limit) ? $promotion->usage_limit - $promotion->usages->count() : 'Không giới hạn' }}</b>
                                </small>
                            </div>
                            <div class="voucher-actions">
                                <button class="voucher-fake-btn detail"><i class="fas fa-eye" style="margin-right: 2px;"></i> Xem chi tiết</button>
                                <button class="voucher-fake-btn"><i class="fas fa-save" style="margin-right: 2px;"></i> Lưu voucher</button>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($tabCurrent === 'history')
                <div class="row">
                    <div class="col-12">
                        <div class="card bg-dark border-light">
                            <div class="card-header bg-gradient text-light"
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5><i class="fas fa-receipt me-2"></i>Lịch sử sử dụng mã giảm giá</h5>
                            </div>
                            <div class="card-body bg-dark"
                                style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                                <div class="table-responsive">
                                    <table class="table table-dark table-striped table-hover text-light border">
                                        <thead>
                                            <tr>
                                                <th class="text-center text-light">Mã đơn hàng</th>
                                                <th class="text-center text-light">Avatar</th>
                                                <th class="text-center text-light">Tên người dùng</th>
                                                <th class="text-center text-light">Email / SĐT</th>
                                                <th class="text-center text-light">Món ăn đặt kèm</th>
                                                <th class="text-center text-light">Tổng tiền</th>
                                                <th class="text-center text-light">Trạng thái</th>
                                                <th class="text-center text-light">Ngày sử dụng</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($usages as $usage)
                                                @php
                                                    $booking = $usage->booking;
                                                    $user = $booking->user;
                                                @endphp
                                                <tr wire:key="usage-{{ $usage->id }}">
                                                    <td class="text-center"><strong>{{ $booking->booking_code ?? 'N/A' }}</strong></td>
                                                    <td>
                                                        <div class="user-avatar-clean" style="width: 55px; aspect-ratio: 1; height: auto; margin: 0 auto; border-radius: 50%;">
                                                            @if($user->avatar)
                                                                <img src="{{ asset('storage/' . $user->avatar) }}" alt style="width: 100%; height: 100%; object-fit: cover; border-radius: inherit;">
                                                            @else
                                                                <i class="fas fa-user icon-white" style="font-size: 20px;"></i>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <strong class="text-light text-wrap d-block mb-2">{{ $user->name }}</strong>
                                                        @switch($user->status)
                                                            @case('active')
                                                                <span class="badge bg-success"><i class="fas fa-play me-1"></i>Đang hoạt động</span>
                                                                @break
                                                            @case('inactive')
                                                                <span class="badge bg-warning text-dark"><i class="fa-solid fa-user-slash me-1"></i>Không hoạt động</span>
                                                                @break
                                                            @case('banned')
                                                                <span class="badge bg-danger"><i class="fa-solid fa-ban me-1"></i>Bị cấm</span>
                                                                @break
                                                        @endswitch
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="text-center text-light">{{ $user->email }}
                                                            @if ($user->phone)
                                                                / {{ $user->phone }}
                                                            @endif
                                                        </span>
                                                    </td>
                                                    <td class="text-center" style="max-width: 200px;">
                                                        @if($booking->foodOrderItems->isNotEmpty())
                                                            <span class="text-light text-wrap lh-base">{{ Str::limit($booking->foodOrderItems->pluck('variant.foodItem.name')->implode(', '), 50, '...') }}</span>
                                                        @else
                                                            <span class="text-muted">Không có món ăn đặt kèm</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-gradient fs-6">
                                                            {{ number_format($booking->total_price, 0, '.', '.') }}đ
                                                        </span>
                                                        <small class="text-danger fw-bold d-block mt-1 ms-1">- {{ number_format($usage->discount_amount, 0, '.', '.') }}đ KM</small>
                                                    </td>
                                                    <td class="text-center">
                                                        @switch($booking->status)
                                                            @case('pending')
                                                                <span class="badge bg-primary">Đang chờ xử lý</span>
                                                                @break
                                                            @case('expired')
                                                                <span class="badge bg-warning text-dark">Đã hết hạn xử lý</span>
                                                                @break
                                                            @case('paid')
                                                                <span class="badge bg-success">Đã thanh toán</span>
                                                                @break
                                                            @case('failed')
                                                                <span class="badge bg-danger">Lỗi thanh toán</span>
                                                                @break
                                                        @endswitch
                                                        <small class="text-muted d-block mt-1" style="font-size: 12px">
                                                            PTTT:
                                                            @switch($booking->payment_method)
                                                                @case('credit_card') Thẻ tín dụng @break
                                                                @case('bank_transfer') Chuyển khoản @break
                                                                @case('e_wallet') Ví điện tử @break
                                                                @case('cash') Tiền mặt @break
                                                            @endswitch
                                                        </small>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="text-light">{{ $usage->used_at->format('d/m/Y H:i') }}</span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="text-center py-4">
                                                        <div class="text-muted">
                                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                                            <p>Chưa có người nào sử dụng mã giảm giá</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-3">
                                    {{ $usages->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
