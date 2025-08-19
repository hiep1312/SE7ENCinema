@assets
<style>
    .tag {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.875rem;
        font-weight: 500;
        display: inline-block;
        margin: 0.125rem;
    }
    .apexcharts-menu {
            color: black;
        }
</style>
@endassets

<div class="scRender">
    <div class="container-lg mb-4" wire:poll.10s>
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Chi tiết suất chiếu: {{ $showtime->movie->title }}</h2>
            <div>
                @if($showtime->status !== "completed" && $showtime->start_time->isFuture())
                    <a href="{{ route('admin.showtimes.edit', $showtime->id) }}" class="btn btn-warning me-2">
                        <i class="fas fa-edit me-1"></i>Chỉnh sửa
                    </a>
                @endif
                <a href="{{ route('admin.showtimes.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Quay lại
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4 g-3">
            <div class="col-lg-6 col-md-6">
                <div class="card text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Tổng lượng vé đã bán</h6>
                                <h3 class="mb-0">{{ number_format($totalBookings) }}</h3>
                                <small>Vé</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-ticket-alt fa-2x opacity-75"></i>
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
                                <h6 class="card-title">Tổng doanh thu</h6>
                                <h3 class="mb-0">{{ number_format($totalRevenue) }}đ</h3>
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
                <button class="nav-link @if($tabCurrent === 'chart') active bg-light text-dark @else text-light @endif"
                    wire:click="$set('tabCurrent', 'chart')" style="border-top-right-radius: 0;">
                    <i class="fas fa-info-circle me-2"></i>Thông tin
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link @if($tabCurrent === 'overview') active bg-light text-dark @else text-light @endif"
                    wire:click="$set('tabCurrent', 'overview')" style="border-top-right-radius: 0;">
                    <i class="fa-solid fa-chart-pie-simple me-1"></i> Tổng quan
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link @if($tabCurrent === 'bookings') active bg-light text-dark @else text-light @endif"
                    wire:click="$set('tabCurrent', 'bookings')" style="border-top-left-radius: 0;">
                    <i class="fas fa-list me-1"></i> Danh sách đặt vé
                </button>
            </li>
            
        </ul>

        <!-- Tab Content -->
        <div class="tab-content mt-1">
            @if($tabCurrent === 'chart')
                <div class="row">
                    <div class="col-lg-6">
                        <div class="bg-dark rounded-3 p-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="text-white mb-0"><i class="fas fa-chart-pie me-2"></i>Tỉ lệ lấp đầy</h5>
                            </div>
                            <div><div id="stSeatFillRateChart" style="height: 320px;" wire:ignore></div></div>
                        </div>
                    </div>

                    <div class="col-lg-6 mt-4">
                        <div class="bg-dark rounded-3 p-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="text-white mb-0"><i class="fas fa-coins me-2"></i>Doanh thu: Vé vs Đồ ăn</h5>
                            </div>
                            <div><div id="stRevenueTicketFoodChart" style="height: 320px;" wire:ignore></div></div>
                        </div>
                    </div>
                </div>
                @elseif($tabCurrent === 'overview')
            <div class="row">
                <div class="col-lg-8">
                    <div class="card bg-dark border-light">
                        <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <h5><i class="fas fa-info-circle me-2"></i>Thông tin suất chiếu</h5>
                        </div>
                        <div class="card-body bg-dark" style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                            <table class="table table-borderless text-light">
                                <tr>
                                    <td><strong class="text-warning">Phim:</strong></td>
                                    <td><strong>{{ $showtime->movie->title }} ({{ $showtime->movie->format }})</strong></td>
                                </tr>
                                <tr>
                                    <td><strong class="text-warning">Thể loại:</strong></td>
                                    <td>
                                        <div class="d-flex flex-wrap justify-content-start gap-1">
                                            @forelse ($showtime->movie->genres as $genre)
                                            <span class="tag">{{ $genre->name }}</span>
                                            @empty
                                            <span class="text-muted">Không có thể loại</span>
                                            @endforelse
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong class="text-warning">Phòng chiếu:</strong></td>
                                    <td>{{ $showtime->room->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong class="text-warning">Sức chứa:</strong></td>
                                    <td>{{ $showtime->room->seats()->count() }} ghế</td>
                                </tr>
                                <tr>
                                    <td><strong class="text-warning">Ngày chiếu:</strong></td>
                                    <td><span style="color: #34c759;">{{ $showtime->start_time->format('d/m/Y') }}</span></td>
                                </tr>
                                <tr>
                                    <td><strong class="text-warning">Khung giờ chiếu:</strong></td>
                                    <td><span style="color: #34c759;">{{ $showtime->start_time->format('H:i') }} -  <span class="text-danger">{{ $showtime->end_time->format('H:i') }}</span></span></td>
                                </tr>
                                <tr>
                                    <td><strong class="text-warning">Ngày kết thúc:</strong></td>
                                    <td><span style="color: #ff4d4f;">{{ $showtime->end_time->format('d/m/Y') }}</span></td>
                                </tr>
                                <tr>
                                    <td><strong class="text-warning">Thời lượng:</strong></td>
                                    <td>{{ number_format($showtime->movie->duration, 0, '.', '.') }}p</td>
                                </tr>
                                <tr>
                                    <td><strong class="text-warning">Trạng thái:</strong></td>
                                    <td>
                                        @switch($showtime->status)
                                        @case('active')
                                        <span class="badge bg-primary"><i class="fas fa-play me-1"></i>Đang hoạt động</span>
                                        @break
                                        @case('completed')
                                        <span class="badge bg-success"><i class="fas fa-check me-1"></i>Đã hoàn thành</span>
                                        @break
                                        @case('canceled')
                                        <span class="badge bg-danger"><i class="fas fa-times me-1"></i>Đã hủy</span>
                                        @break
                                        @endswitch
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong class="text-warning">Ngày tạo:</strong></td>
                                    <td>{{ $showtime->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mt-4 mt-lg-0">
                    <div class="card bg-dark border-light">
                        <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <h5><i class="fas fa-image me-2"></i>Ảnh poster</h5>
                        </div>
                        <div class="card-body bg-dark" style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                            <div class="movie-poster w-100" style="aspect-ratio: 4 / 5; width: auto; height: auto; margin: 0;">
                                @if($showtime->movie->poster)
                                <img src="{{ asset('storage/' . $showtime->movie->poster) }}" alt="Ảnh poster phim {{ $showtime->movie->title }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 0;">
                                @else
                                <i class="fas fa-film" style="font-size: 22px;"></i>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @elseif($tabCurrent === 'bookings')
                <div class="row">
                    <div class="col-12">
                        <div class="card bg-dark border-light">
                            <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5><i class="fas fa-receipt me-2"></i>Chi tiết các đơn đặt vé của suất chiếu</h5>
                            </div>
                            <div class="card-body bg-dark" style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                                <div class="table-responsive">
                                    <table class="table table-dark table-striped table-hover text-light border">
                                        <thead>
                                            <tr>
                                                <th class="text-center text-light">Mã đơn hàng</th>
                                                <th class="text-center text-light">Tên khách hàng</th>
                                                <th class="text-center text-light">Email / SĐT</th>
                                                <th class="text-center text-light">Số ghế</th>
                                                <th class="text-center text-light">Vị trí ghế</th>
                                                <th class="text-center text-light">Tổng giá</th>
                                                <th class="text-center text-light">Phương thức TT</th>
                                                <th class="text-center text-light">Ngày đặt</th>
                                                <th class="text-center text-light">Trạng thái</th>
                                                <th class="text-center text-light">Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($bookings as $booking)
                                            <tr wire:key="booking-{{ $booking->id }}">
                                                <td class="text-center">{{ $booking->booking_code ?? 'N/A' }}</td>
                                                <td class="text-center">
                                                    <strong class="text-light">{{ $booking?->user->name ?? 'N/A' }}</strong>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge" style="background: linear-gradient(to right, #642b73, #c6426e) !important;">
                                                        {{ $booking?->user->email ?? 'N/A' }}
                                                        @if ($booking?->user->phone)
                                                        / {{ $booking->user->phone }}
                                                        @endif
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-primary">{{ $booking->bookingSeats->count() }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <small class="text-muted">
                                                        @foreach($booking->bookingSeats as $bookingSeat)
                                                            {{ $bookingSeat->seat->seat_row }}{{ $bookingSeat->seat->seat_number }}@if(!$loop->last), @endif
                                                        @endforeach
                                                    </small>
                                                </td>
                                                <td class="text-center text-warning fw-bold">
                                                    {{ number_format($booking->total_price, 0, ',', '.') }}đ
                                                </td>
                                                <td class="text-center">
                                                    @switch($booking->payment_method)
                                                    @case('credit_card')
                                                    <i class="fas fa-credit-card text-info me-1"></i>Thẻ tín dụng
                                                    @break
                                                    @case('bank_transfer')
                                                    <i class="fas fa-university text-primary me-1"></i>Chuyển khoản
                                                    @break
                                                    @case('e_wallet')
                                                    <i class="fas fa-wallet text-warning me-1"></i>Ví điện tử
                                                    @break
                                                    @case('cash')
                                                    <i class="fas fa-money-bill text-success me-1"></i>Tiền mặt
                                                    @break
                                                    @default
                                                    {{ $booking->payment_method }}
                                                    @endswitch
                                                </td>
                                                <td class="text-center text-muted">
                                                    {{ $booking->created_at->format('d/m/Y H:i') }}
                                                </td>
                                                <td class="text-center">
                                                    @switch($booking->status)
                                                    @case('pending')
                                                    <span class="badge bg-warning text-dark">Chờ xử lý</span>
                                                    @break
                                                    @case('confirmed')
                                                    <span class="badge bg-primary">Đã xác nhận</span>
                                                    @break
                                                    @case('paid')
                                                    <span class="badge bg-success">Đã thanh toán</span>
                                                    @break
                                                    @endswitch
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-center">
                                                        <a href="{{ route('admin.bookings.detail', $booking->id) }}"
                                                            class="btn btn-sm btn-info" title="Xem chi tiết">
                                                            <i class="fas fa-eye" style="margin-right: 0"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="10" class="text-center py-4">
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
                                    {{ $bookings->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@script
<script>
    {!! $seatFill->compileJavascript() !!}
    {!! $revenueTFChart->compileJavascript() !!}
</script>
@endscript