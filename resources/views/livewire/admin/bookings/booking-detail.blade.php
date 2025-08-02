@use('chillerlan\QRCode\QRCode')
<div class="scRender">
    <div class="container-lg mb-4" @if(session()->missing('deleteExpired')) wire:poll="cleanupBookingsAndUpdateData" @endif>
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Chi tiết đơn hàng: {{ $booking->booking_code }}</h2>
            <div>
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Quay lại
                </a>
            </div>
        </div>

        <div class="row mb-4 g-3">
            <div class="col-lg-3 col-md-6">
                <div class="card text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">SL ghế đã đặt</h6>
                                <h3 class="mb-0">{{ number_format($booking->seats->count(), 0, '.', '.') }}</h3>
                                <small>Ghế</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fa-solid fa-loveseat fa-2x opacity-75"></i>
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
                                <h6 class="card-title">SL món ăn đặt kèm</h6>
                                <h3 class="mb-0">{{ number_format($booking->foodOrderItems->count(), 0, '.', '.') }}</h3>
                                <small>Món ăn</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fa-solid fa-utensils fa-2x opacity-75"></i>
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
                                <h6 class="card-title">Doanh thu món ăn</h6>
                                <h3 class="mb-0" style="color: #ffefbe; text-shadow: 0 0 6px rgba(197, 169, 86, 0.8);">{{ number_format($booking->foodOrderItems->sum(fn($foodOrderItem) => $foodOrderItem->price * $foodOrderItem->quantity), 0, '.', '.') }}đ</h3>
                                <small>VNĐ</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fa-solid fa-money-bill-wave fa-2x opacity-75"></i>
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
                                <h6 class="card-title">SL mã giảm giá sử dụng</h6>
                                <h3 class="mb-0">{{ number_format($booking->promotionUsages->count(), 0, '.', '.') }}</h3>
                                <small>Mã giảm giá</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fa-solid fa-percent fa-2x opacity-75"></i>
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
                <button class="nav-link @if($tabCurrent === 'seats') active bg-light text-dark @else text-light @endif"
                        wire:click="$set('tabCurrent', 'seats')"
                        style="border-top-left-radius: 0; border-top-right-radius: 0;">
                    <i class="fa-solid fa-chair me-1"></i>Ghế đặt
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link @if($tabCurrent === 'tickets') active bg-light text-dark @else text-light @endif"
                        wire:click="$set('tabCurrent', 'tickets')"
                        style="border-top-left-radius: 0; border-top-right-radius: 0;">
                    <i class="fa-solid fa-ticket me-1"></i>Thông tin vé
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link @if($tabCurrent === 'foodsOrder') active bg-light text-dark @else text-light @endif"
                        wire:click="$set('tabCurrent', 'foodsOrder')"
                        style="border-top-left-radius: 0; border-top-right-radius: 0;">
                    <i class="fa-solid fa-burger me-1"></i>Món ăn
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link @if($tabCurrent === 'promotions') active bg-light text-dark @else text-light @endif"
                        wire:click="$set('tabCurrent', 'promotions')"
                        style="border-top-left-radius: 0;">
                    <i class="fa-solid fa-swatchbook me-1"></i>Mã giảm giá
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content mt-1">
            <!-- Overview Tab -->
            @if($tabCurrent === 'overview')
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card bg-dark border-light">
                            <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5><i class="fas fa-info me-2"></i>Thông tin chi tiết</h5>
                            </div>
                            <div class="card-body bg-dark" style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                                <table class="table table-borderless text-light">
                                    <tr>
                                        <td><strong class="text-warning">Mã đơn hàng:</strong></td>
                                        <td><strong>{{ $booking->booking_code }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong class="text-warning">Tổng tiền:</strong></td>
                                        <td>
                                            <span class="badge bg-gradient fs-6">
                                                {{ number_format($booking->total_price, 0, '.', '.') }}đ
                                            </span>
                                            @if($booking->promotionUsage->exists())
                                                <small class="text-danger fw-bold d-block mt-1 ms-1">- {{ number_format($booking->promotionUsage->discount_amount, 0, '.', '.') }}đ KM</small>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong class="text-warning">Mã giao dịch:</strong></td>
                                        <td><strong>{{ $booking->transaction_code }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong class="text-warning">Trạng thái:</strong></td>
                                        <td>
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
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong class="text-warning">Phương thức thanh toán:</strong></td>
                                        <td>
                                            <span class="badge-clean-base badge-clean-gray">
                                                <i class="fa-solid fa-badge-dollar me-1"></i>
                                                @switch($booking->payment_method)
                                                    @case('credit_card') Thẻ tín dụng @break
                                                    @case('bank_transfer') Chuyển khoản @break
                                                    @case('e_wallet') Ví điện tử @break
                                                    @case('cash') Tiền mặt @break
                                                @endswitch
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong class="text-warning">Ngày đặt hàng:</strong></td>
                                        <td>{{ $booking->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mt-4 mt-lg-0">
                        <div class="card bg-dark border-light">
                            <div class="card-header bg-gradient text-light"
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5><i class="fa-solid fa-clapperboard me-2"></i>Suất chiếu</h5>
                            </div>
                            <div class="card-body bg-dark"
                                style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                                @php $showtimeBooking = $booking->showtime @endphp
                                <div class="movie-showtime-card">
                                    <div class="d-flex flex-wrap">
                                        <div class="movie-poster mb-1">
                                            @if($poster = $showtimeBooking->movie->poster)
                                                <img src="{{ asset('storage/' . $poster) }}"
                                                    alt="Ảnh phim {{ $showtimeBooking->movie->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                                            @else
                                                <i class="fas fa-film"></i>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="movie-title">
                                                <a href="{{ route('admin.movies.detail', $showtimeBooking->movie->id) }}" class="link-active" style="font-size: inherit">
                                                    {{ Str::limit($showtimeBooking->movie->title, 45, '...') }}
                                                </a>
                                            </div>
                                            <div class="movie-genre">
                                                <i class="fas fa-tags me-1"></i>
                                                {{ $showtimeBooking->movie->genres->take(3)->implode('name', ', ') ?: 'Không có thể loại' }} • {{ $showtimeBooking->movie->duration }} phút
                                            </div>

                                            <div class="showtime-info text-start">
                                                <i class="fas fa-door-open"></i>
                                                <span>Phòng chiếu: <strong>{{ $showtimeBooking->room->name }}</strong></span>
                                            </div>

                                            <div class="showtime-info text-start">
                                                <i class="fas fa-clock"></i>
                                                <span>Thời gian chiếu: <strong>{{ $showtimeBooking->start_time->format('d/m/Y H:i') }} - {{ $showtimeBooking->end_time->format('H:i') }}</strong></span>
                                            </div>

                                            <div class="showtime-info text-start">
                                                <i class="fa-solid fa-square-dollar"></i>
                                                <span>Giá vé: <strong>{{ number_format((int)$showtimeBooking->movie->price + (int)$showtimeBooking->price, 0, '.', '.') }}</strong></span>
                                            </div>

                                            <div class="showtime-info text-start">
                                                <i class="fas fa-circle-check"></i>
                                                <span>Trạng thái:
                                                    <strong>
                                                        @switch($showtimeBooking->status)
                                                            @case('active')
                                                                <div class="badge bg-primary"><i class="fa-solid fa-clapperboard-play me-1 text-light"></i>Đang hoạt động</div>
                                                                @break
                                                            @case('completed')
                                                                <div class="badge bg-success"><i class="fa-solid fa-calendar-check me-1 text-light"></i>Đã hoàn thành</div>
                                                                @break
                                                            @case('canceled')
                                                                <div class="badge bg-danger"><i class="fa-solid fa-hexagon-xmark me-1 text-light"></i>Đã bị hủy</div>
                                                                @break
                                                        @endswitch
                                                    </strong>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-4">
                        <div class="card bg-dark border-light">
                            <div class="card-header bg-gradient text-light"
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5><i class="fas fa-user me-2"></i>Người đặt</h5>
                            </div>
                            <div class="card-body bg-dark"
                                style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                                @php $userBooking = $booking->user @endphp
                                <div class="d-flex align-items-start justify-content-center flex-wrap flex-lg-nowrap p-3 compact-dark rounded">
                                    <div class="d-flex flex-column align-items-center me-md-3 gap-2">
                                        <div class="user-avatar-clean" style="width: 160px; aspect-ratio: 1; height: auto; margin-bottom: 0; border-radius: 50%;">
                                            @if($userBooking->avatar)
                                                <img src="{{ asset('storage/' . $userBooking->avatar) }}" alt style="width: 100%; height: 100%; object-fit: cover; border-radius: inherit;">
                                            @else
                                                <i class="fas fa-user icon-white" style="font-size: 60px;"></i>
                                            @endif
                                        </div>
                                        <h5 class="card-title text-center">
                                            <a class="user-name-link-dark" href="{{ route('admin.users.detail', $booking->user_id) }}" style="font-size: inherit;">
                                                {{ Str::limit($userBooking->name, 23, '...') ?? 'Không tìm thấy người dùng' }}
                                            </a>
                                        </h5>
                                    </div>
                                    <div class="flex-grow-1 w-100 text-center">
                                        <style>
                                            #user-info-table td{
                                                padding: .75rem .5rem !important;
                                            }
                                        </style>
                                        <table class="table table-md table-bordered table-striped-columns table-hover text-light text-start mb-0" id="user-info-table">
                                            <tr>
                                                <td><strong class="text-warning">Email:</strong></td>
                                                <td><strong>{{ $userBooking->email }}</strong></td>
                                            </tr>
                                            <tr>
                                                <td><strong class="text-warning">Số điện thoại:</strong></td>
                                                <td><strong>{{ $userBooking->phone }}</strong></td>
                                            </tr>
                                            <tr>
                                                <td><strong class="text-warning">Địa chỉ:</strong></td>
                                                <td>
                                                    @if ($userBooking->address)
                                                        <span class="text-light text-wrap lh-base">{{ Str::limit($userBooking->address, 200, '...') }}</span>
                                                    @else
                                                        <span class="text-muted">Không tìm thấy địa chỉ</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong class="text-warning">Ngày sinh:</strong></td>
                                                <td>
                                                    <span>{{ $userBooking->birthday?->format('d/m/Y') ?? 'N/A' }}</span>
                                                    <small class="text-muted d-block mt-1" style="font-size: 12px">
                                                        Giới tính: {{ $userBooking->gender === "man" ? "Nam" : ($userBooking->gender === "woman" ? "Nữ" : "Khác") }}
                                                    </small>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong class="text-warning">Vai trò:</strong></td>
                                                <td>
                                                    @switch($userBooking->role)
                                                        @case('admin')
                                                            <span class="badge-clean-base badge-clean-yellow">
                                                                <i class="fa-solid fa-crown me-1"></i>
                                                                Quản trị viên
                                                            </span>
                                                            @break
                                                        @case('staff')
                                                            <span class="badge-clean-base badge-clean-rose">
                                                                <i class="fa-solid fa-user-tie me-1"></i>
                                                                Nhân viên
                                                            </span>
                                                            @break
                                                        @case('user')
                                                            <span class="badge-clean-base badge-clean-purple">
                                                                <i class="fa-solid fa-user me-1"></i>
                                                                Người dùng
                                                            </span>
                                                            @break
                                                    @endswitch
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong class="text-warning">Trạng thái:</strong></td>
                                                <td>
                                                    @switch($userBooking->status)
                                                        @case('active')
                                                            <span class="badge bg-success">Đang hoạt động</span>
                                                        @break
                                                        @case('inactive')
                                                            <span class="badge bg-warning text-dark">Không hoạt động</span>
                                                        @break
                                                        @case('banned')
                                                            <span class="badge bg-danger">Bị cấm</span>
                                                        @break
                                                    @endswitch
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong class="text-warning">Ngày tạo:</strong></td>
                                                <td>{{ $userBooking->created_at->format('d/m/Y H:i') }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($tabCurrent === 'seats')
                <div class="row">
                    <div class="col-12">
                        <div class="card bg-dark border-light">
                            <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5><i class="fa-solid fa-chair-office me-2"></i>Danh sách ghế đã đặt</h5>
                            </div>
                            <div class="card-body bg-dark" style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">

                            </div>
                        </div>
                    </div>
                </div>
            @elseif($tabCurrent === 'tickets')
                <div class="row">
                    <div class="col-12">
                        <div class="card bg-dark border-light">
                            <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5><i class="fa-solid fa-film me-2"></i>Chi tiết vé phim</h5>
                            </div>
                            <div class="card-body bg-dark" style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                                <table class="table table-md table-bordered table-striped table-hover text-light text-start mb-0" id="user-info-table">
                                    <thead>
                                        <tr>
                                            <th class="text-center text-light">Mã QR</th>
                                            <th class="text-center text-light">Ghi chú</th>
                                            <th class="text-center text-light">Tình trạng vé</th>
                                            <th class="text-center text-light">Trạng thái</th>
                                            <th class="text-center text-light">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tickets as $ticket)
                                            <tr wire:key="ticket-{{ $ticket->id }}">
                                                <td>
                                                    <div class="d-flex justify-content-center">
                                                        <div class="qr-code" style="margin-bottom: 0;">
                                                            <img src="{{ (new QRCode)->render($ticket->qr_code) }}" alt="QR code" style="width: 100%; height: 100%; border-radius: 0;">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-muted text-center">
                                                    <p class="text-wrap lh-base" style="margin-bottom: 0;">{{ Str::limit($ticket->description ?: "Không có ghi chú", 150, '...') }}</p>
                                                </td>
                                                <td class="text-center">
                                                    @if($ticket->taken)
                                                        <span class="badge-clean-base badge-clean-green">
                                                            <i class="fas fa-check-circle me-1"></i>
                                                            Đã lấy vé
                                                        </span>
                                                    @else
                                                        <span class="badge-clean-base badge-clean-orange">
                                                            <i class="fas fa-times-circle me-1"></i>
                                                            Chưa lấy vé
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @switch($ticket->status)
                                                        @case('active')
                                                            <span class="badge bg-primary">Chưa sử dụng</span>
                                                        @break
                                                        @case('used')
                                                            <span class="badge bg-success">Đã sử dụng</span>
                                                        @break
                                                        @case('canceled')
                                                            <span class="badge bg-danger">Đã bị hủy</span>
                                                        @break
                                                    @endswitch
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-center">
                                                        @if($ticket->isValidTicketOrder())
                                                            <a href="{{ route('client.ticket', [$booking->booking_code, $ticket->getCurrentIndex()]) }}" target="_blank"
                                                                class="btn btn-sm btn-outline-info" title="Xem chi tiết">
                                                                <i class="fas fa-eye" style="margin-right: 0"></i>
                                                            </a>
                                                        @else
                                                            <button type="button" class="btn btn-sm btn-outline-info"
                                                                    wire:sc-alert.error="Không thể xem chi tiết vé phim này!"
                                                                    wire:sc-model title="Xem chi tiết">
                                                                <i class="fas fa-eye" style="margin-right: 0"></i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($tabCurrent === 'foodsOrder')
                <div class="row">
                    <div class="col-12">
                        <div class="card bg-dark border-light">
                            <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5><i class="fa-solid fa-burger-soda me-2"></i>Danh sách món ăn đặt kèm</h5>
                            </div>
                            <div class="card-body bg-dark" style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                                @php $foodOrderItems = $booking->foodOrderItems @endphp
                                <div class="table-responsive">
                                    <table class="table table-dark table-striped table-hover text-light">
                                        <thead>
                                            <tr>
                                                <th class="text-center text-light">Ảnh</th>
                                                <th class="text-center text-light">Tên món ăn</th>
                                                <th class="text-center text-light">Mô tả</th>
                                                <th class="text-center text-light">Chi tiết món ăn</th>
                                                <th class="text-center text-light">Giá</th>
                                                <th class="text-center text-light">Số lượng</th>
                                                <th class="text-center text-light">Tổng tiền</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($foodOrderItems as $foodOrder)
                                                <tr wire:key="food-order-{{ $foodOrder->id }}">
                                                    <td>
                                                        <div class="d-flex justify-content-center">
                                                            <div class="food-image">
                                                                @if($foodImage = $foodOrder->variant->image)
                                                                    <img src="{{ asset('storage/' . $foodImage) }}"
                                                                        alt="Ảnh món ăn {{ $foodOrder->variant->foodItem->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 0;">
                                                                @else
                                                                    <i class="fa-solid fa-burger-soda"></i>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <strong class="text-light">{{ $foodOrder->variant->foodItem->name }}</strong>
                                                    </td>
                                                    <td class="text-muted text-center">
                                                        <p class="text-wrap lh-base" style="margin-bottom: 0;">{{ Str::limit($foodOrder->variant->foodItem->description ?: "Không có mô tả", 100, '...') }}</p>
                                                    </td>
                                                    <td>
                                                        @foreach($foodOrder->variant->attributeValues as $attributeValue)
                                                            <ul class="food-attributes-list">
                                                                <li>
                                                                    <i class="fas fa-leaf icon-special"></i>
                                                                    <span class="attr-label">{{ $attributeValue->attribute->name }}:</span>
                                                                    <span class="attr-value" style="color: #a78bfa;">{{ $attributeValue->value }}</span>
                                                                </li>
                                                            </ul>
                                                        @endforeach
                                                    </td>
                                                    <td class="text-center text-warning fw-bold">
                                                        {{ number_format($foodOrder->price, 0, ',', '.') }}đ
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $foodOrder->quantity }}
                                                    </td>
                                                    <td class="text-center text-warning fw-bold">
                                                        {{ number_format($foodOrder->price * $foodOrder->quantity, 0, ',', '.') }}đ
                                                    </td>
                                                </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center py-4">
                                                            <div class="text-muted">
                                                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                                                <p>Không đặt kèm món ăn nào</p>
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
                </div>
            @elseif($tabCurrent === 'promotions')
                <div class="row">
                    <div class="col-12">
                        <div class="card bg-dark border-light">
                            <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5><i class="fa-solid fa-tag me-2"></i>Danh sách mã giảm giá đã áp dụng</h5>
                            </div>
                            <div class="card-body bg-dark" style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                                @php $promotionUsages = $booking->promotionUsages @endphp
                                <div class="table-responsive">
                                    <table class="table table-dark table-striped table-hover text-light">
                                        <thead>
                                            <tr>
                                                <th class="text-center text-light">Mã giảm giá</th>
                                                <th class="text-center text-light">Tên mã</th>
                                                <th class="text-center text-light">Mô tả</th>
                                                <th class="text-center text-light">Số tiền giảm</th>
                                                <th class="text-center text-light">Tổng tiền đã giảm</th>
                                                <th class="text-center text-light">Ngày sử dụng</th>
                                                <th class="text-center text-light">Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($promotionUsages as $promotionUsage)
                                                <tr wire:key="promotion-{{ $promotionUsage->id }}">
                                                    <td class="text-center">
                                                        {{ $promotionUsage->promotion?->code ?? 'N/A' }}
                                                    </td>
                                                    <td class="text-center">
                                                        <strong class="text-light">{{ $promotionUsage->promotion->title }}</strong>
                                                    </td>
                                                    <td class="text-muted text-center">
                                                        <p class="text-wrap lh-base" style="margin-bottom: 0;">{{ Str::limit($promotionUsage->promotion->description ?: "Không có mô tả", 100, '...') }}</p>
                                                    </td>
                                                    <td class="text-center text-warning fw-bold">
                                                        {{ number_format($promotionUsage->promotion->discount_value, 0, ',', '.') }}{{ $promotionUsage->promotion->discount_type === 'percentage' ? '%' : 'đ' }}
                                                    </td>
                                                    <td class="text-center text-warning fw-bold">
                                                        {{ number_format($promotionUsage->discount_amount, 0, ',', '.') }}đ
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $promotionUsage->used_at->format('d/m/Y H:i') }}
                                                    </td>
                                                    <td>
                                                        <div class="d-flex justify-content-center">
                                                            <a href="{{ route('admin.promotions.detail', $promotionUsage->promotion->id) }}"
                                                                class="btn btn-sm btn-info" title="Xem chi tiết">
                                                                <i class="fas fa-eye" style="margin-right: 0"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center py-4">
                                                            <div class="text-muted">
                                                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                                                <p>Không áp dụng mã giảm giá nào</p>
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
                </div>
            @endif
        </div>
    </div>
</div>
