<div>
    <div class="container">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center my-3">
            <div>
                <h2>Chi tiết phòng chiếu: {{ $room->name }}</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.rooms.index') }}">Quản lý phòng chiếu</a>
                        </li>
                        <li class="breadcrumb-item active">{{ $room->name }}</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('admin.rooms.edit', $room->id) }}" 
                   class="btn btn-warning me-2 @if(!$room->canEdit()) disabled @endif">
                    <i class="fas fa-edit me-1"></i>Chỉnh sửa
                </a>
                <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Quay lại
                </a>
            </div>
        </div>

        <!-- Room Status Alert -->
        @if(!$room->canEdit())
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Phòng chiếu có suất chiếu đang hoạt động. Một số chức năng bị hạn chế.
            </div>
        @endif

        <!-- Quick Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Sức chứa</h6>
                                <h3 class="mb-0">{{ $room->capacity }}</h3>
                                <small>ghế</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-chair fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Booking (30 ngày)</h6>
                                <h3 class="mb-0">{{ number_format($totalBookings) }}</h3>
                                <small>lượt đặt</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-ticket fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Doanh thu (30 ngày)</h6>
                                <h3 class="mb-0">{{ number_format($totalRevenue) }}</h3>
                                <small>VNĐ</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-money-bill fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Tỷ lệ lấp đầy</h6>
                                <h3 class="mb-0">{{ $occupancyRate }}%</h3>
                                <small>30 ngày qua</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-chart-pie fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link @if($activeTab === 'overview') active @endif" 
                        wire:click="setActiveTab('overview')">
                    <i class="fas fa-info-circle me-1"></i>Tổng quan
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link @if($activeTab === 'seats') active @endif" 
                        wire:click="setActiveTab('seats')">
                    <i class="fas fa-chair me-1"></i>Sơ đồ ghế
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link @if($activeTab === 'showtimes') active @endif" 
                        wire:click="setActiveTab('showtimes')">
                    <i class="fas fa-calendar me-1"></i>Suất chiếu
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link @if($activeTab === 'bookings') active @endif" 
                        wire:click="setActiveTab('bookings')">
                    <i class="fas fa-ticket me-1"></i>Đặt vé
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content mt-3">
            <!-- Overview Tab -->
            @if($activeTab === 'overview')
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fas fa-info me-2"></i>Thông tin cơ bản</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Tên phòng:</strong></td>
                                        <td>{{ $room->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Sức chứa:</strong></td>
                                        <td>{{ $room->capacity }} ghế</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Số ghế hiện tại:</strong></td>
                                        <td>{{ $room->seats->count() }} ghế</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Trạng thái:</strong></td>
                                        <td>
                                            @switch($room->status)
                                                @case('active')
                                                    <span class="badge bg-success">Hoạt động</span>
                                                    @break
                                                @case('maintenance')
                                                    <span class="badge bg-warning">Bảo trì</span>
                                                    @break
                                                @case('inactive')
                                                    <span class="badge bg-danger">Ngừng hoạt động</span>
                                                    @break
                                            @endswitch
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Bảo trì lần cuối:</strong></td>
                                        <td>
                                            {{ $room->last_maintenance_date ? $room->last_maintenance_date->format('d/m/Y') : 'Chưa có' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Ngày tạo:</strong></td>
                                        <td>{{ $room->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fas fa-chart-bar me-2"></i>Thống kê ghế</h5>
                            </div>
                            <div class="card-body">
                                @php
                                    $seatStats = $room->seats->groupBy('seat_type');
                                @endphp
                                <div class="row text-center">
                                    <div class="col-4">
                                        <div class="card bg-light">
                                            <div class="card-body py-2">
                                                <h4 class="mb-0 text-success">{{ $seatStats->get('standard', collect())->count() }}</h4>
                                                <small>Thường</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="card bg-warning">
                                            <div class="card-body py-2">
                                                <h4 class="mb-0">{{ $seatStats->get('vip', collect())->count() }}</h4>
                                                <small>VIP</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="card bg-danger text-white">
                                            <div class="card-body py-2">
                                                <h4 class="mb-0">{{ $seatStats->get('couple', collect())->count() }}</h4>
                                                <small>Couple</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <hr>
                                
                                <h6>Giá vé theo loại ghế:</h6>
                                <ul class="list-unstyled">
                                    @foreach($seatStats as $type => $seats)
                                        @if($seats->count() > 0)
                                            <li class="d-flex justify-content-between">
                                                <span>
                                                    @switch($type)
                                                        @case('standard') Ghế thường @break
                                                        @case('vip') Ghế VIP @break
                                                        @case('couple') Ghế Couple @break
                                                    @endswitch
                                                </span>
                                                <strong>{{ number_format($seats->first()->price) }}đ</strong>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Seats Tab -->
            @if($activeTab === 'seats')
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-chair me-2"></i>Sơ đồ ghế phòng {{ $room->name }}</h5>
                    </div>
                    <div class="card-body">
                        @if($room->seats->count() > 0)
                            <div class="seat-map text-center">
                                <div class="screen mb-4">
                                    <div class="bg-dark text-white py-3 rounded">
                                        <h5 class="mb-0">MÀN HÌNH</h5>
                                    </div>
                                </div>

                                @php
                                    $seatsByRow = $room->seats->groupBy('seat_row');
                                @endphp

                                <div class="seats-grid">
                                    @foreach($seatsByRow as $rowLetter => $rowSeats)
                                        <div class="seat-row mb-3 d-flex justify-content-center align-items-center">
                                            <span class="row-label me-3 fw-bold fs-5">{{ $rowLetter }}</span>
                                            <div class="seats-container">
                                                @foreach($rowSeats->sortBy('seat_number') as $seat)
                                                    @php
                                                        $seatClass = match($seat->seat_type) {
                                                            'vip' => 'seat-vip',
                                                            'couple' => 'seat-couple',
                                                            default => 'seat-standard'
                                                        };
                                                    @endphp
                                                    <div class="seat {{ $seatClass }}"
                                                         title="{{ $seat->seat_row }}{{ $seat->seat_number }} - {{ ucfirst($seat->seat_type) }} - {{ number_format($seat->price) }}đ">
                                                        {{ $seat->seat_number }}
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="legend mt-4">
                                    <div class="d-flex justify-content-center gap-4">
                                        <span><div class="seat seat-standard d-inline-block me-2"></div>Thường ({{ number_format($room->seats->where('seat_type', 'standard')->first()->price ?? 0) }}đ)</span>
                                        <span><div class="seat seat-vip d-inline-block me-2"></div>VIP ({{ number_format($room->seats->where('seat_type', 'vip')->first()->price ?? 0) }}đ)</span>
                                        <span><div class="seat seat-couple d-inline-block me-2"></div>Couple ({{ number_format($room->seats->where('seat_type', 'couple')->first()->price ?? 0) }}đ)</span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-chair fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Chưa có sơ đồ ghế nào được tạo</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Showtimes Tab -->
            @if($activeTab === 'showtimes')
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fas fa-calendar-plus me-2"></i>Suất chiếu sắp tới</h5>
                            </div>
                            <div class="card-body">
                                @if($upcomingShowtimes->count() > 0)
                                    @foreach($upcomingShowtimes as $showtime)
                                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                                            <div>
                                                <h6 class="mb-1">{{ $showtime->movie->title ?? 'N/A' }}</h6>
                                                <small class="text-muted">
                                                    {{ $showtime->start_time->format('d/m/Y H:i') }} - 
                                                    {{ $showtime->end_time->format('H:i') }}
                                                </small>
                                            </div>
                                            <span class="badge bg-primary">{{ number_format($showtime->price) }}đ</span>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-muted text-center py-3">Không có suất chiếu nào sắp tới</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5><i class="fas fa-history me-2"></i>Suất chiếu gần đây</h5>
                            </div>
                            <div class="card-body">
                                @if($room->showtimes->count() > 0)
                                    @foreach($room->showtimes->take(5) as $showtime)
                                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                                            <div>
                                                <h6 class="mb-1">{{ $showtime->movie->title ?? 'N/A' }}</h6>
                                                <small class="text-muted">
                                                    {{ $showtime->start_time->format('d/m/Y H:i') }}
                                                </small>
                                            </div>
                                            <div class="text-end">
                                                @switch($showtime->status)
                                                    @case('active')
                                                        <span class="badge bg-success">Hoạt động</span>
                                                        @break
                                                    @case('completed')
                                                        <span class="badge bg-secondary">Hoàn thành</span>
                                                        @break
                                                    @case('canceled')
                                                        <span class="badge bg-danger">Đã hủy</span>
                                                        @break
                                                @endswitch
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-muted text-center py-3">Chưa có suất chiếu nào</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Bookings Tab -->
            @if($activeTab === 'bookings')
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-ticket me-2"></i>Đặt vé gần đây</h5>
                    </div>
                    <div class="card-body">
                        @if($recentBookings->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Khách hàng</th>
                                            <th>Phim</th>
                                            <th>Suất chiếu</th>
                                            <th>Ghế</th>
                                            <th>Tổng tiền</th>
                                            <th>Trạng thái</th>
                                            <th>Ngày đặt</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentBookings as $booking)
                                            <tr>
                                                <td>{{ $booking->user->name ?? 'N/A' }}</td>
                                                <td>{{ $booking->showtime->movie->title ?? 'N/A' }}</td>
                                                <td>{{ $booking->showtime->start_time->format('d/m H:i') }}</td>
                                                <td>
                                                    @foreach($booking->seats as $seat)
                                                        <span class="badge bg-secondary">{{ $seat->seat_code }}</span>
                                                    @endforeach
                                                </td>
                                                <td>{{ number_format($booking->total_price) }}đ</td>
                                                <td>
                                                    @switch($booking->status)
                                                        @case('confirmed')
                                                            <span class="badge bg-success">Đã xác nhận</span>
                                                            @break
                                                        @case('pending')
                                                            <span class="badge bg-warning">Chờ xử lý</span>
                                                            @break
                                                        @case('canceled')
                                                            <span class="badge bg-danger">Đã hủy</span>
                                                            @break
                                                    @endswitch
                                                </td>
                                                <td>{{ $booking->created_at->format('d/m H:i') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted text-center py-3">Chưa có đặt vé nào gần đây</p>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <style>
        /* Seat styling */
        .seat {
            width: 30px;
            height: 30px;
            margin: 2px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: bold;
            color: white;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .seat:hover {
            transform: scale(1.1);
        }
        
        .seat-standard { background-color: #28a745; }
        .seat-vip { background-color: #ffc107; color: #000; }
        .seat-couple { background-color: #e83e8c; }
        
        .row-label {
            width: 30px;
            text-align: center;
        }
        
        .seats-container {
            display: flex;
            flex-wrap: wrap;
            gap: 2px;
        }
        
        .seats-grid {
            max-height: 600px;
            overflow-y: auto;
        }
        
        /* Tab styling */
        .nav-tabs .nav-link {
            border: none;
            color: #6c757d;
        }
        
        .nav-tabs .nav-link.active {
            background-color: #007bff;
            color: white;
            border-radius: 0.375rem 0.375rem 0 0;
        }
        
        /* Card hover effects */
        .card {
            transition: box-shadow 0.3s;
        }
        
        .card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
    </style>
</div>
