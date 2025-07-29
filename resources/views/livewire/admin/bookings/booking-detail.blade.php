@use('chillerlan\QRCode\QRCode')
<div class="scRender">
    <div class="container-lg mb-4" @if (session()->missing('deleteExpired')) wire:poll="cleanupBookingsAndUpdateData" @endif>
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Chi tiết đơn hàng: {{ $booking->booking_code }}</h2>
            <div>
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Quay lại
                </a>
            </div>
        </div>

        <div class="row mb-4 g-3">
            <div class="col-lg-3 col-md-6">
                <div class="card text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">SL ghế đã đặt</h6>
                                <h3 class="mb-0">{{ number_format($booking->seats->count(), 0, '.', '.') }}</h3>
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
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">SL món ăn đặt kèm</h6>
                                <h3 class="mb-0">{{ number_format($booking->foodOrderItems->count(), 0, '.', '.') }}
                                </h3>
                                <small>Món ăn</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fa-solid fa-utensils fa-2x opacity-75"></i>
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
                                <h6 class="card-title">Doanh thu món ăn</h6>
                                <h3 class="mb-0"
                                    style="color: #ffefbe; text-shadow: 0 0 6px rgba(197, 169, 86, 0.8);">
                                    {{ number_format($booking->foodOrderItems->sum(fn($foodOrderItem) => $foodOrderItem->price * $foodOrderItem->quantity), 0, '.', '.') }}đ
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
                                <h6 class="card-title">SL mã giảm giá sử dụng</h6>
                                <h3 class="mb-0">{{ number_format($booking->promotionUsages->count(), 0, '.', '.') }}
                                </h3>
                                <small>Mã giảm giá</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fa-solid fa-percent fa-2x opacity-75"></i>
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
                    class="nav-link @if ($tabCurrent === 'information') active bg-light text-dark @else text-light @endif"
                    wire:click="$set('tabCurrent', 'information')" style="border-top-right-radius: 0;">
                    <i class="fas fa-info-circle me-1"></i>Tổng quan
                </button>
            </li>
            <li class="nav-item">
                <button
                    class="nav-link @if ($tabCurrent === 'overview') active bg-light text-dark @else text-light @endif"
                    wire:click="$set('tabCurrent', 'overview')"
                    style="border-top-left-radius: 0; border-top-right-radius: 0;">
                    <i class="fa-solid fa-chair me-1"></i>Thông tin ghế
                </button>
            </li>
            <li class="nav-item">
                <button
                    class="nav-link @if ($tabCurrent === 'seats') active bg-light text-dark @else text-light @endif"
                    wire:click="$set('tabCurrent', 'seats')"
                    style="border-top-left-radius: 0; border-top-right-radius: 0;">
                    <i class="fa-solid fa-chair me-1"></i>Ghế đặt
                </button>
            </li>
            <li class="nav-item">
                <button
                    class="nav-link @if ($tabCurrent === 'tickets') active bg-light text-dark @else text-light @endif"
                    wire:click="$set('tabCurrent', 'tickets')"
                    style="border-top-left-radius: 0; border-top-right-radius: 0;">
                    <i class="fa-solid fa-ticket me-1"></i>Thông tin vé
                </button>
            </li>
            <li class="nav-item">
                <button
                    class="nav-link @if ($tabCurrent === 'foodsOrder') active bg-light text-dark @else text-light @endif"
                    wire:click="$set('tabCurrent', 'foodsOrder')"
                    style="border-top-left-radius: 0; border-top-right-radius: 0;">
                    <i class="fa-solid fa-burger me-1"></i>Món ăn
                </button>
            </li>
            <li class="nav-item">
                <button
                    class="nav-link @if ($tabCurrent === 'promotions') active bg-light text-dark @else text-light @endif"
                    wire:click="$set('tabCurrent', 'promotions')" style="border-top-left-radius: 0;">
                    <i class="fa-solid fa-swatchbook me-1"></i>Mã giảm giá
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content mt-1">
            <!-- Information Tab -->
            @if ($tabCurrent === 'information')
                <div class="container-fluid" wire:ignore>
                    <div class="row g-4">
                        <!-- Revenue Chart -->
                        <div class="col-lg-6">
                            <div class="bg-dark rounded-3 p-3">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="text-white mb-0">
                                        <i class="fas fa-chart-line me-2 text-primary"></i>Doanh thu
                                    </h5>
                                    {{-- Revenue Chart Filter --}}
                                    <div class="btn-group mb-2" role="group">
                                        <select wire:model="revenueYear" wire:change="changeRevenueYear($event.target.value)"
                                            class="form-select form-select-sm w-auto border-primary text-primary fw-bold"
                                            style="background: #23272b; border-width: 2px; border-radius: 0.5rem 0 0 0.5rem; cursor:pointer; padding: 2px 5px;">
                                            @foreach($availableYears as $year)
                                                <option value="{{ $year }}" class="bg-dark text-primary">Năm {{ $year }}</option>
                                            @endforeach
                                        </select>
                                        <select wire:model="revenueMonth" wire:change="changeRevenueMonth($event.target.value)"
                                            class="form-select form-select-sm w-auto border-primary text-primary fw-bold"
                                            style="background: #23272b; border-width: 2px; border-radius: 0; cursor:pointer; padding: 2px 5px;">
                                            @foreach($availableMonths as $month)
                                                <option value="{{ $month }}" class="bg-dark text-primary">Tháng {{ $month }}</option>
                                            @endforeach
                                        </select>
                                        <select wire:model="revenueDay" wire:change="changeRevenueDay($event.target.value)"
                                            class="form-select form-select-sm w-auto border-primary text-primary fw-bold"
                                            style="background: #23272b; border-width: 2px; border-radius: 0 0.5rem 0.5rem 0; cursor:pointer; padding: 2px 5px;">
                                            @foreach($availableDays as $day)
                                                <option value="{{ $day }}" class="bg-dark text-primary">Ngày {{ $day }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div id="revenueChart" style="height: 400px;"></div>
                            </div>
                        </div>

                        <!-- Movies Summary Chart (Top phim theo doanh thu) -->
                        <div class="col-lg-6">
                            <div class="bg-dark rounded-3 p-3">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="text-white mb-0">
                                        <i class="fas fa-film me-2 text-info"></i>Top phim theo doanh thu
                                    </h5>
                                    {{-- Top Movies Chart Filter --}}
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
                                <div id="moviesSummaryChart" style="height: 400px;"></div>
                            </div>
                        </div>

                    </div>
                </div>
                @script
                <script>
                    (function() {
                        // Global variable to store chart instances
                        window.chartInstances = {};

                        // Function to render all charts
                        function renderAllCharts() {
                            if (typeof window.createScChart !== 'undefined') {
                                // Clear existing chart instances
                                Object.values(window.chartInstances).forEach(chart => {
                                    if (chart && typeof chart.destroy === 'function') {
                                        chart.destroy();
                                    }
                                });
                                window.chartInstances = {};

                                // Revenue line chart
                                if (document.querySelector('#revenueChart')) {
                                    window.chartInstances.revenueChart = createScChart(document.querySelector('#revenueChart'), {
                                        chart: {
                                            height: 400,
                                            type: 'line',
                                            stacked: false,
                                            background: 'transparent',
                                            toolbar: { show: false }
                                        },
                                        dataLabels: {
                                            enabled: false
                                        },
                                        colors: ['#FF1654', '#247BA0'],
                                        series: [
                                            {
                                                name: 'Doanh thu (VND)',
                                                data: @json($revenueData['revenue'] ?? [])
                                            },
                                            {
                                                name: 'Số đơn hàng',
                                                data: @json($revenueData['bookings'] ?? [])
                                            }
                                        ],
                                        stroke: {
                                            width: [4, 4],
                                            curve: 'smooth'
                                        },
                                        plotOptions: {
                                            bar: {
                                                columnWidth: '20%'
                                            }
                                        },
                                        xaxis: {
                                            categories: @json($revenueData['labels'] ?? []),
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
                                        yaxis: [
                                            {
                                                axisTicks: {
                                                    show: true
                                                },
                                                axisBorder: {
                                                    show: true,
                                                    color: '#FF1654'
                                                },
                                            labels: {
                                                style: {
                                                        colors: '#FF1654',
                                                    fontSize: '12px'
                                                },
                                                formatter: function(value) {
                                                    return new Intl.NumberFormat('vi-VN').format(value);
                                                    }
                                                },
                                                title: {
                                                    text: 'Doanh thu (VND)',
                                                    style: {
                                                        color: '#FF1654'
                                                    }
                                                }
                                            },
                                            {
                                                opposite: true,
                                                axisTicks: {
                                                    show: true
                                                },
                                                axisBorder: {
                                                    show: true,
                                                    color: '#247BA0'
                                                },
                                            labels: {
                                                style: {
                                                        colors: '#247BA0',
                                                    fontSize: '12px'
                                                    },
                                                    formatter: function(value) {
                                                        return new Intl.NumberFormat('vi-VN').format(value);
                                                    }
                                                },
                                                title: {
                                                    text: 'Số đơn hàng',
                                                style: {
                                                        color: '#247BA0'
                                                    }
                                                }
                                            }
                                        ],
                                        tooltip: {
                                            shared: false,
                                            intersect: true,
                                            theme: 'dark',
                                            x: {
                                                show: false
                                            },
                                            y: {
                                                formatter: function(value) {
                                                    return new Intl.NumberFormat('vi-VN').format(value);
                                                }
                                            }
                                        },
                                        legend: {
                                            horizontalAlign: 'left',
                                            offsetX: 40,
                                            labels: {
                                                colors: '#ffffff'
                                            }
                                        },
                                        grid: {
                                            show: true,
                                            borderColor: '#2d3748',
                                            strokeDashArray: 0,
                                            position: 'back'
                                        }
                                    });
                                }

                                // Movies Summary chart (Top phim theo doanh thu)
                                if (document.querySelector('#moviesSummaryChart')) {
                                    window.chartInstances.moviesSummaryChart = createScChart(document.querySelector('#moviesSummaryChart'), {
                                        chart: {
                                            height: 400,
                                            background: 'transparent',
                                            toolbar: { show: false },
                                        },
                                        dataLabels: { enabled: false },
                                        series: [
                                            {
                                                name: 'Doanh thu',
                                                type: 'column',
                                                data: @json($topMovies['revenue'] ?? [])
                                            },
                                            {
                                                name: 'Vé bán',
                                                type: 'area',
                                                data: @json($topMovies['tickets'] ?? [])
                                            }
                                        ],
                                        fill: {
                                            type: ['solid', 'gradient'],
                                            gradient: {
                                                shadeIntensity: 1,
                                                opacityFrom: 0.7,
                                                opacityTo: 0.9,
                                                stops: [0, 90, 100],
                                                colorStops: [
                                                    [],
                                                    [
                                                        { offset: 0, color: '#4bc3e6', opacity: 0.7 },
                                                        { offset: 100, color: '#23272b', opacity: 0.1 }
                                                    ]
                                                ]
                                            }
                                        },
                                        xaxis: {
                                            categories: @json($topMovies['labels'] ?? []),
                                            labels: {
                                                style: {
                                                    colors: '#ffffff',
                                                    fontSize: '12px'
                                                },
                                                rotate: -45,
                                                rotateAlways: false
                                            },
                                            axisBorder: { show: false },
                                            axisTicks: { show: false }
                                        },
                                        yaxis: [
                                            {
                                                title: { text: 'Doanh thu (VND)', style: { color: '#ffd700' } },
                                                labels: {
                                                    style: { colors: '#ffd700', fontSize: '12px' },
                                                    formatter: function(value) {
                                                        return new Intl.NumberFormat('vi-VN').format(value);
                                                    }
                                                }
                                            },
                                            {
                                                opposite: true,
                                                title: { text: 'Vé bán', style: { color: '#4bc3e6' } },
                                            labels: {
                                                    style: { colors: '#4bc3e6', fontSize: '12px' },
                                                formatter: function(value) {
                                                    return new Intl.NumberFormat('vi-VN').format(value);
                                                }
                                            }
                                            }
                                        ],
                                        colors: ['#ffd700', '#4bc3e6'],
                                        plotOptions: {
                                            bar: {
                                                horizontal: false,
                                                columnWidth: '60%',
                                                endingShape: 'rounded',
                                                borderRadius: 4
                                            }
                                        },
                                        legend: {
                                            position: 'top',
                                            horizontalAlign: 'left',
                                            labels: { colors: '#ffffff' }
                                        },
                                        tooltip: {
                                            theme: 'dark',
                                            custom: function({series, seriesIndex, dataPointIndex, w}) {
                                                const phim = w.globals.labels[dataPointIndex] || '';
                                                const doanhThu = w.globals.series[0][dataPointIndex] || 0;
                                                const veBan = w.globals.series[1][dataPointIndex] || 0;
                                                const donHang = @json($topMovies['bookings'] ?? [])[dataPointIndex] || 0;
                                                return `
                                                    <div style="background:#18191a;padding:14px 18px;border-radius:10px;min-width:220px;box-shadow:0 4px 16px #0007;">
                                                        <div style="font-weight:700;font-size:16px;color:#fff;margin-bottom:10px;line-height:1.3;">🎬 ${phim}</div>
                                                        <div style="color:#ffd700;font-size:15px;margin-bottom:6px;display:flex;justify-content:space-between;align-items:center;">
                                                            <span>Doanh thu:</span> <b>${new Intl.NumberFormat('vi-VN').format(doanhThu)}đ</b>
                                                        </div>
                                                        <div style="color:#4bc3e6;font-size:15px;margin-bottom:6px;display:flex;justify-content:space-between;align-items:center;">
                                                            <span>Vé bán:</span> <b>${veBan}</b>
                                                        </div>
                                                        <div style="color:#34a853;font-size:15px;margin-bottom:10px;display:flex;justify-content:space-between;align-items:center;">
                                                            <span>Đơn hàng:</span> <b>${donHang}</b>
                                                        </div>
                                                    </div>
                                                `;
                                            }
                                        },
                                        grid: {
                                            show: true,
                                            borderColor: '#2d3748',
                                            strokeDashArray: 0,
                                            position: 'back'
                                        }
                                    });
                                }
                            }
                        }

                        // Make renderAllCharts available globally for Livewire
                        window.renderAllCharts = renderAllCharts;

                        // Initial render
                                renderAllCharts();
                    })();
                </script>
            @endscript
            @endif
            @if ($tabCurrent === 'overview')
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card bg-dark border-light">
                            <div class="card-header bg-gradient text-light"
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5><i class="fas fa-info me-2"></i>Thông tin chi tiết</h5>
                            </div>
                            <div class="card-body bg-dark"
                                style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                                <table class="table table-borderless text-light">
                                    <tr>
                                        <td><strong class="text-warning">Mã đơn hàng:</strong></td>
                                        <td><strong>{{ $booking->booking_code }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong class="text-warning">Tổng tiền:</strong></td>
                                        <td>
                                            <span class="badge bg-gradient fs-6"
                                                style="background: linear-gradient(45deg, #667eea, #764ba2);">
                                                {{ number_format($booking->total_price, 0, '.', '.') }}đ
                                            </span>
                                            @if ($booking->promotionUsages->isNotEmpty())
                                                <small class="text-danger fw-bold d-block mt-1 ms-1">-
                                                    {{ number_format($booking->promotionUsages->sum('discount_amount'), 0, '.', '.') }}đ
                                                    KM</small>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong class="text-warning">Mã giao dịch:</strong></td>
                                        <td><strong>{{ $booking->transaction_code }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong class="text-warning">Trạng thái:</strong></td>
                                        <td>
                                            @switch($booking->status)
                                                @case('pending')
                                                    <span class="badge bg-primary">Đang chờ xử lý</span>
                                                @break

                                                @case('expired')
                                                    <span class="badge bg-warning text-dark">Đã hết hạn xử lý</span>
                                                @break

                                                @case('paid')
                                                    <span class="badge bg-success">Đã thanh toán</span>
                                                @break

                                                @case('failed')
                                                    <span class="badge bg-danger">Lỗi thanh toán</span>
                                                @break
                                            @endswitch
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong class="text-warning">Phương thức thanh toán:</strong></td>
                                        <td>
                                            <span class="badge-clean-base badge-clean-gray">
                                                <i class="fa-solid fa-badge-dollar me-1"></i>
                                                @switch($booking->payment_method)
                                                    @case('credit_card')
                                                        Thẻ tín dụng
                                                    @break

                                                    @case('bank_transfer')
                                                        Chuyển khoản
                                                    @break

                                                    @case('e_wallet')
                                                        Ví điện tử
                                                    @break

                                                    @case('cash')
                                                        Tiền mặt
                                                    @break
                                                @endswitch
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong class="text-warning">Ngày đặt hàng:</strong></td>
                                        <td>{{ $booking->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mt-4 mt-lg-0">
                        <div class="card bg-dark border-light">
                            <div class="card-header bg-gradient text-light"
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5><i class="fa-solid fa-clapperboard me-2"></i>Suất chiếu</h5>
                            </div>
                            <div class="card-body bg-dark"
                                style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                                @php $showtimeBooking = $booking->showtime @endphp
                                <div class="movie-showtime-card">
                                    <div class="d-flex flex-wrap">
                                        <div class="movie-poster mb-1">
                                            @if ($poster = $showtimeBooking->movie->poster)
                                                <img src="{{ asset('storage/' . $poster) }}"
                                                    alt="Ảnh phim {{ $showtimeBooking->movie->title }}"
                                                    style="width: 100%; height: 100%; object-fit: cover;">
                                            @else
                                                <i class="fas fa-film"></i>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="movie-title">
                                                <a href="{{ route('admin.movies.detail', $showtimeBooking->movie->id) }}"
                                                    class="link-active" style="font-size: inherit">
                                                    {{ Str::limit($showtimeBooking->movie->title, 45, '...') }}
                                                </a>
                                            </div>
                                            <div class="movie-genre">
                                                <i class="fas fa-tags me-1"></i>
                                                {{ $showtimeBooking->movie->genres->take(3)->implode('name', ', ') ?: 'Không có thể loại' }}
                                                • {{ $showtimeBooking->movie->duration }} phút
                                            </div>

                                            <div class="showtime-info text-start">
                                                <i class="fas fa-door-open"></i>
                                                <span>Phòng chiếu:
                                                    <strong>{{ $showtimeBooking->room->name }}</strong></span>
                                            </div>

                                            <div class="showtime-info text-start">
                                                <i class="fas fa-clock"></i>
                                                <span>Thời gian chiếu:
                                                    <strong>{{ $showtimeBooking->start_time->format('d/m/Y H:i') }} -
                                                        {{ $showtimeBooking->end_time->format('H:i') }}</strong></span>
                                            </div>

                                            <div class="showtime-info text-start">
                                                <i class="fa-solid fa-square-dollar"></i>
                                                <span>Giá vé:
                                                    <strong>{{ number_format((int) $showtimeBooking->movie->price + (int) $showtimeBooking->price, 0, '.', '.') }}</strong></span>
                                            </div>

                                            <div class="showtime-info text-start">
                                                <i class="fas fa-circle-check"></i>
                                                <span>Trạng thái:
                                                    <strong>
                                                        @switch($showtimeBooking->status)
                                                            @case('active')
                                                                <div class="badge bg-primary"><i
                                                                        class="fa-solid fa-clapperboard-play me-1 text-light"></i>Đang
                                                                    hoạt động</div>
                                                            @break

                                                            @case('completed')
                                                                <div class="badge bg-success"><i
                                                                        class="fa-solid fa-calendar-check me-1 text-light"></i>Đã
                                                                    hoàn thành</div>
                                                            @break

                                                            @case('canceled')
                                                                <div class="badge bg-danger"><i
                                                                        class="fa-solid fa-hexagon-xmark me-1 text-light"></i>Đã
                                                                    bị hủy</div>
                                                            @break
                                                        @endswitch
                                                    </strong>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-4">
                        <div class="card bg-dark border-light">
                            <div class="card-header bg-gradient text-light"
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5><i class="fas fa-user me-2"></i>Người đặt</h5>
                            </div>
                            <div class="card-body bg-dark"
                                style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                                @php $userBooking = $booking->user @endphp
                                <div
                                    class="d-flex align-items-start justify-content-center flex-wrap flex-lg-nowrap p-3 compact-dark rounded">
                                    <div class="d-flex flex-column align-items-center me-md-3 gap-2">
                                        <div class="user-avatar-clean"
                                            style="width: 160px; aspect-ratio: 1; height: auto; margin-bottom: 0; border-radius: 50%;">
                                            @if ($userBooking->avatar)
                                                <img src="{{ asset('storage/' . $userBooking->avatar) }}" alt
                                                    style="width: 100%; height: 100%; object-fit: cover; border-radius: inherit;">
                                            @else
                                                <i class="fas fa-user icon-white" style="font-size: 60px;"></i>
                                            @endif
                                        </div>
                                        <h5 class="card-title text-center">
                                            <a class="user-name-link-dark"
                                                href="{{ route('admin.users.detail', $booking->user_id) }}"
                                                style="font-size: inherit;">
                                                {{ Str::limit($userBooking->name, 23, '...') ?? 'Không tìm thấy người dùng' }}
                                            </a>
                                        </h5>
                                    </div>
                                    <div class="flex-grow-1 w-100 text-center">
                                        <style>
                                            #user-info-table td {
                                                padding: .75rem .5rem !important;
                                            }
                                        </style>
                                        <table
                                            class="table table-md table-bordered table-striped-columns table-hover text-light text-start mb-0"
                                            id="user-info-table">
                                            <tr>
                                                <td><strong class="text-warning">Email:</strong></td>
                                                <td><strong>{{ $userBooking->email }}</strong></td>
                                            </tr>
                                            <tr>
                                                <td><strong class="text-warning">Số điện thoại:</strong></td>
                                                <td><strong>{{ $userBooking->phone }}</strong></td>
                                            </tr>
                                            <tr>
                                                <td><strong class="text-warning">Địa chỉ:</strong></td>
                                                <td>
                                                    @if ($userBooking->address)
                                                        <span
                                                            class="text-light text-wrap lh-base">{{ Str::limit($userBooking->address, 200, '...') }}</span>
                                                    @else
                                                        <span class="text-muted">Không tìm thấy địa chỉ</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong class="text-warning">Ngày sinh:</strong></td>
                                                <td>
                                                    <span>{{ $userBooking->birthday?->format('d/m/Y') ?? 'N/A' }}</span>
                                                    <small class="text-muted d-block mt-1" style="font-size: 12px">
                                                        Giới tính:
                                                        {{ $userBooking->gender === 'man' ? 'Nam' : ($userBooking->gender === 'woman' ? 'Nữ' : 'Khác') }}
                                                    </small>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong class="text-warning">Vai trò:</strong></td>
                                                <td>
                                                    @switch($userBooking->role)
                                                        @case('admin')
                                                            <span class="badge-clean-base badge-clean-yellow">
                                                                <i class="fa-solid fa-crown me-1"></i>
                                                                Quản trị viên
                                                            </span>
                                                        @break

                                                        @case('staff')
                                                            <span class="badge-clean-base badge-clean-rose">
                                                                <i class="fa-solid fa-user-tie me-1"></i>
                                                                Nhân viên
                                                            </span>
                                                        @break

                                                        @case('user')
                                                            <span class="badge-clean-base badge-clean-purple">
                                                                <i class="fa-solid fa-user me-1"></i>
                                                                Người dùng
                                                            </span>
                                                        @break
                                                    @endswitch
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong class="text-warning">Trạng thái:</strong></td>
                                                <td>
                                                    @switch($userBooking->status)
                                                        @case('active')
                                                            <span class="badge bg-success">Đang hoạt động</span>
                                                        @break

                                                        @case('inactive')
                                                            <span class="badge bg-warning text-dark">Không hoạt động</span>
                                                        @break

                                                        @case('banned')
                                                            <span class="badge bg-danger">Bị cấm</span>
                                                        @break
                                                    @endswitch
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong class="text-warning">Ngày tạo:</strong></td>
                                                <td>{{ $userBooking->created_at->format('d/m/Y H:i') }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($tabCurrent === 'seats')
                <div class="row">
                    <div class="col-12">
                        <div class="card bg-dark border-light">
                            <div class="card-header bg-gradient text-light"
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5><i class="fa-solid fa-chair-office me-2"></i>Danh sách ghế đã đặt</h5>
                            </div>
                            <div class="card-body bg-dark"
                                style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">

                            </div>
                        </div>
                    </div>
                </div>
            @elseif($tabCurrent === 'tickets')
                <div class="row">
                    <div class="col-12">
                        <div class="card bg-dark border-light">
                            <div class="card-header bg-gradient text-light"
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5><i class="fa-solid fa-film me-2"></i>Chi tiết vé phim</h5>
                            </div>
                            <div class="card-body bg-dark"
                                style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                                <table
                                    class="table table-md table-bordered table-striped table-hover text-light text-start mb-0"
                                    id="user-info-table">
                                    <thead>
                                        <tr>
                                            <th class="text-center text-light">Mã QR</th>
                                            <th class="text-center text-light">Ghi chú</th>
                                            <th class="text-center text-light">Tình trạng vé</th>
                                            <th class="text-center text-light">Trạng thái</th>
                                            <th class="text-center text-light">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tickets as $ticket)
                                            <tr wire:key="ticket-{{ $ticket->id }}">
                                                <td>
                                                    <div class="d-flex justify-content-center">
                                                        <div class="qr-code" style="margin-bottom: 0;">
                                                            <img src="{{ new QRCode()->render($ticket->qr_code) }}"
                                                                alt="QR code"
                                                                style="width: 100%; height: 100%; border-radius: 0;">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-muted text-center">
                                                    <p class="text-wrap lh-base" style="margin-bottom: 0;">
                                                        {{ Str::limit($ticket->description ?: 'Không có ghi chú', 150, '...') }}
                                                    </p>
                                                </td>
                                                <td class="text-center">
                                                    @if ($ticket->taken)
                                                        <span class="badge-clean-base badge-clean-green">
                                                            <i class="fas fa-check-circle me-1"></i>
                                                            Đã lấy vé
                                                        </span>
                                                    @else
                                                        <span class="badge-clean-base badge-clean-orange">
                                                            <i class="fas fa-times-circle me-1"></i>
                                                            Chưa lấy vé
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @switch($ticket->status)
                                                        @case('active')
                                                            <span class="badge bg-primary">Chưa sử dụng</span>
                                                        @break

                                                        @case('used')
                                                            <span class="badge bg-success">Đã sử dụng</span>
                                                        @break

                                                        @case('canceled')
                                                            <span class="badge bg-danger">Đã bị hủy</span>
                                                        @break
                                                    @endswitch
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-center">
                                                        @if ($ticket->isValidTicketOrder())
                                                            <a href="{{ route('client.ticket', [$booking->booking_code, $ticket->getCurrentIndex()]) }}"
                                                                target="_blank" class="btn btn-sm btn-outline-info"
                                                                title="Xem chi tiết">
                                                                <i class="fas fa-eye" style="margin-right: 0"></i>
                                                            </a>
                                                        @else
                                                            <button type="button" class="btn btn-sm btn-outline-info"
                                                                wire:sc-alert.error="Không thể xem chi tiết vé phim này!"
                                                                wire:sc-model title="Xem chi tiết">
                                                                <i class="fas fa-eye" style="margin-right: 0"></i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($tabCurrent === 'foodsOrder')
                <div class="row">
                    <div class="col-12">
                        <div class="card bg-dark border-light">
                            <div class="card-header bg-gradient text-light"
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5><i class="fa-solid fa-burger-soda me-2"></i>Danh sách món ăn đặt kèm</h5>
                            </div>
                            <div class="card-body bg-dark"
                                style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                                @php $foodOrderItems = $booking->foodOrderItems @endphp
                                <div class="table-responsive">
                                    <table class="table table-dark table-striped table-hover text-light">
                                        <thead>
                                            <tr>
                                                <th class="text-center text-light">Ảnh</th>
                                                <th class="text-center text-light">Tên món ăn</th>
                                                <th class="text-center text-light">Mô tả</th>
                                                <th class="text-center text-light">Chi tiết món ăn</th>
                                                <th class="text-center text-light">Giá</th>
                                                <th class="text-center text-light">Số lượng</th>
                                                <th class="text-center text-light">Tổng tiền</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($foodOrderItems as $foodOrder)
                                                <tr wire:key="food-order-{{ $foodOrder->id }}">
                                                    <td>
                                                        <div class="d-flex justify-content-center">
                                                            <div class="food-image">
                                                                @if ($foodImage = $foodOrder->variant->image)
                                                                    <img src="{{ asset('storage/' . $foodImage) }}"
                                                                        alt="Ảnh món ăn {{ $foodOrder->variant->foodItem->name }}"
                                                                        style="width: 100%; height: 100%; object-fit: cover; border-radius: 0;">
                                                                @else
                                                                    <i class="fas fa-film"></i>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <strong
                                                            class="text-light">{{ $foodOrder->variant->foodItem->name }}</strong>
                                                    </td>
                                                    <td class="text-muted text-center">
                                                        <p class="text-wrap lh-base" style="margin-bottom: 0;">
                                                            {{ Str::limit($foodOrder->variant->foodItem->description ?: 'Không có mô tả', 100, '...') }}
                                                        </p>
                                                    </td>
                                                    <td>
                                                        @foreach ($foodOrder->variant->attributeValues as $attributeValue)
                                                            <ul class="food-attributes-list">
                                                                <li>
                                                                    <i class="fas fa-leaf icon-special"></i>
                                                                    <span
                                                                        class="attr-label">{{ $attributeValue->attribute->name }}:</span>
                                                                    <span class="attr-value"
                                                                        style="color: #a78bfa;">{{ $attributeValue->value }}</span>
                                                                </li>
                                                            </ul>
                                                        @endforeach
                                                    </td>
                                                    <td class="text-center text-warning fw-bold">
                                                        {{ number_format($foodOrder->price, 0, ',', '.') }}đ
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $foodOrder->quantity }}
                                                    </td>
                                                    <td class="text-center text-warning fw-bold">
                                                        {{ number_format($foodOrder->price * $foodOrder->quantity, 0, ',', '.') }}đ
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center py-4">
                                                        <div class="text-muted">
                                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                                            <p>Không đặt kèm món ăn nào</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    @elseif($tabCurrent === 'promotions')
        <div class="row">
            <div class="col-12">
                <div class="card bg-dark border-light">
                    <div class="card-header bg-gradient text-light"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h5><i class="fa-solid fa-tag me-2"></i>Danh sách mã giảm giá đã áp dụng</h5>
                    </div>
                    <div class="card-body bg-dark"
                        style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                        @php $promotionUsages = $booking->promotionUsages @endphp
                        <div class="table-responsive">
                            <table class="table table-dark table-striped table-hover text-light">
                                <thead>
                                    <tr>
                                        <th class="text-center text-light">Mã giảm giá</th>
                                        <th class="text-center text-light">Tên mã</th>
                                        <th class="text-center text-light">Mô tả</th>
                                        <th class="text-center text-light">Số tiền giảm</th>
                                        <th class="text-center text-light">Tổng tiền đã giảm</th>
                                        <th class="text-center text-light">Ngày sử dụng</th>
                                        <th class="text-center text-light">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($promotionUsages as $promotionUsage)
                                        <tr wire:key="promotion-{{ $promotionUsage->id }}">
                                            <td class="text-center">
                                                {{ $promotionUsage->promotion?->code ?? 'N/A' }}
                                            </td>
                                            <td class="text-center">
                                                <strong
                                                    class="text-light">{{ $promotionUsage->promotion->title }}</strong>
                                            </td>
                                            <td class="text-muted text-center">
                                                <p class="text-wrap lh-base" style="margin-bottom: 0;">
                                                    {{ Str::limit($promotionUsage->promotion->description ?: 'Không có mô tả', 100, '...') }}
                                                </p>
                                            </td>
                                            <td class="text-center text-warning fw-bold">
                                                {{ number_format($promotionUsage->promotion->discount_value, 0, ',', '.') }}{{ $promotionUsage->promotion->discount_type === 'percentage' ? '%' : 'đ' }}
                                            </td>
                                            <td class="text-center text-warning fw-bold">
                                                {{ number_format($promotionUsage->discount_amount, 0, ',', '.') }}đ
                                            </td>
                                            <td class="text-center">
                                                {{ $promotionUsage->used_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <a href="{{ /* route('admin.promotions.detail', $promotionUsage->promotion->id) */ '#' }}"
                                                        class="btn btn-sm btn-info" title="Xem chi tiết">
                                                        <i class="fas fa-eye" style="margin-right: 0"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                                    <p>Không áp dụng mã giảm giá nào</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
</div>
</div>

