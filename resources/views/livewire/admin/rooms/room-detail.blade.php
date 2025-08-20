<div class="scRender scSeat">
    <div class="container-lg mb-4" wire:poll.1s="calculateMaintenanceInfo">
        <!-- Thêm button test ngay sau header -->
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Chi tiết phòng chiếu: {{ $room->name }}</h2>
            <div>
                <a href="{{ route('admin.rooms.edit', $room->id) }}"
                    class="btn btn-warning me-2 @if ($room->hasActiveShowtimes()) disabled @endif">
                    <i class="fas fa-edit me-1"></i>Chỉnh sửa
                </a>
                <a href="{{ route('admin.rooms.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Quay lại
                </a>
            </div>
        </div>

        <!-- Room Status Alert -->
        @if ($room->hasActiveShowtimes())
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Phòng chiếu có suất chiếu đang hoạt động. Một số chức năng bị hạn chế.
            </div>
        @endif

        <!-- Maintenance Countdown Card -->
        @if ($maintenanceStatus === 'overdue')
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-3 mb-1 mb-md-auto">
                                    <div class="text-center">
                                        <i class="fas fa-tools fa-3x mb-2"></i>
                                        <h5>Thời gian bảo trì</h5>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-1 mb-md-auto">
                                    <div class="text-center">
                                        <h3 class="mb-2">QUÁ HẠN</h3>
                                        <div class="row text-center">
                                            <div class="col-3">
                                                <div class="maintenance-countdown">
                                                    <h2 class="mb-0 fw-bold" id="overdue-days">
                                                        {{ number_format($realTimeCountdown['days'], 0, '.', '.') }}
                                                    </h2>
                                                    <small>Ngày</small>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="maintenance-countdown">
                                                    <h2 class="mb-0 fw-bold" id="overdue-hours">
                                                        {{ number_format($realTimeCountdown['hours'], 0) }}</h2>
                                                    <small>Giờ</small>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="maintenance-countdown">
                                                    <h2 class="mb-0 fw-bold" id="overdue-minutes">
                                                        {{ number_format($realTimeCountdown['minutes'], 0) }}</h2>
                                                    <small>Phút</small>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="maintenance-countdown">
                                                    <h2 class="mb-0 fw-bold" id="overdue-seconds">
                                                        {{ number_format($realTimeCountdown['seconds'], 0) }}</h2>
                                                    <small>Giây</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center">
                                        <h6>Ngày bảo trì tiếp theo</h6>
                                        <h5 class="mb-1">{{ $nextMaintenanceDate->format('d/m/Y') }}</h5>
                                        <small>Quá hạn bảo trì</small>
                                        <div class="mt-2">
                                            <small class="text-light">
                                                Tổng thời gian:
                                                {{ number_format(abs($totalSecondsUntilMaintenance) / 3600, 0, '.', '.') }}
                                                giờ
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
            <div class="col-lg-3 col-md-6">
                <div class="card text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Sức chứa</h6>
                                <h3 class="mb-0">{{ $room->capacity }}</h3>
                                <small>Ghế</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-chair fa-2x opacity-75"></i>
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
                                <h6 class="card-title">Tổng suất chiếu (30 ngày)</h6>
                                <h3 class="mb-0">{{ number_format($totalShowtimes) }}</h3>
                                <small>Suất</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-ticket fa-2x opacity-75"></i>
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
                                <h6 class="card-title">Mức độ sử dụng</h6>
                                <h3 class="mb-0">{{ $averageUtilization }}%</h3>
                                <small>Trung bình</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-chart-line fa-2x opacity-75"></i>
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
                                <h6 class="card-title">Điểm bảo trì</h6>
                                <h3 class="mb-0">{{ $maintenanceScore }}/100</h3>
                                <small>
                                    @if ($maintenanceScore >= 80)
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
                <button
                    class="nav-link @if ($tabCurrent === 'analytics') active bg-light text-dark @else text-light @endif"
                    wire:click="$set('tabCurrent', 'analytics')" style="border-top-right-radius: 0;">
                    <i class="fas fa-chart-bar me-1"></i>Tổng quan
                </button>
            </li>
            <li class="nav-item">
                <button
                    class="nav-link @if ($tabCurrent === 'overview') active bg-light text-dark @else text-light @endif"
                    wire:click="$set('tabCurrent', 'overview')"
                    style="border-top-left-radius: 0; border-top-right-radius: 0;">
                    <i class="fas fa-info-circle me-1"></i>Thông tin
                </button>
            </li>
            <li class="nav-item">
                <button
                    class="nav-link @if ($tabCurrent === 'maintenance') active bg-light text-dark @else text-light @endif"
                    wire:click="$set('tabCurrent', 'maintenance')"
                    style="border-top-left-radius: 0; border-top-right-radius: 0;">
                    <i class="fas fa-tools me-1"></i>Bảo trì
                </button>
            </li>
            <li class="nav-item">
                <button
                    class="nav-link @if ($tabCurrent === 'seats') active bg-light text-dark @else text-light @endif"
                    wire:click="$set('tabCurrent', 'seats')"
                    style="border-top-left-radius: 0; border-top-right-radius: 0;">
                    <i class="fas fa-chair me-1"></i>Sơ đồ ghế
                </button>
            </li>
            <li class="nav-item">
                <button
                    class="nav-link @if ($tabCurrent === 'showtimes') active bg-light text-dark @else text-light @endif"
                    wire:click="$set('tabCurrent', 'showtimes')" style="border-top-left-radius: 0;">
                    <i class="fas fa-calendar me-1"></i>Suất chiếu
                </button>
            </li>
        </ul>
        <!-- Tab Content -->
        <div class="tab-content mt-1">
            <!-- Analytics Tab Content -->
            @if ($tabCurrent === 'analytics')
                <div class="container-fluid" wire:ignore>
                    <div class="row g-4">
                        <!-- Combined Room Statistics Chart -->
                        <div class="col-6">
                            <div class="bg-dark mb-3" style="color: black;">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="text-white mb-0">
                                        <i class="fas fa-chart-bar me-2 text-primary"></i>Thống Kê Tất Cả Phòng
                                        Chiếu
                                    </h5>
                                    {{-- Room Stats Filter --}}
                                    <div class="dropdown" wire:ignore>
                                        <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-filter me-1"></i>
                                            <span id="roomStatsFilterText">
                                                @switch($roomStatsPeriod)
                                                    @case('3_days')
                                                        3 ngày gần nhất
                                                    @break

                                                    @case('7_days')
                                                        7 ngày gần nhất
                                                    @break

                                                    @case('30_days')
                                                        30 ngày gần nhất
                                                    @break

                                                    @case('1_month')
                                                        1 tháng gần nhất
                                                    @break

                                                    @case('3_months')
                                                        3 tháng gần nhất
                                                    @break

                                                    @case('1_year')
                                                        1 năm gần nhất
                                                    @break

                                                    @case('2_years')
                                                        2 năm gần nhất
                                                    @break

                                                    @default
                                                        7 ngày gần nhất
                                                @endswitch
                                            </span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-dark">
                                            <li>
                                                <h6 class="dropdown-header text-primary">Ngày</h6>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeRoomStatsPeriod('3_days')">3 ngày gần
                                                    nhất</a></li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeRoomStatsPeriod('7_days')">7 ngày gần
                                                    nhất</a></li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeRoomStatsPeriod('30_days')">30 ngày
                                                    gần nhất</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <h6 class="dropdown-header text-primary">Tháng</h6>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeRoomStatsPeriod('1_month')">1
                                                    tháng</a></li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeRoomStatsPeriod('3_months')">3
                                                    tháng</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <h6 class="dropdown-header text-primary">Năm</h6>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeRoomStatsPeriod('1_year')">1 năm</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeRoomStatsPeriod('2_years')">2 năm</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div wire:ignore>
                                    <div id="allRoomsStatsChart" style="height: 400px;"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Occupancy Rate Chart -->
                        <div class="col-lg-6">
                            <div class="bg-dark" style="color: black;">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="text-white mb-0">
                                        <i class="fas fa-chart-pie me-2 text-success"></i>Tỷ Lệ Lấp Đầy (%)
                                    </h5>
                                    <div class="dropdown" wire:ignore>
                                        <button class="btn btn-outline-success btn-sm dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-filter me-1"></i>
                                            <span id="occupancyFilterText">
                                                @switch($occupancyPeriod)
                                                    @case('3_days')
                                                        3 ngày gần nhất
                                                    @break

                                                    @case('7_days')
                                                        7 ngày gần nhất
                                                    @break

                                                    @case('30_days')
                                                        30 ngày gần nhất
                                                    @break

                                                    @case('1_month')
                                                        1 tháng gần nhất
                                                    @break

                                                    @case('3_months')
                                                        3 tháng gần nhất
                                                    @break

                                                    @case('1_year')
                                                        1 năm gần nhất
                                                    @break

                                                    @case('2_years')
                                                        2 năm gần nhất
                                                    @break

                                                    @default
                                                        7 ngày gần nhất
                                                @endswitch
                                            </span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-dark">
                                            <li>
                                                <h6 class="dropdown-header text-success">Ngày</h6>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeOccupancyPeriod('3_days')">3 ngày gần
                                                    nhất</a></li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeOccupancyPeriod('7_days')">7 ngày gần
                                                    nhất</a></li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeOccupancyPeriod('30_days')">30 ngày
                                                    gần nhất</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <h6 class="dropdown-header text-success">Tháng</h6>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeOccupancyPeriod('1_month')">1 tháng
                                                    gần nhất</a></li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeOccupancyPeriod('3_months')">3 tháng
                                                    gần nhất</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <h6 class="dropdown-header text-success">Năm</h6>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeOccupancyPeriod('1_year')">1 năm gần
                                                    nhất</a></li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeOccupancyPeriod('2_years')">2 năm gần
                                                    nhất</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div wire:ignore>
                                    <div id="occupancyChart" style="height: 400px;"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Seat Status Chart -->
                        <div class="col-lg-6">
                            <div class="bg-dark" style="color: black;">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="text-white mb-0">
                                        <i class="fas fa-chair me-2 text-warning"></i>Tình Trạng Ghế Phòng
                                        {{ $room->name }}
                                    </h5>
                                    <div class="dropdown" wire:ignore>
                                        <button class="btn btn-outline-warning btn-sm dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-filter me-1"></i>
                                            <span id="seatStatusFilterText">
                                                @switch($seatStatusPeriod)
                                                    @case('3_days')
                                                        3 ngày gần nhất
                                                    @break

                                                    @case('7_days')
                                                        7 ngày gần nhất
                                                    @break

                                                    @case('30_days')
                                                        30 ngày gần nhất
                                                    @break

                                                    @case('1_month')
                                                        1 tháng gần nhất
                                                    @break

                                                    @case('3_months')
                                                        3 tháng gần nhất
                                                    @break

                                                    @case('1_year')
                                                        1 năm gần nhất
                                                    @break

                                                    @case('2_years')
                                                        2 năm gần nhất
                                                    @break

                                                    @default
                                                        7 ngày gần nhất
                                                @endswitch
                                            </span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-dark">
                                            <li>
                                                <h6 class="dropdown-header text-warning">Ngày</h6>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeSeatStatusPeriod('3_days')">3 ngày
                                                    gần nhất</a></li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeSeatStatusPeriod('7_days')">7 ngày
                                                    gần nhất</a></li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeSeatStatusPeriod('30_days')">30 ngày
                                                    gần nhất</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <h6 class="dropdown-header text-warning">Tháng</h6>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeSeatStatusPeriod('1_month')">1 tháng
                                                    gần nhất</a></li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeSeatStatusPeriod('3_months')">3 tháng
                                                    gần nhất</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <h6 class="dropdown-header text-warning">Năm</h6>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeSeatStatusPeriod('1_year')">1 năm gần
                                                    nhất</a></li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeSeatStatusPeriod('2_years')">2 năm
                                                    gần nhất</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div wire:ignore>
                                    <div id="seatStatusChart" style="height: 300px;"></div>
                                </div>
                                <!-- Seat Statistics Table -->
                                <!-- Seat Statistics Table - FIX: Dynamic display for all seat types -->
                                <div class="mt-3">
                                    <div class="row text-center g-2">
                                        @php
                                            // FIX: Define colors for all seat types dynamically
                                            $seatTypeColors = [
                                                'Ghế thường' => ['bg' => 'bg-success', 'text' => 'text-white'],
                                                'Ghế VIP' => ['bg' => 'bg-warning', 'text' => 'text-dark'],
                                                'Ghế đôi' => ['bg' => 'bg-danger', 'text' => 'text-white'],
                                            ];

                                            $availableSeatTypes = $seatStatusData['seat_types'] ?? [];
                                            $totalTypes = count($availableSeatTypes);

                                            $colClass = match (true) {
                                                $totalTypes <= 2 => 'col-6',
                                                $totalTypes == 3 => 'col-4',
                                                $totalTypes == 4 => 'col-3',
                                                default => 'col-2',
                                            };
                                        @endphp

                                        @if (count($availableSeatTypes) > 0)
                                            @foreach ($availableSeatTypes as $seatType)
                                                @php
                                                    $colorConfig = $seatTypeColors[$seatType['name']] ?? [
                                                        'bg' => 'bg-secondary',
                                                        'text' => 'text-white',
                                                    ];
                                                @endphp
                                                <div class="{{ $colClass }}">
                                                    <div class="card {{ $colorConfig['bg'] }} shadow-sm h-100">
                                                        <div class="card-body py-2">
                                                            <h6 class="{{ $colorConfig['text'] }} mb-1 fw-bold">
                                                                {{ $seatType['name'] }}
                                                            </h6>
                                                            <small class="{{ $colorConfig['text'] }} fw-semibold">
                                                                {{ $seatType['booked'] }}/{{ $seatType['total'] }}
                                                                ({{ $seatType['utilization_rate'] }}%)
                                                            </small>
                                                            @if ($seatType['maintenance'] > 0)
                                                                <div class="mt-1">

                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="col-12">
                                                <div class="card bg-secondary">
                                                    <div class="card-body py-2">
                                                        <p class="text-white mb-0">Không có dữ liệu ghế</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Summary Statistics -->

                                </div>
                            </div>
                        </div>

                        <!-- Room Movies Chart -->
                        <div class="col-6">
                            <div class="bg-dark rounded-3 p-3" style="color: black;">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="text-white mb-0">
                                        <i class="fas fa-film me-2 text-info"></i>Phim Được Xem Nhiều Nhất Của
                                        Phòng {{ $room->name }}
                                    </h5>
                                    <div class="dropdown" wire:ignore>
                                        <button class="btn btn-outline-info btn-sm dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-filter me-1"></i>
                                            <span id="roomMoviesFilterText">
                                                @switch($roomMoviesPeriod)
                                                    @case('3_days')
                                                        3 ngày gần nhất
                                                    @break

                                                    @case('7_days')
                                                        7 ngày gần nhất
                                                    @break

                                                    @case('30_days')
                                                        30 ngày gần nhất
                                                    @break

                                                    @case('1_month')
                                                        1 tháng gần nhất
                                                    @break

                                                    @case('3_months')
                                                        3 tháng gần nhất
                                                    @break

                                                    @case('1_year')
                                                        1 năm gần nhất
                                                    @break

                                                    @case('2_years')
                                                        2 năm gần nhất
                                                    @break

                                                    @default
                                                        7 ngày gần nhất
                                                @endswitch
                                            </span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-dark">
                                            <li>
                                                <h6 class="dropdown-header text-info">Ngày</h6>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeRoomMoviesPeriod('3_days')">3 ngày
                                                    gần nhất</a></li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeRoomMoviesPeriod('7_days')">7 ngày
                                                    gần nhất</a></li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeRoomMoviesPeriod('30_days')">30 ngày
                                                    gần nhất</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <h6 class="dropdown-header text-info">Tháng</h6>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeRoomMoviesPeriod('1_month')">1 tháng
                                                    gần nhất</a></li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeRoomMoviesPeriod('3_months')">3 tháng
                                                    gần nhất</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <h6 class="dropdown-header text-info">Năm</h6>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeRoomMoviesPeriod('1_year')">1 năm gần
                                                    nhất</a></li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeRoomMoviesPeriod('2_years')">2 năm
                                                    gần nhất</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div wire:ignore>
                                    <div id="roomMoviesChart" style="height: 400px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($tabCurrent === 'overview')
                <!-- Overview Tab -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card bg-dark border-light">
                            <div class="card-header bg-gradient text-light"
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5><i class="fas fa-info me-2"></i>Thông tin chi tiết</h5>
                            </div>
                            <div class="card-body bg-dark"
                                style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
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
                                            {{ $referenceDate->format('d/m/Y') }}
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
                    <div class="col-md-6 mt-4 mt-md-0">
                        <div class="card bg-dark border-light">
                            <div class="card-header bg-gradient text-light"
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5><i class="fas fa-chart-bar me-2"></i>Thống kê ghế</h5>
                            </div>
                            <div class="card-body bg-dark"
                                style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                                @php
                                    $seatStats = $room->seats->groupBy('seat_type');
                                @endphp
                                <div class="row text-center">
                                    <div class="col-4">
                                        <div class="card bg-success">
                                            <div class="card-body py-2">
                                                <h4 class="mb-0 text-white fw-bold">
                                                    {{ $seatStats->get('standard', collect())->count() }}</h4>
                                                <small class="text-white">Thường</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="card bg-warning">
                                            <div class="card-body py-2">
                                                <h4 class="mb-0 text-dark fw-bold">
                                                    {{ $seatStats->get('vip', collect())->count() }}</h4>
                                                <small class="text-dark">VIP</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="card bg-danger text-white">
                                            <div class="card-body py-2">
                                                <h4 class="mb-0 fw-bold">
                                                    {{ $seatStats->get('couple', collect())->count() }}</h4>
                                                <small>Đôi</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr class="border-warning">
                                <h6 class="text-warning fw-bold mb-3">Giá vé theo loại ghế:</h6>
                                <ul class="list-unstyled text-light">
                                    @foreach ($seatStats as $type => $seats)
                                        @if ($seats->count() > 0 && $type !== 'disabled')
                                            <li class="d-flex justify-content-between">
                                                <span>
                                                    @switch($type)
                                                        @case('standard')
                                                            Ghế thường
                                                        @break

                                                        @case('vip')
                                                            Ghế VIP
                                                        @break

                                                        @case('couple')
                                                            Ghế đôi
                                                        @break
                                                    @endswitch
                                                </span>
                                                <strong
                                                    class="text-warning">{{ number_format($seats->first()->price, 0, '.', '.') }}đ</strong>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($tabCurrent === 'maintenance')
                <!-- Maintenance Tab -->
                <div class="row">
                    <div class="col-md-8">
                        <div class="card bg-dark border-light">
                            <div class="card-header bg-gradient text-light"
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5><i class="fas fa-tools me-2"></i>Thông tin bảo trì chi tiết</h5>
                            </div>
                            <div class="card-body bg-dark"
                                style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="text-warning mb-3">Lịch sử bảo trì</h6>
                                        <table class="table table-borderless text-light">
                                            <tr>
                                                <td><strong>Bảo trì lần cuối:</strong></td>
                                                <td>
                                                    {{ $referenceDate->format('d/m/Y') }}
                                                    <br><small
                                                        class="text-muted">({{ number_format($daysSinceLastMaintenance, 0, '.', '.') }}
                                                        ngày trước)</small>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Bảo trì tiếp theo:</strong></td>
                                                <td>
                                                    {{ $nextMaintenanceDate->format('d/m/Y') }}
                                                    <br><small
                                                        class="text-muted">{{ $daysOfWeek[$nextMaintenanceDate->format('l')] }}</small>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Chu kỳ bảo trì:</strong></td>
                                                <td>3 tháng ({{ $totalDaysIn3Months }} ngày)</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Trạng thái:</strong></td>
                                                <td>
                                                    <span
                                                        class="badge @if ($maintenanceStatus) bg-danger @else @if ($realTimeCountdown['days'] <= 9) bg-warning @else bg-success @endif @endif">
                                                        @if ($maintenanceStatus) Quá hạn bảo
                                                            trì
                                                        @else
                                                            @if ($realTimeCountdown['days'] <= 9) Sắp đến
                                                                hạn bảo trì
                                                            @else
                                                                Bình thường @endif
                                                        @endif
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Thời gian còn lại:</strong></td>
                                                <td>
                                                    <span
                                                        class="text-{{ $maintenanceStatus === 'overdue' ? 'danger' : 'success' }}">
                                                        {{ number_format($realTimeCountdown['days'], 0, '.', '.') }}
                                                        ngày
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-warning mb-3">Điểm bảo trì</h6>
                                        <div class="progress mb-3" style="height: 25px;">
                                            <div class="progress-bar bg-{{ $maintenanceScore >= 60 ? 'success' : ($maintenanceScore >= 40 ? 'warning' : 'danger') }}"
                                                role="progressbar" style="width: {{ $maintenanceScore }}%">
                                                {{ number_format($maintenanceScore, 0) }}/100
                                            </div>
                                        </div>
                                        <small class="text-muted">
                                            Điểm bảo trì được tính dựa trên thời gian từ lần bảo trì cuối cùng
                                        </small>

                                        <div class="mt-4">
                                            <h6 class="text-warning mb-3">Thống kê thời gian @if ($maintenanceStatus === 'overdue') đã qua
                                                @else
                                                    còn lại @endif
                                            </h6>
                                            <ul class="list-unstyled text-light">
                                                <li><strong>Tổng giờ:</strong>
                                                    {{ number_format(abs($totalSecondsUntilMaintenance) / 3600, 0, '.', '.') }}
                                                    giờ</li>
                                                <li><strong>Tổng phút:</strong>
                                                    {{ number_format(abs($totalSecondsUntilMaintenance) / 60, 0, '.', '.') }}
                                                    phút</li>
                                                <li><strong>Tổng giây:</strong>
                                                    {{ number_format(abs($totalSecondsUntilMaintenance), 0, '.', '.') }}
                                                    giây</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mt-4 mt-md-0">
                        <div class="card bg-dark border-light">
                            <div class="card-header bg-gradient text-light"
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5><i class="fas fa-clock me-2"></i>Đếm ngược thời gian thực</h5>
                            </div>
                            <div class="card-body bg-dark text-center"
                                style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                                @if ($maintenanceStatus === 'overdue')
                                    <div class="text-danger">
                                        <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                                        <h4>QUÁ HẠN</h4>
                                        <div class="maintenance-timer">
                                            <div class="timer-item mb-2">
                                                <h3 class="text-danger" id="realtime-overdue-days">
                                                    {{ number_format($realTimeCountdown['days'], 0, '.', '.') }}
                                                </h3>
                                                <small class="text-light">Ngày</small>
                                            </div>
                                            <div class="timer-item mb-2">
                                                <h4 class="text-warning" id="realtime-overdue-hours">
                                                    {{ number_format($realTimeCountdown['hours'], 0) }}</h4>
                                                <small class="text-light">Giờ</small>
                                            </div>
                                            <div class="timer-item mb-2">
                                                <h5 class="text-info" id="realtime-overdue-minutes">
                                                    {{ number_format($realTimeCountdown['minutes'], 0) }}</h5>
                                                <small class="text-light">Phút</small>
                                            </div>
                                            <div class="timer-item">
                                                <h6 class="text-success" id="realtime-overdue-seconds">
                                                    {{ number_format($realTimeCountdown['seconds'], 0) }}</h6>
                                                <small class="text-light">Giây</small>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="maintenance-timer">
                                        <div class="timer-item mb-3">
                                            <h2 class="text-warning" id="realtime-days">
                                                {{ number_format($realTimeCountdown['days'], 0) }}</h2>
                                            <small class="text-light">Ngày</small>
                                        </div>
                                        <div class="timer-item mb-3">
                                            <h3 class="text-info" id="realtime-hours">
                                                {{ number_format($realTimeCountdown['hours'], 0) }}</h3>
                                            <small class="text-light">Giờ</small>
                                        </div>
                                        <div class="timer-item mb-3">
                                            <h4 class="text-success" id="realtime-minutes">
                                                {{ number_format($realTimeCountdown['minutes'], 0) }}</h4>
                                            <small class="text-light">Phút</small>
                                        </div>
                                        <div class="timer-item">
                                            <h5 class="text-primary" id="realtime-seconds">
                                                {{ number_format($realTimeCountdown['seconds'], 0) }}</h5>
                                            <small class="text-light">Giây</small>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($tabCurrent === 'seats')
                <!-- Seats Tab -->

                <div class="card bg-dark border-light">
                    <div class="card-header bg-gradient text-light"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h5><i class="fas fa-chair me-2"></i>Sơ đồ ghế phòng {{ $room->name }}</h5>
                    </div>
                    <div class="card-body bg-dark"
                        style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                        @if ($room->seats->count() > 0)
                            <div class="st_seatlayout_main_wrapper w-100 mt-2 overflow-x-auto">
                                <div class="container">
                                    <div class="st_seat_lay_heading float_left text-center">
                                        <h3 class="text-warning mb-4">{{ $room->name }}</h3>
                                    </div>
                                    <div class="st_seat_full_container" style="float: none">
                                        <div class="st_seat_lay_economy_wrapper float_left w-100">
                                            <div class="screen text-center mb-4">
                                                <img src="{{ asset('client/assets/images/content/screen.png') }}"
                                                    alt="Màn hình" style="max-width: 100%;">
                                            </div>
                                        </div>

                                        <div class="st_seat_lay_economy_wrapper st_seat_lay_economy_wrapperexicutive float_left"
                                            style="width: auto !important">
                                            @php
                                                $seatsByRow = $room->seats->groupBy('seat_row');
                                            @endphp

                                            @foreach ($seatsByRow as $rowLetter => $rowSeats)
                                                <ul id="row-{{ $rowLetter }}"
                                                    wire:sc-sortable.onmove="updateseatid"
                                                    class="seat-row-layout list-unstyled float_left d-flex flex-nowrap gap-2 justify-content-start align-items-center mb-2"
                                                    data-row="{{ $rowLetter }}"
                                                    wire:key="row-{{ $rowLetter }}">

                                                    @php
                                                        $seatGroup = [];
                                                        $count = 0;
                                                    @endphp

                                                    @foreach ($rowSeats->sortBy('seat_number') as $seat)
                                                        @php
                                                            $seatGroup[] = $seat;
                                                            $count += $seat->seat_type === 'couple' ? 1 : 1;
                                                        @endphp

                                                        @if ($count >= 5)
                                                            @foreach ($seatGroup as $gSeat)
                                                                @php
                                                                    $seatClass = match ($gSeat->seat_type) {
                                                                        'vip' => 'seat-vip',
                                                                        'couple' => 'seat-double',
                                                                        default => 'seat-standard',
                                                                    };
                                                                @endphp
                                                                <li data-seat="{{ $gSeat->seat_type }}"
                                                                    class="seat-item"
                                                                    sc-id="{{ $gSeat->seat_row . $gSeat->seat_number }}">
                                                                    <span class="seat-helper">Chỗ ngồi
                                                                        {{ $gSeat->seat_row . $gSeat->seat_number }}</span>
                                                                    <input type="checkbox"
                                                                        class="seat {{ $seatClass }}"
                                                                        id="{{ $gSeat->seat_row . $gSeat->seat_number }}"
                                                                        data-number="{{ $gSeat->seat_number }}">
                                                                    <label
                                                                        for="{{ $gSeat->seat_row . $gSeat->seat_number }}"
                                                                        class="visually-hidden">
                                                                        Chỗ ngồi
                                                                        {{ $gSeat->seat_row . $gSeat->seat_number }}
                                                                    </label>
                                                                </li>
                                                            @endforeach

                                                            {{-- Chèn lối đi sau nhóm 5 chỗ vật lý --}}
                                                            <li data-seat="aisle" sc-id="aisle">
                                                                <span class="seat-helper">Lối đi</span>
                                                                <div class="aisle"></div>
                                                            </li>

                                                            @php
                                                                $seatGroup = [];
                                                                $count = 0;
                                                            @endphp
                                                        @endif
                                                    @endforeach

                                                    {{-- Render ghế còn lại nếu chưa đủ 5 chỗ cuối hàng --}}
                                                    @foreach ($seatGroup as $gSeat)
                                                        @php
                                                            $seatClass = match ($gSeat->seat_type) {
                                                                'vip' => 'seat-vip',
                                                                'couple' => 'seat-double',
                                                                default => 'seat-standard',
                                                            };
                                                        @endphp
                                                        <li data-seat="{{ $gSeat->seat_type }}" class="seat-item"
                                                            sc-id="{{ $gSeat->seat_row . $gSeat->seat_number }}">
                                                            <span class="seat-helper">Chỗ ngồi
                                                                {{ $gSeat->seat_row . $gSeat->seat_number }}</span>
                                                            <input type="checkbox" class="seat {{ $seatClass }}"
                                                                id="{{ $gSeat->seat_row . $gSeat->seat_number }}"
                                                                data-number="{{ $gSeat->seat_number }}">
                                                            <label for="{{ $gSeat->seat_row . $gSeat->seat_number }}"
                                                                class="visually-hidden">
                                                                Chỗ ngồi
                                                                {{ $gSeat->seat_row . $gSeat->seat_number }}
                                                            </label>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endforeach

                                            <div class="row-aisle" style="height: 20px; width: 100%;"></div>
                                        </div>
                                    </div>

                                    <div class="legend mt-4 mb-3">
                                        <div class="row justify-content-center">
                                            <div class="col-auto">
                                                <div
                                                    class="d-flex align-items-center justify-content-center gap-4 flex-wrap">
                                                    <div class="seat-legend-item d-flex align-items-center">
                                                        <div class="seat seat-standard me-2"></div>
                                                        <span class="text-light fw-bold">Thường
                                                            ({{ number_format($room->seats->where('seat_type', 'standard')->first()->price ?? 0) }}đ)</span>
                                                    </div>
                                                    <div class="seat-legend-item d-flex align-items-center">
                                                        <div class="seat seat-vip me-2"></div>
                                                        <span class="text-light fw-bold">VIP
                                                            ({{ number_format($room->seats->where('seat_type', 'vip')->first()->price ?? 0) }}đ)</span>
                                                    </div>
                                                    <div class="seat-legend-item d-flex align-items-center">
                                                        <div class="seat seat-double me-2"></div>
                                                        <span class="text-light fw-bold">Couple
                                                            ({{ number_format($room->seats->where('seat_type', 'couple')->first()->price ?? 0) }}đ)</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
            @elseif($tabCurrent === 'showtimes')
                <!-- Showtimes Tab -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card bg-dark border-light">
                            <div class="card-header bg-gradient text-light"
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5><i class="fas fa-history me-2"></i>Suất chiếu gần đây</h5>
                            </div>
                            <div class="card-body bg-dark"
                                style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                                @if ($recentShowtimes->count() > 0)
                                    <div class="list-group">
                                        @foreach ($recentShowtimes as $showtime)
                                            <div class="list-group-item bg-dark text-light border-warning">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="mb-1 fw-bold">
                                                            {{ $showtime->movie?->title ?? 'N/A' }}</h6>
                                                        <small class="text-warning">
                                                            {{ $showtime->start_time->format('d/m/Y H:i') }}
                                                        </small>
                                                    </div>
                                                    <span
                                                        class="badge bg-{{ $showtime->status === 'active' ? 'success' : ($showtime->status === 'canceled' ? 'danger' : 'secondary') }}">
                                                        {{ $showtime->status === 'active' ? 'Đang chiếu' : ($showtime->status === 'canceled' ? 'Đã bị hủy' : 'Đã kết thúc') }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="mt-3">
                                        {{ $recentShowtimes->links() }}
                                    </div>
                                @else
                                    <p class="text-light">Chưa có suất chiếu nào.</p>
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
                                @if ($upcomingShowtimes->count() > 0)
                                    <div class="list-group">
                                        @foreach ($upcomingShowtimes as $showtime)
                                            <div class="list-group-item bg-dark text-light border-warning">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h6 class="mb-1 text-light fw-bold">
                                                        {{ $showtime->movie?->title ?? 'N/A' }}</h6>
                                                    <small class="text-muted">
                                                        {{ $showtime->start_time->format('d/m/Y H:i') }} -
                                                        {{ $showtime->end_time->format('H:i') }}
                                                    </small>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="mt-3">
                                        {{ $upcomingShowtimes->links() }}
                                    </div>
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
</div>
@script
    <script>
        globalThis.chartInstances = {};

        // Function to update filter text
        function updateFilterText(elementId, text) {
            const element = document.getElementById(elementId);
            if (element) {
                element.textContent = text;
            }
        }

        Livewire.on('updateData', function([$occupancyData, $seatStatusData, $roomStatsData, $roomMoviesData,
        $filterTexts]) {
            // Cập nhật filter text từ server
            if ($filterTexts) {
                if ($filterTexts.roomStatsFilterText) {
                    updateFilterText('roomStatsFilterText', $filterTexts.roomStatsFilterText);
                }
                if ($filterTexts.occupancyFilterText) {
                    updateFilterText('occupancyFilterText', $filterTexts.occupancyFilterText);
                }
                if ($filterTexts.seatStatusFilterText) {
                    updateFilterText('seatStatusFilterText', $filterTexts.seatStatusFilterText);
                }
                if ($filterTexts.roomMoviesFilterText) {
                    updateFilterText('roomMoviesFilterText', $filterTexts.roomMoviesFilterText);
                }
            }

            const occupancyRate = $occupancyData['occupancy_rate'];
            const seatStatusData = $seatStatusData['chart_data'];
            const seatTypeColors = {
                'Ghế thường': '#28a745',
                'Ghế VIP': '#ffc107',
                'Ghế đôi': '#dc3545',
            };

            const enhancedSeries = seatStatusData.series.map((serie, statusIndex) => {
                const statusName = serie.name;
                const statusOpacity = statusIndex === 0 ? 0.4 : (statusIndex === 1 ? 0.8 : 0.9);
                const statusPattern = statusIndex === 2 ? 'diagonalLines' : 'solid';

                return {
                    name: statusName,
                    data: serie.data.map((value, categoryIndex) => {
                        const seatType = seatStatusData.categories[categoryIndex];
                        const baseColor = seatTypeColors[seatType] ||
                            '#6c757d'; // Fallback to gray

                        return {
                            x: seatType,
                            y: value,
                            fillColor: baseColor,
                            meta: {
                                seatType: seatType,
                                status: statusName,
                                count: value,
                                baseColor: baseColor
                            }
                        };
                    }),
                    color: undefined
                };
            });

            const optionsAllRoomsStats = {
                chart: {
                    type: 'line',
                    height: 400,
                    background: 'transparent',
                    toolbar: {
                        show: true
                    },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800
                    }
                },
                series: [{
                        name: 'Vé đã bán',
                        type: 'column',
                        data: $roomStatsData['tickets']
                    },
                    {
                        name: 'Doanh thu (VNĐ)',
                        type: 'line',
                        yAxisIndex: 1,
                        data: $roomStatsData['revenue']
                    }
                ],
                xaxis: {
                    categories: $roomStatsData['labels'],
                    labels: {
                        style: {
                            colors: '#ffffff',
                            fontSize: '12px'
                        }
                    },
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    }
                },
                yaxis: [{
                        title: {
                            text: 'Số vé',
                            style: {
                                color: '#ffffff'
                            }
                        },
                        labels: {
                            style: {
                                colors: '#ffffff',
                                fontSize: '12px'
                            }
                        }
                    },
                    {
                        opposite: true,
                        title: {
                            text: 'Doanh thu (VNĐ)',
                            style: {
                                color: '#ffffff'
                            }
                        },
                        labels: {
                            style: {
                                colors: '#ffffff',
                                fontSize: '12px'
                            },
                            formatter: function(value) {
                                return new Intl.NumberFormat('vi-VN').format(value);
                            }
                        }
                    }
                ],
                colors: ['#4285F4', '#FBBC04'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        type: 'vertical',
                        shadeIntensity: 0.3,
                        gradientToColors: ['#1976D2', '#FF6B35'],
                        inverseColors: false,
                        opacityFrom: 0.9,
                        opacityTo: 0.6,
                        stops: [0, 100]
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '60%',
                        endingShape: 'rounded',
                        borderRadius: 8
                    }
                },
                stroke: {
                    width: [0, 4],
                    curve: 'smooth'
                },
                grid: {
                    show: true,
                    borderColor: '#2d3748',
                    strokeDashArray: 1
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'left',
                    labels: {
                        colors: '#ffffff'
                    }
                },
                tooltip: {
                    theme: 'dark',
                    y: [{
                            formatter: function(value) {
                                return value + ' vé';
                            }
                        },
                        {
                            formatter: function(value) {
                                return new Intl.NumberFormat('vi-VN', {
                                    style: 'currency',
                                    currency: 'VND'
                                }).format(value);
                            }
                        }
                    ]
                }
            }

            const optionsOccupancyRate = {
                chart: {
                    type: 'radialBar',
                    height: 400,
                    background: 'transparent',
                    toolbar: {
                        show: true
                    }
                },
                plotOptions: {
                    radialBar: {
                        startAngle: -135,
                        endAngle: 135,
                        hollow: {
                            margin: 0,
                            size: '70%',
                            background: 'transparent',
                            image: undefined,
                            position: 'front',
                            dropShadow: {
                                enabled: true,
                                top: 3,
                                left: 0,
                                blur: 4,
                                opacity: 0.24
                            }
                        },
                        track: {
                            background: '#2d3748',
                            strokeWidth: '67%',
                            margin: 0,
                            dropShadow: {
                                enabled: true,
                                top: -3,
                                left: 0,
                                blur: 4,
                                opacity: 0.35
                            }
                        },
                        dataLabels: {
                            show: true,
                            name: {
                                offsetY: -10,
                                show: true,
                                color: '#ffffff',
                                fontSize: '17px'
                            },
                            value: {
                                formatter: function(val) {
                                    return parseInt(val) + '%';
                                },
                                color: '#ffffff',
                                fontSize: '36px',
                                show: true,
                            }
                        }
                    }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        type: 'horizontal',
                        shadeIntensity: 0.5,
                        gradientToColors: ['#34A853'],
                        inverseColors: false,
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 100]
                    }
                },
                series: [occupancyRate],
                labels: ['Tỷ lệ lấp đầy'],
                colors: ['#34A853']
            }

            const optionsSeatStatus = {
                chart: {
                    type: 'bar',
                    height: 350,
                    background: 'transparent',
                    toolbar: {
                        show: true
                    },
                    stacked: true,
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '70%',
                        endingShape: 'rounded',
                        borderRadius: 4,
                        dataLabels: {
                            total: {
                                enabled: true,
                                offsetX: 0,
                                offsetY: -5,
                                style: {
                                    fontSize: '12px',
                                    fontWeight: 600,
                                    color: '#ffffff'
                                },
                                formatter: function(val, opts) {
                                    const categoryIndex = opts.dataPointIndex;
                                    const total = seatStatusData.series.reduce((sum, s) => sum +
                                        s.data[categoryIndex], 0);
                                    return total > 0 ? `${total} ghế` : '';
                                }
                            }
                        }
                    }
                },
                series: enhancedSeries,
                xaxis: {
                    categories: seatStatusData.categories || [],
                    labels: {
                        style: {
                            colors: '#ffffff',
                            fontSize: '12px',
                            fontWeight: 600
                        },
                        rotate: seatStatusData.categories.length > 3 ? -45 : 0,
                        maxHeight: 100
                    },
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    }
                },
                yaxis: {
                    title: {
                        text: 'Số lượng ghế',
                        style: {
                            color: '#ffffff',
                            fontSize: '14px'
                        }
                    },
                    labels: {
                        style: {
                            colors: '#ffffff',
                            fontSize: '12px'
                        },
                        formatter: function(value) {
                            return Math.floor(value);
                        }
                    }
                },
                colors: ['rgba(255,255,255,0.4)', 'rgba(255,255,255,0.8)',
                    'rgba(255,255,255,0.9)'
                ],
                fill: {
                    opacity: [0.4, 0.8, 0.9],
                    type: ['solid', 'solid', 'pattern'],
                    pattern: {
                        style: ['', '', 'diagonalLines'],
                        width: 6,
                        height: 6,
                        strokeWidth: 2,
                    }
                },
                stroke: {
                    width: [1, 1, 2],
                    colors: ['rgba(255,255,255,0.3)', 'rgba(255,255,255,0.5)', '#ffffff']
                },
                grid: {
                    show: true,
                    borderColor: '#2d3748',
                    strokeDashArray: 1,
                    xaxis: {
                        lines: {
                            show: false
                        }
                    },
                    yaxis: {
                        lines: {
                            show: true
                        }
                    }
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'center',
                    labels: {
                        colors: '#ffffff',
                        fontSize: '12px'
                    },
                    markers: {
                        width: 12,
                        height: 12,
                        radius: 0,
                        fillColors: ['rgba(128,128,128,0.4)', 'rgba(128,128,128,0.8)',
                            'rgba(128,128,128,0.9)'
                        ]
                    }
                },
                tooltip: {
                    theme: 'dark',
                    shared: true,
                    intersect: false,
                    style: {
                        fontSize: '12px'
                    },
                    custom: function({
                        series,
                        seriesIndex,
                        dataPointIndex,
                        w
                    }) {
                        const category = seatStatusData.categories[dataPointIndex];
                        const baseColor = seatTypeColors[category] || '#6c757d';
                        const total = series.reduce((sum, s) => sum + s[dataPointIndex], 0);

                        if (total === 0) {
                            return `
                                <div style="padding: 10px; background: rgba(0,0,0,0.9); border-radius: 6px; border-left: 4px solid ${baseColor};">
                                    <div style="font-weight: bold; color: ${baseColor}; margin-bottom: 8px;">
                                        <i class="fas fa-chair"></i> ${category}
                                    </div>
                                    <div style="color: #ccc; font-size: 11px;">Không có dữ liệu</div>
                                </div>
                            `;
                        }

                        let tooltipContent = `
                            <div style="padding: 12px; background: rgba(0,0,0,0.95); border-radius: 8px; border-left: 4px solid ${baseColor}; min-width: 200px;">
                                <div style="font-weight: bold; color: ${baseColor}; margin-bottom: 10px; font-size: 13px;">
                                    <i class="fas fa-chair"></i> ${category}
                                </div>
                                <div style="font-size: 11px; color: #aaa; margin-bottom: 8px; border-bottom: 1px solid #333; padding-bottom: 4px;">
                                    <strong style="color: white;">Tổng: ${total} ghế</strong>
                                </div>
                        `;

                        // Status colors for legend
                        const statusColors = [{
                                color: baseColor,
                                opacity: '0.4',
                                name: 'Còn trống'
                            },
                            {
                                color: baseColor,
                                opacity: '0.8',
                                name: 'Đã đặt'
                            },
                            {
                                color: baseColor,
                                opacity: '1',
                                name: 'Bảo trì',
                                pattern: true
                            }
                        ];

                        series.forEach((seriesData, idx) => {
                            const value = seriesData[dataPointIndex];
                            if (value > 0) {
                                const seriesName = w.config.series[idx].name;
                                const percentage = Math.round((value / total) * 100);
                                const statusColor = statusColors[idx];

                                const patternText = statusColor.pattern ? ' (////)' :
                                    '';
                                const colorStyle =
                                    `background: ${statusColor.color}; opacity: ${statusColor.opacity};`;
                                tooltipContent += `
                                    <div style="margin: 4px 0; display: flex; justify-content: space-between; align-items: center;">
                                        <div style="display: flex; align-items: center;">
                                            <div style="width: 12px; height: 12px; ${colorStyle} margin-right: 8px; border-radius: 2px; ${statusColor.pattern ? 'border: 1px dashed white;' : ''}"></div>
                                            <span style="color: #ddd; font-size: 11px;">${seriesName}${patternText}:</span>
                                        </div>
                                        <strong style="color: white; font-size: 12px;">${value} ghế (${percentage}%)</strong>
                                    </div>`;
                            }
                        });

                        tooltipContent += `
                            <div style="margin-top: 8px; padding-top: 6px; border-top: 1px solid #333; font-size: 10px; color: #888;">
                                💡 Màu cột thể hiện loại ghế, độ đậm thể hiện trạng thái
                            </div>
                            </div>`;
                        return tooltipContent;
                    }
                },
                dataLabels: {
                    enabled: true,
                    style: {
                        colors: ['#ffffff'],
                        fontSize: '10px',
                        fontWeight: 'bold',
                        textStroke: '1px rgba(0,0,0,0.5)'
                    },
                    formatter: function(val, opts) {
                        return val > 0 ? val : '';
                    },
                    dropShadow: {
                        enabled: true,
                        top: 1,
                        left: 1,
                        blur: 1,
                        opacity: 0.8
                    }
                },
                // Enhanced responsive design
                responsive: [{
                    breakpoint: 768,
                    options: {
                        plotOptions: {
                            bar: {
                                columnWidth: '90%'
                            }
                        },
                        xaxis: {
                            labels: {
                                rotate: -45,
                                style: {
                                    fontSize: '10px'
                                }
                            }
                        },
                        dataLabels: {
                            style: {
                                fontSize: '9px'
                            }
                        }
                    }
                }]
            }

            const optionsRoomMovies = {
                chart: {
                    type: 'bar',
                    height: 400,
                    background: 'transparent',
                    toolbar: {
                        show: true
                    },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: true,
                        borderRadius: 6,
                        dataLabels: {
                            position: 'top'
                        }
                    }
                },
                series: [{
                    name: 'Số vé bán',
                    data: $roomMoviesData['tickets']
                }],
                xaxis: {
                    categories: $roomMoviesData['labels'],
                    labels: {
                        style: {
                            colors: '#ffffff',
                            fontSize: '12px'
                        }
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: '#ffffff',
                            fontSize: '11px'
                        },
                        maxWidth: 150
                    }
                },
                colors: ['#17A2B8'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        type: 'horizontal',
                        shadeIntensity: 0.3,
                        gradientToColors: ['#20C997'],
                        inverseColors: false,
                        opacityFrom: 0.9,
                        opacityTo: 0.6,
                        stops: [0, 100]
                    }
                },
                grid: {
                    show: true,
                    borderColor: '#2d3748',
                    strokeDashArray: 1
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'left',
                    labels: {
                        colors: '#ffffff'
                    }
                },
                tooltip: {
                    theme: 'dark',
                    y: {
                        formatter: function(value) {
                            return value + ' vé';
                        }
                    }
                },
                dataLabels: {
                    enabled: true,
                    formatter: function(val) {
                        return val + ' vé';
                    },
                    style: {
                        colors: ['#ffffff']
                    }
                }
            }

            window.renderAllCharts = function() {
                if (Object.values(chartInstances).length > 0) {
                    chartInstances.allRoomsStatsChart.updateOptions(optionsAllRoomsStats);
                    chartInstances.occupancyRateChart.updateOptions(optionsOccupancyRate);
                    chartInstances.seatStatusChart.updateOptions(optionsSeatStatus);
                    chartInstances.roomMoviesChart.updateOptions(optionsRoomMovies);
                } else {
                    const allRoomsStatsEl = document.querySelector("#allRoomsStatsChart");
                    const occupancyRateEl = document.querySelector("#occupancyChart");
                    const seatStatusEl = document.querySelector("#seatStatusChart");
                    const roomMoviesEl = document.querySelector("#roomMoviesChart");

                    if (allRoomsStatsEl) chartInstances.allRoomsStatsChart = createScChart(allRoomsStatsEl,
                        optionsAllRoomsStats);
                    if (occupancyRateEl) chartInstances.occupancyRateChart = createScChart(occupancyRateEl,
                        optionsOccupancyRate);
                    if (seatStatusEl) chartInstances.seatStatusChart = createScChart(seatStatusEl,
                        optionsSeatStatus);
                    if (roomMoviesEl) chartInstances.roomMoviesChart = createScChart(roomMoviesEl,
                        optionsRoomMovies);
                }
            }

            renderAllCharts();
        });
    </script>
@endscript
