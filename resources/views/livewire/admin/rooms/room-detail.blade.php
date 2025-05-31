<div>
    <div class="container" data-bs-theme="dark">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center my-3">
            <div>
                <h2 class="text-light">Chi tiết phòng chiếu: {{ $room->name }}</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.rooms.index') }}" class="text-warning">Quản lý phòng chiếu</a>
                        </li>
                        <li class="breadcrumb-item active text-light">{{ $room->name }}</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('admin.rooms.edit', $room->id) }}"
                   class="btn btn-warning me-2 @if($room->hasActiveShowtimes()) disabled @endif">
                    <i class="fas fa-edit me-1"></i>Chỉnh sửa
                </a>
                <a href="{{ route('admin.rooms.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Quay lại
                </a>
            </div>
        </div>

        <!-- Room Status Alert -->
        @if($room->hasActiveShowtimes())
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Phòng chiếu có suất chiếu đang hoạt động. Một số chức năng bị hạn chế.
            </div>
        @endif

        <!-- Maintenance Alert -->
        @if($maintenanceStatus === 'overdue')
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>
                <strong>Cảnh báo:</strong> Phòng chiếu đã quá hạn bảo trì {{ abs($daysUntilMaintenance) }} ngày. Cần bảo trì ngay lập tức!
            </div>
        @elseif($maintenanceStatus === 'urgent')
            <div class="alert alert-warning">
                <i class="fas fa-clock me-2"></i>
                <strong>Thông báo:</strong> Phòng chiếu cần bảo trì trong {{ $daysUntilMaintenance }} ngày tới.
            </div>
        @endif

        <!-- Maintenance Countdown Card -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-{{ $this->getMaintenanceStatusColor() }} text-white">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <div class="text-center">
                                    <i class="fas fa-tools fa-3x mb-2"></i>
                                    <h5>Thời gian bảo trì</h5>
                                </div>
                            </div>
                            <div class="col-md-6">
                                @if($maintenanceStatus === 'overdue')
                                    <div class="text-center">
                                        <h3 class="mb-1">QUÁ HẠN</h3>
                                        <div class="row text-center">
                                            <div class="col-3">
                                                <div class="maintenance-countdown">
                                                    <h2 class="mb-0 fw-bold" id="overdue-days">{{ abs($realTimeCountdown['days']) }}</h2>
                                                    <small>Ngày</small>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="maintenance-countdown">
                                                    <h2 class="mb-0 fw-bold" id="overdue-hours">{{ abs($realTimeCountdown['hours']) }}</h2>
                                                    <small>Giờ</small>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="maintenance-countdown">
                                                    <h2 class="mb-0 fw-bold" id="overdue-minutes">{{ abs($realTimeCountdown['minutes']) }}</h2>
                                                    <small>Phút</small>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="maintenance-countdown">
                                                    <h2 class="mb-0 fw-bold" id="overdue-seconds">{{ abs($realTimeCountdown['seconds']) }}</h2>
                                                    <small>Giây</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="row text-center">
                                        <div class="col-3">
                                            <div class="maintenance-countdown">
                                                <h2 class="mb-0 fw-bold" id="countdown-days">{{ $this->formatNumber($realTimeCountdown['days'], 0) }}</h2>
                                                <small>Ngày</small>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="maintenance-countdown">
                                                <h2 class="mb-0 fw-bold" id="countdown-hours">{{ $this->formatNumber($realTimeCountdown['hours'], 0) }}</h2>
                                                <small>Giờ</small>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="maintenance-countdown">
                                                <h2 class="mb-0 fw-bold" id="countdown-minutes">{{ $this->formatNumber($realTimeCountdown['minutes'], 0) }}</h2>
                                                <small>Phút</small>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="maintenance-countdown">
                                                <h2 class="mb-0 fw-bold" id="countdown-seconds">{{ $this->formatNumber($realTimeCountdown['seconds'], 0) }}</h2>
                                                <small>Giây</small>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <h6>Ngày bảo trì tiếp theo</h6>
                                    <h5 class="mb-1">{{ $nextMaintenanceDate->format('d/m/Y') }}</h5>
                                    <small>{{ $this->getMaintenanceStatusText() }}</small>
                                    <div class="mt-2">
                                        <small class="text-light">
                                            Tổng thời gian: {{ $this->formatNumber(abs($totalSecondsUntilMaintenance) / 3600, 2) }} giờ
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
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
                                <h6 class="card-title">Tổng suất chiếu (30 ngày)</h6>
                                <h3 class="mb-0">{{ number_format($totalShowtimes) }}</h3>
                                <small>suất</small>
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
                                <h6 class="card-title">Mức độ sử dụng</h6>
                                <h3 class="mb-0">{{ $averageUtilization }}%</h3>
                                <small>trung bình</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-chart-line fa-2x opacity-75"></i>
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
                                <h6 class="card-title">Điểm bảo trì</h6>
                                <h3 class="mb-0">{{ $maintenanceScore }}/100</h3>
                                <small>
                                    @if($maintenanceScore >= 80)
                                        Tốt
                                    @elseif($maintenanceScore >= 60)
                                        Khá
                                    @elseif($maintenanceScore >= 40)
                                        Trung bình
                                    @else
                                        Cần bảo trì
                                    @endif
                                </small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-tools fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs bg-dark" role="tablist">
            <li class="nav-item">
                <button class="nav-link @if($activeTab === 'overview') active bg-light text-dark @else text-light @endif"
                        wire:click="setActiveTab('overview')">
                    <i class="fas fa-info-circle me-1"></i>Tổng quan
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link @if($activeTab === 'maintenance') active bg-light text-dark @else text-light @endif"
                        wire:click="setActiveTab('maintenance')">
                    <i class="fas fa-tools me-1"></i>Bảo trì
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link @if($activeTab === 'seats') active bg-light text-dark @else text-light @endif"
                        wire:click="setActiveTab('seats')">
                    <i class="fas fa-chair me-1"></i>Sơ đồ ghế
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link @if($activeTab === 'showtimes') active bg-light text-dark @else text-light @endif"
                        wire:click="setActiveTab('showtimes')">
                    <i class="fas fa-calendar me-1"></i>Suất chiếu
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content mt-3">
            <!-- Overview Tab -->
            @if($activeTab === 'overview')
                <div class="row">
                    <div class="col-md-6">
                        <div class="card bg-dark border-light">
                            <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5><i class="fas fa-info me-2"></i>Thông tin cơ bản</h5>
                            </div>
                            <div class="card-body bg-dark">
                                <table class="table table-borderless text-light">
                                    <tr>
                                        <td><strong class="text-warning">Tên phòng:</strong></td>
                                        <td>{{ $room->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong class="text-warning">Sức chứa:</strong></td>
                                        <td>{{ $room->capacity }} ghế</td>
                                    </tr>
                                    <tr>
                                        <td><strong class="text-warning">Số ghế hiện tại:</strong></td>
                                        <td>{{ $room->seats->count() }} ghế</td>
                                    </tr>
                                    <tr>
                                        <td><strong class="text-warning">Trạng thái:</strong></td>
                                        <td>
                                            @switch($room->status)
                                                @case('active')
                                                    <span class="badge bg-success">Hoạt động</span>
                                                    @break
                                                @case('maintenance')
                                                    <span class="badge bg-warning text-dark">Bảo trì</span>
                                                    @break
                                                @case('inactive')
                                                    <span class="badge bg-danger">Ngừng hoạt động</span>
                                                    @break
                                            @endswitch
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong class="text-warning">Bảo trì lần cuối:</strong></td>
                                        <td>
                                            {{ $room->last_maintenance_date ? $room->last_maintenance_date->format('d/m/Y') : 'Chưa có' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong class="text-warning">Ngày tạo:</strong></td>
                                        <td>{{ $room->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-dark border-light">
                            <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5><i class="fas fa-chart-bar me-2"></i>Thống kê ghế</h5>
                            </div>
                            <div class="card-body bg-dark">
                                @php
                                    $seatStats = $room->seats->groupBy('seat_type');
                                @endphp
                                <div class="row text-center">
                                    <div class="col-4">
                                        <div class="card bg-success">
                                            <div class="card-body py-2">
                                                <h4 class="mb-0 text-white fw-bold">{{ $seatStats->get('standard', collect())->count() }}</h4>
                                                <small class="text-white">Thường</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="card bg-warning">
                                            <div class="card-body py-2">
                                                <h4 class="mb-0 text-dark fw-bold">{{ $seatStats->get('vip', collect())->count() }}</h4>
                                                <small class="text-dark">VIP</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="card bg-danger text-white">
                                            <div class="card-body py-2">
                                                <h4 class="mb-0 fw-bold">{{ $seatStats->get('couple', collect())->count() }}</h4>
                                                <small>Couple</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr class="border-warning">

                                <h6 class="text-warning fw-bold mb-3">Giá vé theo loại ghế:</h6>
                                <ul class="list-unstyled text-light">
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
                                                <strong class="text-warning">{{ number_format($seats->first()->price) }}đ</strong>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Maintenance Tab -->
            @if($activeTab === 'maintenance')
                <div class="row">
                    <div class="col-md-8">
                        <div class="card bg-dark border-light">
                            <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5><i class="fas fa-tools me-2"></i>Thông tin bảo trì chi tiết</h5>
                            </div>
                            <div class="card-body bg-dark">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="text-warning mb-3">Lịch sử bảo trì</h6>
                                        <table class="table table-borderless text-light">
                                            <tr>
                                                <td><strong>Bảo trì lần cuối:</strong></td>
                                                <td>
                                                    @if($room->last_maintenance_date)
                                                        {{ $room->last_maintenance_date->format('d/m/Y') }}
                                                        <br><small class="text-muted">({{ $this->formatNumber($daysSinceLastMaintenance, 0) }} ngày trước)</small>
                                                    @else
                                                        <span class="text-muted">Chưa có</span>
                                                        <br><small class="text-muted">Tính từ ngày tạo: {{ $room->created_at->format('d/m/Y') }}</small>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Bảo trì tiếp theo:</strong></td>
                                                <td>
                                                    {{ $nextMaintenanceDate->format('d/m/Y H:i:s') }}
                                                    <br><small class="text-muted">{{ $nextMaintenanceDate->format('l') }}</small>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Chu kỳ bảo trì:</strong></td>
                                                <td>3 tháng ({{ $this->formatNumber(90, 0) }} ngày)</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Trạng thái:</strong></td>
                                                <td>
                                                    <span class="badge bg-{{ $this->getMaintenanceStatusColor() }}">
                                                        {{ $this->getMaintenanceStatusText() }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Thời gian còn lại:</strong></td>
                                                <td>
                                                    <span class="text-{{ $maintenanceStatus === 'overdue' ? 'danger' : 'success' }}">
                                                        {{ $this->formatNumber(abs($totalSecondsUntilMaintenance) / 86400, 2) }} ngày
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-warning mb-3">Điểm bảo trì</h6>
                                        <div class="progress mb-3" style="height: 25px;">
                                            <div class="progress-bar bg-{{ $maintenanceScore >= 60 ? 'success' : ($maintenanceScore >= 40 ? 'warning' : 'danger') }}"
                                                 role="progressbar"
                                                 style="width: {{ $maintenanceScore }}%">
                                                {{ $this->formatNumber($maintenanceScore, 2) }}/100.00
                                            </div>
                                        </div>
                                        <small class="text-muted">
                                            Điểm bảo trì được tính dựa trên thời gian từ lần bảo trì cuối cùng
                                        </small>

                                        <div class="mt-4">
                                            <h6 class="text-warning mb-3">Thống kê thời gian</h6>
                                            <ul class="list-unstyled text-light">
                                                <li><strong>Tổng giờ:</strong> {{ $this->formatNumber(abs($totalSecondsUntilMaintenance) / 3600, 2) }} giờ</li>
                                                <li><strong>Tổng phút:</strong> {{ $this->formatNumber(abs($totalSecondsUntilMaintenance) / 60, 2) }} phút</li>
                                                <li><strong>Tổng giây:</strong> {{ $this->formatNumber(abs($totalSecondsUntilMaintenance), 0) }} giây</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-dark border-light">
                            <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5><i class="fas fa-clock me-2"></i>Đếm ngược thời gian thực</h5>
                            </div>
                            <div class="card-body bg-dark text-center">
                                @if($maintenanceStatus === 'overdue')
                                    <div class="text-danger">
                                        <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                                        <h4>QUÁ HẠN</h4>
                                        <div class="timer-item mb-2">
                                            <h3 class="text-danger" id="realtime-overdue-days">{{ abs($realTimeCountdown['days']) }}</h3>
                                            <small class="text-light">Ngày</small>
                                        </div>
                                        <div class="timer-item mb-2">
                                            <h4 class="text-warning" id="realtime-overdue-hours">{{ abs($realTimeCountdown['hours']) }}</h4>
                                            <small class="text-light">Giờ</small>
                                        </div>
                                        <div class="timer-item mb-2">
                                            <h5 class="text-info" id="realtime-overdue-minutes">{{ abs($realTimeCountdown['minutes']) }}</h5>
                                            <small class="text-light">Phút</small>
                                        </div>
                                        <div class="timer-item">
                                            <h6 class="text-success" id="realtime-overdue-seconds">{{ abs($realTimeCountdown['seconds']) }}</h6>
                                            <small class="text-light">Giây</small>
                                        </div>
                                    </div>
                                @else
                                    <div class="maintenance-timer">
                                        <div class="timer-item mb-3">
                                            <h2 class="text-warning" id="realtime-days">{{ $this->formatNumber($realTimeCountdown['days'], 0) }}</h2>
                                            <small class="text-light">Ngày</small>
                                        </div>
                                        <div class="timer-item mb-3">
                                            <h3 class="text-info" id="realtime-hours">{{ $this->formatNumber($realTimeCountdown['hours'], 0) }}</h3>
                                            <small class="text-light">Giờ</small>
                                        </div>
                                        <div class="timer-item mb-3">
                                            <h4 class="text-success" id="realtime-minutes">{{ $this->formatNumber($realTimeCountdown['minutes'], 0) }}</h4>
                                            <small class="text-light">Phút</small>
                                        </div>
                                        <div class="timer-item">
                                            <h5 class="text-primary" id="realtime-seconds">{{ $this->formatNumber($realTimeCountdown['seconds'], 0) }}</h5>
                                            <small class="text-light">Giây</small>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Seats Tab -->
            @if($activeTab === 'seats')
                <div class="card bg-dark border-light">
                    <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h5><i class="fas fa-chair me-2"></i>Sơ đồ ghế phòng {{ $room->name }}</h5>
                    </div>
                    <div class="card-body bg-dark">
                        @if($room->seats->count() > 0)
                            <div class="seat-map text-center">
                                <div class="screen mb-4">
                                    <div class="bg-warning text-dark py-3 rounded">
                                        <h5 class="mb-0">MÀN HÌNH</h5>
                                    </div>
                                </div>

                                @php
                                    $seatsByRow = $room->seats->groupBy('seat_row');
                                @endphp

                                <div class="seats-grid">
                                    @foreach($seatsByRow as $rowLetter => $rowSeats)
                                        <div class="seat-row mb-3 d-flex justify-content-center align-items-center">
                                            <span class="row-label me-3 fw-bold fs-5 text-warning">{{ $rowLetter }}</span>
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
                                        <span class="text-light fw-bold"><div class="seat seat-standard d-inline-block me-2"></div>Thường ({{ number_format($room->seats->where('seat_type', 'standard')->first()->price ?? 0) }}đ)</span>
                                        <span class="text-light fw-bold"><div class="seat seat-vip d-inline-block me-2"></div>VIP ({{ number_format($room->seats->where('seat_type', 'vip')->first()->price ?? 0) }}đ)</span>
                                        <span class="text-light fw-bold"><div class="seat seat-couple d-inline-block me-2"></div>Couple ({{ number_format($room->seats->where('seat_type', 'couple')->first()->price ?? 0) }}đ)</span>
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
                        <div class="card bg-dark border-light">
                            <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5><i class="fas fa-history me-2"></i>Suất chiếu gần đây</h5>
                            </div>
                            <div class="card-body bg-dark">
                                @if($recentShowtimes->count() > 0)
                                    <div class="list-group">
                                        @foreach($recentShowtimes as $showtime)
                                            <div class="list-group-item bg-dark text-light border-warning">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="mb-1 fw-bold">{{ $showtime->movie->title }}</h6>
                                                        <small class="text-warning">
                                                            {{ $showtime->start_time->format('d/m/Y H:i') }}
                                                        </small>
                                                    </div>
                                                    <span class="badge bg-{{ $showtime->status === 'active' ? 'success' : 'secondary' }}">
                                                        {{ $showtime->status === 'active' ? 'Đang chiếu' : 'Đã kết thúc' }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-light">Chưa có suất chiếu nào.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-dark border-light">
                            <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5><i class="fas fa-calendar-plus me-2"></i>Suất chiếu sắp tới</h5>
                            </div>
                            <div class="card-body bg-dark">
                                @if($upcomingShowtimes->count() > 0)
                                    @foreach($upcomingShowtimes as $showtime)
                                        <div class="d-flex justify-content-between align-items-center border-bottom border-warning py-2">
                                            <div>
                                                <h6 class="mb-1 text-light fw-bold">{{ $showtime->movie->title ?? 'N/A' }}</h6>
                                                <small class="text-muted">
                                                    {{ $showtime->start_time->format('d/m/Y H:i') }} -
                                                    {{ $showtime->end_time->format('H:i') }}
                                                </small>
                                            </div>
                                            <span class="badge bg-warning text-dark fw-bold">{{ number_format($showtime->price) }}đ</span>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-muted text-center py-3">Không có suất chiếu nào sắp tới</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        // Real-time countdown update
        let countdownInterval;
        let maintenanceDate = new Date('{{ $nextMaintenanceDate->toISOString() }}');
        let isOverdue = {{ $maintenanceStatus === 'overdue' ? 'true' : 'false' }};

        function updateCountdown() {
            const now = new Date();
            const timeDiff = isOverdue ? now - maintenanceDate : maintenanceDate - now;

            if (timeDiff <= 0 && !isOverdue) {
                // Chuyển sang trạng thái quá hạn
                isOverdue = true;
                location.reload(); // Reload để cập nhật trạng thái
                return;
            }

            const totalSeconds = Math.abs(Math.floor(timeDiff / 1000));
            const days = Math.floor(totalSeconds / 86400);
            const hours = Math.floor((totalSeconds % 86400) / 3600);
            const minutes = Math.floor((totalSeconds % 3600) / 60);
            const seconds = totalSeconds % 60;

            // Cập nhật countdown chính
            const daysEl = document.getElementById(isOverdue ? 'overdue-days' : 'countdown-days');
            const hoursEl = document.getElementById(isOverdue ? 'overdue-hours' : 'countdown-hours');
            const minutesEl = document.getElementById(isOverdue ? 'overdue-minutes' : 'countdown-minutes');
            const secondsEl = document.getElementById(isOverdue ? 'overdue-seconds' : 'countdown-seconds');

            if (daysEl) daysEl.textContent = days.toLocaleString();
            if (hoursEl) hoursEl.textContent = hours.toString().padStart(2, '0');
            if (minutesEl) minutesEl.textContent = minutes.toString().padStart(2, '0');
            if (secondsEl) secondsEl.textContent = seconds.toString().padStart(2, '0');

            // Cập nhật realtime countdown trong tab bảo trì
            const realtimeDaysEl = document.getElementById(isOverdue ? 'realtime-overdue-days' : 'realtime-days');
            const realtimeHoursEl = document.getElementById(isOverdue ? 'realtime-overdue-hours' : 'realtime-hours');
            const realtimeMinutesEl = document.getElementById(isOverdue ? 'realtime-overdue-minutes' : 'realtime-minutes');
            const realtimeSecondsEl = document.getElementById(isOverdue ? 'realtime-overdue-seconds' : 'realtime-seconds');

            if (realtimeDaysEl) realtimeDaysEl.textContent = days.toLocaleString();
            if (realtimeHoursEl) realtimeHoursEl.textContent = hours.toString().padStart(2, '0');
            if (realtimeMinutesEl) realtimeMinutesEl.textContent = minutes.toString().padStart(2, '0');
            if (realtimeSecondsEl) realtimeSecondsEl.textContent = seconds.toString().padStart(2, '0');
        }

        // Bắt đầu countdown
        function startCountdown() {
            updateCountdown(); // Cập nhật ngay lập tức
            countdownInterval = setInterval(updateCountdown, 1000); // Cập nhật mỗi giây
        }

        // Dừng countdown
        function stopCountdown() {
            if (countdownInterval) {
                clearInterval(countdownInterval);
            }
        }

        // Khởi động khi trang load
        document.addEventListener('DOMContentLoaded', function() {
            startCountdown();
        });

        // Dừng khi rời khỏi trang
        window.addEventListener('beforeunload', function() {
            stopCountdown();
        });

        // Cập nhật dữ liệu từ server mỗi 5 phút
        setInterval(function() {
            @this.call('updateCountdown');
        }, 300000);
    </script>
</div>
