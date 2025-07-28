<div class="scRender content-wrapper">
    <!-- Statistics Cards -->
    <div class="row mb-4 g-3">
        <div class="col-lg-3 col-md-6">
            <div class="card text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Tổng số phòng</h6>
                            <h3 class="mb-0">{{ number_format($totalRooms, 0, '.', '.') }}</h3>
                            <small>Phòng chiếu</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fa-solid fa-door-open fa-2x opacity-75"></i>
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
                            <h6 class="card-title">Tổng sức chứa</h6>
                            <h3 class="mb-0">{{ number_format($totalCapacity, 0, '.', '.') }}</h3>
                            <small>Ghế</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fa-solid fa-loveseat fa-2x opacity-75"></i>
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
                            <h6 class="card-title">Doanh thu hôm nay</h6>
                            <h3 class="mb-0" style="color: #ffefbe; text-shadow: 0 0 6px rgba(197, 169, 86, 0.8);">
                                {{ number_format($todayRevenue, 0, '.', '.') }}đ
                            </h3>
                            <small>VNĐ</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fa-solid fa-money-bill-wave fa-2x opacity-75"></i>
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
                            <h6 class="card-title">Vé bán hôm nay</h6>
                            <h3 class="mb-0">{{ number_format($todayTickets, 0, '.', '.') }}</h3>
                            <small>Vé</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fa-solid fa-ticket fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Container -->
    <div class="row">
        <!-- Today Tickets Chart -->
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="card-title">Vé Đã Bán Theo Phòng</p>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm {{ $ticketsPeriod === 'today' ? 'btn-primary' : 'btn-outline-primary' }}"
                                wire:click="changeTicketsPeriod('today')">Hôm nay</button>
                            <button type="button" class="btn btn-sm {{ $ticketsPeriod === 'week' ? 'btn-primary' : 'btn-outline-primary' }}"
                                wire:click="changeTicketsPeriod('week')">Tuần</button>
                            <button type="button" class="btn btn-sm {{ $ticketsPeriod === 'month' ? 'btn-primary' : 'btn-outline-primary' }}"
                                wire:click="changeTicketsPeriod('month')">Tháng</button>
                        </div>
                        <i class="fas fa-ticket text-muted"></i>
                    </div>
                    <div id="todayTicketsChart"></div>
                </div>
            </div>
        </div>

        <!-- Occupancy Rate Chart -->
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="card-title">Tỷ Lệ Lấp Đầy Phòng (%)</p>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm {{ $occupancyPeriod === 'today' ? 'btn-success' : 'btn-outline-success' }}"
                                wire:click="changeOccupancyPeriod('today')">Hôm nay</button>
                            <button type="button" class="btn btn-sm {{ $occupancyPeriod === 'week' ? 'btn-success' : 'btn-outline-success' }}"
                                wire:click="changeOccupancyPeriod('week')">Tuần</button>
                            <button type="button" class="btn btn-sm {{ $occupancyPeriod === 'month' ? 'btn-success' : 'btn-outline-success' }}"
                                wire:click="changeOccupancyPeriod('month')">Tháng</button>
                        </div>
                        <i class="fas fa-percentage text-muted"></i>
                    </div>
                    <div id="occupancyChart"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">

        <!-- Room Revenue Chart -->
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="card-title">Doanh Thu Từng Phòng (VNĐ)</p>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm {{ $revenuePeriod === 'daily' ? 'btn-info' : 'btn-outline-info' }}"
                                wire:click="changeRevenuePeriod('daily')">30 ngày</button>
                            <button type="button" class="btn btn-sm {{ $revenuePeriod === 'monthly' ? 'btn-info' : 'btn-outline-info' }}"
                                wire:click="changeRevenuePeriod('monthly')">Năm nay</button>
                            <button type="button" class="btn btn-sm {{ $revenuePeriod === 'yearly' ? 'btn-info' : 'btn-outline-info' }}"
                                wire:click="changeRevenuePeriod('yearly')">2 năm</button>
                        </div>
                        <i class="fas fa-money-bill-wave text-muted"></i>
                    </div>
                    <div id="roomRevenueChart"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Top Movies Chart -->
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="card-title">Top Phim Được Xem Nhiều Nhất</p>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm {{ $moviesPeriod === 'daily' ? 'btn-danger' : 'btn-outline-danger' }}"
                                wire:click="changeMoviesPeriod('daily')">30 ngày</button>
                            <button type="button" class="btn btn-sm {{ $moviesPeriod === 'monthly' ? 'btn-danger' : 'btn-outline-danger' }}"
                                wire:click="changeMoviesPeriod('monthly')">Năm nay</button>
                            <button type="button" class="btn btn-sm {{ $moviesPeriod === 'yearly' ? 'btn-danger' : 'btn-outline-danger' }}"
                                wire:click="changeMoviesPeriod('yearly')">2 năm</button>
                        </div>
                        <i class="fas fa-film text-muted"></i>
                    </div>
                    <div id="topMoviesChart"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
