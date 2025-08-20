@use('chillerlan\QRCode\QRCode')
@assets
<script>
    function toggleSettings(id) {
        if(typeof id === "undefined"){
            document.querySelectorAll('.settings-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.settings-overlay, .settings-panel').forEach(element => element.classList.remove('show'));
        }else{
            const containerEl = document.querySelector(`div[data-id='${id}']`);
            const settingsBtn = containerEl.querySelector('.settings-btn');
            settingsBtn.classList.toggle('active', containerEl.querySelector('.settings-panel').classList.toggle('show'));
            containerEl.querySelector('.settings-overlay').classList.toggle('show');
        }
    }
</script>
@endassets
<div data-id="{{ $bookingSeat->id }}">
    @if(session()->has('success'))
        <div class="no-print" style="position: fixed; top: 20px; left: 50%; transform: translateX(-50%); background: rgb(40, 167, 69); color: white; padding: 12px 20px; border-radius: 6px; font-size: 14px; font-weight: 500; z-index: 9999; box-shadow: rgba(0, 0, 0, 0.2) 0px 4px 12px; opacity: 1; transition: opacity 0.3s;" wire:ignore>
            {{ session('success') }}
        </div>
    @endif
    <!-- Control Buttons -->
    <div class="control-buttons no-print">
        <button class="control-btn print" onclick="window.print()" title="In vé">
            <i class="fas fa-print"></i>
        </button>
        <a class="control-btn open d-none" href="{{ route('client.ticket', [$booking->booking_code, $bookingSeat->ticket->currentIndex]) }}" title="Chi tiết vé">
            <i class="fas fa-external-link-alt"></i>
        </a>
        <button class="control-btn settings-btn" onclick="toggleSettings({{ $bookingSeat->id }})" title="Cài đặt vé" wire:ignore.self>
            <i class="fas fa-cog"></i>
        </button>
    </div>

    <div class="settings-overlay no-print" onclick="toggleSettings()" wire:ignore.self></div>

    <div class="settings-panel no-print" wire:ignore.self>
        <div class="settings-header">
            <h5><i class="fas fa-cog"></i>Cài đặt vé xem phim</h5>
        </div>

        <div class="settings-body">
            <div class="settings-group">
                <h6><i class="fas fa-user me-1"></i>Thông tin khách hàng</h6>
                <div class="mb-3">
                    <label class="form-label" for="customerName">Tên khách hàng</label>
                    <input type="text" wire:model="userName" class="form-control @error('userName') is-invalid @enderror" id="customerName" placeholder="VD: Nguyễn Văn An">
                    @error('userName')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="settings-group">
                <h6><i class="fas fa-sticky-note me-1"></i>Ghi chú</h6>
                <textarea wire:model="notes" class="form-control @error('notes') is-invalid @enderror" id="notes" rows="3" placeholder="Ghi chú nội bộ: Tình trạng đặc biệt của khách, hoặc yêu cầu xử lý riêng từ quản lý..."></textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <label for="notes" class="visually-hidden">Ghi chú</label>
            </div>

            <div class="settings-group" style="margin-bottom: 5px;">
                <h6 class="d-inline-block"><i class="fas fa-box-open me-1"></i>Tình trạng vé: </h6>
                @if($bookingSeat->ticket->taken)
                    <span class="badge-clean-base badge-clean-green light">
                        <i class="fas fa-check-circle me-1"></i>
                        Đã lấy vé
                    </span>
                @else
                    <span class="badge-clean-base badge-clean-orange light">
                        <i class="fas fa-times-circle me-1"></i>
                        Chưa lấy vé
                    </span>
                @endif
            </div>

            <div class="settings-group">
                <h6 class="d-inline-block"><i class="fas fa-refresh me-1"></i>Trạng thái vé: </h6>
                @switch($bookingSeat->ticket->status)
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
            </div>
        </div>

        <div class="settings-actions">
            <button class="btn-apply" wire:click="setCustomInfo">
                <i class="fas fa-check me-1"></i>Áp dụng
            </button>
            <button class="btn-reset" wire:click="resetCustomInfo">
                <i class="fas fa-undo me-1"></i>Đặt lại
            </button>
        </div>
    </div>

    <div class="ticket-container" wire:poll.6s="realtimeUpdate">
        @php $movie = $booking->showtime->movie; @endphp
        <div class="ticket-main">
            <div class="cinema-logo">
                <img src="{{ asset('storage/favicon.ico') }}" alt="Logo">
            </div>

            <div class="age-rating">{{ $movie->age_restriction }}</div>

            <!-- Movie Title -->
            <div class="movie-title">{{ $movie->title }}</div>
            <div class="movie-genres">{{ $movie->genres->take(3)->implode('name', ', ') }}</div>

            <!-- Movie Info Grid -->
            <div class="movie-info">
                <div class="info-item">
                    <i class="fas fa-clock"></i>
                    <span class="info-label">Thời lượng:</span>
                    <span class="info-value">{{ $movie->duration }}</span>
                </div>
                <div class="info-item">
                    <i class="fas fa-user-tie"></i>
                    <span class="info-label">Đạo diễn:</span>
                    <span class="info-value">{{ $movie->director }}</span>
                </div>
                <div class="info-item">
                    <i class="fas fa-calendar-alt"></i>
                    <span class="info-label">Ngày chiếu:</span>
                    <span class="info-value">{{ $booking->showtime->start_time->format('d/m/Y') }}</span>
                </div>
                <div class="info-item">
                    <i class="fas fa-play-circle"></i>
                    <span class="info-label">Định dạng:</span>
                    <span class="info-value">{{ $movie->format }}</span>
                </div>
            </div>

            <!-- Seat, Cinema, Time Info -->
            <div class="seat-cinema-info">
                <div class="seat-info">
                    <h4>{{ $bookingSeat->seat->seat_row }}{{ $bookingSeat->seat->seat_number }}</h4>
                    <p>VỊ TRÍ GHẾ</p>
                </div>
                <div class="cinema-info">
                    <h4>{{ $booking->showtime->room->name }}</h4>
                    <p>PHÒNG CHIẾU</p>
                </div>
                <div class="time-info">
                    <h4>{{ $booking->showtime->start_time->format('H:i') }}</h4>
                    <p>GIỜ CHIẾU</p>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="customer-info">
                <div class="info-item">
                    <i class="fas fa-user"></i>
                    <span class="info-label">Khách hàng:</span>
                    <span class="info-value">{{ session()->has("userName{$bookingSeat->id}") ? session("userName{$bookingSeat->id}")[0] : $booking->user->name }}</span>
                </div>
                <div class="info-item">
                    <i class="fas fa-clock"></i>
                    <span class="info-label">Kết thúc:</span>
                    <span class="info-value">{{ $booking->showtime->end_time->format('H:i') }}</span>
                </div>
            </div>

            <!-- Notes Section -->
            <div class="notes-section">
                <h6><i class="fas fa-info-circle me-1"></i>GHI CHÚ</h6>
                <p id="displayNotes">Vui lòng có mặt trước giờ chiếu 15 phút. Không được mang thức ăn từ bên ngoài vào rạp. Vé đã mua không được hoàn trả.</p>
            </div>

            <div class="notes-section staff" style="margin-top: 13px;">
                <h6><i class="fa-solid fa-note me-1"></i>GHI CHÚ TỪ NHÂN VIÊN</h6>
                <p id="displayNotes">{{ $bookingSeat->ticket->note ?? 'Không có ghi chú' }}</p>
            </div>
        </div>

        <!-- Stub Section -->
        <div class="ticket-stub">
            <div class="movie-poster">
                @if($movie->poster)
                    <img src="{{ asset('storage/' . $movie->poster) }}"
                        alt="Ảnh phim {{ $movie->title }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 0;">
                @else
                    <i class="fas fa-film"></i>
                @endif
            </div>

            <div class="qr-code">
                <img src="{{ (new QRCode)->render($bookingSeat->ticket->qr_code) }}" alt="qr code" style="width: 100%; height: 100%; border-radius: 0;">
            </div>

            <div class="barcode"></div>

            <div class="order-code">{{ $booking->booking_code }}</div>
        </div>
    </div>
</div>
@script
<script>
    window.addEventListener('afterprint', () => !@json($bookingSeat->ticket->taken || $bookingSeat->ticket->status === 'canceled') && $wire.updateTakenStatus());
</script>
@endscript
