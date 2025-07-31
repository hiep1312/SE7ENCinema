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
                                    {{ number_format(
                                        $booking->foodOrderItems->sum(fn($foodOrderItem) => $foodOrderItem->price * $foodOrderItem->quantity),
                                        0,
                                        '.',
                                        '.',
                                    ) }}đ
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
                <div class="container-fluid">
                    <div class="row g-4">
                        <!-- Revenue Chart -->
                        <div class="col-xl-6 col-lg-12">
                            <div class="bg-dark rounded-3 p-3">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="text-white mb-0">
                                        <i class="fas fa-chart-line me-2 text-primary"></i>Doanh thu
                                    </h5>
                                    {{-- Revenue Chart Filter --}}
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

                                                    @case('15_days')
                                                        15 ngày gần nhất
                                                    @break

                                                    @case('30_days')
                                                        30 ngày gần nhất
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
                                                    wire:click.prevent="changeRevenuePeriod('3_days')">3 ngày gần
                                                    nhất</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeRevenuePeriod('7_days')">7 ngày gần
                                                    nhất</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeRevenuePeriod('15_days')">15 ngày gần
                                                    nhất</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeRevenuePeriod('30_days')">30 ngày gần
                                                    nhất</a>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <h6 class="dropdown-header text-primary">Tháng</h6>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeRevenuePeriod('3_months')">3 tháng gần
                                                    nhất</a></li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeRevenuePeriod('6_months')">6 tháng gần
                                                    nhất</a></li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeRevenuePeriod('9_months')">9 tháng gần
                                                    nhất</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <h6 class="dropdown-header text-primary">Năm</h6>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeRevenuePeriod('1_year')">1 năm gần
                                                    nhất</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeRevenuePeriod('2_years')">2 năm gần
                                                    nhất</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeRevenuePeriod('3_years')">3 năm gần
                                                    nhất</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeRevenuePeriod('6_years')">6 năm gần
                                                    nhất</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div>
                                    <div wire:ignore id="revenueChart" style="height: 400px;"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Movies Summary Chart (Top phim theo doanh thu) -->
                        <div class="col-xl-6 col-lg-12">
                            <div class="bg-dark rounded-3 p-3">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="text-white mb-0">
                                        <i class="fas fa-film me-2 text-info"></i>Top phim theo doanh thu
                                    </h5>
                                    {{-- Top Movies Chart Filter --}}
                                    <div class="dropdown" wire:ignore>
                                        <button class="btn btn-outline-info btn-sm dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-filter me-1"></i>
                                            <span id="topMoviesFilterText">
                                                @switch($topMoviesPeriod)
                                                    @case('3_days')
                                                        3 ngày gần nhất
                                                    @break

                                                    @case('7_days')
                                                        7 ngày gần nhất
                                                    @break

                                                    @case('15_days')
                                                        15 ngày gần nhất
                                                    @break

                                                    @case('30_days')
                                                        30 ngày gần nhất
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
                                                    wire:click.prevent="changeTopMoviesPeriod('3_days')">3 ngày gần
                                                    nhất</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeTopMoviesPeriod('7_days')">7 ngày gần
                                                    nhất</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeTopMoviesPeriod('15_days')">15 ngày gần
                                                    nhất</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeTopMoviesPeriod('30_days')">30 ngày gần
                                                    nhất</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <h6 class="dropdown-header text-info">Tháng</h6>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeTopMoviesPeriod('3_months')">3 tháng gần
                                                    nhất</a></li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeTopMoviesPeriod('6_months')">6 tháng gần
                                                    nhất</a></li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeTopMoviesPeriod('9_months')">9 tháng gần
                                                    nhất</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <h6 class="dropdown-header text-info">Năm</h6>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeTopMoviesPeriod('1_year')">1 năm gần
                                                    nhất</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeTopMoviesPeriod('2_years')">2 năm gần
                                                    nhất</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeTopMoviesPeriod('3_years')">3 năm gần
                                                    nhất</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeTopMoviesPeriod('6_years')">6 năm gần
                                                    nhất</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div>
                                    <div wire:ignore id="moviesSummaryChart" style="height: 400px;"></div>
                                </div>
                            </div>
                        </div>



                        <!-- Phân tích đặt đồ ăn & thức uống -->
                        <div class="col-xl-6 col-lg-12">
                            <div class="bg-dark rounded-3 p-3">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="text-white mb-0">
                                        <i class="fas fa-utensils me-2 text-warning"></i>Phân tích đặt đồ ăn & thức uống
                                    </h5>
                                    {{-- Foods Chart Filter --}}
                                    <div class="dropdown" wire:ignore>
                                        <button class="btn btn-outline-warning btn-sm dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-filter me-1"></i>
                                            <span id="foodsFilterText">
                                                @switch($foodsPeriod)
                                                    @case('3_days')
                                                        3 ngày gần nhất
                                                    @break

                                                    @case('7_days')
                                                        7 ngày gần nhất
                                                    @break

                                                    @case('15_days')
                                                        15 ngày gần nhất
                                                    @break

                                                    @case('30_days')
                                                        30 ngày gần nhất
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
                                                    wire:click.prevent="changeFoodsPeriod('3_days')">3 ngày gần
                                                    nhất</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeFoodsPeriod('7_days')">7 ngày gần
                                                    nhất</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeFoodsPeriod('15_days')">15 ngày gần
                                                    nhất</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeFoodsPeriod('30_days')">30 ngày gần
                                                    nhất</a>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <h6 class="dropdown-header text-warning">Tháng</h6>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeFoodsPeriod('3_months')">3 tháng gần
                                                    nhất</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeFoodsPeriod('6_months')">6 tháng gần
                                                    nhất</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeFoodsPeriod('9_months')">9 tháng gần
                                                    nhất</a>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <h6 class="dropdown-header text-warning">Năm</h6>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeFoodsPeriod('1_year')">1 năm gần nhất</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeFoodsPeriod('2_years')">2 năm gần
                                                    nhất</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeFoodsPeriod('3_years')">3 năm gần
                                                    nhất</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeFoodsPeriod('6_years')">6 năm gần
                                                    nhất</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div>
                                    <div wire:ignore id="foodsChart" style="height: 400px;"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Top món ăn & thức uống bán chạy -->
                        <div class="col-xl-6 col-lg-12">
                            <div class="bg-dark rounded-3 p-3">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="text-white mb-0">
                                        <i class="fas fa-trophy me-2 text-danger"></i>Top đồ ăn bán chạy
                                    </h5>
                                    {{-- Top Foods Chart Filter --}}
                                    <div class="dropdown" wire:ignore>
                                        <button class="btn btn-outline-danger btn-sm dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-filter me-1"></i>
                                            <span id="topFoodsFilterText">
                                                @switch($topFoodsPeriod)
                                                    @case('3_days')
                                                        3 ngày gần nhất
                                                    @break

                                                    @case('7_days')
                                                        7 ngày gần nhất
                                                    @break

                                                    @case('15_days')
                                                        15 ngày gần nhất
                                                    @break

                                                    @case('30_days')
                                                        30 ngày gần nhất
                                                    @break

                                                    @default
                                                        7 ngày gần nhất
                                                @endswitch
                                            </span>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-dark">
                                            <li>
                                                <h6 class="dropdown-header text-danger">Ngày</h6>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeTopFoodsPeriod('3_days')">3 ngày gần
                                                    nhất</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeTopFoodsPeriod('7_days')">7 ngày gần
                                                    nhất</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeTopFoodsPeriod('15_days')">15 ngày gần
                                                    nhất</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeTopFoodsPeriod('30_days')">30 ngày gần
                                                    nhất</a>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <h6 class="dropdown-header text-danger">Tháng</h6>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeTopFoodsPeriod('3_months')">3 tháng gần
                                                    nhất</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeTopFoodsPeriod('6_months')">6 tháng gần
                                                    nhất</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeTopFoodsPeriod('9_months')">9 tháng gần
                                                    nhất</a>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <h6 class="dropdown-header text-danger">Năm</h6>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeTopFoodsPeriod('1_year')">1 năm gần nhất</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeTopFoodsPeriod('2_years')">2 năm gần
                                                    nhất</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeTopFoodsPeriod('3_years')">3 năm gần
                                                    nhất</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeTopFoodsPeriod('6_years')">6 năm gần
                                                    nhất</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div>
                                    <div wire:ignore id="topFoodsChart" style="height: 400px;"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Phân tích đặt ghế -->
                        <div class="col-xl-12 col-lg-12">
                            <div class="bg-dark rounded-3 p-3">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="text-white mb-0">
                                        <i class="fas fa-chair me-2 text-success"></i>Phân tích đặt ghế
                                    </h5>
                                    {{-- Seats Chart Filter --}}
                                    <div class="dropdown" wire:ignore>
                                        <button class="btn btn-outline-success btn-sm dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-filter me-1"></i>
                                            <span id="seatsFilterText">
                                                @switch($seatsPeriod)
                                                    @case('3_days')
                                                        3 ngày gần nhất
                                                    @break

                                                    @case('7_days')
                                                        7 ngày gần nhất
                                                    @break

                                                    @case('15_days')
                                                        15 ngày gần nhất
                                                    @break

                                                    @case('30_days')
                                                        30 ngày gần nhất
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
                                                    wire:click.prevent="changeSeatsPeriod('3_days')">3 ngày gần
                                                    nhất</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeSeatsPeriod('7_days')">7 ngày gần
                                                    nhất</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeSeatsPeriod('15_days')">15 ngày gần
                                                    nhất</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeSeatsPeriod('30_days')">30 ngày gần
                                                    nhất</a>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <h6 class="dropdown-header text-success">Tháng</h6>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeSeatsPeriod('3_months')">3 tháng gần
                                                    nhất</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeSeatsPeriod('6_months')">6 tháng gần
                                                    nhất</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeSeatsPeriod('9_months')">9 tháng gần
                                                    nhất</a>
                                            </li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <h6 class="dropdown-header text-success">Năm</h6>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeSeatsPeriod('1_year')">1 năm gần nhất</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeSeatsPeriod('2_years')">2 năm gần
                                                    nhất</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeSeatsPeriod('3_years')">3 năm gần
                                                    nhất</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#"
                                                    wire:click.prevent="changeSeatsPeriod('6_years')">6 năm gần
                                                    nhất</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div>
                                    <div wire:ignore id="seatsChart" style="height: 500px;"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            @elseif ($tabCurrent === 'overview')
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
                                                {{ $showtimeBooking->movie->genres->take(3)->implode('name', ', ') ?:
                                                    'Không
                                                                                            có thể loại' }}
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
                                                            {{ Str::limit(
                                                                $foodOrder->variant->foodItem->description ?:
                                                                'Không
                                                                                                                có mô tả',
                                                                100,
                                                                '...',
                                                            ) }}
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
                                                            {{ Str::limit(
                                                                $promotionUsage->promotion->description ?:
                                                                'Không có
                                                                                                                mô
                                                                                                                tả',
                                                                100,
                                                                '...',
                                                            ) }}
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
            @endif
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
        globalThis.chartInstances = {};

        // Function to update filter text
        function updateFilterText(elementId, text) {
            const element = document.getElementById(elementId);
            if (element) {
                element.textContent = text;
            }
        }

        Livewire.on('updateData', function([$revenueData, $topMovies, $seatsData, $foodsData, $topFoods, $filterTexts]) {
            // Cập nhật filter text từ server
            if ($filterTexts) {
                if ($filterTexts.revenueFilterText) {
                    updateFilterText('revenueFilterText', $filterTexts.revenueFilterText);
                }
                if ($filterTexts.topMoviesFilterText) {
                    updateFilterText('topMoviesFilterText', $filterTexts.topMoviesFilterText);
                }
                if ($filterTexts.seatsFilterText) {
                    updateFilterText('seatsFilterText', $filterTexts.seatsFilterText);
                }
                if ($filterTexts.foodsFilterText) {
                    updateFilterText('foodsFilterText', $filterTexts.foodsFilterText);
                }
                if ($filterTexts.topFoodsFilterText) {
                    updateFilterText('topFoodsFilterText', $filterTexts.topFoodsFilterText);
                }
            }

            // Xử lý dữ liệu an toàn cho topFoods
            const topFoodsData = $topFoods || [];
            const foodLabels = topFoodsData.length > 0 ? topFoodsData.map(item => item.food_name || 'N/A') : ['Không có dữ liệu'];
            const foodQuantities = topFoodsData.length > 0 ? topFoodsData.map(item => item.total_quantity || 0) : [0];
            const foodRevenues = topFoodsData.length > 0 ? topFoodsData.map(item => item.total_revenue || 0) : [0];
            const foodBookings = topFoodsData.length > 0 ? topFoodsData.map(item => item.total_bookings || 0) : [0];
            let optionsRevenueChart = {
                chart: {
                    height: 400,
                    type: 'line',
                    stacked: false,
                    background: 'transparent',
                    toolbar: {
                        show: true,
                        offsetX: 0,
                        offsetY: 0,
                        tools: {
                            download: true,
                            selection: true,
                            pan: true,
                            reset: true
                        },
                        export: {
                            csv: {
                                filename: 'doanh-thu',
                                columnDelimiter: ',',
                                headerCategory: 'Ngày',
                                headerValue: 'Doanh thu',
                                categoryFormatter: function(x) {
                                    return x;
                                },
                                valueFormatter: function(y) {
                                    return new Intl.NumberFormat('vi-VN').format(y);
                                }
                            },
                            svg: {
                                filename: 'doanh-thu',
                            },
                            png: {
                                filename: 'doanh-thu',
                            }
                        },
                        autoSelected: 'zoom'
                    },
                    zoom: {
                        enabled: false,
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
                    }
                },
                dataLabels: {
                    enabled: false,
                },
                colors: ['#FF1654', '#247BA0'],
                series: [{
                        name: 'Doanh thu (VND)',
                        data: $revenueData['revenue']
                    },
                    {
                        name: 'Số đơn hàng',
                        data: $revenueData['bookings']
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
                    categories: $revenueData['labels'] ,
                    labels: {
                        style: {
                            colors: '#ffffff',
                            fontSize: '12px'
                        },
                        rotate: -45,
                        rotateAlways: false,
                        maxHeight: 60
                    },
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    }
                },
                yaxis: [{
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
                    custom: function({
                        series,
                        seriesIndex,
                        dataPointIndex,
                        w
                    }) {
                        const ngay = w.globals.labels[dataPointIndex] || '';
                        const doanhThu = w.globals.series[0][dataPointIndex] || 0;
                        const donHang = w.globals.series[1][dataPointIndex] || 0;
                        const tbDoanhThu = donHang > 0 ? Math.round(doanhThu / donHang) : 0;
                        const phanTramTang = dataPointIndex > 0 ? ((doanhThu - w.globals.series[0][
                            dataPointIndex - 1
                        ]) / w.globals.series[0][dataPointIndex - 1] * 100).toFixed(1) : 0;
                        const mauPhanTram = phanTramTang >= 0 ? '#34a853' : '#ff6b6b';
                        const iconPhanTram = phanTramTang >= 0 ? '📈' : '📉';
                        const iconNgay = phanTramTang >= 0 ? '📊' : '📊';

                        return `
                        <div class="bg-dark border border-secondary rounded-3 p-3 shadow-lg" style="min-width: 300px;">
                            <div class="d-flex align-items-center mb-3">
                                <span class="fs-5 me-2">${iconNgay}</span>
                                <h6 class="mb-0 text-white fw-bold">${ngay}</h6>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-danger">💰 Doanh thu:</span>
                                <span class="fw-bold fs-6 text-danger">${new Intl.NumberFormat('vi-VN').format(doanhThu)}đ</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-info">🛒 Đơn hàng:</span>
                                <span class="fw-bold fs-6 text-info">${donHang}</span>
                            </div>

                            ${dataPointIndex > 0 ? `
                                <hr class="my-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span style="color: ${mauPhanTram}">${iconPhanTram} So với trước:</span>
                                    <span class="fw-bold" style="color: ${mauPhanTram}">${phanTramTang >= 0 ? '+' : ''}${phanTramTang}%</span>
                                </div>
                                ` : ''}
                        </div>
                    `;
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
            }
            let optionsMoviesSummaryChart = {
                chart: {
                    height: 400,
                    background: 'transparent',
                    toolbar: {
                        show: true,
                        offsetX: 0,
                        offsetY: 0,
                        tools: {
                            download: true,
                            selection: true,
                            pan: true,
                            reset: true
                        },
                        export: {
                            csv: {
                                filename: 'top-phim',
                                columnDelimiter: ',',
                                headerCategory: 'Phim',
                                headerValue: 'Doanh thu',
                                categoryFormatter: function(x) {
                                    return x;
                                },
                                valueFormatter: function(y) {
                                    return new Intl.NumberFormat('vi-VN').format(y);
                                }
                            },
                            svg: {
                                filename: 'top-phim',
                            },
                            png: {
                                filename: 'top-phim',
                            }
                        },
                        autoSelected: 'zoom'
                    },
                    zoom: {
                        enabled: false,
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
                    }
                },
                dataLabels: {
                    enabled: false,
                },
                series: [{
                        name: 'Doanh thu',
                        type: 'column',
                        data: $topMovies['revenue']
                    },
                    {
                        name: 'Vé bán',
                        type: 'area',
                        data: $topMovies['tickets']
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
                            [{
                                    offset: 0,
                                    color: '#4bc3e6',
                                    opacity: 0.7
                                },
                                {
                                    offset: 100,
                                    color: '#23272b',
                                    opacity: 0.1
                                }
                            ]
                        ]
                    }
                },
                xaxis: {
                    categories: $topMovies['labels'] ,
                    labels: {
                        style: {
                            colors: '#ffffff',
                            fontSize: '12px'
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
                yaxis: [{
                        title: {
                            text: 'Doanh thu (VND)',
                            style: {
                                color: '#ffd700'
                            }
                        },
                        labels: {
                            style: {
                                colors: '#ffd700',
                                fontSize: '12px'
                            },
                            formatter: function(value) {
                                return new Intl.NumberFormat('vi-VN').format(value);
                            }
                        }
                    },
                    {
                        opposite: true,
                        title: {
                            text: 'Vé bán',
                            style: {
                                color: '#4bc3e6'
                            }
                        },
                        labels: {
                            style: {
                                colors: '#4bc3e6',
                                fontSize: '12px'
                            },
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
                    labels: {
                        colors: '#ffffff'
                    }
                },
                tooltip: {
                    theme: 'dark',
                    custom: function({
                        series,
                        seriesIndex,
                        dataPointIndex,
                        w
                    }) {
                        const phim = w.globals.labels[dataPointIndex] || '';
                        const doanhThu = w.globals.series[0][dataPointIndex] || 0;
                        const veBan = w.globals.series[1][dataPointIndex] || 0;
                        const donHang = $topMovies['bookings'] [dataPointIndex] || 0;
                        const tbVeDon = donHang > 0 ? Math.round(veBan / donHang) : 0;
                        const tbDoanhThuDon = donHang > 0 ? Math.round(doanhThu / donHang) : 0;
                        return `
                        <div class="bg-dark border border-secondary rounded-3 p-3 shadow-lg" style="min-width: 320px;">
                            <div class="d-flex align-items-center mb-3">
                                <span class="fs-5 me-2">🎬</span>
                                <h6 class="mb-0 text-white fw-bold">${phim}</h6>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-warning">💰 Doanh thu:</span>
                                <span class="fw-bold fs-6 text-warning">${new Intl.NumberFormat('vi-VN').format(doanhThu)}đ</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-info">🎫 Vé bán:</span>
                                <span class="fw-bold fs-6 text-info">${veBan}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-white">🛒 Đơn hàng:</span>
                                <span class="fw-bold fs-6 text-white">${donHang}</span>
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
            }
            let optionsSeatsChart = {
                chart: {
                    height: 500,
                    type: 'area',
                    background: 'transparent',
                    toolbar: {
                        show: true,
                        offsetX: 0,
                        offsetY: 0,
                        tools: {
                            download: true,
                            selection: true,
                            pan: true,
                            reset: true
                        },

                        export: {
                            csv: {
                                filename: 'phan-tich-ghe',
                                columnDelimiter: ',',
                                headerCategory: 'Ngày',
                                headerValue: 'Số lượng',
                                categoryFormatter: function(x) {
                                    return x;
                                },
                                valueFormatter: function(y) {
                                    return new Intl.NumberFormat('vi-VN').format(y);
                                }
                            },
                            svg: {
                                filename: 'phan-tich-ghe',
                            },
                            png: {
                                filename: 'phan-tich-ghe',
                            }
                        },
                    },
                    zoom: {
                        enabled: false,
                        type: 'x',
                        autoScaleYaxis: true,
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
                    }
                },
                dataLabels: {
                    enabled: false
                },
                colors: ['#28a745', '#17a2b8', '#ffc107', '#dc3545'],
                series: [{
                        name: 'Tổng ghế',
                        type: 'area',
                        data: $seatsData['totalSeats']
                    },
                    {
                        name: 'Tổng đơn hàng',
                        type: 'line',
                        data: $seatsData['totalBookings']
                    },
                    {
                        name: 'Số phim',
                        type: 'column',
                        data: $seatsData['totalMovies']
                    },
                    {
                        name: 'Số phòng',
                        type: 'column',
                        data: $seatsData['totalRooms']
                    }
                ],
                fill: {
                    type: ['gradient', 'solid', 'solid'],
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.9,
                        stops: [0, 90, 100],
                        colorStops: [
                            [{
                                    offset: 0,
                                    color: '#28a745',
                                    opacity: 0.7
                                },
                                {
                                    offset: 100,
                                    color: '#23272b',
                                    opacity: 0.1
                                }
                            ]
                        ]
                    }
                },
                stroke: {
                    width: [0, 4, 2],
                    curve: 'smooth'
                },
                xaxis: {
                    categories: $seatsData['labels'] ,
                    labels: {
                        style: {
                            colors: '#ffffff',
                            fontSize: '12px'
                        },
                        rotate: -45,
                        rotateAlways: false,
                        maxHeight: 60
                    },
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                },
                yaxis: [{
                        title: {
                            text: 'Số lượng',
                            style: {
                                color: '#28a745'
                            }
                        },
                        labels: {
                            style: {
                                colors: '#28a745',
                                fontSize: '12px'
                            },
                            formatter: function(value) {
                                return new Intl.NumberFormat('vi-VN').format(value);
                            }
                        }
                    },
                    {
                        opposite: true,
                        title: {
                            text: 'Số phim/phòng',
                            style: {
                                color: '#ffc107'
                            }
                        },
                        labels: {
                            style: {
                                colors: '#ffc107',
                                fontSize: '12px'
                            },
                            formatter: function(value) {
                                return new Intl.NumberFormat('vi-VN').format(value);
                            }
                        }
                    }
                ],
                legend: {
                    position: 'top',
                    horizontalAlign: 'left',
                    labels: {
                        colors: '#ffffff'
                    }
                },
                tooltip: {
                    theme: 'dark',
                    custom: function({
                        series,
                        seriesIndex,
                        dataPointIndex,
                        w
                    }) {
                        const ngay = w.globals.labels[dataPointIndex] || '';
                        const tongGhe = w.globals.series[0][dataPointIndex] || 0;
                        const tongDonHang = w.globals.series[1][dataPointIndex] || 0;
                        const soPhim = w.globals.series[2][dataPointIndex] || 0;
                        const soPhong = w.globals.series[3][dataPointIndex] || 0;

                        return `
                        <div class="bg-dark border border-secondary rounded-3 p-3 shadow-lg" style="min-width: 300px;">
                            <div class="d-flex align-items-center mb-3">
                                <span class="fs-5 me-2">🪑</span>
                                <h6 class="mb-0 text-white fw-bold">${ngay}</h6>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-success">🪑 Tổng ghế:</span>
                                <span class="fw-bold fs-6 text-success">${new Intl.NumberFormat('vi-VN').format(tongGhe)}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-info">📋 Tổng đơn hàng:</span>
                                <span class="fw-bold fs-6 text-info">${new Intl.NumberFormat('vi-VN').format(tongDonHang)}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-warning">🎬 Số phim:</span>
                                <span class="fw-bold fs-6 text-warning">${new Intl.NumberFormat('vi-VN').format(soPhim)}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-danger">🎭 Số phòng:</span>
                                <span class="fw-bold fs-6 text-danger">${new Intl.NumberFormat('vi-VN').format(soPhong)}</span>
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
            }
            let optionsFoodsChart = {
                chart: {
                    height: 400,
                    type: 'bar',
                    background: 'transparent',
                    toolbar: {
                        show: true,
                        offsetX: 0,
                        offsetY: 0,
                        tools: {
                            download: true,
                            selection: true,
                            pan: true,
                            reset: true
                        },
                        export: {
                            csv: {
                                filename: 'phan-tich-mon-an',
                                columnDelimiter: ',',
                                headerCategory: 'Ngày',
                                headerValue: 'Số lượng',
                                categoryFormatter: function(x) {
                                    return x;
                                },
                                valueFormatter: function(y) {
                                    return new Intl.NumberFormat('vi-VN').format(y);
                                }
                            },
                            svg: {
                                filename: 'phan-tich-mon-an',
                            },
                            png: {
                                filename: 'phan-tich-mon-an',
                            }
                        },
                        autoSelected: 'zoom'
                    },
                    zoom: {
                        enabled: false
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
                    }
                },
                dataLabels: {
                    enabled: false
                },
                colors: ['#fd7e14', '#e83e8c', '#6f42c1', '#20c997'],
                series: [{
                        name: 'Tổng món ăn',
                        type: 'bar',
                        data: $foodsData['totalFoodItems']
                    },
                    {
                        name: 'Doanh thu món ăn',
                        type: 'line',
                        data: $foodsData['totalFoodRevenue']
                    },
                    {
                        name: 'TB món/đơn',
                        type: 'area',
                        data: $foodsData['avgFoodItems']
                    }
                ],
                fill: {
                    type: ['solid', 'solid', 'gradient'],
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.9,
                        stops: [0, 90, 100],
                        colorStops: [
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
                                }
                            ]
                        ]
                    }
                },
                stroke: {
                    width: [0, 4, 0],
                    curve: 'smooth'
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
                    categories: $foodsData['labels'] ,
                    labels: {
                        style: {
                            colors: '#ffffff',
                            fontSize: '12px'
                        },
                        rotate: -45,
                        rotateAlways: false,
                        maxHeight: 60
                    },
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    },
                    tickPlacement: 'on'
                },
                yaxis: [{
                        title: {
                            text: 'Số lượng',
                            style: {
                                color: '#fd7e14'
                            }
                        },
                        labels: {
                            style: {
                                colors: '#fd7e14',
                                fontSize: '12px'
                            },
                            formatter: function(value) {
                                return new Intl.NumberFormat('vi-VN').format(value);
                            }
                        }
                    },
                    {
                        opposite: true,
                        title: {
                            text: 'Doanh thu (VND)',
                            style: {
                                color: '#e83e8c'
                            }
                        },
                        labels: {
                            style: {
                                colors: '#e83e8c',
                                fontSize: '12px'
                            },
                            formatter: function(value) {
                                return new Intl.NumberFormat('vi-VN').format(value);
                            }
                        }
                    }
                ],
                legend: {
                    position: 'top',
                    horizontalAlign: 'left',
                    labels: {
                        colors: '#ffffff'
                    }
                },
                tooltip: {
                    theme: 'dark',
                    custom: function({
                        series,
                        seriesIndex,
                        dataPointIndex,
                        w
                    }) {
                        const ngay = w.globals.labels[dataPointIndex] || '';
                        const tongMonAn = w.globals.series[0][dataPointIndex] || 0;
                        const doanhThuMonAn = w.globals.series[1][dataPointIndex] || 0;
                        const tbMonDon = w.globals.series[2][dataPointIndex] || 0;

                        return `
                        <div class="bg-dark border border-secondary rounded-3 p-3 shadow-lg" style="min-width: 280px;">
                            <div class="d-flex align-items-center mb-3">
                                <span class="fs-5 me-2">🍽️</span>
                                <h6 class="mb-0 text-white fw-bold">${ngay}</h6>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-warning">🍽️ Tổng món ăn:</span>
                                <span class="fw-bold fs-6 text-warning">${new Intl.NumberFormat('vi-VN').format(tongMonAn)}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-danger">💰 Doanh thu món ăn:</span>
                                <span class="fw-bold fs-6 text-danger">${new Intl.NumberFormat('vi-VN').format(doanhThuMonAn)}đ</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-success">📊 TB món/đơn:</span>
                                <span class="fw-bold fs-6 text-success">${tbMonDon.toFixed(1)}</span>
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
            }
            let optionsTopFoodsChart = {
                chart: {
                    height: 400,
                    type: 'bar',
                    background: 'transparent',
                    toolbar: {
                        show: true,
                        offsetX: 0,
                        offsetY: 0,
                        tools: {
                            download: true,
                            selection: true,
                            pan: true,
                            reset: true
                        },
                        export: {
                            csv: {
                                filename: 'top-mon-an',
                                columnDelimiter: ',',
                                headerCategory: 'Món ăn',
                                headerValue: 'Số lượng',
                                categoryFormatter: function(x) {
                                    return x;
                                },
                                valueFormatter: function(y) {
                                    return new Intl.NumberFormat('vi-VN').format(y);
                                }
                            },
                            svg: {
                                filename: 'top-mon-an',
                            },
                            png: {
                                filename: 'top-mon-an',
                            }
                        },
                        autoSelected: 'zoom'
                    },
                    zoom: {
                        enabled: false,
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
                    }
                },
                dataLabels: {
                    enabled: false
                },
                colors: ['#dc3545', '#fd7e14', '#ffc107', '#28a745', '#17a2b8'],
                series: [{
                        name: 'Số lượng bán',
                        type: 'bar',
                        data: foodQuantities
                    },
                    {
                        name: 'Doanh thu',
                        type: 'line',
                        data: foodRevenues
                    }
                ],
                stroke: {
                    width: [0, 4],
                    curve: 'smooth'
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
                    categories: foodLabels,
                    labels: {
                        style: {
                            colors: '#ffffff',
                            fontSize: '11px'
                        },
                        rotate: -45,
                        rotateAlways: false,
                        maxHeight: 60
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
                            text: 'Số lượng',
                            style: {
                                color: '#dc3545'
                            }
                        },
                        labels: {
                            style: {
                                colors: '#dc3545',
                                fontSize: '12px'
                            },
                            formatter: function(value) {
                                return new Intl.NumberFormat('vi-VN').format(value);
                            }
                        }
                    },
                    {
                        opposite: true,
                        title: {
                            text: 'Doanh thu (VND)',
                            style: {
                                color: '#fd7e14'
                            }
                        },
                        labels: {
                            style: {
                                colors: '#fd7e14',
                                fontSize: '12px'
                            },
                            formatter: function(value) {
                                return new Intl.NumberFormat('vi-VN').format(value);
                            }
                        }
                    }
                ],
                legend: {
                    position: 'top',
                    horizontalAlign: 'left',
                    labels: {
                        colors: '#ffffff'
                    }
                },
                tooltip: {
                    theme: 'dark',
                    custom: function({
                        series,
                        seriesIndex,
                        dataPointIndex,
                        w
                    }) {
                        const monAn = w.globals.labels[dataPointIndex] || '';
                        const soLuongBan = w.globals.series[0][dataPointIndex] || 0;
                        const doanhThu = w.globals.series[1][dataPointIndex] || 0;
                        const tongDonHang = foodBookings[dataPointIndex] || 0;

                        return `
                        <div class="bg-dark border border-secondary rounded-3 p-3 shadow-lg" style="min-width: 300px;">
                            <div class="d-flex align-items-center mb-3">
                                <span class="fs-5 me-2">🏆</span>
                                <h6 class="mb-0 text-white fw-bold">${monAn}</h6>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-danger">🍽️ Số lượng bán:</span>
                                <span class="fw-bold fs-6 text-danger">${new Intl.NumberFormat('vi-VN').format(soLuongBan)}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-warning">💰 Doanh thu:</span>
                                <span class="fw-bold fs-6 text-warning">${new Intl.NumberFormat('vi-VN').format(doanhThu)}đ</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-info">📋 Tổng đơn hàng:</span>
                                <span class="fw-bold fs-6 text-info">${new Intl.NumberFormat('vi-VN').format(tongDonHang)}</span>
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
            }

            // Function to render all charts
            window.renderAllCharts = function() {
                if (Object.values(chartInstances).length > 0) {
                    // Update existing charts
                    if (chartInstances.revenueChart) {
                        chartInstances.revenueChart.updateOptions(optionsRevenueChart);
                    }
                    if (chartInstances.moviesSummaryChart) {
                        chartInstances.moviesSummaryChart.updateOptions(optionsMoviesSummaryChart);
                    }
                    if (chartInstances.seatsChart) {
                        chartInstances.seatsChart.updateOptions(optionsSeatsChart);
                    }
                    if (chartInstances.foodsChart) {
                        chartInstances.foodsChart.updateOptions(optionsFoodsChart);
                    }
                    if (chartInstances.topFoodsChart) {
                        chartInstances.topFoodsChart.updateOptions(optionsTopFoodsChart);
                    }
                } else {
                    // Create new charts
                    if (document.querySelector('#revenueChart')) {
                        chartInstances.revenueChart = createScChart(document.querySelector('#revenueChart'),
                            optionsRevenueChart);
                    }
                    if (document.querySelector('#moviesSummaryChart')) {
                        chartInstances.moviesSummaryChart = createScChart(document.querySelector(
                            '#moviesSummaryChart'), optionsMoviesSummaryChart);
                    }
                    if (document.querySelector('#seatsChart')) {
                        chartInstances.seatsChart = createScChart(document.querySelector('#seatsChart'),
                            optionsSeatsChart);
                    }
                    if (document.querySelector('#foodsChart')) {
                        chartInstances.foodsChart = createScChart(document.querySelector('#foodsChart'),
                            optionsFoodsChart);
                    }
                    if (document.querySelector('#topFoodsChart')) {
                        chartInstances.topFoodsChart = createScChart(document.querySelector('#topFoodsChart'),
                            optionsTopFoodsChart);
                    }
                }
            }
            renderAllCharts();
        })
    </script>
@endscript
