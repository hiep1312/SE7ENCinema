<div class="container-fluid scRender" wire:poll.6s>
    <div class="content-wrapper">
         Header
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-white mb-0">
                <i class="fas fa-chart-line me-2 text-primary"></i>
                Dashboard Analytics
            </h2>
            <div class="text-muted">
                <i class="fas fa-calendar-alt me-1"></i>
                {{ now()->format('d/m/Y H:i') }}
            </div>
        </div>

         Cards thống kê chính
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card bg-gradient border-0 shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body text-white">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <div class="d-flex align-items-center">
                                    <h3 class="mb-0 fw-bold">{{ number_format($currentMonthRevenue) }}đ</h3>
                                    <span class="badge ms-2 {{ $this->getRevenueGrowth() >= 0 ? 'bg-success' : 'bg-danger' }}">
                                        <i class="fas fa-{{ $this->getRevenueGrowth() >= 0 ? 'arrow-up' : 'arrow-down' }} me-1"></i>
                                        {{ $this->getRevenueGrowth() >= 0 ? '+' : '' }}{{ $this->getRevenueGrowth() }}%
                                    </span>
                                </div>
                                <p class="text-white-50 mb-0 mt-1">Doanh thu tháng này</p>
                            </div>
                            <div class="col-4 text-end">
                                <i class="fas fa-money-bill-wave fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-success border-0 shadow-sm">
                    <div class="card-body text-white">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <div class="d-flex align-items-center">
                                    <h3 class="mb-0 fw-bold">{{ number_format($currentMonthBookings) }}</h3>
                                    <span class="badge ms-2 {{ $this->getBookingGrowth() >= 0 ? 'bg-light text-success' : 'bg-danger' }}">
                                        <i class="fas fa-{{ $this->getBookingGrowth() >= 0 ? 'arrow-up' : 'arrow-down' }} me-1"></i>
                                        {{ $this->getBookingGrowth() >= 0 ? '+' : '' }}{{ $this->getBookingGrowth() }}%
                                    </span>
                                </div>
                                <p class="text-white-50 mb-0 mt-1">Đặt vé tháng này</p>
                            </div>
                            <div class="col-4 text-end">
                                <i class="fas fa-ticket-alt fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-info border-0 shadow-sm">
                    <div class="card-body text-white">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <div class="d-flex align-items-center">
                                    <h3 class="mb-0 fw-bold">{{ number_format($currentMonthUsers) }}</h3>
                                    <span class="badge ms-2 {{ $this->getUserGrowth() >= 0 ? 'bg-light text-info' : 'bg-danger' }}">
                                        <i class="fas fa-{{ $this->getUserGrowth() >= 0 ? 'arrow-up' : 'arrow-down' }} me-1"></i>
                                        {{ $this->getUserGrowth() >= 0 ? '+' : '' }}{{ $this->getUserGrowth() }}%
                                    </span>
                                </div>
                                <p class="text-white-50 mb-0 mt-1">Người dùng mới</p>
                            </div>
                            <div class="col-4 text-end">
                                <i class="fas fa-user-plus fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-warning border-0 shadow-sm">
                    <div class="card-body text-white">
                        <div class="row align-items-center">
                            <div class="col-8">
                                <div class="d-flex align-items-center">
                                    <h3 class="mb-0 fw-bold">{{ number_format($currentMonthMovies) }}</h3>
                                    <span class="badge ms-2 {{ $this->getMovieGrowth() >= 0 ? 'bg-light text-warning' : 'bg-danger' }}">
                                        <i class="fas fa-{{ $this->getMovieGrowth() >= 0 ? 'arrow-up' : 'arrow-down' }} me-1"></i>
                                        {{ $this->getMovieGrowth() >= 0 ? '+' : '' }}{{ $this->getMovieGrowth() }}%
                                    </span>
                                </div>
                                <p class="text-white-50 mb-0 mt-1">Phim mới</p>
                            </div>
                            <div class="col-4 text-end">
                                <i class="fas fa-film fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

         Cards thống kê chi tiết
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
                                            wire:click.prevent="changePeriod('revenue', '3_days')">3 ngày gần nhất</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changePeriod('revenue', '7_days')">7 ngày gần nhất</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changePeriod('revenue', '30_days')">30 ngày gần nhất</a></li>
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
                                            wire:click.prevent="changePeriod('revenue', '1_month')">1 tháng</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changePeriod('revenue', '3_months')">3 tháng</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changePeriod('revenue', '6_months')">6 tháng</a></li>
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
                                            wire:click.prevent="changePeriod('revenue', '1_year')">1 năm</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changePeriod('revenue', '2_years')">2 năm</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changePeriod('revenue', '3_years')">3 năm</a></li>
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

        <!-- Biểu đồ tổng hợp (Combo Chart) -->
        <div class="row g-4 mb-4">
            <div class="col-md-8">
                <div class="card bg-dark border-secondary shadow-sm">
                    <div class="card-header bg-transparent border-secondary">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0 text-white">
                                <i class="fas fa-chart-line me-2 text-success"></i>
                                Phim và đồ ăn theo thời gian
                            </h5>
                            <div class="dropdown" wire:ignore>
                                <button class="btn btn-outline-success btn-sm dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-filter me-1"></i>
                                    <span id="comboFilterText">
                                        @switch($comboPeriod)
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
                                        <h6 class="dropdown-header text-success">
                                            <i class="fas fa-calendar-day me-1"></i>
                                            Theo ngày
                                        </h6>
                                    </li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changePeriod('combo', '3_days')">3 ngày gần nhất</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changePeriod('combo', '7_days')">7 ngày gần nhất</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changePeriod('combo', '30_days')">30 ngày gần nhất</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <h6 class="dropdown-header text-success">
                                            <i class="fas fa-calendar me-1"></i>
                                            Theo tháng
                                        </h6>
                                    </li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changePeriod('combo', '1_month')">1 tháng</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changePeriod('combo', '3_months')">3 tháng</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changePeriod('combo', '6_months')">6 tháng</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <h6 class="dropdown-header text-success">
                                            <i class="fas fa-calendar-check me-1"></i>
                                            Theo năm
                                        </h6>
                                    </li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changePeriod('combo', '1_year')">1 năm</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changePeriod('combo', '2_years')">2 năm</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changePeriod('combo', '3_years')">3 năm</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div wire:ignore id="comboChart" style="height: 350px;"></div>
                    </div>
                </div>
            </div>

            <!-- Biểu đồ tròn (Pie Chart) -->
            <div class="col-md-4">
                <div class="card bg-dark border-secondary shadow-sm">
                    <div class="card-header bg-transparent border-secondary">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0 text-white">
                                <i class="fas fa-chart-pie me-2 text-warning"></i>
                                Phân bố doanh thu
                            </h5>
                            <div class="dropdown" wire:ignore>
                                <button class="btn btn-outline-warning btn-sm dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-filter me-1"></i>
                                    <span id="pieFilterText">
                                        @switch($piePeriod)
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
                                        <h6 class="dropdown-header text-warning">
                                            <i class="fas fa-calendar-day me-1"></i>
                                            Theo ngày
                                        </h6>
                                    </li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changePeriod('pie', '3_days')">3 ngày gần nhất</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changePeriod('pie', '7_days')">7 ngày gần nhất</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changePeriod('pie', '30_days')">30 ngày gần nhất</a></li>
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
                                            wire:click.prevent="changePeriod('pie', '1_month')">1 tháng</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changePeriod('pie', '3_months')">3 tháng</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changePeriod('pie', '6_months')">6 tháng</a></li>
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
                                            wire:click.prevent="changePeriod('pie', '1_year')">1 năm</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changePeriod('pie', '2_years')">2 năm</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changePeriod('pie', '3_years')">3 năm</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div wire:ignore id="pieChart" style="height: 350px;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Biểu đồ radar (Radar Chart) -->
        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card bg-dark border-secondary shadow-sm">
                    <div class="card-header bg-transparent border-secondary">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0 text-white">
                                <i class="fas fa-chart-radar me-2 text-info"></i>
                                Chỉ số hiệu suất
                            </h5>
                            <div class="dropdown" wire:ignore>
                                <button class="btn btn-outline-info btn-sm dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-filter me-1"></i>
                                    <span id="radarFilterText">
                                        @switch($radarPeriod)
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
                                        <h6 class="dropdown-header text-info">
                                            <i class="fas fa-calendar-day me-1"></i>
                                            Theo ngày
                                        </h6>
                                    </li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changePeriod('radar', '3_days')">3 ngày gần nhất</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changePeriod('radar', '7_days')">7 ngày gần nhất</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changePeriod('radar', '30_days')">30 ngày gần nhất</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <h6 class="dropdown-header text-info">
                                            <i class="fas fa-calendar me-1"></i>
                                            Theo tháng
                                        </h6>
                                    </li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changePeriod('radar', '1_month')">1 tháng</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changePeriod('radar', '3_months')">3 tháng</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changePeriod('radar', '6_months')">6 tháng</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <h6 class="dropdown-header text-info">
                                            <i class="fas fa-calendar-check me-1"></i>
                                            Theo năm
                                        </h6>
                                    </li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changePeriod('radar', '1_year')">1 năm</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changePeriod('radar', '2_years')">2 năm</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changePeriod('radar', '3_years')">3 năm</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div wire:ignore id="radarChart" style="height: 350px;"></div>
                    </div>
                </div>
            </div>

            <!-- To-do list -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Danh sách công việc</h4>
                        <div class="add-items d-flex">
                            <input type="text" class="form-control todo-list-input" placeholder="Nhập công việc...">
                            <button class="add btn btn-primary todo-list-add-btn">Thêm</button>
                        </div>
                        <div class="list-wrapper">
                            <ul class="d-flex flex-column-reverse text-white todo-list todo-list-custom">
                                <li>
                                    <div class="form-check form-check-primary">
                                        <label class="form-check-label">
                                            <input class="checkbox" type="checkbox"> Tạo hóa đơn
                                        </label>
                                    </div>
                                    <i class="remove mdi mdi-close-box"></i>
                                </li>
                                <li>
                                    <div class="form-check form-check-primary">
                                        <label class="form-check-label">
                                            <input class="checkbox" type="checkbox"> Họp với Alita
                                        </label>
                                    </div>
                                    <i class="remove mdi mdi-close-box"></i>
                                </li>
                                <li class="completed">
                                    <div class="form-check form-check-primary">
                                        <label class="form-check-label">
                                            <input class="checkbox" type="checkbox" checked> Chuẩn bị thuyết trình
                                        </label>
                                    </div>
                                    <i class="remove mdi mdi-close-box"></i>
                                </li>
                                <li>
                                    <div class="form-check form-check-primary">
                                        <label class="form-check-label">
                                            <input class="checkbox" type="checkbox"> Lên kế hoạch cuối tuần
                                        </label>
                                    </div>
                                    <i class="remove mdi mdi-close-box"></i>
                                </li>
                                <li>
                                    <div class="form-check form-check-primary">
                                        <label class="form-check-label">
                                            <input class="checkbox" type="checkbox"> Đón con từ trường
                                        </label>
                                    </div>
                                    <i class="remove mdi mdi-close-box"></i>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .apexcharts-menu {
            color: black;
        }
        .todo-list {
            list-style: none;
            padding: 0;
        }
        .todo-list li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #2d3748;
        }
        .todo-list li:last-child {
            border-bottom: none;
        }
        .todo-list li.completed .form-check-label {
            text-decoration: line-through;
            opacity: 0.6;
        }
        .todo-list .remove {
            cursor: pointer;
            padding: 5px;
            border-radius: 3px;
        }
        .todo-list .remove:hover {
            background-color: rgba(220, 53, 69, 0.2);
        }
    </style>
