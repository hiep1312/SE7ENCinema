@assets
@vite('resources/css/userInfo.css')
@endassets
<div class="scUserInfo">
    <div style="clear: both"></div>
    <div class="info">
        <!-- Main Content -->
        @use('chillerlan\QRCode\QRCode')
        <div class="fix"></div>
        @if (session()->has('success'))
        <div class="alertSuccess">
            {{ session('success') }}
        </div>
        @endif
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
                            <span class="info-value">{{$user->birthday != null ? $user->birthday->format('d/m/Y') :
                                'N/A'}}</span>
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
                                    <input type="file" id="fileUpload" style="display:none" wire:model.live='avatar' accept="image/*" />
                                    <label for="fileUpload" class="btn btn-upload"><i
                                            class="fa-solid fa-arrow-up-from-bracket"></i> Tải ảnh lên</label>
                                </div>
                                <p style="color: #666; font-size: 12px;">Định dạng: JPG, PNG • Kích thước tối đa: 20MB
                                </p>
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
                                <button type="button" wire:click='openModal'>🔒 Đổi mật khẩu</button>
                                <a href="{{route('client.userConfirm')}}">
                                    Xóa tài khoản
                                </a>
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
                            <h2 class="history-title">Lịch sử đơn hàng</h2>
                            <div class="history-filters">
                                <input type="text" class="filter-date" wire:model.live='nameFilter'
                                    placeholder="Tìm kiếm theo tên phim...">
                                <select class="filter-select" wire:model.live='statusFilter'>
                                    <option value="">Tất cả trạng thái</option>
                                    <option value="failed">Đã thất bại</option>
                                    <option value="paid">Đã thanh toán</option>
                                    <option value="pending">Chờ thanh toán</option>
                                    <option value="expired">Hết hạn</option>
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
                                                <span class="info-icon"><i
                                                        class="fa-regular fa-location-dot"></i></span>
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
                                                <span>Ghế:
                                                    @forelse ($booking->seats as $seat)
                                                    {{$seat->seat_row}}{{sprintf('%02d',
                                                    $seat->seat_number);}}@if(!$loop->last),@endif
                                                    @empty
                                                    Không có ghế
                                                    @endforelse
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="booking-summary">
                                        <div
                                            class="status-badge @if($booking->status === 'paid') status-completed @elseif($booking->status === 'pending') status-pending @elseif($booking->status == 'expired') status-upcoming @elseif($booking->status == 'failed') status-failed @endif">
                                            @switch($booking->status)
                                            @case('paid')
                                            Đã thanh toán
                                            @break
                                            @case('expired')
                                            Đã hết hạn
                                            @break
                                            @case('pending')
                                            Chờ thanh toán
                                            @break
                                            @case('failed')
                                            Đã thất bại
                                            @break
                                            @default

                                            @endswitch
                                        </div>
                                        <div class="booking-price">
                                            {{number_format($booking->total_price,0,',','.')}}
                                            VNĐ
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
                                            @break
                                            @default
                                            <span>N/A</span>
                                            @endswitch
                                        </div>
                                        <div class="booking-actions">
                                            <button class="action-btn-history btn-rate">
                                                <i class="fa-light fa-star"></i><a style="color: black" href="{{route('client.movie_detail', $booking->showtime->movie->id)}}"> Đánh Giá</a>
                                            </button>
                                            <a href="{{route('client.userBooking', $booking->id)}}" class="action-btn-history btn-detail">
                                                <i class="fa-light fa-eye"></i> Chi tiết
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div style="text-align: center">
                                <p>Không có lịch sử mua vé</p>
                            </div>
                            @endforelse
                            <div class="test">
                                {{ $bookings->links() }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @if ($modal)
    <div class="popup-overlay">
        <div class="popup-container">
            <div class="popup-header">
                <h2 class="popup-title">
                    Đổi Mật Khẩu
                </h2>
                <p class="popup-subtitle">Cập nhật mật khẩu để bảo mật tài khoản</p>
                <button type="button" class="close-btn" wire:click='closeModal'>×</button>
            </div>
            <div class="popup-body">
                <form wire:submit.prevent='changePassword'>
                    <div class="form-group">
                        <label class="form-label">
                            Mật khẩu hiện tại <span class="required">*</span>
                        </label>
                        <div class="input-wrapper">
                            <input type="password" class="form-input" wire:model.live='currentPassword'
                                placeholder="Nhập mật khẩu hiện tại">
                            <button type="button" class="password-toggle" onclick="
                                const input = this.previousElementSibling;
                                input.type = input.type === 'password' ? 'text' : 'password';">
                                <i class="fa-light fa-eye"></i>
                            </button>
                        </div>
                        @if (session()->has('error'))
                        <div class="error-message">
                            ⚠️ <span> {{session('error')}}</span>
                        </div>
                        @endif
                    </div>

                    <!-- New Password -->
                    <div class="form-group">
                        <label class="form-label">
                            Mật khẩu mới <span class="required">*</span>
                        </label>
                        <div class="input-wrapper">
                            <input type="password" class="form-input" placeholder="Nhập mật khẩu mới"
                                wire:model.live='newPassword'>
                            <button type="button" class="password-toggle"
                                onclick="this.previousElementSibling.type = this.previousElementSibling.type === 'password' ? 'text' : 'password'">
                                <i class="fa-light fa-eye"></i>
                            </button>
                        </div>
                        @error('newPassword')
                        <div class="error-message">
                            ⚠️ <span>{{$message}}</span>
                        </div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label class="form-label">
                            Xác nhận mật khẩu mới <span class="required">*</span>
                        </label>
                        <div class="input-wrapper">
                            <input type="password" class="form-input" wire:model.live='confirmPassword'
                                placeholder="Nhập lại mật khẩu mới">
                            <button type="button" class="password-toggle"
                                onclick="this.previousElementSibling.type = this.previousElementSibling.type === 'password' ? 'text' : 'password'">
                                <i class="fa-light fa-eye"></i>
                            </button>
                        </div>
                        @error('confirmPassword')
                        <div class="error-message">
                            ⚠️ <span>{{$message}}</span>
                        </div>
                        @enderror
                    </div>
                    <!-- Popup Footer -->
                    <div class="popup-footer">
                        <button type="button" class="btn btn-cancel" wire:click='closeModal'>
                            Hủy bỏ
                        </button>
                        <button type="submit" class="btn btn-changePass">
                            Đổi mật khẩu
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
