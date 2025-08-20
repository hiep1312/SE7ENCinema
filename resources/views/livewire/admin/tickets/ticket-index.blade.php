@assets
<style>
    .collapsing {
        transition: none !important;
        height: auto !important;
    }
</style>
@endassets
@use('chillerlan\QRCode\QRCode')
<div class="scRender">
    {{-- Hiển thị thông báo --}}
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
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center my-3">
            <h2 class="text-light">Quản lý vé</h2>
            <a href="{{ route('admin.scanner', 'tickets') }}" class="btn btn-primary">
                <i class="fa-light fa-qrcode me-1"></i>Quét vé
            </a>
        </div>

        {{-- Bộ lọc --}}
        <div class="card bg-dark mb-3" wire:poll.6s>
            <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="row g-3">
                    <div class="col-md-4 col-lg-3">
                        <div class="input-group">
                            <input type="text" wire:model.live.debounce.300ms="search"
                                class="form-control bg-dark text-light" placeholder="Tìm kiếm vé phim...">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-2">
                        <select wire:model.live="takenFilter" class="form-select bg-dark text-light">
                            <option value="">Tất cả tình trạng vé</option>
                            <option value="1">Đã lấy vé</option>
                            <option value="0">Chưa lấy vé</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-lg-2">
                        <select wire:model.live="statusFilter" class="form-select bg-dark text-light">
                            <option value="">Tất cả trạng thái</option>
                            <option value="active">Chưa sử dụng</option>
                            <option value="used">Đã sử dụng</option>
                            <option value="canceled">Đã bị hủy</option>
                        </select>
                    </div>
                    <div class="col-md-3 col-lg-2">
                        <select wire:model.live="movieFilter" class="form-select bg-dark text-light">
                            <option value="">Tất cả phim</option>
                            @foreach ($movies as $movie)
                            <option value="{{ $movie->id }}">{{ $movie->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 col-xxl-1">
                        <button wire:click="resetFilters" class="btn btn-outline-warning w-100">
                            <i class="fas fa-refresh me-1"></i>Reset
                        </button>
                    </div>
                </div>
            </div>

            {{-- Danh sách vé --}}
            <div class="card-body bg-dark">
                <div class="table-responsive">
                    <table class="table table-dark table-striped align-middle">
                        <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <tr>
                                <th class="text-center">STT</th>
                                <th class="text-center">Mã đơn hàng</th>
                                <th>Chi tiết đơn hàng</th>
                                <th class="text-center">Thời gian đặt vé</th>
                                <th class="text-center">Tình trạng vé</th>
                                <th class="text-center">Hành động</th>
                            </tr>
                        </thead>
                        @forelse($bookings as $booking)
                        @php
                        $ticket = $booking->tickets;
                        $collapseId = 'collapse-'.$booking->id;
                        @endphp
                        <tbody class="border rounded-3 shadow-sm mb-3">
                            {{-- Hàng chính --}}
                            <tr>
                                <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                <td class="text-center"><strong class="text-light">{{ $booking->booking_code }}</strong>
                                </td>
                                <td>
                                    <div class="mb-1 text-primary fw-bold">
                                        <i class="fa-solid fa-person-booth me-1"></i>
                                        {{ $booking->showtime->room->name ?? 'Không tìm thấy phòng' }}
                                    </div>
                                    <div class="mb-1 text-info fw-bold">
                                        <i class="fas fa-film me-1"></i>
                                        {{ Str::limit($booking->showtime->movie->title ?? 'Không tìm thấy phim', 20,
                                        '...') }}
                                    </div>
                                    <div class="text-success">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ $booking->showtime->start_time->format('d/m/Y H:i') }} - {{
                                        $booking->showtime->end_time->format('H:i') }}
                                    </div>
                                </td>
                                <td class="text-center">{{ $booking->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-center">
                                    @if($ticket->first()?->taken)
                                    <span class="badge-clean-base badge-clean-green">
                                        <i class="fas fa-check-circle me-1"></i>Đã lấy vé
                                    </span>
                                    @else
                                    <span class="badge-clean-base badge-clean-orange">
                                        <i class="fas fa-times-circle me-1"></i>Chưa lấy vé
                                    </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($ticket->first()?->isValidTicketOrder())
                                    <a wire:click='loadTickets({{ $booking->id }})' class="btn btn-sm btn-outline-info "
                                        data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}"
                                        style="cursor: pointer;" title="Xem chi tiết">
                                        <i class="fas fa-eye ms-1"></i>
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            {{-- Hàng chi tiết --}}
                            <tr class="collapse" id="{{ $collapseId }}" wire:ignore.self>
                                <td colspan="6">
                                    <div class="card shadow-sm border-0">
                                        <div class="movie-card border rounded p-3">
                                            <div class="row">
                                                <div class="col-lg-4 text-wrap lh-base">
                                                    <div style="font-size:15px; font-weight:600" class="mt-2">
                                                        <i
                                                            class="fa-regular fa-film me-2"></i>{{$booking->showtime->movie->title
                                                        }}
                                                    </div>
                                                    <p class="mb-0 mt-3">
                                                        <i class="fas fa-clock me-2"></i>
                                                        {{ $booking->showtime->start_time->format('d/m/Y H:i') }} - {{
                                                        $booking->showtime->end_time->format('H:i') }}
                                                    </p>
                                                    <p class="mb-0 mt-3">
                                                        <i class="fas fa-door-open me-2"></i>
                                                        {{ $booking->showtime->room->name }}
                                                    </p>
                                                    <p class="mb-0 mt-3">
                                                        <i class="fas fa-money-bill me-2"></i>Giá vé:
                                                        {{ number_format($booking->total_price, 0,'.','.') }} VNĐ
                                                    </p>
                                                    <p class="mb-0 mt-3">
                                                        <i class="fas fa-user me-2"></i>Người đặt:
                                                        {{ Str::limit($booking->user->name, 20, '...') }}
                                                    </p>
                                                </div>
                                                <div class="col-lg-8 mt-3 mt-lg-0">
                                                    <div class="table-responsive">
                                                        <table class="table table-dark table-hover mb-0">
                                                            <thead>
                                                                <tr>
                                                                    <th class="text-center" style="width: 60px;">STT</th>
                                                                    <th class="text-center">Ghế đã đặt</th>
                                                                    <th class="text-center">Thời gian vào phòng</th>
                                                                    <th class="text-center">Trạng thái</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($this->getBookingTickets($booking->id) as $tickets)
                                                                <tr>
                                                                    <td class="text-center">{{ $loop->iteration }}</td>
                                                                    <td class="text-center">
                                                                        {{ $tickets->bookingSeat->seat->seat_row }}{{
                                                                        $tickets->bookingSeat->seat->seat_number }}
                                                                    </td>
                                                                    <td class="text-center">{{ $tickets->taken_at ?? 'Chưa
                                                                        vào phòng' }}</td>
                                                                    <td class="text-center">
                                                                        @switch($tickets->status)
                                                                        @case('active') <span class="badge bg-primary">Chưa
                                                                            sử dụng</span> @break
                                                                        @case('used') <span class="badge bg-success">Đã sử
                                                                            dụng</span> @break
                                                                        @case('canceled') <span class="badge bg-danger">Đã
                                                                            bị hủy</span> @break
                                                                        @endswitch
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                        <div class="mt-2">{{ $this->getBookingTickets($booking->id)->links() }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <p>Không có vé nào</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </table>
                </div>
                <div class="mt-3">{{ $bookings->links() }}</div>
            </div>
        </div>
    </div>
</div>