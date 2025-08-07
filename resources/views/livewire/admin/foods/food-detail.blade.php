<div class="scRender">
    <div class="container-lg mb-4" wire:poll>
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Chi tiết món ăn: {{ $foodItem->name }}</h2>
            <div>
                <a href="{{ route('admin.foods.edit', $foodItem->id) }}" class="btn btn-warning me-2">
                    <i class="fas fa-edit me-1"></i>Chỉnh sửa
                </a>
                <a href="{{ route('admin.foods.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Quay lại
                </a>
            </div>
        </div>

        <!-- Quick Stats Cards -->
        <div class="row mb-4 g-3">
            <div class="col-lg-3 col-md-6">
                <div class="card text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Tổng số biến thể</h6>
                                <h3 class="mb-0">
                                    @if ($isOriginal)
                                        0
                                    @else
                                        {{ number_format($foodItem->variants->count(), 0, '.', '.') }}
                                    @endif
                                </h3>
                                <small>
                                    @if ($isOriginal)
                                        Sản phẩm gốc
                                    @else
                                        Biến thể
                                    @endif
                                </small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-layer-group fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Số lượng còn lại</h6>
                                <h3 class="mb-0">
                                    {{ number_format($foodItem->variants?->sum('quantity_available') ?? 0, 0, '.', '.') }}
                                </h3>
                                <small>món ăn</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-box-open fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Giá trung bình</h6>
                                <h3 class="mb-0">
                                    {{ number_format($foodItem->variants?->avg('price') ?? 0, 0, '.', '.') }}đ
                                </h3>
                                <small>VNĐ</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-coins fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Số lượng đơn hàng</h6>
                                <h3 class="mb-0">{{ number_format($totalOrderItemsIn30Days, 0, '.', '.') }}</h3>
                                <small>đơn hàng</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-shopping-cart fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs bg-dark" role="tablist">
            <li class="nav-item">
                <button
                    class="nav-link @if ($tabCurrent === 'overview') active bg-light text-dark @else text-light @endif"
                    wire:click="$set('tabCurrent', 'overview')" style="border-top-right-radius: 0;">
                    <i class="fas fa-chart-pie me-1"></i>Tổng quan
                </button>
            </li>
            <li class="nav-item">
                <button
                    class="nav-link @if ($tabCurrent === 'variants') active bg-light text-dark @else text-light @endif"
                    wire:click="$set('tabCurrent', 'variants')"
                    style="border-top-left-radius: 0; border-top-right-radius: 0;">
                    <i class="fas fa-layer-group me-1"></i>Biến thể
                </button>
            </li>
            <li class="nav-item">
                <button
                    class="nav-link @if ($tabCurrent === 'orders') active bg-light text-dark @else text-light @endif"
                    wire:click="$set('tabCurrent', 'orders')" style="border-top-left-radius: 0;">
                    <i class="fas fa-shopping-cart me-1"></i>Đơn hàng
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content mt-1">
            <!-- Overview Tab -->
            @if ($tabCurrent === 'overview')
                <div class="row">
                    <div class="col-lg-8 col-xxl-9">
                        <div class="card bg-dark border-light">
                            <div class="card-header bg-gradient text-light"
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5><i class="fas fa-tags me-2"></i>Thông tin chi tiết</h5>
                            </div>
                            <div class="card-body bg-dark"
                                style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                                <table class="table table-borderless text-light">
                                    <tr>
                                        <td><strong class="text-warning">Tên món ăn:</strong></td>
                                        <td>{{ $foodItem->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong class="text-warning">Mô tả:</strong></td>
                                        <td>
                                            <p class="text-start text-wrap text-muted lh-base"
                                                style="margin-bottom: 0;">
                                                {{ $foodItem->description ?? 'Không có mô tả' }}</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong class="text-warning">Trạng thái:</strong></td>
                                        <td>
                                            @switch($foodItem->status)
                                                @case('activate')
                                                    <span class="badge bg-success">Đang bán</span>
                                                @break

                                                @case('discontinued')
                                                    <span class="badge bg-danger">Ngừng bán</span>
                                                @break

                                                @default
                                                    <span class="badge bg-secondary">Không xác định</span>
                                            @endswitch
                                        </td>
                                    </tr>

                                    @if ($isOriginal)
                                        <tr>
                                            <td><strong class="text-warning">Giá:</strong></td>
                                            <td>
                                                <span class="badge bg-gradient fs-6">
                                                    {{ number_format($price ?? 0, 0, ',', '.') }}đ
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong class="text-warning">Số lượng:</strong></td>
                                            <td>{{ number_format($quantity_available ?? 0, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong class="text-warning">Giới hạn nhập:</strong></td>
                                            <td>
                                                @if ($limit)
                                                    {{ number_format($limit, 0, ',', '.') }}
                                                @else
                                                    <span class="text-muted">Không giới hạn</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td><strong class="text-warning">Ngày tạo:</strong></td>
                                        <td>{{ $foodItem->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-xxl-3 mt-4 mt-md-0">
                        <div class="card bg-dark border-light">
                            <div class="card-header bg-gradient text-light"
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5><i class="fas fa-image me-2"></i>Ảnh món ăn</h5>
                            </div>
                            <div class="card-body bg-dark"
                                style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                                <div class="food-image w-100" style="height: auto;">
                                    @if ($foodItem->image)
                                        <img src="{{ asset('storage/' . $foodItem->image) }}"
                                            alt="Ảnh biến thể hiện tại"
                                            style="width: 100%; height: 100%; object-fit: cover; border-radius: 0;">
                                    @else
                                        <i class="fa-solid fa-burger-soda" style="font-size: 22px;"></i>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($tabCurrent === 'variants')
                <div class="row">
                    <div class="col-12">
                        <div class="card bg-dark border-light">
                            <div class="card-header bg-gradient text-light"
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5><i class="fas fa-layer-group me-2"></i>Thông tin biến thể chi tiết</h5>
                            </div>
                            <div class="card-body bg-dark"
                                style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                                <div class="table-responsive">
                                    @if ($foodItem->variants->count() === 0)
                                        <div class="text-center text-muted py-4">
                                            <i class="fas fa-box-open fa-3x mb-3"></i>
                                            <p><strong>Sản phẩm gốc</strong> - Không có biến thể</p>
                                        </div>
                                    @else
                                        <table class="table table-dark table-striped table-hover text-light border">
                                            <thead>
                                                <tr>
                                                    <th class="text-center text-light">Thuộc tính biến thể</th>
                                                    <th class="text-center text-light">Ảnh</th>
                                                    <th class="text-center text-light">Giá</th>
                                                    <th class="text-center text-light">Số lượng</th>
                                                    <th class="text-center text-light">Giới hạn nhập</th>
                                                    <th class="text-center text-light">Trạng thái</th>
                                                    <th class="text-center text-light">Hành động</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($foodItem->variants->filter(fn($v) => $v->attributeValues->count() > 0) as $variant)
                                                    <tr wire:key="{{ $variant->id }}">
                                                        <td class="text-center">
                                                            @forelse ($variant->attributeValues as $value)
                                                                <div class="mb-2">
                                                                    <strong>{{ $value->attribute->name }}:</strong>
                                                                    <span
                                                                        class="badge bg-secondary ms-1">{{ $value->value }}</span>
                                                                </div>
                                                            @empty
                                                                <span class="text-muted">Đây là sản phẩm gốc không có biến thể</span>
                                                            @endforelse
                                                        </td>

                                                        {{-- Cột ảnh --}}
                                                        <td class="d-flex justify-content-center">
                                                            <div class="mt-1 overflow-auto d-block"
                                                                style="max-height: 70px; width: 100px;">
                                                                <img src="{{ asset('storage/' . ($variant->image ?? '404.webp')) }}"
                                                                    alt="Ảnh biến thể" class="rounded"
                                                                    style="width: 100%; height: auto;">
                                                            </div>
                                                        </td>

                                                        {{-- Giá --}}
                                                        <td class="text-center">
                                                            {{ number_format($variant->price, 0, ',', '.') }}đ
                                                        </td>

                                                        {{-- Số lượng --}}
                                                        <td class="text-center">
                                                            {{ number_format($variant->quantity_available, 0, ',', '.') }}
                                                        </td>

                                                        {{-- Giới hạn nhập --}}
                                                        <td class="text-center">
                                                            @if ($variant->limit)
                                                                <span
                                                                    class="text-light">{{ number_format($variant->limit, 0, ',', '.') }}</span>
                                                            @else
                                                                <span class="text-muted">Không có giới hạn</span>
                                                            @endif
                                                        </td>

                                                        {{-- Trạng thái --}}
                                                        <td class="text-center">
                                                            @switch($variant->status)
                                                                @case('available')
                                                                    <span class="badge bg-success">Còn hàng</span>
                                                                @break

                                                                @case('hidden')
                                                                    <span class="badge bg-warning text-dark">Ẩn</span>
                                                                @break

                                                                @case('out_of_stock')
                                                                    <span class="badge bg-danger">Hết hàng</span>
                                                                @break
                                                            @endswitch
                                                        </td>

                                                        {{-- Hành động --}}
                                                        <td>
                                                            <div class="d-flex gap-2 justify-content-center">
                                                                <a href="{{ route('admin.food_variants.detail', $variant->id) }}"
                                                                    class="btn btn-sm btn-info" title="Xem chi tiết">
                                                                    <i class="fas fa-eye" style="margin-right: 0"></i>
                                                                </a>
                                                                <a href="{{ route('admin.food_variants.edit', $variant->id) }}"
                                                                    class="btn btn-sm btn-warning" title="Chỉnh sửa">
                                                                    <i class="fas fa-edit"
                                                                        style="margin-right: 0"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="7" class="text-center py-4">
                                                                <div class="text-muted">
                                                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                                                    <p>Không có biến thể nào</p>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($tabCurrent === 'orders')
                    <div class="row">
                        <div class="col-12">
                            <div class="card bg-dark border-light">
                                <div class="card-header bg-gradient text-light"
                                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                    <h5><i class="fas fa-receipt me-2"></i>Chi tiết các đơn hàng đã đặt</h5>
                                </div>
                                <div class="card-body bg-dark"
                                    style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                                    <div class="table-responsive">
                                        <table class="table table-dark table-striped table-hover text-light border">
                                            <thead>
                                                <tr>
                                                    <th class="text-center text-light">Mã đơn hàng</th>
                                                    <th class="text-center text-light">Tên món ăn</th>
                                                    <th class="text-center text-light">Số lượng</th>
                                                    <th class="text-center text-light">Tổng giá</th>
                                                    <th class="text-center text-light">Tên khách hàng</th>
                                                    <th class="text-center text-light">Email / SĐT</th>
                                                    <th class="text-center text-light">Trạng thái</th>
                                                    <th class="text-center text-light">Ngày mua</th>
                                                    <th class="text-center text-light">Hành động</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($foodOrderItems as $foodOrder)
                                                    <tr wire:key="{{ $foodOrder->id }}">
                                                        <td class="text-center">
                                                            {{ $foodOrder->booking?->booking_code ?? 'N/A' }}</td>
                                                        <td class="text-center">
                                                            <strong class="text-light">{{ $foodItem->name }}</strong>
                                                        </td>
                                                        <td class="text-center">
                                                            {{ number_format($foodOrder->quantity, 0, ',', '.') }}</td>
                                                        <td class="text-center text-warning">
                                                            {{ number_format($foodOrder->price, 0, ',', '.') }}đ</td>
                                                        <td class="text-center">
                                                            <strong class="text-light text-wrap d-block mb-2">{{ $foodOrder->booking?->user->name }}</strong>
                                                            @switch($foodOrder->booking?->user->status)
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
                                                            <span class="text-light">{{ $foodOrder->booking?->user->email ?? 'N/A' }}</span>
                                                            @if ($foodOrder->booking?->user->phone)
                                                                <small class="text-muted d-block mt-2" style="font-size: 12px">
                                                                    SĐT: {{ $foodOrder->booking->user->phone }}
                                                                </small>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            @switch($foodOrder->booking->status)
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
                                                                @switch($foodOrder->booking->payment_method)
                                                                    @case('credit_card') Thẻ tín dụng @break
                                                                    @case('bank_transfer') Chuyển khoản @break
                                                                    @case('e_wallet') Ví điện tử @break
                                                                    @case('cash') Tiền mặt @break
                                                                @endswitch
                                                            </small>
                                                        </td>
                                                        <td class="text-center">
                                                            {{ $foodOrder->created_at->format('d/m/Y H:i') }}đ</td>
                                                        <td>
                                                            <div class="d-flex justify-content-center">
                                                                <a href="{{ route('admin.bookings.detail', $foodOrder->booking->id) }}"
                                                                    class="btn btn-sm btn-info" title="Xem chi tiết">
                                                                    <i class="fas fa-eye" style="margin-right: 0"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="9" class="text-center py-4">
                                                            <div class="text-muted">
                                                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                                                <p>Không có đơn hàng nào đã đặt</p>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mt-3">
                                        {{ $foodOrderItems->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
