<div class="scRender" wire:poll>
    
    <div class="container-fluid">
        <div class="row mt-3">
            <div class="col-12">
                <div class="dashboard-header px-4 mt-2 animate-fade-in">
                    <div class="header-content">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="text-white mb-2 fw-bold">
                                    <i class="fas fa-chart-line me-3"></i>
                                    Biểu đồ thống kê
                                </h3>
                                <p class="text-white-50 mb-0">
                                    <i class="fas fa-calendar-alt me-2"></i>
                                    Theo dõi và phân tích dữ liệu bán vé theo thời gian
                                </p>
                            </div>
                            <div class="text-end">
                                <div class="badge bg-light text-dark px-3 py-2 rounded-pill">
                                    <i class="fas fa-clock me-1"></i>
                                    Cập nhật: {{ now()->format('Y-m-d') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="filter-card animate-fade-in">
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="mdi mdi-calendar-start me-2"></i>
                                    Từ ngày
                                </label>
                                <input wire:model.live='fromDate' type="date" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="mdi mdi-clock-time-four me-2"></i>
                                    Khoảng thời gian
                                </label>
                                <select wire:model.live='rangeDays' class="form-select">
                                    <option value="">Chọn</option>
                                    <option value="2 days">2 ngày</option>
                                    <option value="3 days">3 ngày</option>
                                    <option value="7 days">7 ngày</option>
                                    <option value="15 days">15 ngày</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>


    <div class="content-wrapper">
        <div class="row">
            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card bg-dark border-secondary shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-primary mb-2">
                                    <i class="mdi mdi-movie me-2" style="font-size: 14px;"></i>
                                    Tổng số phim đang chiếu
                                </h6>
                                <div class="d-flex align-items-center mb-2">
                                    <h4 class="mb-0 text-white fw-bold">{{ number_format($totalMoviesShowing) }}</h4>
                                    @if($moviesShowingGrowthPercent != 0)
                                        <span class="badge ms-2 {{ $moviesShowingGrowthPercent > 0 ? 'bg-success' : 'bg-danger' }}">
                                            <small>
                                                {{ $moviesShowingGrowthPercent > 0 ? '+' : '' }}{{ $moviesShowingGrowthPercent }}%
                                            </small>
                                        </span>
                                    @else
                                        <span class="badge ms-2 bg-secondary">
                                            <small>0%</small>
                                        </span>
                                    @endif
                                </div>
                                <p class="text-muted mb-0"><small>So với tháng trước</small></p>
                            </div>
                            <div class="text-primary">
                                <i class="mdi mdi-movie opacity-75" style="font-size: 24px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card bg-dark border-secondary shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-warning mb-2">
                                    <i class="mdi mdi-cash-multiple me-2" style="font-size: 14px;"></i>
                                    Doanh thu
                                </h6>
                                <div class="d-flex align-items-center mb-2">
                                    <h4 class="mb-0 text-white fw-bold">{{ number_format($totalRevenueThisYear) }}đ</h4>
                                    @if($revenueYearGrowthPercent != 0)
                                        <span class="badge ms-2 {{ $revenueYearGrowthPercent > 0 ? 'bg-success' : 'bg-danger' }}">
                                            <small>
                                                {{ $revenueYearGrowthPercent > 0 ? '+' : '' }}{{ $revenueYearGrowthPercent }}%
                                            </small>
                                        </span>
                                    @else
                                        <span class="badge ms-2 bg-secondary">
                                            <small>0%</small>
                                        </span>
                                    @endif
                                </div>
                                <p class="text-muted mb-0"><small>So với tháng trước</small></p>
                            </div>
                            <div class="text-warning">
                                <i class="mdi mdi-cash-multiple opacity-75" style="font-size: 24px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card bg-dark border-secondary shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-success mb-2">
                                    <i class="mdi mdi-ticket-confirmation me-2" style="font-size: 14px;"></i>
                                    Số vé bán ra
                                </h6>
                                <div class="d-flex align-items-center mb-2">
                                    <h4 class="mb-0 text-white fw-bold">{{ number_format($totalPaidTicketsToday) }}</h4>
                                    @if($ticketsTodayGrowthPercent != 0)
                                        <span class="badge ms-2 {{ $ticketsTodayGrowthPercent > 0 ? 'bg-success' : 'bg-danger' }}">
                                            <small>
                                                {{ $ticketsTodayGrowthPercent > 0 ? '+' : '' }}{{ $ticketsTodayGrowthPercent }}%
                                            </small>
                                        </span>
                                    @else
                                        <span class="badge ms-2 bg-secondary">
                                            <small>0%</small>
                                        </span>
                                    @endif
                                </div>
                                <p class="text-muted mb-0"><small>So với tháng trước</small></p>
                            </div>
                            <div class="text-success">
                                <i class="mdi mdi-ticket-confirmation opacity-75" style="font-size: 24px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
            <!-- Card 4: New Users This Month -->
            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card bg-dark border-secondary shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-info mb-2">
                                    <i class="mdi mdi-account-plus me-2" style="font-size: 14px;"></i>
                                    Người dùng mới
                                </h6>
                                <div class="d-flex align-items-center mb-2">
                                    <h4 class="mb-0 text-white fw-bold">{{ number_format($totalActiveUsers) }}</h4>
                                    @if($activeUsersGrowthPercent != 0)
                                        <span class="badge ms-2 {{ $activeUsersGrowthPercent > 0 ? 'bg-success' : 'bg-danger' }}">
                                            <small>
                                                {{ $activeUsersGrowthPercent > 0 ? '+' : '' }}{{ $activeUsersGrowthPercent }}%
                                            </small>
                                        </span>
                                    @else
                                        <span class="badge ms-2 bg-secondary">
                                            <small>0%</small>
                                        </span>
                                    @endif
                                </div>
                                <p class="text-muted mb-0"><small>So với tháng trước</small></p>
                            </div>
                            <div class="text-info">
                                <i class="mdi mdi-account-plus opacity-75" style="font-size: 24px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Các biểu đồ --> 
        <div class="row">
            <!-- Biểu đồ doanh thu -->

            <div class="col-12 mb-3">
                <div class="card bg-dark border-secondary shadow-sm">
                    <div class="card-header bg-transparent border-secondary">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0 text-white">
                                <i class="fas fa-chart-area me-2 text-primary" style="font-size: 16px;"></i>
                                Biểu đồ doanh thu
                            </h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div wire:ignore id="revenueChart" style="height: 450px;"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0">Cơ cấu khách hàng: Giới tính & Độ tuổi</h5>
                        </div>
                        <div wire:ignore>
                            <div id="transactionHistoryChart" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Biểu đồ doanh thu vé và đồ ăn -->
            <div class="col-md-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0">Xu hướng doanh thu vé & đồ ăn</h5>
                        </div>
                        <div wire:ignore>
                            <div id="revenueSourceChart" style="height: 350px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top phim và đồ ăn -->
        <div class="row">
            <!-- Top phim theo doanh thu -->
            <div class="col-xl-6 col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0">Top phim theo doanh thu</h5>
                        </div>
                        <div wire:ignore>
                            <div id="topMoviesChart" style="height: 400px;"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Top đồ ăn bán chạy -->
            <div class="col-xl-6 col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0">Top đồ ăn bán chạy</h5>
                        </div>
                        <div wire:ignore>
                            <div id="topFoodsChart" style="height: 400px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Phân tích đặt ghế -->
        <div class="row">
            <div class="col-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0">Phân tích đặt ghế</h5>
                        </div>
                        <div wire:ignore>
                            <div id="seatsAnalysisChart" style="height: 300px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        

        <!-- Khung giờ suất chiếu -->
            <div class="col-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0">Khung giờ suất chiếu phổ biến</h5>
                        </div>
                        <div wire:ignore>
                            <div id="showtimeTimeSlotChart" style="height: 300px;"></div>
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
    </style>
</div>

@script
<script>
         {!! $revenue->compileJavascript() !!}
         {!! $transactionHistory->compileJavascript() !!}
         {!! $revenueSource->compileJavascript() !!}
         {!! $topMovies->compileJavascript() !!}
         {!! $topFoods->compileJavascript() !!}
         {!! $seatsAnalysis->compileJavascript() !!}
         {!! $showtimeTimeSlot->compileJavascript() !!}

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
</script>
@endscript
