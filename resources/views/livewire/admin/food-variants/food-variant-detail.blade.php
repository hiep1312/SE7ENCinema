<div>
    <div class="container-lg mb-4" wire:poll="realTimeVariantUpdate">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Chi tiết Biến thể: {{ $variantItem->name }}</h2>
            <div>
                <a href="{{ route('admin.food_variants.edit',$variantItem->id) }}" class="btn btn-warning me-2">
                    <i class="fas fa-edit me-1"></i>Chỉnh sửa
                </a>
                <a href="{{ route('admin.food_variants.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Quay lại
                </a>
            </div>
        </div>

        <!-- Quick Stats Cards -->
        <div class="row mb-4 g-3">
            <div class="col-md-6">
                <div class="card text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Số lượng đơn hàng (30 ngày)</h6>
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
            <div class="col-lg-6 col-md-6">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Doanh thu món ăn (30 ngày)</h6>
                                <h3 class="mb-0">{{ number_format($totalPriceIn30Days ?? 0, 0, '.', '.') }}đ</h3>
                                <small>VNĐ</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fa-solid fa-money-bill-wave fa-2x opacity-75"></i>
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
                    wire:click="$set('tabCurrent', 'overview')">
                    <i class="fas fa-chart-pie me-1"></i>Tổng quan
                </button>
            </li>
            <li class="nav-item">
                <button
                    class="nav-link @if ($tabCurrent === 'variants') active bg-light text-dark @else text-light @endif"
                    wire:click="$set('tabCurrent', 'variants')"
                    style="border-top-left-radius: 0; border-top-right-radius: 0;">
                    <i class="fas fa-layer-group me-1"></i> Biến thể cùng loại
                </button>
            </li>
            <li class="nav-item">
                <button
                    class="nav-link @if ($tabCurrent === 'orders') active bg-light text-dark @else text-light @endif"
                    wire:click="$set('tabCurrent', 'orders')">
                    <i class="fas fa-shopping-cart me-1"></i> Đơn hàng
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content mt-3">
            <!-- Overview Tab -->
            @if ($tabCurrent === 'overview')
                <div class="row">
                    <div class="col-md-6">
                        <div class="card bg-dark border-light">
                            <div class="card-header bg-gradient text-light"
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5><i class="fas fa-tags me-2"></i>Thông tin chi tiết</h5>
                            </div>
                            <div class="card-body bg-dark"
                                style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                                <table class="table table-borderless text-light">
                                    <tr>
                                        <td><strong class="text-warning">Tên món ăn liên kết:</strong></td>
                                        <td><strong class="badge bg-gradient text-light" style="background: linear-gradient(to right, #642b73, #c642ab) !important; font-size: 0.875rem !important;">
                                            {{ $variantItem->FoodItem->name ?? 'N/A' }}
                                        </strong><br>
                                    </tr>
                                    <tr>
                                        <td><strong class="text-warning">Tên biến thể:</strong></td>
                                        <td>{{ $variantItem->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong class="text-warning">Giá</strong></td>
                                        <td><span class="text-warning">{{ number_format($variantItem->price, 0, ',', '.') }}đ</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong class="text-warning">Số lượng còn lại</strong></td>
                                        <td>{{ number_format($variantItem->quantity_available, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong class="text-warning">Giới hạn số lượng nhập</strong></td>
                                        <td>
                                            @if($variantItem->limit > 0)
                                                <span>{{ number_format($variantItem->limit, 0, ',', '.') }}</span>
                                            @else
                                                <span class="text-muted">Không có giới hạn</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong class="text-warning">Trạng thái:</strong></td>
                                        <td>
                                            @switch($variantItem->status)
                                                @case('available')
                                                    <span class="badge bg-success">Còn hàng</span>
                                                @break

                                                @case('out_of_stock')
                                                    <span class="badge bg-danger">Hết hàng</span>
                                                @break

                                                @case('hidden')
                                                    <span class="badge bg-danger">Ẩn</span>
                                                @break
                                            @endswitch
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong class="text-warning">Ngày tạo:</strong></td>
                                        <td>{{ $variantItem->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mt-4 mt-md-0">
                        <div class="card bg-dark border-light">
                            <div class="card-header bg-gradient text-light"
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5><i class="fas fa-image me-2"></i>Ảnh món ăn</h5>
                            </div>
                            <div class="card-body bg-dark"
                                style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                                <div class="overflow-auto position-relative" style="max-height: 400px;">
                                    <img src="{{ asset('storage/' . ($variantItem->image ?? '404.webp')) }}"
                                        alt="Ảnh biến thể hiện tại" class="img-thumbnail" style="width: 100%;">
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
                                <h5><i class="fas fa-layer-group me-2"></i>Danh sách biến thể cùng loại</h5>
                            </div>
                            <div class="card-body bg-dark"
                                style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                                <div class="table-responsive">
                                    <table class="table table-dark table-striped table-hover text-light border">
                                        <thead>
                                            <tr>
                                                <th class="text-center text-light">Tên biến thể</th>
                                                <th class="text-center text-light">Ảnh</th>
                                                <th class="text-center text-light">Giá</th>
                                                <th class="text-center text-light">Số lượng</th>
                                                <th class="text-center text-light">Giới hạn nhập</th>
                                                <th class="text-center text-light">Trạng thái</th>
                                                <th class="text-center text-light">Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($relatedVariants as $variant)
                                                <tr wire:key="{{ $variant->id }}">
                                                    <td class="text-center"><strong
                                                            class="text-light">{{ $variant->name }}</strong></td>
                                                    <td class="d-flex justify-content-center">
                                                        <div class="mt-1 overflow-auto d-block"
                                                            style="max-height: 70px; width: 100px;">
                                                            <img src="{{ asset('storage/' . ($variant->image ?? '404.webp')) }}"
                                                                alt="Ảnh biến thể" class="rounded"
                                                                style="width: 100%; height: auto;">
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        {{ number_format($variant->price, 0, ',', '.') }}đ</td>
                                                    <td class="text-center">
                                                        {{ number_format($variant->quantity_available, 0, ',', '.') }}
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($variant->limit)
                                                            <span
                                                                class="text-light">{{ number_format($variant->limit, 0, ',', '.') }}</span>
                                                        @else
                                                            <span class="text-muted">Không có giới hạn</span>
                                                        @endif
                                                    </td>
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
                                                    <td>
                                                        <div class="d-flex gap-2 justify-content-center">
                                                            <a href="{{ route('admin.food_variants.detail', $variant->id) }}"
                                                                class="btn btn-sm btn-info" title="Xem chi tiết">
                                                                <i class="fas fa-eye" style="margin-right: 0"></i>
                                                            </a>
                                                            <a href="{{ route('admin.food_variants.edit', $variant->id) }}"
                                                                class="btn btn-sm btn-warning" title="Chỉnh sửa">
                                                                <i class="fas fa-edit" style="margin-right: 0"></i>
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($tabCurrent === 'orders')
                    <div class="row ml-3 mr-3">
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
                                                    <th class="text-center text-light">Tên món ăn / Biến thể</th>
                                                    <th class="text-center text-light">Số lượng</th>
                                                    <th class="text-center text-light">Tổng giá</th>
                                                    <th class="text-center text-light">Tên khách hàng</th>
                                                    <th class="text-center text-light">Email/SĐT</th>
                                                    <th class="text-center text-light">Địa chỉ</th>
                                                    <th class="text-center text-light">Ngày mua</th>
                                                    <th class="text-center text-light">Hành động</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($foodOrderItems as $foodOrder)
                                                    <tr wire:key="{{ $foodOrder->id }}">
                                                        <td class="text-center">
                                                            {{ $foodOrder->booking?->booking_code ?? 'N/A' }}</td>
                                                        <td class="text-center"><strong class="badge bg-gradient text-light" style="background: linear-gradient(to right, #642b73, #c6426e) !important;">
                                                            {{ $foodOrder->variant->FoodItem->name }} / {{ $foodOrder->variant->name }}
                                                        </strong>
                                                        </td>
                                                        <td class="text-center">
                                                            {{ number_format($foodOrder->quantity, 0, ',', '.') }}</td>
                                                        <td class="text-center">
                                                            {{ number_format($foodOrder->price, 0, ',', '.') }}đ</td>
                                                        <td class="text-center"><strong
                                                                class="text-light">{{ $foodOrder->booking?->user->name ?? 'N/A' }}</strong>
                                                        </td>
                                                        <td class="text-center">
                                                            <span
                                                                class="badge bg-success">{{ $foodOrder->booking?->user->email ?? 'N/A' }}
                                                                @if ($foodOrder->booking?->user->phone)
                                                                    / {{ $foodOrder->booking->user->phone }}
                                                                @endif
                                                            </span>
                                                        </td>
                                                        <td class="text-center"><span
                                                                class="text-light text-wrap">{{ Str::limit($foodOrder->booking?->user->address ?? 'N/A', 20, '...') }}</span>
                                                        </td>
                                                        <td class="text-center">
                                                            {{ $foodOrder->created_at->format('d/m/Y H:i') }}đ</td>
                                                        <td>
                                                            <div class="d-flex justify-content-center">
                                                                <a href="{{ /* route('admin.bookings.detail', $foodOrder->booking->id) */ '#' }}"
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