// Định nghĩa function createScChart
function createScChart(element, options) {
    const chart = new ApexCharts(element, options);
    chart.render();
    return chart;
}

document.addEventListener('DOMContentLoaded', function() {
    // 1. Biểu đồ vé đã bán theo phòng
    const todayTicketsElement = document.querySelector('#todayTicketsChart');
    if (todayTicketsElement) {
        var todayTicketsOptions = {
            chart: {
                width: '100%',
                height: 350,
                type: 'bar',
                toolbar: {
                    show: false
                }
            },
            series: [{
                name: 'Vé đã bán',
                data: @json(array_values($todayTicketsData))
            }],
            xaxis: {
                categories: @json(array_keys($todayTicketsData)),
                labels: {
                    style: {
                        colors: '#9aa0ac'
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: '#9aa0ac'
                    }
                }
            },
            colors: ['#4B49AC'],
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    horizontal: false,
                }
            },
            dataLabels: {
                enabled: true,
                style: {
                    colors: ['#fff']
                }
            },
            grid: {
                borderColor: '#e7e7e7',
                row: {
                    colors: ['transparent', 'transparent'],
                    opacity: 0.5
                }
            },
            title: {
                text: 'Tổng số vé đã bán theo phòng',
                align: 'center',
                style: {
                    color: '#9aa0ac'
                }
            }
        };
        const todayTicketsChart = createScChart(todayTicketsElement, todayTicketsOptions);
    }

    // 2. Biểu đồ tỷ lệ lấp đầy phòng
    const occupancyElement = document.querySelector('#occupancyChart');
