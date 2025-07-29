<div class="scRender">
    <div class="container-lg mb-4" wire:poll>
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Chi tiết phim: {{ $movie->title }}</h2>
            <div>
                <a href="{{ route('admin.movies.edit', $movie->id) }}" class="btn btn-warning me-2">
                    <i class="fas fa-edit me-1"></i>Chỉnh sửa
                </a>
                <a href="{{ route('admin.movies.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Quay lại
                </a>
            </div>
        </div>

        @if($movie->hasActiveShowtimes())
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            Phim đang có suất chiếu hoạt động. Một số chức năng sẽ bị hạn chế.
        </div>
        @endif

        <div class="row mb-4 g-3">
            <div class="col-lg-3 col-md-6">
                <div class="card text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Điểm đánh giá trung bình</h6>
                                @if($movie->ratings->avg('score'))
                                <h3 class="mb-0" style="color: #ffc107; text-shadow: 0 0 6px rgba(255, 193, 7, 0.8);">
                                    {!! Str::repeat('<i class="fas fa-star"></i>', round($movie->ratings->avg('score'),
                                    0, PHP_ROUND_HALF_UP)) !!}</h3>
                                @else
                                <h3 class="mb-0">{!! Str::repeat('<i class="fas fa-star"></i>', 5) !!}</h3>
                                @endif
                                <small>Sao</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-star fa-2x opacity-75"></i>
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
                                <h6 class="card-title">TS suất chiếu sắp chiếu</h6>
                                <h3 class="mb-0">{{ number_format($upcomingShowtimes->count(), 0, '.', '.') }}</h3>
                                <small>Suất chiếu</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-clock fa-2x opacity-75"></i>
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
                                <h6 class="card-title">SL đơn hàng (30 ngày)</h6>
                                <h3 class="mb-0">{{ number_format($totalOrdersIn30Days, 0, '.', '.') }}</h3>
                                <small>Trung bình</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-shopping-cart fa-2x opacity-75"></i>
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
                                <h6 class="card-title">Doanh thu phim (30 ngày)</h6>
                                <h3 class="mb-0">{{ number_format($totalOrdersIn30Days * $movie->price, 0, '.', '.') }}đ
                                </h3>
                                <small>VNĐ</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-dollar-sign fa-2x opacity-75"></i>
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
                    wire:click="setTab('chart')" style="border-top-right-radius: 0;">
                    <i class="fas fa-info-circle me-1"></i>Thông tin tổng quan
                </button>
            </li>
            <li class="nav-item">
                <button
                    class="nav-link @if($tabCurrent === 'overview') active bg-light text-dark @else text-light @endif"
                    wire:click="setTab('overview')" style="border-top-right-radius: 0;">
                    <i class="fa-solid fa-chart-pie-simple me-1"></i> Tổng quan
                </button>
            </li>
            <li class="nav-item">
                <button
                    class="nav-link @if($tabCurrent === 'showtimes') active bg-light text-dark @else text-light @endif"
                    wire:click="setTab('showtimes')" style="border-top-left-radius: 0; border-top-right-radius: 0;">
                    <i class="fas fa-calendar me-1"></i>Suất chiếu
                </button>
            </li>
            <li class="nav-item">
                <button
                    class="nav-link @if($tabCurrent === 'ratingsAndComments') active bg-light text-dark @else text-light @endif"
                    wire:click="setTab('ratingsAndComments')"
                    style="border-top-left-radius: 0; border-top-right-radius: 0;">
                    <i class="fas fa-comments me-1"></i>Đánh giá và bình luận
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link @if($tabCurrent === 'orders') active bg-light text-dark @else text-light @endif"
                    wire:click="setTab('orders')" style="border-top-left-radius: 0;">
                    <i class="fas fa-shopping-cart me-1"></i>Đơn hàng
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content mt-1">
            <!-- Overview Tab -->
            @if($tabCurrent === 'overview')
            <div class="row">
                <div class="col-lg-8">
                    <div class="card bg-dark border-light">
                        <div class="card-header bg-gradient text-light"
                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <h5><i class="fas fa-info me-2"></i>Thông tin chi tiết</h5>
                        </div>
                        <div class="card-body bg-dark"
                            style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                            <table class="table table-borderless text-light">
                                <tr>
                                    <td><strong class="text-warning">Tiêu đề:</strong></td>
                                    <td><strong>{{ $movie->title }}</strong></td>
                                </tr>
                                <tr>
                                    <td><strong class="text-warning">Thể loại:</strong></td>
                                    <td>
                                        <div class="d-flex flex-wrap justify-content-start gap-1">
                                            @forelse ($movie->genres as $genre)
                                            <span class="tag">
                                                {{ $genre->name }}
                                            </span>
                                            @empty
                                            <span class="text-muted">Không có thể loại</span>
                                            @endforelse
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong class="text-warning">Mô tả:</strong></td>
                                    <td class="text-wrap text-muted lh-base text-start">{{ $movie->description ?? 'Không
                                        có mô tả' }}</td>
                                </tr>
                                <tr>
                                    <td><strong class="text-warning">Thời lượng:</strong></td>
                                    <td>{{ number_format($movie->duration, 0, '.', '.') }}p</td>
                                </tr>
                                <tr>
                                    <td><strong class="text-warning">Ngày khởi chiếu:</strong></td>
                                    <td><span style="color: #34c759;"><i class="fas fa-play me-1"></i>{{
                                            $movie->release_date->format('d/m/Y') }}</span></td>
                                </tr>
                                <tr>
                                    <td><strong class="text-warning">Ngày kết thúc:</strong></td>
                                    <td><span style="color: #ff4d4f;"><i class="fas fa-stop me-1"></i>{{
                                            $movie->end_date?->format('d/m/Y') ?? 'Vĩnh viễn' }}</span></td>
                                </tr>
                                <tr>
                                    <td><strong class="text-warning">Đạo diễn:</strong></td>
                                    <td>{{ $movie->director ?? 'Không có thông tin' }}</td>
                                </tr>
                                <tr>
                                    <td><strong class="text-warning">Diễn viên:</strong></td>
                                    <td>{{ $movie->actors ?? 'Không có thông tin' }}</td>
                                </tr>
                                <tr>
                                    <td><strong class="text-warning">Giới hạn độ tuổi:</strong></td>
                                    <td>
                                        @switch($movie->age_restriction)
                                        @case('P')
                                        Không giới hạn độ tuổi (P)
                                        @break
                                        @case('K')
                                        Dưới 13 tuổi (K)
                                        @break
                                        @case('T13')
                                        Trên 13+ tuổi (T13)
                                        @break
                                        @case('T16')
                                        Trên 16+ tuổi (T16)
                                        @break
                                        @case('T18')
                                        Trên 18+ tuổi (T18)
                                        @break
                                        @case('C')
                                        Cấm chiếu (C)
                                        @break
                                        @endswitch
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong class="text-warning">Định dạng phim:</strong></td>
                                    <td>{{ $movie->format }}</td>
                                </tr>
                                <tr>
                                    <td><strong class="text-warning">Trailer:</strong></td>
                                    <td>
                                        @if($movie->trailer_url)
                                        <a href="{{ $movie->trailer_url }}" target="_blank"
                                            class="btn btn-sm btn-outline-primary" title="Mở trailer liên kết">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                        @else
                                        <span class="text-muted">Không có trailer</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong class="text-warning">Giá vé:</strong></td>
                                    <td>
                                        <span class="badge bg-gradient fs-6"
                                            style="background: linear-gradient(45deg, #667eea, #764ba2);">
                                            {{ number_format($movie->price, 0, '.', '.') }}đ
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong class="text-warning">Trạng thái:</strong></td>
                                    <td>
                                        @switch($movie->status)
                                        @case('showing')
                                        <span class="badge bg-success"><i class="fas fa-play me-1"></i>Đang chiếu</span>
                                        @break
                                        @case('coming_soon')
                                        <span class="badge" style="background-color: #2bbafc; color: #ffffff;"><i
                                                class="fas fa-clock me-1"></i>Sắp ra mắt</span>
                                        @break
                                        @case('ended')
                                        <span class="badge bg-danger"><i class="fas fa-clock me-1"></i>Đã kết
                                            thúc</span>
                                        @break
                                        @endswitch
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong class="text-warning">Ngày tạo:</strong></td>
                                    <td>{{ $movie->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mt-4 mt-lg-0">
                    <div class="card bg-dark border-light">
                        <div class="card-header bg-gradient text-light"
                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <h5><i class="fas fa-image me-2"></i>Ảnh poster</h5>
                        </div>
                        <div class="card-body bg-dark"
                            style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                            <div class="movie-poster w-100"
                                style="aspect-ratio: 4 / 5; width: auto; height: auto; margin: 0;">
                                @if($movie->poster)
                                <img src="{{ asset('storage/' . $movie->poster) }}"
                                    alt="Ảnh poster phim {{ $movie->title }}"
                                    style="width: 100%; height: 100%; object-fit: cover; border-radius: 0;">
                                @else
                                <i class="fas fa-film" style="font-size: 22px;"></i>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 mt-4">
                    <div class="card bg-dark border-light">
                        <div class="card-header bg-gradient text-light"
                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <h5><i class="fas fas fa-play me-2"></i>Trailer phim</h5>
                        </div>
                        <div class="card-body bg-dark"
                            style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                            <div class="video-container glow-effect mt-1">
                                <div class="video-header">
                                    <div class="video-icon">
                                        <i class="fa-brands fa-youtube"></i>
                                    </div>
                                    <div>
                                        <h3 class="video-title">{{ $movie->title }} | Official Trailer</h3>
                                    </div>
                                </div>
                                <div class="video-wrapper">
                                    <div class="responsive-iframe">
                                        <iframe
                                            src="{{ getYoutubeEmbedUrl($movie->trailer_url) ?: asset('storage/404.webp') }}"
                                            title="YouTube video player" frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                            allowfullscreen></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @elseif($tabCurrent === 'showtimes')
            <div class="row">
                <div class="col-md-6">
                    <div class="card bg-dark border-light">
                        <div class="card-header bg-gradient text-light"
                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <h5><i class="fas fa-history me-2"></i>Suất chiếu gần đây</h5>
                        </div>
                        <div class="card-body bg-dark"
                            style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                            @if($recentShowtimes->count() > 0)
                            <div class="list-group">
                                @foreach($recentShowtimes as $showtime)
                                <div class="list-group-item bg-dark text-light border-warning">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1 fw-bold">{{ $showtime->room?->name ?? 'N/A' }}</h6>
                                            <small class="text-warning">
                                                {{ $showtime->start_time->format('d/m/Y H:i') }} -
                                                {{ $showtime->end_time->format('H:i') }}
                                            </small>
                                        </div>
                                        <span
                                            class="badge bg-{{ $showtime->status === 'canceled' ? 'danger' : 'success' }}">
                                            {{ $showtime->status === 'canceled' ? 'Đã bị hủy' : 'Đã kết thúc' }}
                                        </span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="mt-3">
                                {{ $recentShowtimes->links() }}
                            </div>
                            @else
                            <p class="text-muted text-center">Chưa có suất chiếu nào.</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mt-4 mt-md-0">
                    <div class="card bg-dark border-light">
                        <div class="card-header bg-gradient text-light"
                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <h5><i class="fas fa-calendar-plus me-2"></i>Suất chiếu sắp tới</h5>
                        </div>
                        <div class="card-body bg-dark"
                            style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                            @if($upcomingShowtimes->count() > 0)
                            <div class="list-group">
                                @foreach($upcomingShowtimes as $showtime)
                                <div class="list-group-item bg-dark text-light border-warning">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-1 text-light fw-bold">{{ $showtime->room?->name ?? 'N/A' }}</h6>
                                        <small class="text-muted">
                                            {{ $showtime->start_time->format('d/m/Y H:i') }} -
                                            {{ $showtime->end_time->format('H:i') }}
                                        </small>
                                    </div>
                                    <span class="badge bg-warning text-dark fw-bold">{{ number_format($showtime->price,
                                        0, '.', '.') }}đ</span>
                                </div>
                                @endforeach
                            </div>
                            <div class="mt-3">
                                {{ $upcomingShowtimes->links() }}
                            </div>
                            @else
                            <p class="text-muted text-center">Không có suất chiếu nào sắp tới</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @elseif($tabCurrent === 'ratingsAndComments')
            <div class="row">
                <div class="col-md-6">
                    <div class="card bg-dark border-light">
                        <div class="card-header bg-gradient text-light"
                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <h5><i class="fa-solid far fa-star me-2"></i>Đánh giá gần đây</h5>
                        </div>
                        <div class="card-body bg-dark"
                            style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                            @if($ratings->count() > 0)
                            <div class="list-group">
                                @foreach($ratings as $rating)
                                <div class="list-group-item bg-dark text-light border-warning">
                                    <div class="d-flex justify-content-between align-items-center gap-3">
                                        <div>
                                            <h6 class="mb-2 fw-bold text-capitalize"><i class="fas fa-film me-2"></i>{{
                                                $rating->user?->name ?? 'N/A' }}</h6>
                                            <p class="my-1"
                                                style="background: rgba(255, 255, 255, 0.05); border-radius: 8px; padding: 12px; border-left: 3px solid #ffc107; font-style: italic; line-height: 1.5; color: #e8e9ea;">
                                                {{ $rating->review ?? 'N/A' }}
                                            </p>
                                            <small class="text-warning">
                                                {{ $rating->created_at->format('d/m/Y H:i') }}
                                            </small>
                                        </div>
                                        <span
                                            class="badge bg-{{ $rating->score > 3 ? 'success' : ($rating->score === 3 ? 'primary' : 'danger') }} d-flex gap-1 justify-content-center align-items-center">
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
                            <p class="text-muted text-center">Chưa có đánh giá nào.</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mt-4 mt-md-0">
                    <div class="card bg-dark border-light">
                        <div class="card-header bg-gradient text-light"
                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <h5><i class="fa-solid fa-comments me-2"></i>Bình luận gần đây</h5>
                        </div>
                        <div class="card-body bg-dark"
                            style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                            @if($comments->count() > 0)
                            <div class="list-group">
                                @foreach($comments as $comment)
                                <div class="list-group-item bg-dark text-light border-warning">
                                    <div class="d-flex justify-content-between align-items-center gap-3">
                                        <div>
                                            <h6 class="mb-2 fw-bold text-capitalize"><i class="fas fa-film me-2"></i>{{
                                                $comment->user?->name ?? 'N/A' }}</h6>
                                            <p class="my-1"
                                                style="background: rgba(255, 255, 255, 0.05); border-radius: 8px; padding: 12px; border-left: 3px solid #ffc107; font-style: italic; line-height: 1.5; color: #e8e9ea;">
                                                {{ Str::limit($comment->content ?? 'N/A', 150) }}
                                            </p>
                                            <small class="text-warning">
                                                {{ $comment->created_at->format('d/m/Y H:i') }}
                                            </small>
                                        </div>
                                        <span
                                            class="badge bg-{{ $comment->status === 'active' ? 'success' : ($comment->status === 'hidden' ? 'primary' : 'danger') }} d-flex gap-1 justify-content-center align-items-center">
                                            {{ $comment->status === 'active' ? 'Đã đăng' : ($comment->status ===
                                            'hidden' ? 'Đã ẩn' : 'Đã xóa') }}
                                        </span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="mt-3">
                                {{ $comments->links() }}
                            </div>
                            @else
                            <p class="text-muted text-center">Chưa có bình luận nào.</p>
                            @endif
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
                                            <th class="text-center text-light">Phòng chiếu</th>
                                            <th class="text-center text-light">Thời gian chiếu</th>
                                            <th class="text-center text-light">Tên món ăn</th>
                                            <th class="text-center text-light">Số lượng</th>
                                            <th class="text-center text-light">Tên khách hàng</th>
                                            <th class="text-center text-light">Email / SĐT</th>
                                            <th class="text-center text-light">Tổng giá</th>
                                            <th class="text-center text-light">Trạng thái</th>
                                            <th class="text-center text-light">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($bookings as $booking)
                                        <tr wire:key="booking-{{ $booking->id }}">
                                            <td class="text-center">{{ $booking->booking_code ?? 'N/A' }}</td>
                                            <td class="text-center">
                                                <strong>
                                                    {{ $booking->showtime->room->name }}
                                                </strong>
                                            </td>
                                            <td class="text-center text-muted">
                                                <i class="fas fa-clock me-1" style="color: #34c759;"></i>
                                                <span style="color: #34c759;">
                                                    {{ $booking->showtime->start_time->format('d/m/Y') }}
                                                </span>
                                                <br>
                                                <small class="text-muted ms-3">
                                                    {{ $booking->showtime->start_time->format('H:i') }} -
                                                    {{ $booking->showtime->end_time->format('H:i') }}
                                                </small>
                                            </td>
                                            <td class="text-center">
                                                <strong class="text-light">
                                                    {{
                                                    Str::limit($booking->foodOrderItems()->with('variant.foodItem')->get()->pluck('variant.foodItem.name')->implode(',
                                                    '), 20, '...') }}
                                                </strong>
                                            </td>
                                            <td class="text-center">
                                                {{ $booking->foodOrderItems->sum('quantity') }}</td>
                                            <td class="text-center">
                                                <strong class="text-light">{{ $booking?->user->name ?? 'N/A' }}</strong>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge"
                                                    style="background: linear-gradient(to right, #642b73, #c6426e) !important;">{{
                                                    $booking?->user->email ?? 'N/A' }}
                                                    @if ($booking?->user->phone)
                                                    / {{ $booking->user->phone }}
                                                    @endif
                                                </span>
                                            </td>
                                            <td class="text-center text-warning fw-bold">
                                                {{ number_format($booking->total_price, 0, ',', '.') }}đ</td>
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
            @if($tabCurrent === 'chart')
                <div class="row">
                    <!-- 2. Bảng so sánh vé bán theo suất chiếu -->
                    <div class="col-lg-6">
                        <div class="bg-dark rounded-3 p-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="text-white mb-0">
                                    <i class="fas fa-ticket me-2 text-success"></i>Vé đã bán theo suất chiếu
                                </h5>
                                <div class="btn-group" role="group">
                                    <button type="button"
                                        class="btn btn-sm {{ $showtimeChart === 'daily' ? 'btn-success' : 'btn-outline-success' }}"
                                        wire:click="changeshowtimeChart('daily')">Ngày</button>
                                    <button type="button"
                                        class="btn btn-sm {{ $showtimeChart === 'monthly' ? 'btn-success' : 'btn-outline-success' }}"
                                        wire:click="changeshowtimeChart('monthly')">Tháng</button>
                                    <button type="button"
                                        class="btn btn-sm {{ $showtimeChart === 'yearly' ? 'btn-success' : 'btn-outline-success' }}"
                                        wire:click="changeshowtimeChart('yearly')">Năm</button>
                                </div>
                            </div>
                            <div wire:ignore><div id="showtimeChart" style="height: 400px;"></div></div>
                        </div>
                    </div>
    
                    <!-- 3. Tỷ lệ check-in -->
                    <div class="col-lg-6">
                        <div class="bg-dark rounded-3 p-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="text-white mb-0">
                                    <i class="fas fa-chart-pie me-2 text-warning"></i>Hiệu suất
                                </h5>
                                <div class="btn-group" role="group">
                                    <button type="button"
                                        class="btn btn-sm {{ $checkinChart === 'daily' ? 'btn-warning' : 'btn-outline-warning' }}"
                                        wire:click="changecheckinChart('daily')">Ngày</button>
                                    <button type="button"
                                        class="btn btn-sm {{ $checkinChart === 'monthly' ? 'btn-warning' : 'btn-outline-warning' }}"
                                        wire:click="changecheckinChart('monthly')">Tháng</button>
                                    <button type="button"
                                        class="btn btn-sm {{ $checkinChart === 'yearly' ? 'btn-warning' : 'btn-outline-warning' }}"
                                        wire:click="changecheckinChart('yearly')">Năm</button>
                                </div>
                            </div>
                            <div wire:ignore><div id="checkinChart" style="height: 400px;"></div></div>
                        </div>
                    </div>
                    <!-- 1. Biểu đồ số vé đã bán -->
                    <div class="col-lg-12">
                        <div class="bg-dark rounded-3 p-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="text-white mb-0">
                                    <i class="fas fa-chart-line me-2 text-primary"></i>Vé đã bán theo ngày
                                </h5>
                                <div class="btn-group" role="group">
                                    <button type="button"
                                        class="btn btn-sm {{ $dailyChart === 'weekly' ? 'btn-primary' : 'btn-outline-primary' }}"
                                        onclick="updateChart('daily')">Ngày</button>
                                    <button type="button"
                                        class="btn btn-sm {{ $dailyChart === 'monthly' ? 'btn-primary' : 'btn-outline-primary' }}"
                                        onclick="updateChart('monthly')">Tháng</button>
                                    <button type="button"
                                        class="btn btn-sm {{ $dailyChart === 'yearly' ? 'btn-primary' : 'btn-outline-primary' }}"
                                        onclick="updateChart('yearly')">Năm</button>
                                </div>
                            </div>
                                <div wire:ignore><div id="dailyChart" style="height: 400px;"></div></div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    @script
    <script>
        
        function updateChart (period) {
            @this.call('changedailyChart', period);
        }

        let chartInstances = {};
        const showtimeDate = @json($bookingCountFormatted);
        const failedCounts = Object.values(showtimeDate).map(count => count.failed);
        const paidCounts = Object.values(showtimeDate).map(count => count.paid);
        
        const stats = @json($bookingStatByDate);
        const labels = Object.keys(stats);
        const paid = labels.map(d => stats[d].paid);  
        const cancelled = labels.map(d => stats[d].cancelled);
        const total = labels.map(d => stats[d].total);
        const totalRevenue = labels.map(d => stats[d].totalRevenue || 0);
        const optionsDailyChart = {
                series: [{
                    name: 'Số vé đã bán',
                    data: total
                }],
                chart: {
                    height: 400,
                    type: 'area',
                    background: 'transparent',
                    toolbar: { show: false },
                    zoom: { enabled: false },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800,
                    }
                },
                colors: ['#4285F4'],
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        type: 'vertical',
                        shadeIntensity: 0.3,
                        gradientToColors: ['#4285F4'],
                        inverseColors: false,
                        opacityFrom: 0.4,
                        opacityTo: 0.1,
                        stops: [0, 100]
                    }
                },
                dataLabels: { enabled: false },
                markers: {
                    size: 6,
                    colors: ['#4285F4'],
                    strokeColors: '#2c3034',
                    strokeWidth: 2,
                    hover: { size: 8 }
                },
                xaxis: {
                    categories: labels,
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: {
                        style: {
                            colors: '#adb5bd',
                            fontSize: '12px',
                            fontWeight: 600
                        }
                    }
                },
                yaxis: {
                    min: 0,
                    max: 500,
                    tickAmount: 5,
                    labels: {
                        style: {
                            colors: '#adb5bd', /* Muted text color */
                            fontSize: '12px'
                        }
                    }
                },
                grid: {
                    show: true,
                    borderColor: '#495057', /* Darker grid lines */
                    strokeDashArray: 2
                },
                tooltip: {
                    theme: 'dark',
                    custom: function({series, seriesIndex, dataPointIndex, w}) {
                        const value = paid[dataPointIndex];                    
                        const cancelledValue = cancelled[dataPointIndex];
                        const revenue = totalRevenue.map(n => n.toLocaleString('de-DE'))[dataPointIndex];
                        return `
                            <div style="
                                background: linear-gradient(135deg, #4285F4 0%, #1976D2 100%);
                                color: white;
                                padding: 15px;
                                border-radius: 10px;
                                box-shadow: 0 4px 20px rgba(66, 133, 244, 0.3);
                                min-width: 200px;
                            ">
                                <div style="margin-bottom: 6px;">
                                    🎟️ Vé bán: <strong>${value}</strong>
                                </div>
                                <div style="margin-bottom: 6px;">
                                    ❌ Hủy: <strong>${cancelledValue}</strong>
                                </div>
                                <div style="margin-bottom: 6px;">
                                    💵 Doanh thu: <strong>${revenue}</strong>
                                </div>
                            </div>
                        `;
                    }
                }
            };
        const optionsShowtimeChart = {
                series: [
                    {
                        name: 'Vé đã bán',
                        data: Object.values(showtimeDate)
                    },
                    {
                        name: 'Sức chứa',
                        data: Object.values(@json($todayCapacities))
                    }
                ],
                chart: {
                    type: 'bar',
                    height: 400,
                    background: 'transparent',
                    toolbar: { show: false },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800
                    }
                },
                colors: ['#4285F4', '#34A853'],
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '60%',
                        endingShape: 'rounded',
                        borderRadius: 6
                    }
                },
                dataLabels: { enabled: false },
                stroke: { show: false },
                xaxis: {
                    categories:Object.keys(showtimeDate),
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: {
                        style: {
                            colors: '#adb5bd',
                            fontSize: '12px',
                            fontWeight: 600
                        }
                    }
                },
                yaxis: {
                    min: 0,
                    max: 140,
                    tickAmount: 7,
                    labels: {
                        style: {
                            colors: '#adb5bd', /* Muted text color */
                            fontSize: '12px'
                        }
                    }
                },
                grid: {
                    show: true,
                    borderColor: '#495057', /* Darker grid lines */
                    strokeDashArray: 2,
                    xaxis: { lines: { show: false } }
                },
                legend: {
                    show: true,
                    position: 'top',
                    horizontalAlign: 'left',
                    offsetY: -10,
                    labels: { colors: '#f8f9fa' }, /* Light text color */
                    markers: {
                        width: 12,
                        height: 12,
                        fillColors: ['#4285F4', '#34A853'],
                        radius: 3
                    }
                },
                tooltip: {
                    shared: true,
                    intersect: false,
                    theme: 'dark',
                    custom: function({series, seriesIndex, dataPointIndex, w}) {
                        const time = Object.keys(showtimeDate)[dataPointIndex];
                        const sold = paidCounts[dataPointIndex];
                        const failed = failedCounts[dataPointIndex];
                        const capacity = Object.values(@json($todayCapacities))[dataPointIndex];
                        const percentage = ((sold / capacity) * 100).toFixed(1);
                        return `
                            <div style="
                                background: #2c3034; /* Card background color */
                                color: #f8f9fa; /* Light text color */
                                padding: 15px;
                                border-radius: 10px;
                                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
                                min-width: 200px;
                                border: 1px solid #495057; /* Darker border */
                            ">
                                <div style="font-weight: 600; font-size: 14px; margin-bottom: 8px;">
                                    🎬 Suất ${time}
                                </div>
                                <div style="margin-bottom: 6px;">
                                    🎟️ Vé bán: <strong>${sold}</strong>
                                </div>
                                <div style="margin-bottom: 6px;">
                                    🎟️ Sức chứa: ${capacity}
                                </div>
                                <div style="margin-bottom: 6px;">
                                    📊 Tỷ lệ lấp đầy: <strong>${percentage}%</strong>
                                </div>
                                <div style="margin-bottom: 6px;">
                                    ❌ Đã hủy: <strong>${failed}</strong>
                                </div>
                            </div>
                        `;
                    }
                }
            };
        const optionsCheckinChart = {
                series: [@json($totalCount),@json($caps)],
                chart: {
                    type: 'pie',
                    height: 400,
                    background: 'transparent',
                    animations: {
                        enabled: false,
                    },
                },
                labels: ['Số vé đã bán', 'Số vé còn lại'], //tính theo số ghế còn lại của từng showtime
                colors: ['#34A853', '#FBBC04'],
                stroke: { show: false },
                dataLabels: {
                    enabled: true,
                    style: {
                        fontSize: '14px',
                        fontWeight: 600,
                        colors: ['#fff']
                    },
                    formatter: function (val, opts) {
                        return Math.round(val) + '%';
                    }
                },
                plotOptions: {
                    pie: {
                        expandOnClick: false,
                        donut: { size: '0%' }
                    }
                },
                legend: {
                    show: true,
                    position: 'bottom',
                    horizontalAlign: 'center',
                    offsetY: 10,
                    labels: { colors: '#f8f9fa' }, /* Light text color */
                    markers: {
                        width: 12,
                        height: 12,
                        fillColors: ['#34A853', '#FBBC04'],
                        radius: 3
                    }
                },
                tooltip: {
                    theme: 'dark',
                    y: {
                        formatter: function (val) {
                            const percentage = ((val / (@json($totalCount)+@json($caps))) * 100).toFixed(1);
                            return `${val.toLocaleString()} vé (${percentage}%)`;
                        }
                    }
                }
            };
        function renderAllCharts() {
                if (chartInstances.dailyChart) chartInstances.dailyChart.destroy();
                if (chartInstances.showtimeChart) chartInstances.showtimeChart.destroy();
                if (chartInstances.checkinChart) chartInstances.checkinChart.destroy();

                const dailyChartEl = document.querySelector("#dailyChart");
                const showtimeChartEl = document.querySelector("#showtimeChart");
                const checkinChartEl = document.querySelector("#checkinChart");

                if (dailyChartEl) chartInstances.dailyChart = createScChart(dailyChartEl, optionsDailyChart);
                if (showtimeChartEl) chartInstances.showtimeChart = createScChart(showtimeChartEl, optionsShowtimeChart);
                if (checkinChartEl) chartInstances.checkinChart = createScChart(checkinChartEl, optionsCheckinChart);
            }
        renderAllCharts();
        document.addEventListener('tabChanged', function(e) {
                if (e.detail && e.detail[0] === 'chart') {
                    setTimeout(() => {
                        renderAllCharts();
                    }, 150);
                }
            });
    </script>
    @endscript
</div>