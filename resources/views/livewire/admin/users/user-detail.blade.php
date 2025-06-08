<div>
    <div class="container-lg mb-4" wire:poll.1s="realTimeUserUpdate">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Chi tiết người dùng: {{ $user->name }}</h2>
            <div>
                <a href="{{ route('admin.users.edit', $user->id) }}"
                   class="btn btn-warning me-2">
                    <i class="fas fa-edit me-1"></i>Chỉnh sửa
                </a>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Quay lại
                </a>
            </div>
        </div>

        @if($checkUserAboutToDeleted)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-3 mb-1 mb-md-auto">
                                    <div class="text-center">
                                        <i class="fas fa-tools fa-3x mb-2"></i>
                                        <h5>Tài khoản sắp bị xóa</h5>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-1 mb-md-auto">
                                    <div class="text-center">
                                        <h3 class="mb-2">NGỪNG HOẠT ĐỘNG QUÁ LÂU</h3>
                                        <div class="row text-center">
                                            <div class="col-3">
                                                <div class="maintenance-countdown">
                                                    <h2 class="mb-0 fw-bold" id="overdue-days">{{ number_format(floor($totalDowntimeSeconds / 86400), 0, '.', '.') }}</h2>
                                                    <small>Ngày</small>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="maintenance-countdown">
                                                    <h2 class="mb-0 fw-bold" id="overdue-hours">{{ number_format(floor(($totalDowntimeSeconds % 86400) / 3600), 0) }}</h2>
                                                    <small>Giờ</small>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="maintenance-countdown">
                                                    <h2 class="mb-0 fw-bold" id="overdue-minutes">{{ number_format(floor(($totalDowntimeSeconds % 3600) / 60), 0) }}</h2>
                                                    <small>Phút</small>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="maintenance-countdown">
                                                    <h2 class="mb-0 fw-bold" id="overdue-seconds">{{ number_format($totalDowntimeSeconds % 60, 0) }}</h2>
                                                    <small>Giây</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <h6>Ngày ngừng hoạt động</h6>
                                        <h5 class="mb-1">{{ $user->updated_at->format('d/m/Y') }}</h5>
                                        <div class="mt-2">
                                            <small class="text-light">
                                                Tổng thời gian: {{ number_format($this->user->updated_at->diffInDays(now(), true), 0, '.', '.') }} ngày
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Quick Stats Cards -->
        <div class="row mb-4 g-3">
            <div class="col-md-6">
                <div class="card text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Tổng số phim đã xem (30 ngày)</h6>
                                <h3 class="mb-0">{{ number_format($totalMoviesWatchedIn30Days, 0, '.', '.') }}</h3>
                                <small>phim</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fa-solid fa-ticket fa-2x opacity-75"></i>
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
                                <h6 class="card-title">Tổng số tiền đã bỏ (30 ngày)</h6>
                                <h3 class="mb-0">{{ number_format($user->bookings->where('created_at', '>=', now()->subDays(30)->format('Y-m-d 00:00:00'))->where('status', 'paid')->sum('total_price'), 0, '.', '.') }}đ</h3>
                                <small>VNĐ</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fa-solid fa-money-bill fa-2x opacity-75"></i>
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
                        wire:click="$set('tabCurrent', 'overview')">
                    <i class="fas fa-info-circle me-1"></i> Tổng quan
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link @if($tabCurrent === 'orders') active bg-light text-dark @else text-light @endif"
                        wire:click="$set('tabCurrent', 'orders')"
                        style="border-top-left-radius: 0; border-top-right-radius: 0;">
                    <i class="fas fa-receipt me-2"></i> Đơn hàng
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link @if($tabCurrent === 'ratingAndComment') active bg-light text-dark @else text-light @endif"
                        wire:click="$set('tabCurrent', 'ratingAndComment')">
                    <i class="fa-regular fa-comment me-1"></i> Đánh giá và bình luận
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content mt-3">
            <!-- Overview Tab -->
            @if($tabCurrent === 'overview')
                <div class="row">
                    <div class="col-md-6 col-xl-8">
                        <div class="card bg-dark border-light">
                            <div class="card-header bg-gradient text-light"
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5><i class="fas fa-info me-2"></i>Thông tin chi tiết</h5>
                            </div>
                            <div class="card-body bg-dark"
                                style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                                <table class="table table-borderless text-light">
                                    <tr>
                                        <td><strong class="text-warning">Tên người dùng:</strong></td>
                                        <td>{{ $user->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong class="text-warning">Email:</strong></td>
                                        <td>{{ $user->email }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong class="text-warning">Số điện thoại:</strong></td>
                                        <td>{{ $user->phone ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong class="text-warning">Địa chỉ:</strong></td>
                                        <td class="text-wrap lh-base">{{ $user->address ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong class="text-warning">Ngày sinh:</strong></td>
                                        <td>{{ $user->birthday?->format('d/m/Y') ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong class="text-warning">Giới tính:</strong></td>
                                        <td>{{ $user->gender === "man" ? "Nam" : ($user->gender === "woman" ? "Nữ" : "Khác") }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong class="text-warning">Vai trò:</strong></td>
                                        <td>
                                            @switch($user->role)
                                                @case('user')
                                                    <span class="badge bg-info text-dark">Người dùng</span>
                                                @break
                                                @case('staff')
                                                    <span class="badge bg-primary">Nhân viên</span>
                                                @break
                                                @case('admin')
                                                    <span class="badge bg-success">Quản trị viên</span>
                                                    @break
                                            @endswitch
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong class="text-warning">Trạng thái:</strong></td>
                                        <td>
                                            @switch($user->status)
                                                @case('active')
                                                    <span class="badge bg-success">Đang hoạt động</span>
                                                @break
                                                @case('inactive')
                                                    <span class="badge bg-warning text-dark">Ngừng hoạt động</span>
                                                @break
                                                @case('banned')
                                                    <span class="badge bg-danger">Bị cấm</span>
                                                    @break
                                            @endswitch
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong class="text-warning">Ngày tạo:</strong></td>
                                        <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-4 mt-4 mt-md-0">
                        <div class="card bg-dark border-light">
                            <div class="card-header bg-gradient text-light"
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5><i class="fas fa-image me-2"></i>Ảnh đại diện</h5>
                            </div>
                            <div class="card-body bg-dark"
                                style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                                <div class="overflow-auto position-relative text-center" style="max-height: 400px;">
                                    <img src="{{ asset('storage/' . ($user->avatar ?? '404.webp')) }}"
                                        alt="Ảnh người dùng hiện tại" style="width: 230px; height: 230px; border-radius: 50%; object-fit: cover; border: 3px solid white; box-shadow: 0 3px 10px rgba(0,0,0,0.1);">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <!-- Orders Tab -->
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
                                                    <th class="text-center text-light">Phòng / Tên phim</th>
                                                    <th class="text-center text-light">Thời lượng</th>
                                                    <th class="text-center text-light">Thời gian chiếu</th>
                                                    <th class="text-center text-light">Tên món ăn</th>
                                                    <th class="text-center text-light">Số lượng</th>
                                                    <th class="text-center text-light">Tổng giá</th>
                                                    <th class="text-center text-light">Trạng thái</th>
                                                    <th class="text-center text-light">Hành động</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($bookings as $booking)
                                                    <tr wire:key="{{ $booking->id }}">
                                                        <td class="text-center">{{ $booking->booking_code ?? 'N/A' }}</td>
                                                        <td class="text-center">
                                                            <strong class="badge bg-gradient text-light" style="background: linear-gradient(to right, #642b73, #c6426e) !important;">
                                                                {{ $booking->showtime->room->name }} / {{ $booking->showtime->movie->title }}
                                                            </strong>
                                                        </td>
                                                        <td class="text-center">
                                                            {{ $booking->showtime->movie->duration }}</td>
                                                        <td class="text-center">
                                                            {{ $booking->showtime->start_time->format('d/m/Y H:i') }} - {{ $booking->showtime->end_time->format('d/m/Y H:i') }}</td>
                                                        <td class="text-center"><strong
                                                                class="text-light">{{ Str::limit($booking->foodOrderItems()->with('variant')->get()->pluck('variant.name')->implode(', '), 20, '...') }}</strong>
                                                        </td>
                                                        <td class="text-center">
                                                            {{ $booking->foodOrderItems->sum('quantity') }}</td>
                                                        <td class="text-center">
                                                            {{ number_format($booking->total_price, 0, ',', '.') }}đ</td>
                                                        <td class="text-center">
                                                             @switch($booking->status)
                                                                @case('pending')
                                                                    <span class="badge bg-info text-dark">Chờ xử lý</span>
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
                                                                <a href="{{ /* route('admin.bookings.detail', $variant->id) */ '#' }}"
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
                                        {{ $bookings->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            <!-- Seats Tab -->
            @elseif($tabCurrent === 'ratingAndComment')
                <div class="row">
                    <div class="col-md-6">
                        <div class="card bg-dark border-light">
                            <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5><i class="fa-solid fa-comment-nodes me-2"></i>Đánh giá gần đây</h5>
                            </div>
                            <div class="card-body bg-dark" style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                                @if($ratings->count() > 0)
                                    <div class="list-group">
                                        @foreach($ratings as $rating)
                                            <div class="list-group-item bg-dark text-light border-warning">
                                                <div class="d-flex justify-content-between align-items-center gap-3">
                                                    <div>
                                                        <h6 class="mb-2 fw-bold text-capitalize"><i class="fas fa-film me-2"></i>{{ $rating->movie?->title ?? 'N/A' }}</h6>
                                                        <p class="my-1" style="background: rgba(255, 255, 255, 0.05); border-radius: 8px; padding: 12px; border-left: 3px solid #ffc107; font-style: italic; line-height: 1.5; color: #e8e9ea;">
                                                            {{ $rating->review ?? 'N/A' }}
                                                        </p>
                                                        <small class="text-warning">
                                                            {{ $rating->created_at->format('d/m/Y H:i') }}
                                                        </small>
                                                    </div>
                                                    <span class="badge bg-{{ $rating->score > 3 ? 'success' : ($rating->score === 3 ? 'primary' : 'danger') }} d-flex gap-1 justify-content-center align-items-center">
                                                        {!! Str::repeat('<i class="fas fa-star"></i>', $rating->score) !!}
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="mt-3">
                                        {{ $ratings->links() }}
                                    </div>
                                @else
                                    <p class="text-light">Chưa có đánh giá nào.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mt-4 mt-md-0">
                        <div class="card bg-dark border-light">
                            <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5><i class="fa-solid fa-comments me-2"></i>Bình luận gần đây</h5>
                            </div>
                            <div class="card-body bg-dark" style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                                @if($comments->count() > 0)
                                    <div class="list-group">
                                        @foreach($comments as $comment)
                                            <div class="list-group-item bg-dark text-light border-warning">
                                                <div class="d-flex justify-content-between align-items-center gap-3">
                                                    <div>
                                                        <h6 class="mb-2 fw-bold text-capitalize"><i class="fas fa-film me-2"></i>{{ $comment->movie?->title ?? 'N/A' }}</h6>
                                                        <p class="my-1" style="background: rgba(255, 255, 255, 0.05); border-radius: 8px; padding: 12px; border-left: 3px solid #ffc107; font-style: italic; line-height: 1.5; color: #e8e9ea;">
                                                            {{ Str::limit($comment->content ?? 'N/A', 150) }}
                                                        </p>
                                                        <small class="text-warning">
                                                            {{ $comment->created_at->format('d/m/Y H:i') }}
                                                        </small>
                                                    </div>
                                                    <span class="badge bg-{{ $comment->status === 'active' ? 'success' : ($comment->status === 'hidden' ? 'primary' : ($comment->status === 'reported' ? 'warning' : 'danger')) }} d-flex gap-1 justify-content-center align-items-center">
                                                        {{ $comment->status === 'active' ? 'Đã đăng' : ($comment->status === 'hidden' ? 'Đã ẩn' : ($comment->status === 'reported' ? 'Bị báo cáo' : 'Bị xóa')) }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="mt-3">
                                        {{ $comments->links() }}
                                    </div>
                                @else
                                    <p class="text-muted text-center py-3">Chưa có bình luận nào.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