if (occupancyElement) {
    var occupancyOptions = {
        chart: {
            width: '100%',
            height: 350,
            type: 'line', // Thay đổi từ 'donut' thành 'line'
            toolbar: {
                show: false
            }
        },
        series: [{
            name: 'Tỷ lệ lấp đầy (%)',
            data: @json(array_values($occupancyRateData))
        }],
        xaxis: {
            categories: @json(array_keys($occupancyRateData)),
            labels: {
                style: {
                    colors: '#9aa0ac'
                }
            }
        },
        yaxis: {
            min: 0,
            max: 100,
            labels: {
                style: {
                    colors: '#9aa0ac'
                },
                formatter: function (val) {
                    return val.toFixed(1) + "%";
                }
            },
            title: {
                text: 'Tỷ lệ (%)',
                style: {
                    color: '#9aa0ac'
                }
            }
        },
        colors: ['#4B49AC'], // Sử dụng một màu chính
        stroke: {
            curve: 'smooth',
            width: 3
        },
        markers: {
            size: 6,
            strokeWidth: 2,
            strokeColors: '#fff',
            fillColors: '#4B49AC',
            hover: {
                size: 8
            }
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'light',
                type: 'vertical',
                shadeIntensity: 0.3,
                gradientToColors: ['#7DA0FA'],
                inverseColors: false,
                opacityFrom: 0.8,
                opacityTo: 0.1,
                stops: [0, 100]
            }
        },
        grid: {
            borderColor: '#e7e7e7',
            strokeDashArray: 5,
            row: {
                colors: ['transparent', 'transparent'],
                opacity: 0.5
            }
        },
        dataLabels: {
            enabled: true,
            formatter: function (val) {
                return val.toFixed(1) + "%";
            },
            style: {
                fontSize: '12px',
                colors: ['#304758']
            },
            background: {
                enabled: true,
                foreColor: '#fff',
                borderRadius: 2,
                padding: 4,
                opacity: 0.9,
                borderWidth: 1,
                borderColor: '#4B49AC'
            }
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val.toFixed(1) + "%";
                }
            }
        },
        title: {
            text: 'Tỷ lệ lấp đầy từng phòng',
            align: 'center',
            style: {
                color: '#9aa0ac',
                fontSize: '14px',
                fontWeight: 500
            }
        },
        legend: {
            show: false // Ẩn legend vì chỉ có 1 series
        }
    };
    const occupancyChart = createScChart(occupancyElement, occupancyOptions);
}


    // 4. Biểu đồ doanh thu từng phòng
    const roomRevenueElement = document.querySelector('#roomRevenueChart');
    if (roomRevenueElement) {
        var roomRevenueOptions = {
            chart: {
                width: '100%',
                height: 350,
                type: 'area',
                toolbar: {
                    show: false
                }
            },
            series: [{
                name: 'Doanh thu',
                data: @json(array_values($roomRevenueData))
            }],
            xaxis: {
                categories: @json(array_keys($roomRevenueData)),
                labels: {
                    style: {
                        colors: '#9aa0ac'
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: '#9aa0ac'
                    },
                    formatter: function (val) {
                        return (val / 1000000).toFixed(1) + 'M';
                    }
                }
            },
            colors: ['#4B49AC'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.3,
                    stops: [0, 90, 100]
                }
            },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            markers: {
                size: 5,
                strokeWidth: 0,
                hover: {
                    size: 8
                }
            },
            grid: {
                borderColor: '#e7e7e7',
                row: {
                    colors: ['transparent', 'transparent'],
                    opacity: 0.5
                }
            },
            dataLabels: {
                enabled: false
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return new Intl.NumberFormat('vi-VN', {
                            style: 'currency',
                            currency: 'VND'
                        }).format(val);
                    }
                }
            },
            title: {
                text: 'Doanh thu theo phòng chiếu',
                align: 'center',
                style: {
                    color: '#9aa0ac'
                }
            }
        };
        const roomRevenueChart = createScChart(roomRevenueElement, roomRevenueOptions);
    }

    // 5. Biểu đồ top phim được xem nhiều nhất
    const topMoviesElement = document.querySelector('#topMoviesChart');
    if (topMoviesElement) {
        var topMoviesOptions = {
            chart: {
                width: '100%',
                height: 400,
                type: 'bar',
                toolbar: {
                    show: false
                }
            },
            series: [
                {
                    name: 'Số vé bán',
                    data: @json($topMoviesData['tickets'] ?? [])
                },
                {
                    name: 'Doanh thu',
                    data: @json($topMoviesData['revenue'] ?? [])
                }
            ],
            xaxis: {
                categories: @json($topMoviesData['labels'] ?? []),
                labels: {
                    style: {
                        colors: '#9aa0ac'
                    },
                    rotate: -45
                }
            },
            yaxis: [
                {
                    title: {
                        text: 'Số vé bán',
                        style: {
                            color: '#9aa0ac'
                        }
                    },
                    labels: {
                        style: {
                            colors: '#9aa0ac'
                        }
                    }
                },
                {
                    opposite: true,
                    title: {
                        text: 'Doanh thu (VNĐ)',
                        style: {
                            color: '#9aa0ac'
                        }
                    },
                    labels: {
                        style: {
                            colors: '#9aa0ac'
                        },
                        formatter: function (val) {
                            return (val / 1000000).toFixed(1) + 'M';
                        }
                    }
                }
            ],
            colors: ['#4B49AC', '#7DA0FA'],
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    horizontal: false,
                    columnWidth: '60%'
                }
            },
            dataLabels: {
                enabled: false
            },
            grid: {
                borderColor: '#e7e7e7',
                row: {
                    colors: ['transparent', 'transparent'],
                    opacity: 0.5
                }
            },
            legend: {
                position: 'top',
                labels: {
                    colors: '#9aa0ac'
                }
            },
            tooltip: {
                y: {
                    formatter: function (val, opts) {
                        if (opts.seriesIndex === 0) {
                            return val + ' vé';
                        } else {
                            return new Intl.NumberFormat('vi-VN', {
                                style: 'currency',
                                currency: 'VND'
                            }).format(val);
                        }
                    }
                }
            },
            title: {
                text: 'Top phim được xem nhiều nhất',
                align: 'center',
                style: {
                    color: '#9aa0ac'
                }
            }
        };
        const topMoviesChart = createScChart(topMoviesElement, topMoviesOptions);
    }
});

// Re-render charts when Livewire updates
document.addEventListener('livewire:updated', () => {
    // Destroy existing charts and recreate them
    setTimeout(() => {
        location.reload();
    }, 100);
});
</script>
