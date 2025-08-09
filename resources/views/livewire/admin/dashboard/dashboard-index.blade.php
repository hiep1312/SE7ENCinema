<div class="container-fluid scRender">
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

         Biểu đồ doanh thu
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
                                                1 tháng gần nhất
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
                                            wire:click.prevent="changeRevenuePeriod('3_days')">
                                            <i class="fas fa-calendar-alt me-2"></i>3 ngày gần nhất</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changeRevenuePeriod('7_days')">
                                            <i class="fas fa-calendar-week me-2"></i>7 ngày gần nhất</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changeRevenuePeriod('30_days')">
                                            <i class="fas fa-calendar-alt me-2"></i>30 ngày gần nhất</a></li>
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
                                            wire:click.prevent="changeRevenuePeriod('1_month')">
                                            <i class="fas fa-calendar me-2"></i>1 tháng</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changeRevenuePeriod('3_months')">
                                            <i class="fas fa-calendar me-2"></i>3 tháng</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changeRevenuePeriod('6_months')">
                                            <i class="fas fa-calendar me-2"></i>6 tháng</a></li>
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
                                            wire:click.prevent="changeRevenuePeriod('1_year')">
                                            <i class="fas fa-calendar-check me-2"></i>1 năm</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changeRevenuePeriod('2_years')">
                                            <i class="fas fa-calendar-check me-2"></i>2 năm</a></li>
                                    <li><a class="dropdown-item" href="#"
                                            wire:click.prevent="changeRevenuePeriod('3_years')">
                                            <i class="fas fa-calendar-check me-2"></i>3 năm</a></li>
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
    let revenueChart;

    // Khởi tạo biểu đồ
    initRevenueChart();

    // Lắng nghe sự kiện cập nhật biểu đồ
    Livewire.on('updateRevenueChart', (event) => {
        if (revenueChart) {
            const chartData = event.data;
            revenueChart.updateSeries([{
                name: 'Doanh thu (VNĐ)',
                data: chartData.map(item => ({
                    x: item.x,
                    y: item.y,
                    bookings: item.bookings
                }))
            }]);
            revenueChart.updateOptions({
                xaxis: {
                    categories: chartData.map(item => item.x)
                }
            });
        }
    });

    function initRevenueChart() {
        const chartData = @json($chartData);

        const options = {
            series: [{
                name: 'Doanh thu (VNĐ)',
                data: chartData.map(item => ({
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
                categories: chartData.map(item => item.x),
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

        revenueChart = createScChart(document.querySelector("#revenueChart"), options);
    }

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
