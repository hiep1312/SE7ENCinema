@use('chillerlan\QRCode\QRCode')
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
            <h2 class="text-light">Quản lý vé</h2>
            {{-- <div>
                <a href="{{ route('admin.bookings.create') }}" class="btn btn-success me-2">
                    <i class="fas fa-plus me-1"></i>Thêm suất chiếu
                </a>
            </div> --}}
        </div>

        <div class="card bg-dark" wire:poll.6s>
            <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="row g-3">
                    <!-- Tìm kiếm -->
                    <div class="col-md-4 col-lg-3">
                        <div class="input-group">
                            <input type="text"
                                   wire:model.live.debounce.300ms="search"
                                   class="form-control bg-dark text-light"
                                   placeholder="Tìm kiếm vé phim...">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                    </div>

                    <!-- Lọc theo tình trạng vé -->
                    <div class="col-md-3 col-lg-2">
                        <select wire:model.live="takenFilter" class="form-select bg-dark text-light">
                            <option value="">Tất cả tình trạng vé</option>
                            <option value="1">Đã lấy vé</option>
                            <option value="0">Chưa lấy vé</option>
                        </select>
                    </div>

                    <!-- Lọc theo trạng thái vé -->
                    <div class="col-md-3 col-lg-2">
                        <select wire:model.live="statusFilter" class="form-select bg-dark text-light">
                            <option value="">Tất cả trạng thái</option>
                            <option value="active">Chưa sử dụng</option>
                            <option value="used">Đã sử dụng</option>
                            <option value="canceled">Đã bị hủy</option>
                        </select>
                    </div>

                    <!-- Lọc theo phim -->
                    <div class="col-md-3 col-lg-2">
                        <select wire:model.live="movieFilter" class="form-select bg-dark text-light">
                            <option value="">Tất cả phim</option>
                            @foreach ($movies as $movie)
                                <option value="{{ $movie->id }}" wire:key="movie-{{ $movie->id }}">{{ $movie->title }}</option>
                            @endforeach
                        </select>
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
                                    <i class="fa-solid fa-memo-circle-info me-1"></i>Chi tiết đơn hàng
                                </th>
                                <th class="text-center text-light">Mã QR</th>
                                <th class="text-center text-light">Ghi chú</th>
                                <th class="text-center text-light">Tình trạng vé</th>
                                <th class="text-center text-light">Trạng thái</th>
                                <th class="text-center text-light">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tickets as $ticket)
                                @php $booking = $ticket->bookingSeat->booking; @endphp
                                <tr>
                                    <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                    <td>
                                        <strong class="text-light">{{ $booking->booking_code }}</strong>
                                    </td>
                                    <td class="bg-opacity-10 border-start border-3" style="width: 200px;">
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
                                                    {{ Str::limit($booking->showtime->movie->title ?? 'Không tìm thấy phim chiếu', 20, '...') }}
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

                                            <div class="mb-1">
                                                <i class="fas fa-money-bill me-1 text-warning"></i>
                                                <span class="text-warning">
                                                    {{ number_format($booking->total_price, 0, '.', '.') }}đ
                                                </span>
                                            </div>

                                            @switch($booking->status)
                                                @case('pending')
                                                    <span class="badge bg-primary"><i class="fa-solid fa-clock-three me-1"></i>Đang chờ xử lý</span>
                                                    @break
                                                @case('expired')
                                                    <span class="badge bg-warning text-dark"><i class="fas fa-hourglass-half me-1"></i>Đã hết hạn xử lý</span>
                                                    @break
                                                @case('paid')
                                                    <span class="badge bg-success"><i class="fa-solid fa-badge-check me-1"></i>Đã thanh toán</span>
                                                    @break
                                                @case('failed')
                                                    <span class="badge bg-danger"><i class="fa-solid fa-circle-exclamation me-1"></i>Lỗi thanh toán</span>
                                                    @break
                                            @endswitch

                                            <div class="mt-1">
                                                <small class="text-info">
                                                    <i class="fa-solid fa-user me-1"></i>
                                                    {{ $booking->user->name }}
                                                </small>
                                            </div>

                                            <div class="mt-1">
                                                <small style="color: #c084fc;">
                                                    <i class="fa-solid fa-chair-office me-1"></i>
                                                    {{ $ticket->bookingSeat->seat->seat_row }}{{ $ticket->bookingSeat->seat->seat_number }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <div class="qr-code" style="margin-bottom: 0; width: 90px; height: 90px;">
                                                <img src="{{ (new QRCode)->render($ticket->qr_code) }}" alt="QR code" style="width: 100%; height: 100%; border-radius: 0;">
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-muted text-center">
                                        <p class="text-wrap lh-base" style="margin-bottom: 0;">{{ Str::limit($ticket->description ?: "Không có ghi chú", 150, '...') }}</p>
                                    </td>
                                    <td class="text-center">
                                        @if($ticket->taken)
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
                                            @if($ticket->isValidTicketOrder())
                                                <a href="{{ route('client.ticket', [$booking->booking_code, $ticket->getCurrentIndex()]) }}" target="_blank"
                                                    class="btn btn-sm btn-outline-info" title="Xem chi tiết">
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
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <p>
                                                Không có vé nào
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $tickets->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
