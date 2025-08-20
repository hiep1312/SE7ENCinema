<div class="scRender">
    @if (session()->has('success'))
    <div class="alert alert-success alert-dismissible fade show mt-2 mx-2" role="alert" wire:ignore>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if (session()->has('error'))
    <div class="alert alert-danger alert-dismissible fade show mt-2 mx-2" role="alert" wire:ignore>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="container-lg mb-4">
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Quản lý đơn hàng</h2>
            <div>
                <a href="{{ route('admin.scanner', 'bookings') }}" class="btn btn-primary me-2">
                    <i class="fa-light fa-qrcode me-1"></i>Quét đơn hàng
                </a>
            </div>
        </div>

        <div class="card bg-dark" wire:poll.6s>
            <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="row g-3">
                    <!-- Tìm kiếm -->
                    <div class="col-md-4 col-lg-3">
                        <div class="input-group">
                            <input type="text" wire:model.live.debounce.300ms="search"
                                class="form-control bg-dark text-light" placeholder="Tìm kiếm đơn hàng...">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                    </div>

                    <!-- Lọc theo trạng thái -->
                    <div class="col-md-3 col-lg-2">
                        <select wire:model.live="statusFilter" class="form-select bg-dark text-light">
                            <option value="">Tất cả trạng thái</option>
                            <option value="pending">Đang chờ xử lý</option>
                            <option value="expired">Đã hết hạn xử lý</option>
                            <option value="paid">Đã thanh toán</option>
                            <option value="failed">Lỗi thanh toán</option>
                        </select>
                    </div>

                    <!-- Lọc theo phương thức thanh toán -->
                    <div class="col-md-3 col-lg-2">
                        <select wire:model.live="paymentMethodFilter" class="form-select bg-dark text-light">
                            <option value="">Tất cả phương thức thanh toán</option>
                            <option value="credit_card">Thẻ tín dụng</option>
                            <option value="bank_transfer">Chuyển khoản</option>
                            <option value="e_wallet">Ví điện tử</option>
                            <option value="cash">Tiền mặt</option>
                        </select>
                    </div>

                    <!-- Lọc theo giá tiền -->
                    <div class="col-md-9 col-xl-5 col-xxl-4 mb-2 mb-md-0 d-flex align-items-center gap-2">
                        <span id="lowerValue" x-text="$wire.priceFilter[0].toLocaleString('vi-VN') + 'đ'"></span>
                        <div class="dual-range">
                            <div class="range-track"></div>
                            <div class="range-fill" id="rangeFill" wire:ignore.self></div>
                            <input type="range" class="range-input lower" id="lowerRange" min="{{ $rangePrice[0] }}"
                                max="{{ $rangePrice[1] }}" value="{{ $priceFilter[0] }}" wire:input="$js.updateSlider">
                            <input type="range" class="range-input upper" id="upperRange" min="{{ $rangePrice[0] }}"
                                max="{{ $rangePrice[1] }}" value="{{ $priceFilter[1] }}" wire:input="$js.updateSlider">
                        </div>
                        <span id="upperValue" x-text="$wire.priceFilter[1].toLocaleString('vi-VN') + 'đ'"></span>
                    </div>
                    <!-- Reset filters -->
                    <div class="col-md-2 col-xxl-1">
                        <button wire:click="resetFilters" class="btn btn-outline-warning">
                            <i class="fas fa-refresh me-1"></i>Reset
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body bg-dark">
                <div class="table-responsive">
                    <table class="table table-dark table-striped table-hover">
                        <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <tr>
                                <th class="text-center text-light">STT</th>
                                <th class="text-center text-light">Mã đơn hàng</th>
                                <th class="text-center text-light">
                                    <i class="fas fa-calendar-alt me-1"></i>Suất chiếu
                                </th>
                                <th class="text-center text-light">Người đặt</th>
                                <th class="text-center text-light">Món ăn đặt kèm</th>
                                <th class="text-center text-light">Tổng tiền</th>
                                <th class="text-center text-light">Trạng thái</th>
                                <th class="text-center text-light">Ngày đặt</th>
                                <th class="text-center text-light">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $booking)
                            <tr>
                                <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                <td>
                                    <strong class="text-light">{{ $booking->booking_code }}</strong>
                                </td>
                                <td class="bg-opacity-10 border-start border-3 align-top">
                                    <div>
                                        <div class="mb-1">
                                            <i class="fa-solid fa-person-booth text-primary me-1"></i>
                                            <strong class="text-primary">
                                                {{ $booking->showtime->room->name ?? 'Không tìm thấy phòng chiếu' }}
                                            </strong>
                                        </div>

                                        <div class="mb-1">
                                            <i class="fas fa-film me-1 text-info"></i>
                                            <strong class="text-info">
                                                {{ Str::limit($booking->showtime->movie->title ?? 'Không tìm thấy phim
                                                chiếu', 20, '...') }}
                                            </strong>
                                        </div>

                                        <!-- Thời gian chiếu -->
                                        <div class="mb-1">
                                            <i class="fas fa-clock me-1 text-success"></i>
                                            <span class="text-success">
                                                {{ $booking->showtime->start_time->format('d/m/Y') }}
                                            </span>
                                            <br>
                                            <small class="text-muted ms-3">
                                                {{ $booking->showtime->start_time->format('H:i') }} -
                                                {{ $booking->showtime->end_time->format('H:i') }}
                                            </small>
                                        </div>

                                        @switch($booking->showtime->status)
                                            @case('active')
                                                <div class="badge bg-primary mb-1"><i class="fa-solid fa-clapperboard-play me-1"></i>Đang hoạt động</div>
                                                @break
                                            @case('completed')
                                                <div class="badge bg-success mb-1"><i class="fa-solid fa-calendar-check me-1"></i>Đã hoàn thành</div>
                                                @break
                                            @case('canceled')
                                                <div class="badge bg-danger mb-1"><i class="fa-solid fa-hexagon-xmark me-1"></i>Đã bị hủy</div>
                                                @break
                                        @endswitch

                                        <div>
                                            <small class="text-info">
                                                <i class="fas fa-hourglass-half me-1"></i>
                                                {{ $booking->showtime->start_time->diffForHumans() }}
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td style="max-width: 250px">
                                    <div
                                        class="d-flex align-items-center justify-content-center flex-column p-3 compact-dark rounded">
                                        <div class="user-avatar-clean">
                                            @if($avatar = $booking->user->avatar)
                                            <img src="{{ asset('storage/' . $avatar) }}" alt
                                                style="width: 45px; height: 45px; object-fit: cover;">
                                            @else
                                            <i class="fas fa-user icon-white"></i>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1 text-center">
                                            <h6 class="card-title mb-2">
                                                <a class="user-name-link-dark"
                                                    href="{{ route('admin.users.detail', $booking->user_id) }}">
                                                    {{ Str::limit($booking->user->name, 20, '...') ?? 'Không tìm thấy
                                                    người dùng' }}
                                                </a>
                                            </h6>
                                            <div class="badge-clean d-block">
                                                <i class="fa-solid fa-envelope me-1 icon-blue"></i>
                                                {{ Str::limit($booking->user->email, 30, '...') }}
                                            </div>
                                            @if($booking->user->phone)
                                            <div class="badge-purple">
                                                <i class="fa-solid fa-phone-volume me-1 icon-purple"></i>
                                                {{ $booking->user->phone }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($booking->foodOrderItems->isNotEmpty())
                                    <span class="text-light text-wrap lh-base">{{
                                        Str::limit($booking->foodOrderItems->pluck('variant.foodItem.name')->implode(',
                                        '), 50, '...') }}</span>
                                    @else
                                    <span class="text-muted">Không có món ăn đặt kèm</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-gradient fs-6">
                                        {{ number_format($booking->total_price, 0, '.', '.') }}đ
                                    </span>
                                    @if($booking->promotionUsage?->exists())
                                    <small class="text-danger fw-bold d-block mt-1 ms-1">- {{
                                        number_format($booking->promotionUsage->discount_amount, 0, '.', '.') }}đ
                                        KM</small>
                                    @endif
                                </td>
                                <td class="text-center">
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
                                    <small class="text-muted d-block mt-1" style="font-size: 12px">
                                        PTTT:
                                        @switch($booking->payment_method)
                                        @case('credit_card') Thẻ tín dụng @break
                                        @case('bank_transfer') Chuyển khoản @break
                                        @case('e_wallet') Ví điện tử @break
                                        @case('cash') Tiền mặt @break
                                        @endswitch
                                    </small>
                                </td>

                                <td class="text-center">
                                    <span class="text-light">
                                        {{ $booking->created_at ? $booking->created_at->format('d/m/Y H:i') : 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center">
                                        <a href="{{ route('admin.bookings.detail', $booking->id) }}"
                                            class="btn btn-sm btn-info" title="Xem chi tiết">
                                            <i class="fas fa-eye" style="margin-right: 0"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <p>
                                            Không có đơn hàng nào
                                        </p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $bookings->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@script
<script>
    $js('resetSlider', function() {
        document.getElementById('lowerRange').value = {{ $rangePrice[0] ?? 0 }};
        document.getElementById('upperRange').value = {{ $rangePrice[1] ?? 1_000_000_000 }};
        document.getElementById('rangeFill').style = "left: 0%; width: 100%;";
    })

    $js('updateSlider', function() {
        // Lấy element
        const lowerRange = document.getElementById('lowerRange');
        const upperRange = document.getElementById('upperRange');
        const rangeFill = document.getElementById('rangeFill');

        // Lấy và phân tích giá trị
        const lower = lowerRange?.valueAsNumber ?? parseInt(lowerRange.value);
        const upper = upperRange?.valueAsNumber ?? parseInt(upperRange.value);

        // Kiểm tra logic tránh lower >= upper && upper <= lower
        lower >= upper && (lowerRange.value = upper - 1);
        upper <= lower && (upperRange.value = lower + 1);

        // Tính phần trăm
        const lowerPercent = ((lowerRange.value - lowerRange.min) / (lowerRange.max - lowerRange.min)) * 100;
        const upperPercent = ((upperRange.value - upperRange.min) / (upperRange.max - upperRange.min)) * 100;

        // Cập nhật thanh fill
        rangeFill.style.left = lowerPercent + '%';
        rangeFill.style.width = (upperPercent - lowerPercent) + '%';

        $wire.$set('priceFilter', [lower, upper], true);
    })
</script>
@endscript
