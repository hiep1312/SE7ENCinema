<div class="scRender">
    <div class="container-lg mb-4" wire:poll.1s="calculateMaintenanceInfo">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Chi tiết phòng chiếu: {{ $room->name }}</h2>
            <div>
                <a href="{{ route('admin.rooms.edit', $room->id) }}"
                   class="btn btn-warning me-2 @if($room->hasActiveShowtimes()) disabled @endif">
                    <i class="fas fa-edit me-1"></i>Chỉnh sửa
                </a>
                <a href="{{ route('admin.rooms.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Quay lại
                </a>
            </div>
        </div>

        <!-- Room Status Alert -->
        @if($room->hasActiveShowtimes())
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Phòng chiếu có suất chiếu đang hoạt động. Một số chức năng bị hạn chế.
            </div>
        @endif

        <!-- Maintenance Countdown Card -->
        @if($maintenanceStatus === 'overdue')
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
                                                    <h2 class="mb-0 fw-bold" id="overdue-days">{{ number_format($realTimeCountdown['days'], 0, '.', '.') }}</h2>
                                                    <small>Ngày</small>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="maintenance-countdown">
                                                    <h2 class="mb-0 fw-bold" id="overdue-hours">{{ number_format($realTimeCountdown['hours'], 0) }}</h2>
                                                    <small>Giờ</small>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="maintenance-countdown">
                                                    <h2 class="mb-0 fw-bold" id="overdue-minutes">{{ number_format($realTimeCountdown['minutes'], 0) }}</h2>
                                                    <small>Phút</small>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="maintenance-countdown">
                                                    <h2 class="mb-0 fw-bold" id="overdue-seconds">{{ number_format($realTimeCountdown['seconds'], 0) }}</h2>
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
                                                Tổng thời gian: {{ number_format(abs($totalSecondsUntilMaintenance) / 3600, 0, '.', '.') }} giờ
                                            </small>
                                        </div>
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
                                    @if($maintenanceScore >= 80)
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
        <ul class="nav nav-tabs bg-dark" role="tablist">
            <li class="nav-item">
                <button class="nav-link @if($tabCurrent === 'overview') active bg-light text-dark @else text-light @endif"
                        wire:click="$set('tabCurrent', 'overview')">
                    <i class="fas fa-info-circle me-1"></i>Tổng quan
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link @if($tabCurrent === 'maintenance') active bg-light text-dark @else text-light @endif"
                        wire:click="$set('tabCurrent', 'maintenance')"
                        style="border-top-left-radius: 0; border-top-right-radius: 0;">
                    <i class="fas fa-tools me-1"></i>Bảo trì
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link @if($tabCurrent === 'seats') active bg-light text-dark @else text-light @endif"
                        wire:click="$set('tabCurrent', 'seats')"
                        style="border-top-left-radius: 0; border-top-right-radius: 0;">
                    <i class="fas fa-chair me-1"></i>Sơ đồ ghế
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link @if($tabCurrent === 'showtimes') active bg-light text-dark @else text-light @endif"
                        wire:click="$set('tabCurrent', 'showtimes')">
                    <i class="fas fa-calendar me-1"></i>Suất chiếu
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content mt-3">
            <!-- Overview Tab -->
            @if($tabCurrent === 'overview')
                <div class="row">
                    <div class="col-md-6">
                        <div class="card bg-dark border-light">
                            <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5><i class="fas fa-info me-2"></i>Thông tin cơ bản</h5>
                                <h5><i class="fas fa-info me-2"></i>Thông tin chi tiết</h5>
                            </div>
                            <div class="card-body bg-dark" style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
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
                            <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5><i class="fas fa-chart-bar me-2"></i>Thống kê ghế</h5>
                            </div>
                            <div class="card-body bg-dark" style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                                @php
                                    $seatStats = $room->seats->groupBy('seat_type');
                                @endphp
                                <div class="row text-center">
                                    <div class="col-4">
                                        <div class="card bg-success">
                                            <div class="card-body py-2">
                                                <h4 class="mb-0 text-white fw-bold">{{ $seatStats->get('standard', collect())->count() }}</h4>
                                                <small class="text-white">Thường</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="card bg-warning">
                                            <div class="card-body py-2">
                                                <h4 class="mb-0 text-dark fw-bold">{{ $seatStats->get('vip', collect())->count() }}</h4>
                                                <small class="text-dark">VIP</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="card bg-danger text-white">
                                            <div class="card-body py-2">
                                                <h4 class="mb-0 fw-bold">{{ $seatStats->get('couple', collect())->count() }}</h4>
                                                <small>Đôi</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr class="border-warning">
                                <h6 class="text-warning fw-bold mb-3">Giá vé theo loại ghế:</h6>
                                <ul class="list-unstyled text-light">
                                    @foreach($seatStats as $type => $seats)
                                        @if($seats->count() > 0 && $type !== "disabled")
                                            <li class="d-flex justify-content-between">
                                                <span>
                                                    @switch($type)
                                                        @case('standard') Ghế thường @break
                                                        @case('vip') Ghế VIP @break
                                                        @case('couple') Ghế đôi @break
                                                    @endswitch
                                                </span>
                                                <strong class="text-warning">{{ number_format($seats->first()->price, 0, '.', '.') }}đ</strong>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            <!-- Maintenance Tab -->
            @elseif($tabCurrent === 'maintenance')
                <div class="row">
                    <div class="col-md-8">
                        <div class="card bg-dark border-light">
                            <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5><i class="fas fa-tools me-2"></i>Thông tin bảo trì chi tiết</h5>
                            </div>
                            <div class="card-body bg-dark" style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="text-warning mb-3">Lịch sử bảo trì</h6>
                                        <table class="table table-borderless text-light">
                                            <tr>
                                                <td><strong>Bảo trì lần cuối:</strong></td>
                                                <td>
                                                    {{ $referenceDate->format('d/m/Y') }}
                                                    <br><small class="text-muted">({{ number_format($daysSinceLastMaintenance, 0, '.', '.') }} ngày trước)</small>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Bảo trì tiếp theo:</strong></td>
                                                <td>
                                                    {{ $nextMaintenanceDate->format('d/m/Y') }}
                                                    <br><small class="text-muted">{{ $daysOfWeek[$nextMaintenanceDate->format('l')] }}</small>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Chu kỳ bảo trì:</strong></td>
                                                <td>3 tháng ({{ $totalDaysIn3Months }} ngày)</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Trạng thái:</strong></td>
                                                <td>
                                                    <span class="badge @if($maintenanceStatus) bg-danger @else @if($realTimeCountdown['days'] <= 9) bg-warning @else bg-success @endif @endif">
                                                        @if($maintenanceStatus) Quá hạn bảo trì @else @if($realTimeCountdown['days'] <= 9) Sắp đến hạn bảo trì @else Bình thường @endif @endif
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Thời gian còn lại:</strong></td>
                                                <td>
                                                    <span class="text-{{ $maintenanceStatus === 'overdue' ? 'danger' : 'success' }}">
                                                        {{ number_format($realTimeCountdown['days'], 0, '.', '.') }} ngày
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-warning mb-3">Điểm bảo trì</h6>
                                        <div class="progress mb-3" style="height: 25px;">
                                            <div class="progress-bar bg-{{ $maintenanceScore >= 60 ? 'success' : ($maintenanceScore >= 40 ? 'warning' : 'danger') }}"
                                                 role="progressbar"
                                                 style="width: {{ $maintenanceScore }}%">
                                                {{ number_format($maintenanceScore, 0) }}/100
                                            </div>
                                        </div>
                                        <small class="text-muted">
                                            Điểm bảo trì được tính dựa trên thời gian từ lần bảo trì cuối cùng
                                        </small>

                                        <div class="mt-4">
                                            <h6 class="text-warning mb-3">Thống kê thời gian @if($maintenanceStatus === "overdue") đã qua @else còn lại @endif</h6>
                                            <ul class="list-unstyled text-light">
                                                <li><strong>Tổng giờ:</strong> {{ number_format(abs($totalSecondsUntilMaintenance) / 3600, 0, '.', '.') }} giờ</li>
                                                <li><strong>Tổng phút:</strong> {{ number_format(abs($totalSecondsUntilMaintenance) / 60, 0, '.', '.') }} phút</li>
                                                <li><strong>Tổng giây:</strong> {{ number_format(abs($totalSecondsUntilMaintenance), 0, '.', '.') }} giây</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mt-4 mt-md-0">
                        <div class="card bg-dark border-light">
                            <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5><i class="fas fa-clock me-2"></i>Đếm ngược thời gian thực</h5>
                            </div>
                            <div class="card-body bg-dark text-center" style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                                @if($maintenanceStatus === 'overdue')
                                    <div class="text-danger">
                                        <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                                        <h4>QUÁ HẠN</h4>
                                        <div class="maintenance-timer">
                                            <div class="timer-item mb-2">
                                                <h3 class="text-danger" id="realtime-overdue-days">{{ number_format($realTimeCountdown['days'], 0, '.', '.') }}</h3>
                                                <small class="text-light">Ngày</small>
                                            </div>
                                            <div class="timer-item mb-2">
                                                <h4 class="text-warning" id="realtime-overdue-hours">{{ number_format($realTimeCountdown['hours'], 0) }}</h4>
                                                <small class="text-light">Giờ</small>
                                            </div>
                                            <div class="timer-item mb-2">
                                                <h5 class="text-info" id="realtime-overdue-minutes">{{ number_format($realTimeCountdown['minutes'], 0) }}</h5>
                                                <small class="text-light">Phút</small>
                                            </div>
                                            <div class="timer-item">
                                                <h6 class="text-success" id="realtime-overdue-seconds">{{ number_format($realTimeCountdown['seconds'], 0) }}</h6>
                                                <small class="text-light">Giây</small>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="maintenance-timer">
                                        <div class="timer-item mb-3">
                                            <h2 class="text-warning" id="realtime-days">{{ number_format($realTimeCountdown['days'], 0) }}</h2>
                                            <small class="text-light">Ngày</small>
                                        </div>
                                        <div class="timer-item mb-3">
                                            <h3 class="text-info" id="realtime-hours">{{ number_format($realTimeCountdown['hours'], 0) }}</h3>
                                            <small class="text-light">Giờ</small>
                                        </div>
                                        <div class="timer-item mb-3">
                                            <h4 class="text-success" id="realtime-minutes">{{ number_format($realTimeCountdown['minutes'], 0) }}</h4>
                                            <small class="text-light">Phút</small>
                                        </div>
                                        <div class="timer-item">
                                            <h5 class="text-primary" id="realtime-seconds">{{ number_format($realTimeCountdown['seconds'], 0) }}</h5>
                                            <small class="text-light">Giây</small>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            <!-- Seats Tab -->
            @elseif($tabCurrent === 'seats')
                <div class="card bg-dark border-light">
                    <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <h5><i class="fas fa-chair me-2"></i>Sơ đồ ghế phòng {{ $room->name }}</h5>
                    </div>
