<div>
    <div class="content-wrapper">
        <!-- Cards thống kê chính -->
        <div class="row">
            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                <div class="d-flex align-items-center align-self-start">
                                    <h3 class="mb-0">{{ number_format($currentMonthRevenue) }}đ</h3>
                                    <p class="text-{{ $this->getRevenueGrowth() >= 0 ? 'success' : 'danger' }} ml-2 mb-0 font-weight-medium">
                                        {{ $this->getRevenueGrowth() >= 0 ? '+' : '' }}{{ $this->getRevenueGrowth() }}%
                                    </p>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="icon icon-box-{{ $this->getRevenueGrowth() >= 0 ? 'success' : 'danger' }}">
                                    <span class="mdi mdi-{{ $this->getRevenueGrowth() >= 0 ? 'arrow-top-right' : 'arrow-bottom-left' }} icon-item"></span>
                                </div>
                            </div>
                        </div>
                        <h6 class="text-muted font-weight-normal">Doanh thu tháng này</h6>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                <div class="d-flex align-items-center align-self-start">
                                    <h3 class="mb-0">{{ number_format($currentMonthBookings) }}</h3>
                                    <p class="text-{{ $this->getBookingGrowth() >= 0 ? 'success' : 'danger' }} ml-2 mb-0 font-weight-medium">
                                        {{ $this->getBookingGrowth() >= 0 ? '+' : '' }}{{ $this->getBookingGrowth() }}%
                                    </p>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="icon icon-box-{{ $this->getBookingGrowth() >= 0 ? 'success' : 'danger' }}">
                                    <span class="mdi mdi-{{ $this->getBookingGrowth() >= 0 ? 'arrow-top-right' : 'arrow-bottom-left' }} icon-item"></span>
                                </div>
                            </div>
                        </div>
                        <h6 class="text-muted font-weight-normal">Đặt vé tháng này</h6>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                <div class="d-flex align-items-center align-self-start">
                                    <h3 class="mb-0">{{ number_format($currentMonthUsers) }}</h3>
                                    <p class="text-{{ $this->getUserGrowth() >= 0 ? 'success' : 'danger' }} ml-2 mb-0 font-weight-medium">
                                        {{ $this->getUserGrowth() >= 0 ? '+' : '' }}{{ $this->getUserGrowth() }}%
                                    </p>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="icon icon-box-{{ $this->getUserGrowth() >= 0 ? 'success' : 'danger' }}">
                                    <span class="mdi mdi-{{ $this->getUserGrowth() >= 0 ? 'arrow-top-right' : 'arrow-bottom-left' }} icon-item"></span>
                                </div>
                            </div>
                        </div>
                        <h6 class="text-muted font-weight-normal">Người dùng mới</h6>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                <div class="d-flex align-items-center align-self-start">
                                    <h3 class="mb-0">{{ number_format($currentMonthMovies) }}</h3>
                                    <p class="text-{{ $this->getMovieGrowth() >= 0 ? 'success' : 'danger' }} ml-2 mb-0 font-weight-medium">
                                        {{ $this->getMovieGrowth() >= 0 ? '+' : '' }}{{ $this->getMovieGrowth() }}%
                                    </p>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="icon icon-box-{{ $this->getMovieGrowth() >= 0 ? 'success' : 'danger' }}">
                                    <span class="mdi mdi-{{ $this->getMovieGrowth() >= 0 ? 'arrow-top-right' : 'arrow-bottom-left' }} icon-item"></span>
                                </div>
                            </div>
                        </div>
                        <h6 class="text-muted font-weight-normal">Phim mới</h6>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cards thống kê chi tiết -->
        <div class="row">
            <div class="col-sm-4 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h5>Doanh thu tổng</h5>
                        <div class="row">
                            <div class="col-8 col-sm-12 col-xl-8 my-auto">
                                <div class="d-flex d-sm-block d-md-flex align-items-center">
                                    <h2 class="mb-0">{{ number_format($currentMonthRevenue) }}đ</h2>
                                    <p class="text-{{ $this->getRevenueGrowth() >= 0 ? 'success' : 'danger' }} ml-2 mb-0 font-weight-medium">
                                        {{ $this->getRevenueGrowth() >= 0 ? '+' : '' }}{{ $this->getRevenueGrowth() }}%
                                    </p>
                                </div>
                                <h6 class="text-muted font-weight-normal">So với tháng trước</h6>
                            </div>
                            <div class="col-4 col-sm-12 col-xl-4 text-center text-xl-right">
                                <i class="icon-lg mdi mdi-cash-multiple text-primary ml-auto"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-4 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h5>Đặt vé thành công</h5>
                        <div class="row">
                            <div class="col-8 col-sm-12 col-xl-8 my-auto">
                                <div class="d-flex d-sm-block d-md-flex align-items-center">
                                    <h2 class="mb-0">{{ number_format($currentMonthBookings) }}</h2>
                                    <p class="text-{{ $this->getBookingGrowth() >= 0 ? 'success' : 'danger' }} ml-2 mb-0 font-weight-medium">
                                        {{ $this->getBookingGrowth() >= 0 ? '+' : '' }}{{ $this->getBookingGrowth() }}%
                                    </p>
                                </div>
                                <h6 class="text-muted font-weight-normal">So với tháng trước</h6>
                            </div>
                            <div class="col-4 col-sm-12 col-xl-4 text-center text-xl-right">
                                <i class="icon-lg mdi mdi-ticket text-success ml-auto"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-4 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h5>Người dùng mới</h5>
                        <div class="row">
                            <div class="col-8 col-sm-12 col-xl-8 my-auto">
                                <div class="d-flex d-sm-block d-md-flex align-items-center">
                                    <h2 class="mb-0">{{ number_format($currentMonthUsers) }}</h2>
                                    <p class="text-{{ $this->getUserGrowth() >= 0 ? 'success' : 'danger' }} ml-2 mb-0 font-weight-medium">
                                        {{ $this->getUserGrowth() >= 0 ? '+' : '' }}{{ $this->getUserGrowth() }}%
                                    </p>
                                </div>
                                <h6 class="text-muted font-weight-normal">So với tháng trước</h6>
                            </div>
                            <div class="col-4 col-sm-12 col-xl-4 text-center text-xl-right">
                                <i class="icon-lg mdi mdi-account-plus text-info ml-auto"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Biểu đồ doanh thu -->
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-row justify-content-between align-items-center mb-3">
                            <h4 class="card-title mb-0">Biểu đồ doanh thu</h4>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-primary btn-sm {{ $revenuePeriod == 'week' ? 'active' : '' }}"
                                        wire:click="changeRevenuePeriod('week')">Tuần</button>
                                <button type="button" class="btn btn-outline-primary btn-sm {{ $revenuePeriod == 'month' ? 'active' : '' }}"
                                        wire:click="changeRevenuePeriod('month')">Tháng</button>
                                <button type="button" class="btn btn-outline-primary btn-sm {{ $revenuePeriod == 'year' ? 'active' : '' }}"
                                        wire:click="changeRevenuePeriod('year')">Năm</button>
                            </div>
                        </div>
                        <div id="revenueChart" style="height: 400px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @script
    <script>
        let revenueChart;

        document.addEventListener('livewire:init', () => {
            // Khởi tạo biểu đồ
            initRevenueChart();

            // Lắng nghe sự kiện cập nhật biểu đồ
            Livewire.on('updateRevenueChart', (event) => {
                if (revenueChart) {
                    revenueChart.updateSeries([{
                        name: 'Doanh thu',
                        data: event.data.map(item => item.y)
                    }]);
                    revenueChart.updateOptions({
                        xaxis: {
                            categories: event.data.map(item => item.x)
                        }
                    });
                }
            });
        });

        function initRevenueChart() {
            const options = {
                series: [{
                    name: 'Doanh thu',
                    data: @json(array_column($chartData, 'y'))
                }],
                chart: {
                    type: 'area',
                    height: 400,
                    toolbar: {
                        show: true
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                colors: ['#007bff'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.3,
                        stops: [0, 90, 100]
                    }
                },
                xaxis: {
                    categories: @json(array_column($chartData, 'x'))
                },
                yaxis: {
                    labels: {
                        formatter: function(value) {
                            return new Intl.NumberFormat('vi-VN').format(value) + 'đ';
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(value) {
                            return new Intl.NumberFormat('vi-VN').format(value) + 'đ';
                        }
                    }
                }
            };

            revenueChart = new ApexCharts(document.querySelector("#revenueChart"), options);
            revenueChart.render();
        }
    </script>
    @endscript
</div>
