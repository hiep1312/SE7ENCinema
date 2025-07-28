<div class="scRender">
    <div class="container-lg mb-4" wire:poll.1s="calculateMaintenanceInfo">
        <!-- Thêm button test ngay sau header -->
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Chi tiết phòng chiếu: {{ $room->name }}</h2>
            <div>
                <!-- Button test -->
                <button wire:click="testTabChange" class="btn btn-sm btn-info me-2">Test Tab</button>
                <span class="text-light me-2">Current Tab: {{ $tabCurrent }}</span>

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
        <div x-data="{ activeTab: @entangle('tabCurrent') }">
            <ul class="nav nav-tabs bg-dark" role="tablist">
                <li class="nav-item">
                    <button type="button" class="nav-link"
                        :class="activeTab === 'analytics' ? 'active bg-light text-dark' : 'text-light'"
                        @click="activeTab = 'analytics'; $wire.changeTab('analytics')"
                        style="border-top-right-radius: 0;">
                        <i class="fas fa-chart-bar me-1"></i>Phân tích
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link"
                        :class="activeTab === 'overview' ? 'active bg-light text-dark' : 'text-light'"
                        @click="activeTab = 'overview'; $wire.changeTab('overview')"
                        style="border-top-left-radius: 0; border-top-right-radius: 0;">
                        <i class="fas fa-info-circle me-1"></i>Tổng quan
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link"
                        :class="activeTab === 'maintenance' ? 'active bg-light text-dark' : 'text-light'"
                        @click="activeTab = 'maintenance'; $wire.changeTab('maintenance')"
                        style="border-top-left-radius: 0; border-top-right-radius: 0;">
                        <i class="fas fa-tools me-1"></i>Bảo trì
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link"
                        :class="activeTab === 'seats' ? 'active bg-light text-dark' : 'text-light'"
                        @click="activeTab = 'seats'; $wire.changeTab('seats')"
                        style="border-top-left-radius: 0; border-top-right-radius: 0;">
                        <i class="fas fa-chair me-1"></i>Sơ đồ ghế
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link"
                        :class="activeTab === 'showtimes' ? 'active bg-light text-dark' : 'text-light'"
                        @click="activeTab = 'showtimes'; $wire.changeTab('showtimes')"
                        style="border-top-left-radius: 0;">
                        <i class="fas fa-calendar me-1"></i>Suất chiếu
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content mt-3">
<!-- Analytics Tab Content -->
<div x-show="activeTab === 'analytics'">
    <div class="container-fluid" wire:ignore>
        <div class="row g-4">
            <!-- Combined Room Statistics Chart -->
            <div class="col-lg-8">
                <div class="bg-dark rounded-3 p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="text-white mb-0">
                            <i class="fas fa-chart-bar me-2 text-primary"></i>Thống Kê Tất Cả Phòng Chiếu
                        </h5>
                        {{-- Room Stats Chart Dynamic Filter --}}
                        <div class="btn-group mb-2" role="group">
                            <select wire:model="roomStatsYear" wire:change="changeRoomStatsYear($event.target.value)"
                                class="form-select form-select-sm w-auto border-primary text-primary fw-bold"
                                style="background: #23272b; border-width: 2px; border-radius: 0.5rem 0 0 0.5rem; cursor:pointer; padding: 2px 5px;">
                                @foreach($availableYears as $year)
                                    <option value="{{ $year }}" class="bg-dark text-primary">Năm {{ $year }}</option>
                                @endforeach
                            </select>
                            <select wire:model="roomStatsMonth" wire:change="changeRoomStatsMonth($event.target.value)"
                                class="form-select form-select-sm w-auto border-primary text-primary fw-bold"
                                style="background: #23272b; border-width: 2px; border-radius: 0; cursor:pointer; padding: 2px 5px;">
                                @foreach($availableMonths as $month)
                                    <option value="{{ $month }}" class="bg-dark text-primary">Tháng {{ $month }}</option>
                                @endforeach
                            </select>
                            <select wire:model="roomStatsDay" wire:change="changeRoomStatsDay($event.target.value)"
                                class="form-select form-select-sm w-auto border-primary text-primary fw-bold"
                                style="background: #23272b; border-width: 2px; border-radius: 0 0.5rem 0.5rem 0; cursor:pointer; padding: 2px 5px;">
                                @foreach($availableDays as $day)
                                    <option value="{{ $day }}" class="bg-dark text-primary">Ngày {{ $day }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div id="allRoomsStatsChart" style="height: 400px;"></div>
                </div>
            </div>

            <!-- Occupancy Rate Chart -->
            <div class="col-lg-4">
                <div class="bg-dark rounded-3 p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="text-white mb-0">
                            <i class="fas fa-chart-pie me-2 text-success"></i>Tỷ Lệ Lấp Đầy (%)
                        </h5>
                        {{-- Occupancy Chart Dynamic Filter --}}
                        <div class="btn-group mb-2" role="group">
                            <select wire:model="occupancyYear" wire:change="changeOccupancyYear($event.target.value)"
                                class="form-select form-select-sm w-auto border-success text-success fw-bold"
                                style="background: #23272b; border-width: 2px; border-radius: 0.5rem 0 0 0.5rem; cursor:pointer; padding: 2px 5px;">
                                @foreach($availableYears as $year)
                                    <option value="{{ $year }}" class="bg-dark text-success">Năm {{ $year }}</option>
                                @endforeach
                            </select>
                            <select wire:model="occupancyMonth" wire:change="changeOccupancyMonth($event.target.value)"
                                class="form-select form-select-sm w-auto border-success text-success fw-bold"
                                style="background: #23272b; border-width: 2px; border-radius: 0; cursor:pointer; padding: 2px 5px;">
                                @foreach($availableMonths as $month)
                                    <option value="{{ $month }}" class="bg-dark text-success">Tháng {{ $month }}</option>
                                @endforeach
                            </select>
                            <select wire:model="occupancyDay" wire:change="changeOccupancyDay($event.target.value)"
                                class="form-select form-select-sm w-auto border-success text-success fw-bold"
                                style="background: #23272b; border-width: 2px; border-radius: 0 0.5rem 0.5rem 0; cursor:pointer; padding: 2px 5px;">
                                @foreach($availableDays as $day)
                                    <option value="{{ $day }}" class="bg-dark text-success">Ngày {{ $day }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div id="occupancyChart" style="height: 400px;"></div>
                </div>
            </div>

            <!-- Seat Status Chart -->
            <div class="col-lg-6">
                <div class="bg-dark rounded-3 p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="text-white mb-0">
                            <i class="fas fa-chair me-2 text-warning"></i>Tình Trạng Ghế Phòng {{ $room->name }}
                        </h5>
                        {{-- Seat Status Chart Dynamic Filter --}}
                        <div class="btn-group mb-2" role="group">
                            <select wire:model="seatStatusYear" wire:change="changeSeatStatusYear($event.target.value)"
                                class="form-select form-select-sm w-auto border-warning text-warning fw-bold"
                                style="background: #23272b; border-width: 2px; border-radius: 0.5rem 0 0 0.5rem; cursor:pointer; padding: 2px 5px;">
                                @foreach($availableYears as $year)
                                    <option value="{{ $year }}" class="bg-dark text-warning">Năm {{ $year }}</option>
                                @endforeach
                            </select>
                            <select wire:model="seatStatusMonth" wire:change="changeSeatStatusMonth($event.target.value)"
                                class="form-select form-select-sm w-auto border-warning text-warning fw-bold"
                                style="background: #23272b; border-width: 2px; border-radius: 0; cursor:pointer; padding: 2px 5px;">
                                @foreach($availableMonths as $month)
                                    <option value="{{ $month }}" class="bg-dark text-warning">Tháng {{ $month }}</option>
                                @endforeach
                            </select>
                            <select wire:model="seatStatusDay" wire:change="changeSeatStatusDay($event.target.value)"
                                class="form-select form-select-sm w-auto border-warning text-warning fw-bold"
                                style="background: #23272b; border-width: 2px; border-radius: 0 0.5rem 0.5rem 0; cursor:pointer; padding: 2px 5px;">
                                @foreach($availableDays as $day)
                                    <option value="{{ $day }}" class="bg-dark text-warning">Ngày {{ $day }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div id="seatStatusChart" style="height: 400px;"></div>

                    <!-- Seat Statistics Table -->
                    <div class="mt-3">
                        <div class="row text-center">
                            @foreach($seatStatusData['seat_types'] ?? [] as $seatType)
                            <div class="col-4 mb-2">
                                <div class="card bg-secondary">
                                    <div class="card-body py-2">
                                        <h6 class="text-white mb-1">{{ $seatType['name'] }}</h6>
                                        <small class="text-light">
                                            {{ $seatType['booked'] }}/{{ $seatType['total'] }}
                                            ({{ $seatType['total'] > 0 ? round(($seatType['booked']/$seatType['total'])*100, 1) : 0 }}%)
                                        </small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Movies Chart -->
            <div class="col-lg-6">
                <div class="bg-dark rounded-3 p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="text-white mb-0">
                            <i class="fas fa-film me-2 text-info"></i>Top Phim Được Xem Nhiều Nhất
                        </h5>
                        {{-- Top Movies Chart Dynamic Filter --}}
                        <div class="btn-group mb-2" role="group">
                            <select wire:model="topMoviesYear" wire:change="changeTopMoviesYear($event.target.value)"
                                class="form-select form-select-sm w-auto border-info text-info fw-bold"
                                style="background: #23272b; border-width: 2px; border-radius: 0.5rem 0 0 0.5rem; cursor:pointer; padding: 2px 5px;">
                                @foreach($availableYears as $year)
                                    <option value="{{ $year }}" class="bg-dark text-info">Năm {{ $year }}</option>
                                @endforeach
                            </select>
                            <select wire:model="topMoviesMonth" wire:change="changeTopMoviesMonth($event.target.value)"
                                class="form-select form-select-sm w-auto border-info text-info fw-bold"
                                style="background: #23272b; border-width: 2px; border-radius: 0; cursor:pointer; padding: 2px 5px;">
                                @foreach($availableMonths as $month)
                                    <option value="{{ $month }}" class="bg-dark text-info">Tháng {{ $month }}</option>
                                @endforeach
                            </select>
                            <select wire:model="topMoviesDay" wire:change="changeTopMoviesDay($event.target.value)"
                                class="form-select form-select-sm w-auto border-info text-info fw-bold"
                                style="background: #23272b; border-width: 2px; border-radius: 0 0.5rem 0.5rem 0; cursor:pointer; padding: 2px 5px;">
                                @foreach($availableDays as $day)
                                    <option value="{{ $day }}" class="bg-dark text-info">Ngày {{ $day }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div id="topMoviesChart" style="height: 400px;"></div>
                </div>
            </div>
        </div>
    </div>

    @script
    <script>
        (function() {
            // Global variable to store chart instances
            window.roomChartInstances = {};

            // Function to render all charts
            function renderAllRoomCharts() {
                if (typeof window.createScChart !== 'undefined') {
                    // Clear existing chart instances
                    Object.values(window.roomChartInstances).forEach(chart => {
                        if (chart && typeof chart.destroy === 'function') {
                            chart.destroy();
                        }
                    });
                    window.roomChartInstances = {};

                    // 1. Combined All Rooms Statistics - Mixed Chart (Column + Line)
                    if (document.querySelector('#allRoomsStatsChart')) {
                        window.roomChartInstances.allRoomsStatsChart = createScChart(document.querySelector('#allRoomsStatsChart'), {
                            chart: {
                                type: 'line',
                                height: 400,
                                background: 'transparent',
                                toolbar: { show: false },
                                animations: {
                                    enabled: true,
                                    easing: 'easeinout',
                                    speed: 800
                                }
                            },
                            series: [
                                {
                                    name: 'Vé đã bán',
                                    type: 'column',
                                    data: @json($allRoomsStatsData['tickets'] ?? [])
                                },
                                {
                                    name: 'Doanh thu (VNĐ)',
                                    type: 'line',
                                    yAxisIndex: 1,
                                    data: @json($allRoomsStatsData['revenue'] ?? [])
                                }
                            ],
                            xaxis: {
                                categories: @json($allRoomsStatsData['labels'] ?? []),
                                labels: {
                                    style: { colors: '#ffffff', fontSize: '12px' }
                                },
                                axisBorder: { show: false },
                                axisTicks: { show: false }
                            },
                            yaxis: [
                                {
                                    title: {
                                        text: 'Số vé',
                                        style: { color: '#ffffff' }
                                    },
                                    labels: {
                                        style: { colors: '#ffffff', fontSize: '12px' }
                                    }
                                },
                                {
                                    opposite: true,
                                    title: {
                                        text: 'Doanh thu (VNĐ)',
                                        style: { color: '#ffffff' }
                                    },
                                    labels: {
                                        style: { colors: '#ffffff', fontSize: '12px' },
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
                                labels: { colors: '#ffffff' }
                            },
                            tooltip: {
                                theme: 'dark',
                                y: [
                                    {
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
                        });
                    }

                    // 2. Occupancy Rate - Radial Bar Chart
                    if (document.querySelector('#occupancyChart')) {
                        const occupancyRate = @json($occupancyRateData['occupancy_rate'] ?? 0);
                        window.roomChartInstances.occupancyChart = createScChart(document.querySelector('#occupancyChart'), {
                            chart: {
                                type: 'radialBar',
                                height: 400,
                                background: 'transparent',
                                toolbar: { show: false }
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
                        });
                    }

                    // 3. Seat Status Chart - Donut Chart
                    if (document.querySelector('#seatStatusChart')) {
                        const seatStatusData = @json($seatStatusData['chart_data'] ?? ['labels' => [], 'data' => []]);
                        window.roomChartInstances.seatStatusChart = createScChart(document.querySelector('#seatStatusChart'), {
                            chart: {
                                type: 'donut',
                                height: 400,
                                background: 'transparent',
                                toolbar: { show: false },
                                animations: {
                                    enabled: true,
                                    easing: 'easeinout',
                                    speed: 800
                                }
                            },
                            series: seatStatusData.data,
                            labels: seatStatusData.labels,
                            colors: seatStatusData.colors || ['#dc3545', '#28a745'],
                            plotOptions: {
                                pie: {
                                    startAngle: -90,
                                    endAngle: 270,
                                    donut: {
                                        size: '75%',
                                        labels: {
                                            show: true,
                                            name: {
                                                show: true,
                                                fontSize: '22px',
                                                fontFamily: 'Helvetica, Arial, sans-serif',
                                                fontWeight: 600,
                                                color: '#ffffff',
                                                offsetY: -10,
                                                formatter: function (val) {
                                                    return val
                                                }
                                            },
                                            value: {
                                                show: true,
                                                fontSize: '16px',
                                                fontFamily: 'Helvetica, Arial, sans-serif',
                                                fontWeight: 400,
                                                color: '#ffffff',
                                                offsetY: 16,
                                                formatter: function (val) {
                                                    return val + ' ghế'
                                                }
                                            },
                                            total: {
                                                show: true,
                                                showAlways: false,
                                                label: 'Tổng ghế',
                                                fontSize: '22px',
                                                fontFamily: 'Helvetica, Arial, sans-serif',
                                                fontWeight: 600,
                                                color: '#ffffff',
                                                formatter: function (w) {
                                                    return w.globals.seriesTotals.reduce((a, b) => {
                                                        return a + b
                                                    }, 0) + ' ghế'
                                                }
                                            }
                                        }
                                    }
                                }
                            },
                            dataLabels: {
                                enabled: true,
                                formatter: function (val, opts) {
                                    return Math.round(val) + '%'
                                },
                                style: {
                                    fontSize: '14px',
                                    fontFamily: 'Helvetica, Arial, sans-serif',
                                    fontWeight: 'bold',
                                    colors: ['#ffffff']
                                }
                            },
                            legend: {
                                position: 'bottom',
                                horizontalAlign: 'center',
                                labels: {
                                    colors: '#ffffff',
                                    useSeriesColors: false
                                },
                                markers: {
                                    width: 12,
                                    height: 12,
                                }
                            },
                            tooltip: {
                                theme: 'dark',
                                y: {
                                    formatter: function(value) {
                                        return value + ' ghế';
                                    }
                                }
                            },
                            stroke: {
                                show: false
                            }
                        });
                    }

                    // 4. Top Movies - Horizontal Bar Chart
                    if (document.querySelector('#topMoviesChart')) {
                        window.roomChartInstances.topMoviesChart = createScChart(document.querySelector('#topMoviesChart'), {
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
                                data: @json($topMoviesData['tickets'] ?? [])
                            }],
                            xaxis: {
                                categories: @json($topMoviesData['labels'] ?? []),
                                labels: {
                                    style: { colors: '#ffffff', fontSize: '12px' }
                                }
                            },
                            yaxis: {
                                labels: {
                                    style: { colors: '#ffffff', fontSize: '11px' },
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
                                labels: { colors: '#ffffff' }
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
                        });
                    }
                }
            }

            // Make renderAllRoomCharts available globally
            window.renderAllRoomCharts = renderAllRoomCharts;

            // Initialize charts when component is ready
            document.addEventListener('livewire:init', () => {
                if (document.querySelector('#allRoomsStatsChart')) {
                    renderAllRoomCharts();
                }
            });

            // Re-initialize charts when tab changes or data updates
            document.addEventListener('livewire:updated', () => {
                // Small delay to ensure DOM is updated
                setTimeout(() => {
                    if (document.querySelector('#allRoomsStatsChart') && typeof window.createScChart !== 'undefined') {
                        renderAllRoomCharts();
                    }
                }, 100);
            });

            // Listen for custom tab change event
            document.addEventListener('tabChanged', function(e) {
                if (e.detail && e.detail[0] === 'analytics') {
                    setTimeout(() => {
                        renderAllRoomCharts();
                    }, 150);
                }
            });

            // Also initialize when DOM is ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => {
                    if (document.querySelector('#allRoomsStatsChart')) {
                        renderAllRoomCharts();
                    }
                });
            } else {
                if (document.querySelector('#allRoomsStatsChart')) {
                    renderAllRoomCharts();
                }
            }
        })();
    </script>
    @endscript
</div>
            </div>
            <div x-show="activeTab === 'overview'">
                <!-- Overview content -->
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
            </div>
            <!-- Maintenance Tab -->
            <div x-show="activeTab === 'maintenance'">
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
            </div>
            <!-- Seats Tab -->
            <div x-show="activeTab === 'seats'">
                <div class="card bg-dark border-light">
                    <div class="card-header bg-gradient text-light"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h5><i class="fas fa-chair me-2"></i>Sơ đồ ghế phòng {{ $room->name }}</h5>
                    </div>
                    <div class="card-body bg-dark"
                        style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                        @if ($room->seats->count() > 0)
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
                                    @foreach ($seatsByRow as $rowLetter => $rowSeats)
                                        <div class="seat-row mb-3 d-flex justify-content-center align-items-center">
                                            <span
                                                class="row-label me-3 fw-bold fs-5 text-warning">{{ $rowLetter }}</span>
                                            <div class="seats-container">
                                                @foreach ($rowSeats->sortBy('seat_number') as $seat)
                                                    @php
                                                        $seatClass = match ($seat->seat_type) {
                                                            'vip' => 'seat-vip',
                                                            'couple' => 'seat-couple',
                                                            default => 'seat-standard',
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
                                        <span class="text-light fw-bold">
                                            <div class="seat seat-standard d-inline-block me-2"></div>Thường
                                            ({{ number_format($room->seats->where('seat_type', 'standard')->first()->price ?? 0) }}đ)
                                        </span>
                                        <span class="text-light fw-bold">
                                            <div class="seat seat-vip d-inline-block me-2"></div>VIP
                                            ({{ number_format($room->seats->where('seat_type', 'vip')->first()->price ?? 0) }}đ)
                                        </span>
                                        <span class="text-light fw-bold">
                                            <div class="seat seat-couple d-inline-block me-2"></div>Couple
                                            ({{ number_format($room->seats->where('seat_type', 'couple')->first()->price ?? 0) }}đ)
                                        </span>
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
            </div>
            <!-- Showtimes Tab -->
            <div x-show="activeTab === 'showtimes'">
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
                                                <span
                                                    class="badge bg-warning text-dark fw-bold">{{ number_format($showtime->price, 0, '.', '.') }}đ</span>
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
            </div>
        </div>
    </div>
</div>
</div>