<div class="card-body bg-dark" style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
    @if($room->seats->count() > 0)
        <div class="st_seatlayout_main_wrapper w-100 mt-2 overflow-x-auto">
            <div class="container">
                <div class="st_seat_lay_heading float_left text-center">
                    <h3 class="text-warning mb-4">{{ $room->name }}</h3>
                </div>
                <div class="st_seat_full_container" style="float: none">
                    <div class="st_seat_lay_economy_wrapper float_left w-100">
                        <div class="screen text-center mb-4">
                            <img src="{{ asset('client/assets/images/content/screen.png') }}" alt="Màn hình" style="max-width: 100%;">
                        </div>
                    </div>

                    <div class="st_seat_lay_economy_wrapper st_seat_lay_economy_wrapperexicutive float_left" style="width: auto !important">
                        @php
                            $seatsByRow = $room->seats->groupBy('seat_row');
                        @endphp

                       @foreach($seatsByRow as $rowLetter => $rowSeats)
    <ul id="row-{{ $rowLetter }}"
        wire:sc-sortable.onmove="updateseatid"
        class="seat-row-layout list-unstyled float_left d-flex flex-nowrap gap-2 justify-content-start align-items-center mb-2"
        data-row="{{ $rowLetter }}"
        wire:key="row-{{ $rowLetter }}">

        @php
            $seatGroup = [];
            $count = 0;
        @endphp

        @foreach($rowSeats->sortBy('seat_number') as $seat)
            @php
                $seatGroup[] = $seat;
                $count += ($seat->seat_type === 'couple') ? 1 : 1;
            @endphp

            @if($count >= 5)
                @foreach($seatGroup as $gSeat)
                    @php
                        $seatClass = match($gSeat->seat_type) {
                            'vip' => 'seat-vip',
                            'couple' => 'seat-double',
                            default => 'seat-standard'
                        };
                    @endphp
                    <li data-seat="{{ $gSeat->seat_type }}" class="seat-item" sc-id="{{ $gSeat->seat_row . $gSeat->seat_number }}">
                        <span class="seat-helper">Chỗ ngồi {{ $gSeat->seat_row . $gSeat->seat_number }}</span>
                        <input type="checkbox"
                               class="seat {{ $seatClass }}"
                               id="{{ $gSeat->seat_row . $gSeat->seat_number }}"
                               data-number="{{ $gSeat->seat_number }}">
                        <label for="{{ $gSeat->seat_row . $gSeat->seat_number }}" class="visually-hidden">
                            Chỗ ngồi {{ $gSeat->seat_row . $gSeat->seat_number }}
                        </label>
                    </li>
                @endforeach

                {{-- Chèn lối đi sau nhóm 5 chỗ vật lý --}}
                <li data-seat="aisle" sc-id="aisle">
                    <span class="seat-helper">Lối đi</span>
                    <div class="aisle"></div>
                </li>

                @php
                    $seatGroup = [];
                    $count = 0;
                @endphp
            @endif
        @endforeach

        {{-- Render ghế còn lại nếu chưa đủ 5 chỗ cuối hàng --}}
        @foreach($seatGroup as $gSeat)
            @php
                $seatClass = match($gSeat->seat_type) {
                    'vip' => 'seat-vip',
                    'couple' => 'seat-double',
                    default => 'seat-standard'
                };
            @endphp
            <li data-seat="{{ $gSeat->seat_type }}" class="seat-item" sc-id="{{ $gSeat->seat_row . $gSeat->seat_number }}">
                <span class="seat-helper">Chỗ ngồi {{ $gSeat->seat_row . $gSeat->seat_number }}</span>
                <input type="checkbox"
                       class="seat {{ $seatClass }}"
                       id="{{ $gSeat->seat_row . $gSeat->seat_number }}"
                       data-number="{{ $gSeat->seat_number }}">
                <label for="{{ $gSeat->seat_row . $gSeat->seat_number }}" class="visually-hidden">
                    Chỗ ngồi {{ $gSeat->seat_row . $gSeat->seat_number }}
                </label>
            </li>
        @endforeach
    </ul>
