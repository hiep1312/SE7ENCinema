<div class="scRender" wire:poll.6s>
    <!-- Redesigned filter section with cleaner, more compact styling -->
    <div class="container-fluid my-4">
    <!-- Main filter card with shadow and rounded corners -->
    <div class="card border-0 shadow-sm">
        <!-- Card header with accent background -->
        <div class="card-header bg-primary bg-opacity-10 border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-25 text-primary rounded-2 p-2 me-3">
                        <i class="mdi mdi-filter-variant fs-4"></i>
                    </div>
                    <div>
                        <h4 class="mb-0 fw-semibold">Bộ Lọc Thống Kê</h4>
                        <small class="text-muted">Phân tích doanh thu và hiệu suất theo thời gian</small>
                    </div>
                </div>
                <span class="badge bg-primary bg-opacity-25 text-primary">
                    <i class="mdi mdi-{{ $showComparison ? 'compare' : 'chart-line' }} me-1"></i>
                    {{ $showComparison ? 'So sánh' : 'Đơn lẻ' }}
                </span>
            </div>
        </div>

        <!-- Card body with form controls -->
        <div class="card-body">
            <div class="row g-4 mb-3">
                <!-- Date range section -->
                <div class="col-lg-5">
                    <label class="form-label fw-semibold d-flex align-items-center mb-2">
                        <i class="mdi mdi-calendar-range me-2"></i>
                        Khoảng thời gian
                    </label>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <input type="date" wire:model.live="startDate" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <input type="date" wire:model.live="endDate" class="form-control form-control-sm">
                        </div>
                    </div>
                </div>

                <!-- Custom time period -->
                <div class="col-lg-4">
                    <label class="form-label fw-semibold d-flex align-items-center mb-2">
                        <i class="mdi mdi-clock-time-four me-2"></i>
                        Thời gian tùy chỉnh
                    </label>
                    <div class="input-group input-group-sm">
                        <input type="number" wire:model.live="customValue" class="form-control" placeholder="Số" min="1">
                        <select wire:model.live="customUnit" class="form-select">
                            <option value="days">Ngày</option>
                            <option value="months">Tháng</option>
                            <option value="years">Năm</option>
                        </select>
                    </div>
                    <small class="text-muted mt-1 d-block">VD: 1 tháng, 6 tháng, 2 năm</small>
                </div>

                <!-- Action buttons -->
                <div class="col-lg-3">
                    <div class="h-100 d-flex flex-column justify-content-end">
                        <div class="d-grid gap-2">
                            <button type="button" wire:click="resetFilters" class="btn btn-outline-secondary btn-sm">
                                <i class="mdi mdi-refresh me-1"></i>
                                Đặt lại
                            </button>
                            <button type="button" wire:click="toggleComparison" class="btn btn-primary btn-sm">
                                <i class="mdi mdi-{{ $showComparison ? 'eye-off' : 'eye' }} me-1"></i>
                                {{ $showComparison ? 'Ẩn so sánh' : 'Hiện so sánh' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



    <div class="content-wrapper">
        <!-- Improved statistics cards with smaller icons and better typography -->
        <div class="row">
            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                <div class="d-flex align-items-center align-self-start">
                                    <h4 class="mb-0">{{ number_format($totalMoviesShowing) }}</h4>
                                    <p class="{{ $moviesShowingGrowthPercent >= 0 ? 'text-success' : 'text-danger' }} ms-2 mb-0 fw-medium">
                                        <small>{{ $moviesShowingGrowthPercent >= 0 ? '+' : '' }}{{ $moviesShowingGrowthPercent }}%</small>
                                    </p>
                                </div>
                            </div>
                            <div class="col-3 d-flex justify-content-end align-items-start">
                                <div class="bg-primary bg-opacity-75 rounded p-2">
                                    <i class="mdi mdi-movie text-white" style="font-size: 20px;"></i>
                                </div>
                            </div>
                        </div>
                        <h6 class="text-muted fw-normal mt-2">Tổng số phim đang chiếu</h6>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                <div class="d-flex align-items-center align-self-start">
                                    <h4 class="mb-0">{{ number_format($totalRevenueThisYear) }}đ</h4>
                                    <p class="{{ $revenueYearGrowthPercent >= 0 ? 'text-success' : 'text-danger' }} ms-2 mb-0 fw-medium">
                                        <small>{{ $revenueYearGrowthPercent >= 0 ? '+' : '' }}{{ $revenueYearGrowthPercent }}%</small>
                                    </p>
                                </div>
                            </div>
                            <div class="col-3 d-flex justify-content-end align-items-start">
                                <div class="bg-warning bg-opacity-75 rounded p-2">
                                    <i class="mdi mdi-cash-multiple text-white" style="font-size: 20px;"></i>
                                </div>
                            </div>
                        </div>
                        <h6 class="text-muted fw-normal mt-2">Doanh thu năm {{ date('Y') }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                <div class="d-flex align-items-center align-self-start">
                                    <h4 class="mb-0">{{ number_format($totalRevenueToday) }}đ</h4>
                                    <p class="{{ $revenueTodayGrowthPercent >= 0 ? 'text-success' : 'text-danger' }} ms-2 mb-0 fw-medium">
                                        <small>{{ $revenueTodayGrowthPercent >= 0 ? '+' : '' }}{{ $revenueTodayGrowthPercent }}%</small>
                                    </p>
                                </div>
                            </div>
                            <div class="col-3 d-flex justify-content-end align-items-start">
                                <div class="bg-success bg-opacity-75 rounded p-2">
                                    <i class="mdi mdi-calendar-today text-white" style="font-size: 20px;"></i>
                                </div>
                            </div>
                        </div>
                        <h6 class="text-muted fw-normal mt-2">Doanh thu hôm nay</h6>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                <div class="d-flex align-items-center align-self-start">
                                    <h4 class="mb-0">{{ number_format($totalPaidTicketsToday) }}</h4>
                                    <p class="{{ $ticketsTodayGrowthPercent >= 0 ? 'text-success' : 'text-danger' }} ms-2 mb-0 fw-medium">
                                        <small>{{ $ticketsTodayGrowthPercent >= 0 ? '+' : '' }}{{ $ticketsTodayGrowthPercent }}%</small>
                                    </p>
                                </div>
                            </div>
                            <div class="col-3 d-flex justify-content-end align-items-start">
                                <div class="bg-info bg-opacity-75 rounded p-2">
                                    <i class="mdi mdi-ticket-confirmation text-white" style="font-size: 20px;"></i>
                                </div>
                            </div>
                        </div>
                        <h6 class="text-muted fw-normal mt-2">Vé đã thanh toán hôm nay</h6>
                    </div>
                </div>
            </div>
        </div>


        <!-- Các biểu đồ -->
        <div class="row">
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0">Lịch sử giao dịch</h5>
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
                        <div wire:ignore>
                            <div id="revenueSourceChart" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0">Quản lý đồ ăn & Phim - Top theo doanh thu</h5>
                        </div>
                        <div wire:ignore>
                            <div id="foodManagementChart" style="height: 380px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Simplified detailed statistics cards with smaller icons -->
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card bg-dark border-secondary shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-primary mb-2">
                                    <i class="fas fa-cash-register me-2" style="font-size: 14px;"></i>
                                    Doanh thu tổng
                                </h6>
                                <div class="d-flex align-items-center mb-2">
                                    <h4 class="mb-0 text-white fw-bold">{{ number_format($currentMonthRevenue) }}đ</h4>
                                    <span class="badge ms-2 {{ $this->getRevenueGrowth() >= 0 ? 'bg-success' : 'bg-danger' }}">
                                        <small>{{ $this->getRevenueGrowth() >= 0 ? '+' : '' }}{{ $this->getRevenueGrowth() }}%</small>
                                    </span>
                                </div>
                                <p class="text-muted mb-0"><small>So với tháng trước</small></p>
                            </div>
                            <div class="text-primary">
                                <i class="fas fa-money-bill-wave opacity-75" style="font-size: 24px;"></i>
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
                                <h6 class="text-success mb-2">
                                    <i class="fas fa-check-circle me-2" style="font-size: 14px;"></i>
                                    Đặt vé thành công
                                </h6>
                                <div class="d-flex align-items-center mb-2">
                                    <h4 class="mb-0 text-white fw-bold">{{ number_format($currentMonthBookings) }}</h4>
                                    <span class="badge ms-2 {{ $this->getBookingGrowth() >= 0 ? 'bg-success' : 'bg-danger' }}">
                                        <small>{{ $this->getBookingGrowth() >= 0 ? '+' : '' }}{{ $this->getBookingGrowth() }}%</small>
                                    </span>
                                </div>
                                <p class="text-muted mb-0"><small>So với tháng trước</small></p>
                            </div>
                            <div class="text-success">
                                <i class="fas fa-ticket-alt opacity-75" style="font-size: 24px;"></i>
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
                                <h6 class="text-info mb-2">
                                    <i class="fas fa-user-plus me-2" style="font-size: 14px;"></i>
                                    Người dùng mới
                                </h6>
                                <div class="d-flex align-items-center mb-2">
                                    <h4 class="mb-0 text-white fw-bold">{{ number_format($currentMonthUsers) }}</h4>
                                    <span class="badge ms-2 {{ $this->getUserGrowth() >= 0 ? 'bg-success' : 'bg-danger' }}">
                                        <small>{{ $this->getUserGrowth() >= 0 ? '+' : '' }}{{ $this->getUserGrowth() }}%</small>
                                    </span>
                                </div>
                                <p class="text-muted mb-0"><small>So với tháng trước</small></p>
                            </div>
                            <div class="text-info">
                                <i class="fas fa-users opacity-75" style="font-size: 24px;"></i>
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
                            <h5 class="card-title mb-0 text-white">
                                <i class="fas fa-chart-area me-2 text-primary" style="font-size: 16px;"></i>
                                Biểu đồ doanh thu (30 ngày qua)
                            </h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div wire:ignore id="revenueChart" style="height: 450px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Simplified styling -->
    <style>
        .apexcharts-menu {
            color: black;
        }
    </style>
</div>

@script
<script>
    {!! $transactionHis->compileJavascript() !!}
    {!! $revenueSource->compileJavascript() !!}
    {!! $foodManagement->compileJavascript() !!}
    {!! $revenue->compileJavascript() !!}

    globalThis.chartInstances = globalThis.chartInstances || {};

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

    Livewire.on('updateData', function([$transactionHistoryData, $revenueSourceData, $foodManagementData]) {
        // Data updated without filter text processing
    });
</script>
@endscript
