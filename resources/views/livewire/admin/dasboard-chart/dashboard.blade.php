<div class="scRender" wire:poll.1s>
    <div class="content-wrapper">
        <div class="row">
            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                <div class="d-flex align-items-center align-self-start">
                                    <h3 class="mb-0">{{ number_format($totalMoviesShowing) }}</h3>
                                    <p
                                        class="{{ $moviesShowingGrowthPercent >= 0 ? 'text-success' : 'text-danger' }} ml-2 mb-0 font-weight-medium">
                                        {{ $moviesShowingGrowthPercent >= 0 ? '+' : '' }}{{ $moviesShowingGrowthPercent }}%
                                    </p>
                                </div>
                            </div>
                            <div class="col-3 d-flex justify-content-end align-items-start">
                                <div class="icon {{ $moviesShowingGrowthPercent >= 0 ? 'icon-box-success' : 'icon-box-danger' }}"
                                    style="background: linear-gradient(135deg,#0ea5e9,#22d3ee); width:56px; height:56px; border-radius:12px; display:flex; align-items:center; justify-content:center;">
                                    <span class="mdi mdi-movie" style="font-size:28px; color:#0b1220;"></span>
                                </div>
                            </div>
                        </div>
                        <h6 class="text-muted font-weight-normal">Tổng số phim đang chiếu</h6>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                <div class="d-flex align-items-center align-self-start">
                                    <h3 class="mb-0">{{ number_format($totalRevenueThisYear) }}đ</h3>
                                    <p
                                        class="{{ $revenueYearGrowthPercent >= 0 ? 'text-success' : 'text-danger' }} ml-2 mb-0 font-weight-medium">
                                        {{ $revenueYearGrowthPercent >= 0 ? '+' : '' }}{{ $revenueYearGrowthPercent }}%
                                    </p>
                                </div>
                            </div>
                            <div class="col-3 d-flex justify-content-end align-items-start">
                                <div class="icon {{ $revenueYearGrowthPercent >= 0 ? 'icon-box-success' : 'icon-box-danger' }}"
                                    style="background: linear-gradient(135deg,#f97316,#f59e0b); width:56px; height:56px; border-radius:12px; display:flex; align-items:center; justify-content:center;">
                                    <span class="mdi mdi-cash-multiple" style="font-size:28px; color:#0b1220;"></span>
                                </div>
                            </div>
                        </div>
                        <h6 class="text-muted font-weight-normal">Doanh thu năm {{ date('Y') }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                <div class="d-flex align-items-center align-self-start">
                                    <h3 class="mb-0">{{ number_format($totalRevenueToday) }}đ</h3>
                                    <p
                                        class="{{ $revenueTodayGrowthPercent >= 0 ? 'text-success' : 'text-danger' }} ml-2 mb-0 font-weight-medium">
                                        {{ $revenueTodayGrowthPercent >= 0 ? '+' : '' }}{{ $revenueTodayGrowthPercent }}%
                                    </p>
                                </div>
                            </div>
                            <div class="col-3 d-flex justify-content-end align-items-start">
                                <div class="icon {{ $revenueTodayGrowthPercent >= 0 ? 'icon-box-success' : 'icon-box-danger' }}"
                                    style="background: linear-gradient(135deg,#22c55e,#84cc16); width:56px; height:56px; border-radius:12px; display:flex; align-items:center; justify-content:center;">
                                    <span class="mdi mdi-calendar-today" style="font-size:28px; color:#0b1220;"></span>
                                </div>
                            </div>
                        </div>
                        <h6 class="text-muted font-weight-normal">Doanh thu hôm nay</h6>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                <div class="d-flex align-items-center align-self-start">
                                    <h3 class="mb-0">{{ number_format($totalPaidTicketsToday) }}</h3>
                                    <p
                                        class="{{ $ticketsTodayGrowthPercent >= 0 ? 'text-success' : 'text-danger' }} ml-2 mb-0 font-weight-medium">
                                        {{ $ticketsTodayGrowthPercent >= 0 ? '+' : '' }}{{ $ticketsTodayGrowthPercent }}%
                                    </p>
                                </div>
                            </div>
                            <div class="col-3 d-flex justify-content-end align-items-start">
                                <div class="icon {{ $ticketsTodayGrowthPercent >= 0 ? 'icon-box-success' : 'icon-box-danger' }}"
                                    style="background: linear-gradient(135deg,#06b6d4,#3b82f6); width:56px; height:56px; border-radius:12px; display:flex; align-items:center; justify-content:center;">
                                    <span class="mdi mdi-ticket-confirmation"
                                        style="font-size:28px; color:#0b1220;"></span>
                                </div>
                            </div>
                        </div>
                        <h6 class="text-muted font-weight-normal">Vé đã thanh toán hôm nay</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="card-title mb-0">Lịch sử giao dịch</h4>
                            <div class="dropdown" wire:ignore>
                                <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-filter me-1"></i>
                                    <span id="transactionHistoryFilterText">
                                        @switch($transactionHistoryPeriod)
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
                                        <h6 class="dropdown-header text-primary">
                                            <i class="fas fa-calendar-day me-1"></i>
                                            Theo ngày
                                        </h6>
                                    </li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changeTransactionHistoryPeriod('3_days')">3 ngày gần
                                            nhất</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changeTransactionHistoryPeriod('7_days')">7 ngày gần
                                            nhất</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changeTransactionHistoryPeriod('30_days')">30 ngày
                                            gần nhất</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <h6 class="dropdown-header text-primary">
                                            <i class="fas fa-calendar me-1"></i>
                                            Theo tháng
                                        </h6>
                                    </li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changeTransactionHistoryPeriod('1_month')">1
                                            tháng</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changeTransactionHistoryPeriod('3_months')">3
                                            tháng</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <h6 class="dropdown-header text-primary">
                                            <i class="fas fa-calendar-check me-1"></i>
                                            Theo năm
                                        </h6>
                                    </li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changeTransactionHistoryPeriod('1_year')">1 năm</a>
                                    </li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changeTransactionHistoryPeriod('2_years')">2 năm</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div wire:ignore>
                            <div id="transactionHistoryChart" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="card-title mb-0">Xu hướng doanh thu theo thời gian</h4>
                            <div class="dropdown" wire:ignore>
                                <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-filter me-1"></i>
                                    <span id="revenueSourceFilterText">
                                        @switch($revenueSourcePeriod)
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
                                        <h6 class="dropdown-header text-primary">
                                            <i class="fas fa-calendar-day me-1"></i>
                                            Theo ngày
                                        </h6>
                                    </li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changeRevenueSourcePeriod('3_days')">3 ngày gần
                                            nhất</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changeRevenueSourcePeriod('7_days')">7 ngày gần
                                            nhất</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changeRevenueSourcePeriod('30_days')">30 ngày gần
                                            nhất</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <h6 class="dropdown-header text-primary">
                                            <i class="fas fa-calendar me-1"></i>
                                            Theo tháng
                                        </h6>
                                    </li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changeRevenueSourcePeriod('1_month')">1 tháng</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changeRevenueSourcePeriod('3_months')">3 tháng</a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <h6 class="dropdown-header text-primary">
                                            <i class="fas fa-calendar-check me-1"></i>
                                            Theo năm
                                        </h6>
                                    </li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changeRevenueSourcePeriod('1_year')">1 năm</a>
                                    </li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changeRevenueSourcePeriod('2_years')">2 năm</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div wire:ignore>
                            <div id="revenueSourceChart" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row ">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="card-title mb-0">Quản lý đồ ăn & Phim - Top theo doanh thu</h4>
                            <div class="dropdown" wire:ignore>
                                <button class="btn btn-outline-warning btn-sm dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-filter me-1"></i>
                                    <span id="foodManagementFilterText">
                                        @switch($foodManagementPeriod)
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
                                        <h6 class="dropdown-header text-warning">
                                            <i class="fas fa-calendar-day me-1"></i>
                                            Theo ngày
                                        </h6>
                                    </li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changeFoodManagementPeriod('3_days')">3 ngày gần
                                            nhất</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changeFoodManagementPeriod('7_days')">7 ngày gần
                                            nhất</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changeFoodManagementPeriod('30_days')">30 ngày gần
                                            nhất</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <h6 class="dropdown-header text-warning">
                                            <i class="fas fa-calendar me-1"></i>
                                            Theo tháng
                                        </h6>
                                    </li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changeFoodManagementPeriod('1_month')">1 tháng</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changeFoodManagementPeriod('3_months')">3 tháng</a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <h6 class="dropdown-header text-warning">
                                            <i class="fas fa-calendar-check me-1"></i>
                                            Theo năm
                                        </h6>
                                    </li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changeFoodManagementPeriod('1_year')">1 năm</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changeFoodManagementPeriod('2_years')">2 năm</a></li>
                                </ul>
                            </div>
                        </div>
                        <div wire:ignore>
                            <div id="foodManagementChart" style="height: 380px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Cards thống kê chi tiết --}}
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card bg-dark border-secondary shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-cash-register me-2"></i>
                                    Doanh thu tổng
                                </h5>
                                <div class="d-flex align-items-center mb-2">
                                    <h2 class="mb-0 text-white fw-bold">{{ number_format($currentMonthRevenue) }}đ</h2>
                                    <span class="badge ms-2 {{ $this->getRevenueGrowth() >= 0 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $this->getRevenueGrowth() >= 0 ? '+' : '' }}{{ $this->getRevenueGrowth() }}%
                                    </span>
                                </div>
                                <p class="text-muted mb-0">So với tháng trước</p>
                            </div>
                            <div class="text-primary">
                                <i class="fas fa-money-bill-wave fa-3x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-dark border-secondary shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="text-success mb-3">
                                    <i class="fas fa-check-circle me-2"></i>
                                    Đặt vé thành công
                                </h5>
                                <div class="d-flex align-items-center mb-2">
                                    <h2 class="mb-0 text-white fw-bold">{{ number_format($currentMonthBookings) }}</h2>
                                    <span class="badge ms-2 {{ $this->getBookingGrowth() >= 0 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $this->getBookingGrowth() >= 0 ? '+' : '' }}{{ $this->getBookingGrowth() }}%
                                    </span>
                                </div>
                                <p class="text-muted mb-0">So với tháng trước</p>
                            </div>
                            <div class="text-success">
                                <i class="fas fa-ticket-alt fa-3x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-dark border-secondary shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="text-info mb-3">
                                    <i class="fas fa-user-plus me-2"></i>
                                    Người dùng mới
                                </h5>
                                <div class="d-flex align-items-center mb-2">
                                    <h2 class="mb-0 text-white fw-bold">{{ number_format($currentMonthUsers) }}</h2>
                                    <span class="badge ms-2 {{ $this->getUserGrowth() >= 0 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $this->getUserGrowth() >= 0 ? '+' : '' }}{{ $this->getUserGrowth() }}%
                                    </span>
                                </div>
                                <p class="text-muted mb-0">So với tháng trước</p>
                            </div>
                            <div class="text-info">
                                <i class="fas fa-users fa-3x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Biểu đồ doanh thu -->
        <div class="row">
            <div class="col-12">
                <div class="card bg-dark border-secondary shadow-sm">
                    <div class="card-header bg-transparent border-secondary">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0 text-white">
                                <i class="fas fa-chart-area me-2 text-primary"></i>
                                Biểu đồ doanh thu
                            </h4>
                            <div class="dropdown" wire:ignore>
                                <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-filter me-1"></i>
                                    <span id="revenueFilterText">
                                        @switch($revenuePeriod)
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
                                            @case('6_months')
                                                6 tháng gần nhất
                                            @break
                                            @case('1_year')
                                                1 năm gần nhất
                                            @break
                                            @case('2_years')
                                                2 năm gần nhất
                                            @break
                                            @case('3_years')
                                                3 năm gần nhất
                                            @break
                                            @default
                                                7 ngày gần nhất
                                        @endswitch
                                    </span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-dark">
                                    <li>
                                        <h6 class="dropdown-header text-primary">
                                            <i class="fas fa-calendar-day me-1"></i>
                                            Theo ngày
                                        </h6>
                                    </li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changeRevenuePeriod('3_days')">3 ngày gần nhất</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changeRevenuePeriod('7_days')">7 ngày gần nhất</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changeRevenuePeriod('30_days')">30 ngày gần nhất</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <h6 class="dropdown-header text-primary">
                                            <i class="fas fa-calendar me-1"></i>
                                            Theo tháng
                                        </h6>
                                    </li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changeRevenuePeriod('1_month')">1 tháng</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changeRevenuePeriod('3_months')">3 tháng</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changeRevenuePeriod('6_months')">6 tháng</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <h6 class="dropdown-header text-primary">
                                            <i class="fas fa-calendar-check me-1"></i>
                                            Theo năm
                                        </h6>
                                    </li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changeRevenuePeriod('1_year')">1 năm</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changeRevenuePeriod('2_years')">2 năm</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changeRevenuePeriod('3_years')">3 năm</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div wire:ignore id="revenueChart" style="height: 450px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .apexcharts-menu {
            color: black;
        }
    </style>
</div>
@script
    <script>
        globalThis.chartInstances = globalThis.chartInstances || {};

        // Options cho biểu đồ doanh thu (Area Chart)
        const optionsRevenueChart = {
            series: [{
                name: 'Doanh thu (VNĐ)',
                data: @json($chartData).map(item => ({
                    x: item.x,
                    y: item.y,
                    bookings: item.bookings || 0
                }))
            }],
            chart: {
                type: 'area',
                height: 450,
                background: 'transparent',
                toolbar: {
                    show: true,
                    tools: {
                        download: true,
                        selection: true,
                        zoom: true,
                        zoomin: true,
                        zoomout: true,
                        pan: true,
                        reset: true
                    },
                    export: {
                        csv: {
                            filename: 'doanh-thu-se7en-cinema',
                            columnDelimiter: ',',
                            headerCategory: 'Thời gian',
                            headerValue: 'Doanh thu (VNĐ)',
                        },
                        svg: {
                            filename: 'doanh-thu-se7en-cinema',
                        },
                        png: {
                            filename: 'doanh-thu-se7en-cinema',
                        }
                    }
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800,
                    animateGradually: {
                        enabled: true,
                        delay: 150
                    },
                    dynamicAnimation: {
                        enabled: true,
                        speed: 350
                    }
                },
                zoom: {
                    enabled: true,
                    type: 'x',
                    autoScaleYaxis: true,
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth',
                width: 3
            },
            colors: ['#007bff'],
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'dark',
                    type: 'vertical',
                    shadeIntensity: 0.3,
                    gradientToColors: ['#0056b3'],
                    inverseColors: false,
                    opacityFrom: 0.9,
                    opacityTo: 0.3,
                    stops: [0, 90, 100]
                }
            },
            xaxis: {
                categories: @json($chartData).map(item => item.x),
                labels: {
                    style: {
                        colors: '#e2e8f0',
                        fontSize: '12px',
                        fontWeight: 500
                    },
                    rotate: -45,
                    rotateAlways: false
                },
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                },
                title: {
                    text: 'Thời gian',
                    style: {
                        color: '#e2e8f0',
                        fontSize: '14px',
                        fontWeight: 600
                    }
                }
            },
            yaxis: {
                title: {
                    text: 'Doanh thu (VNĐ)',
                    style: {
                        color: '#e2e8f0',
                        fontSize: '14px',
                        fontWeight: 600
                    }
                },
                labels: {
                    style: {
                        colors: '#e2e8f0',
                        fontSize: '12px',
                        fontWeight: 500
                    },
                    formatter: function(value) {
                        return new Intl.NumberFormat('vi-VN').format(value) + 'đ';
                    }
                }
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
                },
                padding: {
                    top: 0,
                    right: 0,
                    bottom: 0,
                    left: 0
                }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'left',
                labels: {
                    colors: '#e2e8f0'
                },
                markers: {
                    width: 12,
                    height: 12,
                    radius: 6
                }
            },
            tooltip: {
                theme: 'dark',
                custom: function({ series, seriesIndex, dataPointIndex, w }) {
                    const data = w.config.series[0].data[dataPointIndex];
                    const thoiGian = data.x || '';
                    const doanhThu = data.y || 0;
                    const donHang = data.bookings || 0;
                    const tbDoanhThu = donHang > 0 ? Math.round(doanhThu / donHang) : 0;

                    // Tính phần trăm tăng trưởng so với điểm trước
                    let phanTramTang = 0;
                    let iconTangTruong = '📊';
                    let mauTangTruong = '#6c757d';
                    if (dataPointIndex > 0) {
                        const doanhThuTruoc = w.config.series[0].data[dataPointIndex - 1].y || 0;
                        if (doanhThuTruoc > 0) {
                            phanTramTang = ((doanhThu - doanhThuTruoc) / doanhThuTruoc * 100).toFixed(1);
                            if (phanTramTang > 0) {
                                iconTangTruong = '📈';
                                mauTangTruong = '#28a745';
                            } else if (phanTramTang < 0) {
                                iconTangTruong = '📉';
                                mauTangTruong = '#dc3545';
                            }
                        }
                    }
                    return `
                        <div class="bg-dark border border-secondary rounded-3 p-3 shadow-lg" style="min-width: 320px; max-width: 400px;">
                            <div class="d-flex align-items-center mb-3">
                                <span class="fs-4 me-2">💰</span>
                                <h6 class="mb-0 text-white fw-bold">${thoiGian}</h6>
                            </div>
                            <div class="row g-2 mb-3">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center p-2 bg-primary bg-opacity-10 rounded">
                                        <span class="text-primary fw-medium">
                                            <i class="fa-solid fa-money-bill-trend-up me-1"></i>
                                            Doanh thu:
                                        </span>
                                        <span class="fw-bold fs-6 text-primary">
                                            ${new Intl.NumberFormat('vi-VN').format(doanhThu)}đ
                                        </span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex justify-content-between align-items-center p-2 bg-success bg-opacity-10 rounded">
                                        <span class="text-primary fw-medium">
                                            <i class="fa-solid fa-cart-shopping me-1"></i>
                                            Đơn hàng:
                                        </span>
                                        <span class="fw-bold text-success">${donHang}</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex justify-content-between align-items-center p-2 bg-info bg-opacity-10 rounded">
                                        <span class="text-info fw-medium">
                                            <i class="fa-solid fa-calculator-simple me-1"></i>
                                            TB/đơn:
                                        </span>
                                        <span class="fw-bold text-info">${new Intl.NumberFormat('vi-VN').format(tbDoanhThu)}đ</span>
                                    </div>
                                </div>
                            </div>
                            ${dataPointIndex > 0 ? `
                                <div class="border-top border-secondary pt-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span style="color: ${mauTangTruong}" class="fw-medium">
                                            ${iconTangTruong} So với trước:
                                        </span>
                                        <span class="fw-bold" style="color: ${mauTangTruong}">
                                            ${phanTramTang >= 0 ? '+' : ''}${phanTramTang}%
                                        </span>
                                    </div>
                                </div>
                            ` : ''}
                        </div>
                    `;
                }
            },
            responsive: [{
                breakpoint: 768,
                options: {
                    chart: {
                        height: 350
                    },
                    xaxis: {
                        labels: {
                            rotate: -45,
                            style: {
                                fontSize: '10px'
                            }
                        }
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        // Function to update filter text
        function updateFilterText(elementId, text) {
            const element = document.getElementById(elementId);
            if (element) {
                element.textContent = text;
            }
        }

        Livewire.on('updateFilterText', (event) => {
            if (event.elementId && event.text) {
                updateFilterText(event.elementId, event.text);
            }
        });

        // Lắng nghe sự kiện cập nhật biểu đồ doanh thu
        Livewire.on('updateRevenueChart', (event) => {
            if (chartInstances.revenueChart) {
                const chartData = event.data;
                chartInstances.revenueChart.updateSeries([{
                    name: 'Doanh thu (VNĐ)',
                    data: chartData.map(item => ({
                        x: item.x,
                        y: item.y,
                        bookings: item.bookings
                    }))
                }]);
                chartInstances.revenueChart.updateOptions({
                    xaxis: {
                        categories: chartData.map(item => item.x)
                    }
                });
            }
        });

        Livewire.on('updateData', function([$transactionHistoryData, $revenueSourceData, $foodManagementData,
        $filterTexts]) {
            // Update filter text from server
            if ($filterTexts) {
                if ($filterTexts.transactionHistoryFilterText) {
                    updateFilterText('transactionHistoryFilterText', $filterTexts.transactionHistoryFilterText);
                }
                if ($filterTexts.revenueSourceFilterText) {
                    updateFilterText('revenueSourceFilterText', $filterTexts.revenueSourceFilterText);
                }
                if ($filterTexts.foodManagementFilterText) {
                    updateFilterText('foodManagementFilterText', $filterTexts.foodManagementFilterText);
                }
            }

            // Options for Transaction History Chart (Donut Chart)
            const optionsTransactionHistory = {
                chart: {
                    type: 'pie',
                    height: 380,
                    background: 'transparent',
                    toolbar: {
                        show: true
                    },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 700
                    }
                },
                series: $transactionHistoryData.map(item => item.value),
                labels: $transactionHistoryData.map(item => item.name),
                colors: [
                    '#6366F1', '#8B5CF6', '#EC4899', '#F59E0B',
                    '#EF4444', '#06B6D4', '#A78BFA'
                ],
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['#111827']
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        type: 'vertical',
                        shadeIntensity: 0.35,
                        opacityFrom: 0.95,
                        opacityTo: 0.85,
                        stops: [0, 90, 100]
                    }
                },
                states: {
                    hover: {
                        filter: {
                            type: 'lighten',
                            value: 0.08
                        }
                    },
                    active: {
                        filter: {
                            type: 'lighten',
                            value: 0.15
                        }
                    }
                },
                // Ẩn nhãn phần trăm trên lát cắt
                dataLabels: {
                    enabled: false
                },
                plotOptions: {
                    pie: {
                        expandOnClick: true,
                        donut: {
                            size: '70%',
                            background: 'transparent',
                            labels: {
                                show: false
                            }
                        }
                    }
                },
                // Hiển thị legend để người dùng thấy loại thanh toán (tên) luôn hiển thị
                legend: {
                    show: true,
                    position: 'bottom',
                    horizontalAlign: 'center',
                    fontSize: '12px',
                    labels: {
                        colors: '#ffffff'
                    },
                    markers: {
                        width: 10,
                        height: 10,
                        radius: 2
                    },
                    formatter: function(seriesName, opts) {
                        const value = opts.w.globals.series[opts.seriesIndex] || 0;
                        const total = opts.w.globals.seriesTotals.reduce((a, b) => a + b, 0) || 1;
                        const pct = Math.round((value / total) * 100);
                        return `${seriesName}: ${value.toLocaleString('vi-VN')} (${pct}%)`;
                    }
                },
                tooltip: {
                    theme: 'dark',
                    custom: function({
                        seriesIndex,
                        w
                    }) {
                        const name = w.config.labels[seriesIndex] || '';
                        const value = (w.globals.series[seriesIndex] || 0);
                        const total = w.globals.seriesTotals.reduce((a, b) => a + b, 0) || 1;
                        const pct = Math.round((value / total) * 100);
                        const iconMap = {
                            'Thẻ tín dụng': '💳',
                            'Ví điện tử': '📱',
                            'Tiền mặt': '💵',
                            'Chuyển khoản ngân hàng': '🏦'
                        };
                        const icon = iconMap[name] || '💠';
                        return `
                            <div style="padding:12px;background:rgba(0,0,0,0.95);border-radius:8px;min-width:220px;">
                                <div style="font-weight:700;color:#fff;margin-bottom:8px">${icon} ${name}</div>
                                <div style="display:flex;justify-content:space-between;color:#ddd">
                                    <span>Giao dịch:</span>
                                    <strong style="color:#fff">${value.toLocaleString('vi-VN')}</strong>
                                </div>
                                <div style="display:flex;justify-content:space-between;color:#ddd;margin-top:6px">
                                    <span>Tỷ lệ:</span>
                                    <strong style="color:#fff">${pct}%</strong>
                                </div>
                            </div>`;
                    }
                },
                responsive: [{
                        breakpoint: 992,
                        options: {
                            chart: {
                                height: 300
                            },
                            plotOptions: {
                                pie: {
                                    donut: {
                                        size: '68%'
                                    }
                                }
                            }
                        }
                    },
                    {
                        breakpoint: 576,
                        options: {
                            chart: {
                                height: 280
                            },
                            plotOptions: {
                                pie: {
                                    donut: {
                                        size: '62%'
                                    }
                                }
                            }
                        }
                    }
                ]
            };

            // Options for Revenue Source Chart (Trend Chart)
            const optionsRevenueSource = {
                chart: {
                    type: 'area',
                    height: 300,
                    background: 'transparent',
                    toolbar: {
                        show: true
                    }
                },
                series: ($revenueSourceData.series || []),
                xaxis: {
                    categories: ($revenueSourceData.labels || []),
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
                yaxis: {
                    labels: {
                        style: {
                            colors: '#ffffff',
                            fontSize: '12px'
                        },
                        formatter: function(value) {
                            return new Intl.NumberFormat('vi-VN').format(value);
                        }
                    }
                },
                colors: ['#17A2B8', '#20C997'],
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        type: 'vertical',
                        shadeIntensity: 0.3,
                        gradientToColors: ['#0EA5A8', '#16A34A'],
                        inverseColors: false,
                        opacityFrom: 0.5,
                        opacityTo: 0.2,
                        stops: [0, 100]
                    }
                },
                legend: {
                    position: 'top',
                    labels: {
                        colors: '#ffffff'
                    }
                },
                tooltip: {
                    theme: 'dark',
                    y: {
                        formatter: function(value) {
                            return new Intl.NumberFormat('vi-VN', {
                                style: 'currency',
                                currency: 'VND'
                            }).format(value);
                        }
                    }
                }
            };

            // Options for Food Management (Stacked/dual-axis bar)
            const optionsFoodManagement = {
                chart: {
                    type: 'bar',
                    height: 380,
                    background: 'transparent',
                    toolbar: {
                        show: true
                    }
                },
                series: [{
                        name: 'Đồ ăn - Doanh thu (VNĐ)',
                        type: 'column',
                        data: ($foodManagementData.revenue || [])
                    },
                    {
                        name: 'Đồ ăn - Số lượng',
                        type: 'line',
                        data: ($foodManagementData.quantity || [])
                    },
                    {
                        name: 'Phim - Doanh thu (VNĐ)',
                        type: 'column',
                        data: ($foodManagementData.movieRevenue || [])
                    },
                    {
                        name: 'Phim - Số vé',
                        type: 'line',
                        data: ($foodManagementData.movieTickets || [])
                    }
                ],
                xaxis: {
                    categories: ($foodManagementData.labels || []),
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
                        labels: {
                            style: {
                                colors: '#ffffff'
                            },
                            formatter: v => new Intl.NumberFormat('vi-VN').format(v)
                        },
                        title: {
                            text: 'VNĐ',
                            style: {
                                color: '#fff'
                            }
                        }
                    },
                    {
                        opposite: true,
                        labels: {
                            style: {
                                colors: '#ffffff'
                            },
                            formatter: v => Math.floor(v)
                        },
                        title: {
                            text: 'Số lượng',
                            style: {
                                color: '#fff'
                            }
                        }
                    }
                ],
                colors: ['#FF9800', '#4CAF50', '#9C27B0', '#F44336'],
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    width: [0, 3, 0, 3],
                    curve: 'smooth'
                },
                legend: {
                    position: 'top',
                    labels: {
                        colors: '#ffffff'
                    }
                },
                grid: {
                    borderColor: '#2d3748',
                    strokeDashArray: 1
                },
                tooltip: {
                    theme: 'dark',
                    shared: true,
                    intersect: false,
                    custom: function({
                        series,
                        dataPointIndex,
                        w
                    }) {
                        const categories = (w.config.xaxis && w.config.xaxis.categories) || [];
                        const name = categories[dataPointIndex] || '';
                        const foodRevenue = series?.[0]?.[dataPointIndex] ?? 0;
                        const foodQuantity = series?.[1]?.[dataPointIndex] ?? 0;
                        const movieRevenue = series?.[2]?.[dataPointIndex] ?? 0;
                        const movieTickets = series?.[3]?.[dataPointIndex] ?? 0;

                        const foodRevenueFmt = new Intl.NumberFormat('vi-VN', {
                            style: 'currency',
                            currency: 'VND'
                        }).format(foodRevenue);

                        const movieRevenueFmt = new Intl.NumberFormat('vi-VN', {
                            style: 'currency',
                            currency: 'VND'
                        }).format(movieRevenue);

                        return `
                            <div style="padding:12px;background:rgba(0,0,0,0.95);border-radius:8px;min-width:280px">
                                <div style="font-weight:700;color:#FF9800;margin-bottom:8px">🍔 ${name}</div>

                                <div style="border-left:3px solid #FF9800;padding-left:8px;margin-bottom:8px">
                                    <div style="color:#FF9800;font-weight:600;margin-bottom:4px">🍽️ Đồ ăn:</div>
                                    <div style="display:flex;justify-content:space-between;gap:8px;color:#ddd;margin-bottom:2px">
                                        <span>Doanh thu:</span>
                                        <strong style="color:#fff">${foodRevenueFmt}</strong>
                                    </div>
                                    <div style="display:flex;justify-content:space-between;gap:8px;color:#ddd">
                                        <span>Số lượng:</span>
                                        <strong style="color:#fff">${foodQuantity} số lượng</strong>
                                    </div>
                                </div>

                                <div style="border-left:3px solid #9C27B0;padding-left:8px">
                                    <div style="color:#9C27B0;font-weight:600;margin-bottom:4px">🎬 Phim:</div>
                                    <div style="display:flex;justify-content:space-between;gap:8px;color:#ddd;margin-bottom:2px">
                                        <span>Doanh thu:</span>
                                        <strong style="color:#fff">${movieRevenueFmt}</strong>
                                    </div>
                                    <div style="display:flex;justify-content:space-between;gap:8px;color:#ddd">
                                        <span>Số vé:</span>
                                        <strong style="color:#fff">${movieTickets} vé</strong>
                                    </div>
                                </div>
                            </div>
                        `;
                    }
                }
            };

            window.renderAllCharts = function() {
                if (Object.values(chartInstances).length > 0) {
                    chartInstances.transactionHistoryChart.updateOptions(optionsTransactionHistory);
                    chartInstances.revenueSourceChart.updateOptions(optionsRevenueSource);
                    chartInstances.foodManagementChart.updateOptions(optionsFoodManagement);
                    chartInstances.revenueChart.updateOptions(optionsRevenueChart);
                } else {
                    const transactionHistoryEl = document.querySelector("#transactionHistoryChart");
                    const revenueSourceEl = document.querySelector("#revenueSourceChart");
                    const foodManagementEl = document.querySelector('#foodManagementChart');
                    const revenueEl = document.querySelector("#revenueChart");

                    if (transactionHistoryEl) chartInstances.transactionHistoryChart = createScChart(
                        transactionHistoryEl,
                        optionsTransactionHistory);
                    if (revenueSourceEl) chartInstances.revenueSourceChart = createScChart(revenueSourceEl,
                        optionsRevenueSource);
                    if (foodManagementEl) chartInstances.foodManagementChart = createScChart(foodManagementEl,
                        optionsFoodManagement);
                    if (revenueEl) chartInstances.revenueChart = createScChart(revenueEl, optionsRevenueChart);
                }
            }

            renderAllCharts();
        });
    </script>
@endscript
