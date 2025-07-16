<div class="info">
    <style>
        .info {
            background-color: #EFF1F5 !important;

            .orders-grid {
                display: grid;
                gap: 20px;
            }

            .order-card {
                background: rgba(255, 255, 255, 0.1);
                border-radius: 15px;
                padding: 25px;
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.2);
                transition: transform 0.3s, box-shadow 0.3s;
            }

            .order-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 30px rgba(255, 71, 87, 0.3);
            }

            .order-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 20px;
                padding-bottom: 15px;
                border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            }

            .order-id {
                font-size: 18px;
                font-weight: bold;
                color: #ff4757;
            }

            .order-date {
                color: #ccc;
                font-size: 14px;
            }

            .order-status {
                padding: 6px 12px;
                border-radius: 20px;
                font-size: 12px;
                font-weight: bold;
                text-transform: uppercase;
            }

            .status-completed {
                background: rgb(22 163 74 / 1);
                color: white;
            }

            .status-cancelled {
                background: #e74c3c;
                color: white;
            }

            .status-pending {
                background: #f39c12;
                color: white;
            }

            .order-content {
                display: grid;
                grid-template-columns: 1fr 2fr 1fr;
                gap: 20px;
                align-items: center;
            }

            .movie-poster {
                width: 130px;
                border-radius: 8px;
                object-fit: cover;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            }

            .movie-title {
                font-size: 20px;
                font-weight: bold;
                color: #333;
                margin-bottom: 15px;
            }

            .movie-info h4 {
                color: #ff4757;
                margin-bottom: 10px;
                font-size: 18px;
            }

            .movie-details {
                color: #ccc;
                font-size: 14px;
                line-height: 1.6;
            }

            .movie-details span {
                display: block;
                margin-bottom: 5px;
            }

            .order-summary {
                text-align: right;
            }

            .total-price {
                font-size: 24px;
                font-weight: bold;
                color: #ff4757;
                margin-bottom: 10px;
            }

            .order-actions {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }

            .btn {
                padding: 8px 16px;
                border: none;
                border-radius: 6px;
                cursor: pointer;
                font-size: 14px;
                transition: all 0.3s;
                text-decoration: none;
                text-align: center;
            }

            .btn-primary {
                background: #ff4757;
                color: white;
            }

            .btn-primary:hover {
                background: #ff3742;
            }

            .btn-secondary {
                background: rgba(255, 255, 255, 0.1);
                color: white;
                border: 1px solid rgba(255, 255, 255, 0.3);
            }

            .btn-secondary:hover {
                background: rgba(255, 255, 255, 0.2);
            }

            .empty-state {
                text-align: center;
                padding: 60px 20px;
                color: #ccc;
            }

            .empty-state-icon {
                font-size: 80px;
                margin-bottom: 20px;
            }

            .empty-state h3 {
                font-size: 24px;
                margin-bottom: 10px;
            }

            .fix {
                clear: both;
            }

            .invalid-feedback {

                width: 100%;
                margin-top: 0.25rem;
                font-size: 0.875em;
                color: #dc3545;
            }

            .main-content {
                max-width: 1425px;
                margin: 0 auto;
                padding: 30px 20px;
            }

            .profile-container {
                display: grid;
                grid-template-columns: 350px 1fr;
                gap: 30px;
                background: white;
                border-radius: 10px;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
                overflow: hidden;
            }

            .profile-sidebar {
                background: #f8f9fa;
                padding: 30px;
                border-right: 1px solid #e9ecef;
            }

            .profile-avatar {
                text-align: center;
                margin-bottom: 20px;
            }

            .avatar-circle {
                width: 120px;
                height: 120px;
                border-radius: 50%;
                background: linear-gradient(135deg, #ddd 0%, #bbb 100%);
                margin: 0 auto 15px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 48px;
                color: white;
                position: relative;
                overflow: hidden;
            }

            .avatar-circle img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                object-position: center;
                display: block;
                position: relative;
                z-index: 1;
            }

            .avatar-circle::before {
                content: "👤";
                font-size: 60px;
                opacity: 0.3;
                position: absolute;
                z-index: 0;
            }

            .profile-name {
                font-size: 22px;
                font-weight: bold;
                color: #333;
                margin-bottom: 5px;
            }

            .profile-join-date {
                color: #666;
                font-size: 14px;
            }

            .profile-info {
                margin-top: 20px;
            }

            .info-item {
                display: flex;
                justify-content: space-between;
                padding: 12px 0;
                border-bottom: 1px solid #e9ecef;
            }

            .info-label {
                font-weight: 500;
                color: #333;
            }

            .info-value {
                color: #666;
                text-wrap: balance;
                max-inline-size: 17ch;
                text-align: right;
            }

            .profile-main {
                padding: 0;
            }

            .tab-header {
                display: flex;
                background-color: #f8f9fa;
                border-bottom: 1px solid #dee2e6;
            }

            .tab-item {
                padding: 15px 25px;
                background-color: transparent;
                border: none;
                cursor: pointer;
                font-size: 14px;
                color: #6c757d;
                border-right: 1px solid #dee2e6;
                transition: all 0.3s;
                text-transform: uppercase;
                font-weight: 500;
            }

            .tab-item:hover {
                background-color: #e9ecef;
            }

            .tab-item.active {
                background: linear-gradient(135deg, #ff6060 0%, #fe2e2e 100%);
                color: white;
            }

            .form-container {
                padding: 40px;
            }

            .upload-section {
                text-align: center;
                margin-bottom: 40px;
                padding: 30px;
                background: #f8f9fa;
                border-radius: 10px;
                border: 2px dashed #dee2e6;
            }

            .upload-buttons {
                display: flex;
                justify-content: center;
                gap: 15px;
                margin-bottom: 15px;
            }

            .btn {
                padding: 12px 24px;
                border: none;
                border-radius: 6px;
                cursor: pointer;
                font-size: 14px;
                font-weight: 500;
                text-transform: uppercase;
                transition: all 0.3s;
            }

            .btn-upload {
                background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
                color: white;
            }

            .btn-upload:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
            }

            .form-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 25px;
                margin-bottom: 25px;
            }

            .form-group {
                display: flex;
                flex-direction: column;
            }

            .booking-actions {
                display: flex;
                gap: 8px;
                margin-top: 15px;
                flex-wrap: wrap;
            }

            .action-btn {
                padding: 6px 12px;
                border: none;
                border-radius: 6px;
                font-size: 12px;
                font-weight: 500;
                cursor: pointer;
                transition: all 0.3s;
                display: flex;
                align-items: center;
                gap: 4px;
            }

            .btn-detail {
                background: #007bff;
                color: white;
            }

            .btn-detail:hover {
                background: #0056b3;
            }

            .btn-download {
                background: #28a745;
                color: white;
            }

            .btn-download:hover {
                background: #1e7e34;
            }

            .btn-rate {
                background: #ffc107;
                color: #212529;
            }

            .btn-rate:hover {
                background: #e0a800;
            }

            .btn-cancel {
                background: #dc3545;
                color: white;
            }

            .btn-cancel:hover {
                background: #c82333;
            }

            .form-group.full-width {
                grid-column: span 2;
            }

            .form-group label {
                margin-bottom: 8px;
                font-weight: 600;
                color: #333;
                font-size: 14px;
            }

            .required {
                color: #dc3545;
            }

            .form-group input,
            .form-group select,
            .form-group textarea {
                padding: 12px 15px;
                border: 2px solid #e9ecef;
                border-radius: 6px;
                font-size: 14px;
                transition: all 0.3s;
                background-color: white;
            }

            .form-group input:focus,
            .form-group select:focus,
            .form-group textarea:focus {
                outline: none;
                border-color: #007bff;
                box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
                background-color: #f8f9ff;
            }

            .form-group textarea {
                resize: vertical;
                min-height: 100px;
            }

            .form-actions {
                display: flex;
                justify-content: center;
                gap: 20px;
                margin-top: 40px;
                padding-top: 30px;
                border-top: 1px solid #e9ecef;
            }

            .btn-update {
                background: linear-gradient(135deg, #f35252 0%, #FF4444 100%);
                color: white;
                padding: 15px 40px;
                border: none;
                border-radius: 6px;
                cursor: pointer;
                font-size: 16px;
                font-weight: 600;
                text-transform: uppercase;
                transition: all 0.3s;
            }

            .booking-item {
                background: white;
                border: 1px solid #e9ecef;
                border-radius: 10px;
                padding: 25px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
                transition: all 0.3s;
            }

            .booking-item:hover {
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
                transform: translateY(-2px);
            }

            .booking-content {
                display: grid;
                grid-template-columns: auto 1fr auto;
                gap: 20px;
                align-items: start;
            }

            .btn-update:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 20px rgba(0, 123, 255, 0.3);
            }

            .forgot-password {
                margin-top: 20px;
                display: flex;
                justify-content: space-between;
            }

            .forgot-password a {
                color: #007bff;
                text-decoration: none;
                font-size: 14px;
                font-weight: 500;
            }

            .forgot-password a:hover {
                text-decoration: underline;
            }

            .booking-info {
                line-height: 35px;
                margin-bottom: 15px;
            }

            .info-icon {
                font-size: 15px;
                padding: 5px
            }

            .status-badge {
                padding: 6px 12px;
                border-radius: 20px;
                font-size: 12px;
                font-weight: 600;
            }

            .status-completed {
                background: rgb(220 252 231 / 1);
                color: #155724;
            }

            .status-upcoming {
                background: #cce7ff;
                color: #004085;
            }

            .status-pending {
                background: #ffc107;
                color: #721c24;
            }

            .booking-price {
                font-size: 18px;
                font-weight: bold;
                color: #333;
                margin: 5px 0;
            }

            .booking-code {
                color: #666;
                font-size: 15px;
                line-height: 1.25rem;
            }


            .tab-content {
                padding: 40px;
            }

            .history-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 30px;
            }

            .history-title {
                font-size: 24px;
                font-weight: bold;
                color: #333;
            }

            .history-filters {
                display: flex;
                gap: 15px;
                align-items: center;
            }

            .filter-select,
            .filter-date {
                padding: 8px 12px;
                border: 1px solid #ddd;
                border-radius: 6px;
                font-size: 14px;
                background: white;
            }

            .booking-list {
                display: flex;
                flex-direction: column;
                gap: 20px;
            }

            .tab-content.active {
                display: block;
            }

            .payment-method {
                font-size: 15px;
                color: #666;
                display: flex;
                align-items: center;
                gap: 5px;
            }

            .booking-summary {
                text-align: right;
                display: flex;
                flex-direction: column;
                gap: 10px;
                align-items: flex-end;
            }

            @media (max-width: 968px) {
                .profile-container {
                    grid-template-columns: 1fr;
                }

                .form-grid {
                    grid-template-columns: 1fr;
                }

                .form-group.full-width {
                    grid-column: span 1;
                }

                .info-row {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    font-size: 14px;
                    color: #666;
                }

                .tab-item {
                    padding: 12px 15px;
                    font-size: 12px;
                }
            }
        }
    </style>

    <!-- Main Content -->
    <div class="fix"></div>
    <div class="main-content">
        <div class="profile-container">
            <!-- Profile Sidebar -->
            <div wire:poll.2s class="profile-sidebar">
                <div class="profile-avatar">
                    <div class="avatar-circle">
                        @if ($avatar != null)
                        <img src="{{ $avatar->temporaryUrl() }}">
                        @elseif (!empty($user->avatar))
                        <img src="{{ asset('storage/' . ($user->avatar ?? '404.webp')) }}">
                        @endif
                    </div>
                    <div class="profile-name">{{$user->name}}</div>
                    <div class="profile-join-date">Tham gia: {{$user->created_at->format('d/m/Y')}}</div>
                </div>
                <div class="profile-info">
                    <div class="info-item">
                        <span class="info-label">Họ tên:</span>
                        <span class="info-value"> {{$user->name}}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Email:</span>
                        <span class="info-value">{{$user->email}}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Số điện thoại:</span>
                        <span class="info-value">{{$user->phone}}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Ngày sinh:</span>
                        <span class="info-value">{{$user->birthday->format('d/m/Y')}}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Giới tính:</span>
                        <span class="info-value">
                            @switch($user->gender)
                            @case('man')
                            Nam
                            @break;
                            @case('woman')
                            Nữ
                            @break
                            @case('other')
                            Khác
                            @break
                            @default
                            N\A
                            @endswitch
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Địa chỉ:</span>
                        <span class="info-value">{{$user->address}}</a></span>
                    </div>
                </div>
            </div>

            <!-- Profile Main Content -->
            <div class="profile-main">
                <div class="tab-header">
                    <button class="tab-item @if($tabCurrent === 'info') active  @endif"
                        wire:click="$set('tabCurrent', 'info')">
                        Thông tin tài khoản
                    </button>
                    <button class="tab-item @if($tabCurrent === 'booking') active  @endif"
                        wire:click="$set('tabCurrent', 'booking')">
                        Lịch sử xem phim
                    </button>
                </div>
                @if ($tabCurrent === 'info')
                <div class="form-container">
                    <form wire:submit.prevent="update" enctype="multipart/form-data" class="was-validated">
                        <div class="upload-section">
                            <div class="upload-buttons">
                                <input type="file" id="fileUpload" style="display:none" wire:model.live='avatar' />
                                <label for="fileUpload" class="btn btn-upload">📤 Tải ảnh lên</label>
                            </div>
                            <p style="color: #666; font-size: 12px;">Định dạng: JPG, PNG • Kích thước tối đa: 20MB</p>
                            @error('avatar')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="form-grid">
                            <div class="form-group">
                                <label for="fullname"><span class="required">*</span> Họ tên</label>
                                <input type="text" id="fullname" name="fullname" wire:model.live='name'
                                    placeholder="Nhập họ tên">
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- <div class="form-group">
                                <label for="email"><span class="required">*</span> Email</label>
                                <input type="email" id="email" name="email" wire:model.live='email'
                                    placeholder="Nhập email">
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div> --}}

                            <div class="form-group">
                                <label for="phone"><span class="required">*</span> Số điện thoại</label>
                                <input type="number" id="phone" name="phone" wire:model.live='phone'
                                    placeholder="Nhập số điện thoại">
                                @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="birthday"><span class="required">*</span> Ngày sinh</label>
                                <input type="date" id="birthday" name="birthday" wire:model.live='birthday'>
                                @error('birthday')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="gender">Giới tính</label>
                                <select id="gender" name="gender">
                                    <option value="">Chọn giới tính</option>
                                    <option value="man" {{$user->gender == 'man'?'selected':''}} >Nam</option>
                                    <option value="woman" {{$user->gender == 'woman'?'selected':''}}>Nữ</option>
                                    <option value="other" {{$user->gender == 'other'?'selected':''}}>Khác</option>
                                </select>
                                @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group full-width">
                                <label for="address">Địa chỉ</label>
                                <textarea id="address" name="address" placeholder="Nhập địa chỉ chi tiết"
                                    wire:model.live='address'></textarea>
                                @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="forgot-password">
                            <a href="{{ route('password.request') }}">🔒 Đổi mật khẩu?</a>
                            <a href="{{ route('password.request') }}">Xóa tài khoản</a>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-update">📝 Cập nhật</button>
                        </div>
                    </form>
                </div>
                @endif
                @if ($tabCurrent === 'booking')
                <div wire:poll.2s class="tab-content">
                    <div class="history-header">
                        <h2 class="history-title">Lịch sử mua vé</h2>
                        <div class="history-filters">
                            <select class="filter-select" wire:model.live='statusFilter'>
                                <option value="">Tất cả trạng thái</option>
                                <option value="confirmed">Đã xác nhận</option>
                                <option value="paid">Đã thanh toán</option>
                                <option value="pending">Chờ thanh toán</option>
                            </select>
                            <input type="date" class="filter-date" id="dateFilter" wire:model.live='dateFilter'>
                        </div>
                    </div>
                    <div class="booking-list">
                        @forelse ($bookings as $booking)

                        <div class="booking-item">
                            <div class="booking-content">
                                <img src="{{ asset('storage/' . ($booking->showtime->movie->poster ?? '404.webp')) }}"
                                    class="movie-poster">

                                <div class="booking-details">
                                    <h3 class="movie-title">{{$booking->showtime->movie->title}}</h3>
                                    <div class="booking-info">
                                        <div class="info-row">
                                            <span class="info-icon"><i class="fa-regular fa-location-dot"></i></span>
                                            <span>Se7en Cinema Landmark 81</span>
                                        </div>
                                        <div class="info-row">
                                            <span class="info-icon"><i class="fa-light fa-calendar"></i></span>
                                            <span>{{$booking->showtime->start_time->format('d/m/Y')}}</span>
                                        </div>
                                        <div class="info-row">
                                            <span class="info-icon"><i class="fa-light fa-clock"></i></span>
                                            <span>{{$booking->showtime->start_time->format('H:i')}}</span>
                                        </div>
                                        <div class="info-row">
                                            <span class="info-icon"><i class="fa-light fa-seat-airline"></i></span>
                                            <span>Ghế: Chưa được note lại
                                                {{-- @foreach ($booking->seats as $seat)
                                                {{$seat}}
                                                @endforeach --}}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="booking-summary">
                                    <div
                                        class="status-badge @if($booking->status === 'paid') status-completed @elseif($booking->status === 'pending') status-pending @elseif($booking->status == 'confirmed') status-upcoming @endif">
                                        @switch($booking->status)
                                        @case('paid')
                                        Đã thanh toán
                                        @break
                                        @case('confirmed')
                                        Đã xác nhận
                                        @break
                                        @case('pending')
                                        Chờ thanh toán
                                        @break
                                        @default

                                        @endswitch
                                    </div>
                                    <div class="booking-price">{{number_format($booking->total_price,0,',','.')}} VNĐ
                                    </div>
                                    <div class="booking-code">Mã: {{$booking->booking_code}}</div>
                                    <div class="payment-method">
                                        <span><i class="fa-light fa-credit-card"></i></span>
                                        @switch($booking->payment_method)
                                        @case('cash')
                                        <span>Tiền mặt</span>
                                        @break
                                        @case('e_wallet')
                                        <span>Ví điện tử</span>
                                        @break
                                        @case('bank_transfer')
                                        <span>Chuyển khoản ngân hàng</span>
                                        @break
                                        @case('credit_card')
                                        <span>Thẻ tín dụng</span>
                                        @break
                                        @default

                                        @endswitch
                                    </div>
                                    <div class="booking-actions">
                                        <button class="action-btn btn-rate">
                                            <i class="fa-light fa-star"></i> Đánh giá
                                        </button>
                                        <button class="action-btn btn-detail">
                                            <i class="fa-light fa-eye"></i> Chi tiết
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty

                        @endforelse
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>