</div>

@script
<script>
    let revenueChart, comboChart, pieChart, radarChart;
    let chartInstances = {};

    // Khởi tạo tất cả biểu đồ
    function renderAllCharts() {
        if (Object.values(chartInstances).length > 0) {
            chartInstances.revenueChart.updateOptions(optionsRevenueChart);
            chartInstances.comboChart.updateOptions(optionsComboChart);
            chartInstances.pieChart.updateOptions(optionsPieChart);
            chartInstances.radarChart.updateOptions(optionsRadarChart);
        } else {
            const revenueEl = document.querySelector("#revenueChart");
            const comboEl = document.querySelector("#comboChart");
            const pieEl = document.querySelector("#pieChart");
            const radarEl = document.querySelector("#radarChart");

            if (revenueEl) chartInstances.revenueChart = createScChart(revenueEl, optionsRevenueChart);
            if (comboEl) chartInstances.comboChart = createScChart(comboEl, optionsComboChart);
            if (pieEl) chartInstances.pieChart = createScChart(pieEl, optionsPieChart);
            if (radarEl) chartInstances.radarChart = createScChart(radarEl, optionsRadarChart);
        }
    }

    // Lắng nghe sự kiện cập nhật biểu đồ
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

    Livewire.on('updateComboChart', (event) => {
        if (chartInstances.comboChart) {
            const chartData = event.data;
            chartInstances.comboChart.updateSeries([
                {
                    name: 'Phim mới',
                    type: 'bar',
                    data: chartData.map(item => item.movies)
                },
                {
                    name: 'Phim đang chiếu',
                    type: 'bar',
                    data: chartData.map(item => item.activeMovies)
                },
                {
                    name: 'Doanh thu đồ ăn',
                    type: 'line',
                    data: chartData.map(item => item.foodRevenue)
                },
                {
                    name: 'Người dùng mới',
                    type: 'area',
                    data: chartData.map(item => item.users)
                }
            ]);
            chartInstances.comboChart.updateOptions({
                xaxis: {
                    categories: chartData.map(item => item.x)
                }
            });
        }
    });

    Livewire.on('updatePieChart', (event) => {
        if (chartInstances.pieChart) {
            const chartData = event.data;
            chartInstances.pieChart.updateSeries(chartData.revenue.map(item => item.value));
            chartInstances.pieChart.updateOptions({
                labels: chartData.revenue.map(item => item.name)
            });
        }
    });

    Livewire.on('updateRadarChart', (event) => {
        if (chartInstances.radarChart) {
            const chartData = event.data;
            chartInstances.radarChart.updateSeries([{
                name: 'Chỉ số hiệu suất',
                data: chartData.values
            }]);
            chartInstances.radarChart.updateOptions({
                xaxis: {
                    categories: chartData.categories
                }
            });
        }
    });

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
                                        <i class="fas fa-money-bill-wave me-1"></i>
                                        Doanh thu:
                                    </span>
                                    <span class="fw-bold fs-6 text-primary">
                                        ${new Intl.NumberFormat('vi-VN').format(doanhThu)}đ
                                    </span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-between align-items-center p-2 bg-success bg-opacity-10 rounded">
                                    <span class="text-success fw-medium">
                                        <i class="fas fa-shopping-cart me-1"></i>
                                        Đơn hàng:
                                    </span>
                                    <span class="fw-bold text-success">${donHang}</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-between align-items-center p-2 bg-info bg-opacity-10 rounded">
                                    <span class="text-info fw-medium">
                                        <i class="fas fa-calculator me-1"></i>
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

    // Options cho biểu đồ tổng hợp (Combo Chart)
    const optionsComboChart = {
        series: [
            {
                name: 'Phim mới',
                type: 'bar',
                data: @json($comboChartData).map(item => item.movies)
            },
            {
                name: 'Phim đang chiếu',
                type: 'bar',
                data: @json($comboChartData).map(item => item.activeMovies)
            },
            {
                name: 'Doanh thu đồ ăn',
                type: 'line',
                data: @json($comboChartData).map(item => item.foodRevenue)
            },
            {
                name: 'Người dùng mới',
                type: 'area',
                data: @json($comboChartData).map(item => item.users)
            }
        ],
        chart: {
            type: 'line',
            height: 350,
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
                }
            },
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            width: [0, 0, 4, 0],
            curve: 'smooth'
        },
        colors: ['#ffc107', '#fd7e14', '#dc3545', '#20c997'],
        fill: {
            type: ['solid', 'solid', 'solid', 'gradient'],
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.9,
                stops: [0, 90, 100],
                colorStops: [
                    [],
                    [],
                    [],
                    [{
                        offset: 0,
                        color: '#20c997',
                        opacity: 0.7
                    },
                    {
                        offset: 100,
                        color: '#23272b',
                        opacity: 0.1
                    }]
                ]
            }
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '60%',
                endingShape: 'rounded',
                borderRadius: 4
            }
        },
        xaxis: {
            categories: @json($comboChartData).map(item => item.x),
            labels: {
                style: {
                    colors: '#e2e8f0',
                    fontSize: '11px',
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
            }
        },
        yaxis: [
            {
                title: {
                    text: 'Số lượng',
                    style: {
                        color: '#ffc107',
                        fontSize: '12px',
                        fontWeight: 600
                    }
                },
                labels: {
                    style: {
                        colors: '#ffc107',
                        fontSize: '11px',
                        fontWeight: 500
                    }
                }
            },
            {
                opposite: true,
                title: {
                    text: 'Doanh thu (VND)',
                    style: {
                        color: '#dc3545',
                        fontSize: '12px',
                        fontWeight: 600
                    }
                },
                labels: {
                    style: {
                        colors: '#dc3545',
                        fontSize: '11px',
                        fontWeight: 500
                    },
                    formatter: function(value) {
                        return new Intl.NumberFormat('vi-VN').format(value);
                    }
                }
            }
        ],
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
                const thoiGian = w.config.xaxis.categories[dataPointIndex];
                const phimMoi = series[0][dataPointIndex] || 0;
                const phimDangChieu = series[1][dataPointIndex] || 0;
                const doanhThuDoAn = series[2][dataPointIndex] || 0;
                const nguoiDungMoi = series[3][dataPointIndex] || 0;

                return `
                    <div class="bg-dark border border-secondary rounded-3 p-3 shadow-lg" style="min-width: 320px;">
                        <div class="d-flex align-items-center mb-3">
                            <span class="fs-4 me-2">📊</span>
                            <h6 class="mb-0 text-white fw-bold">${thoiGian}</h6>
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="d-flex justify-content-between align-items-center p-2 bg-warning bg-opacity-10 rounded">
                                    <span class="text-warning fw-medium">
                                        <i class="fas fa-film me-1"></i>
                                        Phim mới:
                                    </span>
                                    <span class="fw-bold text-warning">${phimMoi}</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-between align-items-center p-2 bg-orange bg-opacity-10 rounded">
                                    <span class="text-orange fw-medium">
                                        <i class="fas fa-play me-1"></i>
                                        Đang chiếu:
                                    </span>
                                    <span class="fw-bold text-orange">${phimDangChieu}</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-between align-items-center p-2 bg-danger bg-opacity-10 rounded">
                                    <span class="text-danger fw-medium">
                                        <i class="fas fa-utensils me-1"></i>
                                        Doanh thu đồ ăn:
                                    </span>
                                    <span class="fw-bold text-danger">${new Intl.NumberFormat('vi-VN').format(doanhThuDoAn)}đ</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-between align-items-center p-2 bg-success bg-opacity-10 rounded">
                                    <span class="text-success fw-medium">
                                        <i class="fas fa-user-plus me-1"></i>
                                        Người dùng mới:
                                    </span>
                                    <span class="fw-bold text-success">${nguoiDungMoi}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }
        },
        responsive: [{
            breakpoint: 768,
            options: {
                chart: {
                    height: 300
                },
                xaxis: {
                    labels: {
                        rotate: -45,
                        style: {
                            fontSize: '9px'
                        }
                    }
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };

    // Options cho biểu đồ tròn (Pie Chart)
    const optionsPieChart = {
        series: @json($pieChartData['revenue']).map(item => item.value),
        chart: {
            type: 'pie',
            height: 350,
            background: 'transparent',
            toolbar: {
                show: true,
                tools: {
                    download: true,
                    selection: true,
                    pan: true,
                    reset: true
                }
            },
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800
            }
        },
        labels: @json($pieChartData['revenue']).map(item => item.name),
        colors: ['#007bff', '#dc3545'],
        dataLabels: {
            enabled: true,
            formatter: function(val, opts) {
                return opts.w.globals.seriesTotals.reduce((a, b) => a + b, 0) > 0
                    ? ((opts.w.globals.series[opts.seriesIndex] / opts.w.globals.seriesTotals.reduce((a, b) => a + b, 0)) * 100).toFixed(1) + '%'
                    : '0%';
            },
            style: {
                fontSize: '12px',
                fontWeight: 'bold',
                colors: ['#ffffff']
            }
        },
        legend: {
            position: 'bottom',
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
                const label = w.config.labels[dataPointIndex];
                const value = series[seriesIndex];
                const total = series.reduce((a, b) => a + b, 0);
                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;

                return `
                    <div class="bg-dark border border-secondary rounded-3 p-3 shadow-lg" style="min-width: 250px;">
                        <div class="d-flex align-items-center mb-3">
                            <span class="fs-4 me-2">📊</span>
                            <h6 class="mb-0 text-white fw-bold">${label}</h6>
                        </div>
                        <div class="row g-2">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center p-2 bg-primary bg-opacity-10 rounded">
                                    <span class="text-primary fw-medium">
                                        <i class="fas fa-money-bill-wave me-1"></i>
                                        Giá trị:
                                    </span>
                                    <span class="fw-bold text-primary">
                                        ${new Intl.NumberFormat('vi-VN').format(value)}đ
                                    </span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center p-2 bg-success bg-opacity-10 rounded">
                                    <span class="text-success fw-medium">
                                        <i class="fas fa-percentage me-1"></i>
                                        Tỷ lệ:
                                    </span>
                                    <span class="fw-bold text-success">${percentage}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }
        },
        responsive: [{
            breakpoint: 768,
            options: {
                chart: {
                    height: 300
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };

    // Options cho biểu đồ radar (Radar Chart)
    const optionsRadarChart = {
        series: [{
            name: 'Chỉ số hiệu suất',
            data: @json($radarChartData['values'])
        }],
        chart: {
            type: 'radar',
            height: 350,
            background: 'transparent',
            toolbar: {
                show: true,
                tools: {
                    download: true,
                    selection: true,
                    pan: true,
                    reset: true
                }
            },
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800
            }
        },
        colors: ['#17a2b8'],
        xaxis: {
            categories: @json($radarChartData['categories']),
            labels: {
                style: {
                    colors: '#e2e8f0',
                    fontSize: '11px',
                    fontWeight: 500
                }
            }
        },
        yaxis: {
            labels: {
                style: {
                    colors: '#e2e8f0',
                    fontSize: '11px',
                    fontWeight: 500
                },
                formatter: function(value) {
                    return value.toFixed(0) + '%';
                }
            },
            min: 0,
            max: 100
        },
        plotOptions: {
            radar: {
                polygons: {
                    strokeColors: '#2d3748',
                    strokeWidth: 1,
                    fill: {
                        colors: ['#1a202c', '#2d3748']
                    }
                }
            }
        },
        fill: {
            opacity: 0.3
        },
        stroke: {
            width: 3
        },
        markers: {
            size: 5,
            colors: ['#17a2b8'],
            strokeColors: '#17a2b8',
            strokeWidth: 2
        },
        tooltip: {
            theme: 'dark',
            custom: function({ series, seriesIndex, dataPointIndex, w }) {
                const category = w.config.xaxis.categories[dataPointIndex];
                const value = series[seriesIndex][dataPointIndex];

                return `
                    <div class="bg-dark border border-secondary rounded-3 p-3 shadow-lg" style="min-width: 250px;">
                        <div class="d-flex align-items-center mb-3">
                            <span class="fs-4 me-2">📈</span>
                            <h6 class="mb-0 text-white fw-bold">${category}</h6>
                        </div>
                        <div class="row g-2">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center p-2 bg-info bg-opacity-10 rounded">
                                    <span class="text-info fw-medium">
                                        <i class="fas fa-chart-line me-1"></i>
                                        Chỉ số:
                                    </span>
                                    <span class="fw-bold text-info">${value.toFixed(1)}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }
        },
        responsive: [{
            breakpoint: 768,
            options: {
                chart: {
                    height: 300
                }
            }
        }]
    };

    // Khởi tạo tất cả biểu đồ
    renderAllCharts();

    // Hàm cập nhật text filter
    function updateFilterText(elementId, text) {
        const element = document.getElementById(elementId);
        if (element) {
            element.textContent = text;
        }
    }

    // Lắng nghe sự kiện cập nhật filter text
    Livewire.on('updateFilterText', (event) => {
        if (event.elementId && event.text) {
            updateFilterText(event.elementId, event.text);
        }
    });

</script>
@endscript