@endforeach

                        <div class="row-aisle" style="height: 20px; width: 100%;"></div>
                    </div>
                </div>

                <div class="legend mt-4 text-center">
                    <div class="d-flex justify-content-center gap-4 flex-wrap">
                        <span class="text-light fw-bold">
                            <div class="seat seat-standard d-inline-block me-2"></div>Thường ({{ number_format($room->seats->where('seat_type', 'standard')->first()->price ?? 0) }}đ)
                        </span>
                        <span class="text-light fw-bold">
                            <div class="seat seat-vip d-inline-block me-2"></div>VIP ({{ number_format($room->seats->where('seat_type', 'vip')->first()->price ?? 0) }}đ)
                        </span>
                        <span class="text-light fw-bold">
                            <div class="seat seat-double d-inline-block me-2"></div>Couple ({{ number_format($room->seats->where('seat_type', 'couple')->first()->price ?? 0) }}đ)
                        </span>
                    </div>
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
            <!-- Showtimes Tab -->
            @elseif($tabCurrent === 'showtimes')
                <div class="row">
                    <div class="col-md-6">
                        <div class="card bg-dark border-light">
                            <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5><i class="fas fa-history me-2"></i>Suất chiếu gần đây</h5>
                            </div>
                            <div class="card-body bg-dark" style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                                @if($recentShowtimes->count() > 0)
                                    <div class="list-group">
                                        @foreach($recentShowtimes as $showtime)
                                            <div class="list-group-item bg-dark text-light border-warning">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="mb-1 fw-bold">{{ $showtime->movie?->title ?? 'N/A' }}</h6>
                                                        <small class="text-warning">
                                                            {{ $showtime->start_time->format('d/m/Y H:i') }}
                                                        </small>
                                                    </div>
                                                    <span class="badge bg-{{ $showtime->status === 'active' ? 'success' : ($showtime->status === 'canceled' ? 'danger' : 'secondary') }}">
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
                            <div class="card-header bg-gradient text-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5><i class="fas fa-calendar-plus me-2"></i>Suất chiếu sắp tới</h5>
                            </div>
                            <div class="card-body bg-dark" style="border-radius: 0 0 var(--bs-card-inner-border-radius) var(--bs-card-inner-border-radius);">
                                @if($upcomingShowtimes->count() > 0)
                                    <div class="list-group">
                                        @foreach($upcomingShowtimes as $showtime)
                                            <div class="list-group-item bg-dark text-light border-warning">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h6 class="mb-1 text-light fw-bold">{{ $showtime->movie?->title ?? 'N/A' }}</h6>
                                                    <small class="text-muted">
                                                        {{ $showtime->start_time->format('d/m/Y H:i') }} -
                                                        {{ $showtime->end_time->format('H:i') }}
                                                    </small>
                                                </div>
                                                <span class="badge bg-warning text-dark fw-bold">{{ number_format($showtime->price, 0, '.', '.') }}đ</span>
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
            @endif
        </div>
    </div>
</div>
