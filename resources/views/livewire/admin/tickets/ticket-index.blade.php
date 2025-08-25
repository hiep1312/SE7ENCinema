@use('chillerlan\QRCode\QRCode')
@assets
    @vite('resources/css/ticketAdmin.css')
@endassets
<div class="scRender scTicketAdmin">
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

            <div class="card-body bg-dark">
                <div class="table-responsive">
                    <table class="table table-dark table-striped table-hover">
                        <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); ">
                            <tr>
                                <th class="text-center">STT</th>
                                <th class="text-center">Mã đơn hàng</th>
                                <th class="text-center">Chi tiết đơn hàng</th>
                                <th class="text-center">Thời gian đặt vé</th>
                                <th class="text-center">Tình trạng vé</th>
                                <th class="text-center">Thời gian lấy vé</th>
                                <th class="text-center">Hành động</th>
                            </tr>
                        </thead>
                        @forelse($bookings as $booking)
                            <tbody class="border rounded-3 shadow-sm mb-3">
                                <tr class="tickets">
                                    <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                    <td class="text-center"><strong class="text-light">{{ $booking->booking_code }}</strong>
                                    </td>
                                    <td class="text-center detail-bookings">
                                        <div class="booking-details-container">
                                            <div class="booking-detail-item">
                                                <i class="fas fa-door-open booking-detail-icon"></i>
                                                <span class="booking-detail-text fw-600">
                                                    {{ $booking->showtime->room?->name ?? 'Không tìm thấy phòng' }}
                                                </span>
                                            </div>
                                            <div class="booking-detail-item">
                                                <i class="fas fa-film booking-detail-icon movie-info"></i>
                                                <span class="booking-detail-text movie-info">
                                                    {{ Str::limit($booking->showtime->movie?->title ?? 'Không tìm thấy phim', 25, '...') }}
                                                </span>
                                            </div>
                                            <div class="booking-detail-item">
                                                <i class="fas fa-clock booking-detail-icon time-info"></i>
                                                <span class="booking-detail-text time-info">
                                                    {{ $booking->showtime->start_time->format('d/m/Y H:i') }} - {{ $booking->showtime->end_time->format('H:i') }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ $booking->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="text-center">
                                        @if($booking->tickets->first()?->taken)
                                            <span class="badge-clean-base badge-clean-green">
                                                <i class="fas fa-check-circle me-1"></i>Đã lấy vé
                                            </span>
                                        @else
                                            <span class="badge-clean-base badge-clean-orange">
                                                <i class="fas fa-times-circle me-1"></i>Chưa lấy vé
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $booking->tickets?->first()->taken_at?->format('d/m/Y H:i') ?? 'Chưa lấy vé' }}</td>
                                    <td class="text-center">
                                        @if($booking->tickets->first()?->isValidTicketOrder())
                                            <a class="btn btn-sm btn-outline-info"
                                                data-bs-toggle="collapse" data-bs-target="#{{ "collapse-{$booking->id}" }}"
                                                style="cursor: pointer" title="Xem chi tiết">
                                                <i class="fas fa-eye" style="margin-right: 0"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>

                                <tr class="collapse" id="{{ "collapse-{$booking->id}" }}" wire:ignore.self>
                                    <td colspan="7">
                                        <div class="ticket-detail-card mt-2 mb-2">
                                            <div class="row g-3">
                                                <div class="col-xl-4">
                                                    <div class="ticket-info-section">
                                                        <div class="info-item">
                                                            <i class="fa-solid fa-film"></i>
                                                            <strong>Phim:</strong>
                                                            <span class="ms-2">{{ Str::limit($booking->showtime->movie?->title ?? 'Không tìm thấy phim', 27, '...') }}</span>
                                                        </div>

                                                        <div class="info-item">
                                                            <i class="fas fa-clock"></i>
                                                            <strong>Suất chiếu:</strong>
                                                            <span class="ms-2">{{ $booking->showtime->start_time->format('d/m/Y H:i') }} - {{ $booking->showtime->end_time->format('H:i') }}</span>
                                                        </div>

                                                        <div class="info-item">
                                                            <i class="fas fa-door-open"></i>
                                                            <strong>Phòng chiếu:</strong>
                                                            <span class="ms-2">{{ $booking->showtime->room?->name ?? 'Không tìm thấy phòng chiếu' }}</span>
                                                        </div>

                                                        <div class="info-item">
                                                            <i class="fas fa-money-bill"></i>
                                                            <strong>Tổng tiền:</strong>
                                                            <span class="ms-2 text-warning fw-bold">{{ number_format($booking->total_price, 0,'.','.') }} VNĐ</span>
                                                        </div>

                                                        <div class="info-item mb-0">
                                                            <i class="fas fa-user"></i>
                                                            <strong>Khách hàng:</strong>
                                                            <span class="ms-2">{{ Str::limit($booking->user->name, 25, '...') }}</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-xl-8">
                                                    <div class="ticket-table-section">
                                                        <h6 class="text-light mb-3">
                                                            <i class="fas fa-list me-2"></i>Chi tiết vé
                                                        </h6>
                                                        <div class="table-responsive">
                                                            <table class="table detail-table mb-0">
                                                                <thead class="collapse-header">
                                                                    <tr>
                                                                        <th class="text-center">STT</th>
                                                                        <th class="text-center">Ghế đã đặt</th>
                                                                        <th class="text-center">Thời gian vào phòng</th>
                                                                        <th class="text-center">Trạng thái</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($ticketsList[$booking->id] as $ticket)
                                                                        <tr>
                                                                            <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                                                            <td class="text-center">
                                                                                <span class="seat-badge">
                                                                                    {{ $ticket->bookingSeat->seat->seat_row }}{{ $ticket->bookingSeat->seat->seat_number }}
                                                                                </span>
                                                                            </td>
                                                                            <td class="text-center">
                                                                                {{ $ticket->taken_at ?? 'Chưa vào phòng' }}
                                                                            </td>
                                                                            <td class="text-center">
                                                                                @switch($ticket->status)
                                                                                    @case('active')
                                                                                        <span class="badge-clean-base badge-clean-yellow">
                                                                                            <i class="fas fa-clock me-1"></i>Chưa sử dụng
                                                                                        </span>
                                                                                    @break
                                                                                    @case('used')
                                                                                        <span class="badge-clean-base badge-clean-green">
                                                                                            <i class="fas fa-check me-1"></i>Đã sử dụng
                                                                                        </span>
                                                                                    @break
                                                                                    @case('canceled')
                                                                                        <span class="badge-clean-base badge-clean-red">
                                                                                            <i class="fas fa-times me-1"></i>Đã bị hủy
                                                                                        </span>
                                                                                    @break
                                                                                @endswitch
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                            <div class="mt-3">
                                                                {{ $ticketsList[$booking->id]->links(data: ['scrollTo' => false]) }}
                                                            </div>
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
                                <td colspan="7" class="text-center py-4">
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
