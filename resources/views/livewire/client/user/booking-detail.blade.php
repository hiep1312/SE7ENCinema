@assets
@vite('resources/css/userInfo.css')
@endassets
<div class="scUserInfo">
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
                        <a style="text-decoration: none" href="{{route('client.userInfo',['tab'=>'info'])}}"
                            class="tab-item">
                            Thông tin tài khoản
                        </a>
                        <a style="text-decoration: none" href="{{route('client.userInfo',['tab'=>'booking'])}}"
                            class="tab-item">
                            Lịch sử xem phim
                        </a>
                    </div>
                    <div class="booking-info">
                        <div class="ticket-detail-container">
                            <div class="ticket-header">
                                <h1 class="ticket-title">Chi Tiết Đơn Hàng</h1>
                                <p class="ticket-subtitle">Thông tin đầy đủ về vé đã đặt</p>
                            </div>

                            <div class="ticket-content">
                                <div class="ticket-section">
                                    <h3 class="section-title">
                                        🎬 Thông Tin Phim
                                    </h3>
                                    <div class="movie-info">
                                        <img src="{{ asset('storage/' . ($bookingInfo->showtime->movie->poster ?? '404.webp')) }}"
                                            class="movie-poster-large">
                                        <div class="movie-details">
                                            <h2 class="movie-title-large" id="movieTitle">
                                                {{$bookingInfo->showtime->movie->title}}</h2>
                                            <div class="movie-meta">
                                                <div class="meta-item">
                                                    <span>🎭</span>
                                                    <span>Thể loại:
                                                        @forelse ($bookingInfo->showtime->movie->genres as $item)
                                                        {{$item->name}} @if (!$loop->last),@endif
                                                        @empty
                                                        Không có
                                                        @endforelse
                                                    </span>
                                                </div>
                                                <div class="meta-item">
                                                    <span>⏱️</span>
                                                    <span>Thời lượng: {{$bookingInfo->showtime->movie->duration}}
                                                        phút</span>
                                                </div>
                                                <div class="meta-item">
                                                    <span>🔞</span>
                                                    <span>Độ tuổi:
                                                        {{$bookingInfo->showtime->movie->age_restriction}}</span>
                                                </div>
                                                <div class="meta-item">
                                                    <span>🔍</span>
                                                    <span>Định dạng: {{$bookingInfo->showtime->movie->format}}</span>
                                                </div>
                                                <div class="meta-item">
                                                    <span>🌟</span>
                                                    <span>Đánh giá:
                                                        {{$bookingInfo->showtime->movie->ratings->avg('score')}}/10</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Booking Information -->
                                <div class="ticket-section">
                                    <h3 class="section-title">
                                        📋 Thông Tin Đặt Vé
                                    </h3>
                                    <div class="detail-grid">
                                        <div class="detail-item">
                                            <span class="detail-label">Mã vé:</span>
                                            <span class="detail-value highlight"
                                                id="bookingCode">{{$bookingInfo->booking_code}}</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Ngày đặt:</span>
                                            <span class="detail-value"
                                                id="bookingDate">{{$bookingInfo->created_at->format('d/m/Y')}}</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Rạp chiếu:</span>
                                            <span class="detail-value" id="cinema">SE7ENCinema Landmark 81</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Phòng chiếu:</span>
                                            <span class="detail-value"
                                                id="room">{{$bookingInfo->showtime->room->name}}</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Ngày chiếu:</span>
                                            <span class="detail-value"
                                                id="showDate">{{$bookingInfo->showtime->start_time->format('d/m/Y')}}</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Giờ chiếu:</span>
                                            <span class="detail-value"
                                                id="showTime">{{$bookingInfo->showtime->start_time->format('H:i')}} -
                                                {{$bookingInfo->showtime->end_time->format('H:i')}}</span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Ghế ngồi:</span>
                                            <span class="detail-value highlight">
                                                @forelse ($bookingInfo->seats as $seat)
                                                {{$seat->seat_row}}{{$seat->seat_number}}@if(!$loop->last),@endif
                                                @empty
                                                N\A
                                                @endforelse
                                            </span>
                                        </div>
                                        <div class="detail-item">
                                            <span class="detail-label">Số lượng vé:</span>
                                            <span class="detail-value" id="ticketCount">{{
                                                number_format($bookingInfo->seats->count(), 0, '.', '.') }} vé</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Information -->
                            <div class="ticket-section">
                                <h3 class="section-title">
                                    💳 Thông Tin Thanh Toán
                                </h3>
                                <div class="detail-grid">
                                    <div style="font-weight: 600;color: #333; font-size: 16px;">Thức ăn kèm:</div>
                                    <div style="text-align: right">
                                        @if ($bookingInfo->foodOrderItems->isNotEmpty())
                                        {{ number_format($bookingInfo->foodOrderItems->sum(fn($foodOrderItem) =>
                                        $foodOrderItem->price * $foodOrderItem->quantity), 0, '.', '.') }}
                                        @else
                                        Không có
                                        @endif
                                    </div>
                                    @if ($bookingInfo->foodOrderItems->isNotEmpty())
                                    @forelse ($bookingInfo->foodOrderItems as $foodOrder)
                                    <div class="detail-item" style="grid-column:span 2;">
                                        <span class="detail-label">
                                            {{$foodOrder->variant->foodItem->name}} ({{$foodOrder->quantity}}x)
                                            <div class="variants">
                                                @foreach($foodOrder->variant->attributeValues as $attributeValue)
                                                {{ $attributeValue->attribute->name }}: {{ $attributeValue->value
                                                }}@if(!$loop->last),@endif
                                                @endforeach
                                            </div>
                                            <div class="price-detail">{{ number_format($foodOrder->price, 0,
                                                ',', '.') }} × {{$foodOrder->quantity}}
                                            </div>
                                        </span>
                                        <span class="detail-value">
                                            @if (!$bookingInfo->foodOrderItems->isNotEmpty())
                                            Không
                                            @endif
                                            {{
                                            number_format(
                                            $foodOrder->quantity*$foodOrder->price,0, '.', '.')
                                            }}
                                        </span>
                                    </div>
                                    @empty
                                    @endforelse
                                    @endif
                                    <div class="detail-item">
                                        <span class="detail-label">Giá vé
                                            ({{number_format($bookingInfo->seats->count(),0, '.', '.') }}x):</span>
                                        <span class="detail-value">
                                            {{number_format($bookingInfo->showtime->movie->price *
                                            $bookingInfo->seats->count(), 0, '.','.')}}
                                        </span>
                                    </div>

                                    <div class="detail-item">
                                        <span class="detail-label">Tổng tiền:</span>
                                        <span class="detail-value price"
                                            id="totalAmount">{{number_format($bookingInfo->total_price, 0, '.',
                                            '.')}}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Phương thức:</span>
                                        <span class="detail-value" id="paymentMethod">
                                            @switch($bookingInfo->payment_method)
                                            @case('bank_transfer')
                                            <span>Chuyển khoản ngân hàng</span>
                                            @break
                                            @case('e_wallet')
                                            <span>Ví điện tử</span>
                                            @break
                                            @case('credit_card')
                                            <span>Thẻ tín dụng</span>
                                            @break
                                            @case('cash')
                                            <span>Tiền mặt</span>
                                            @break
                                            @default
                                            <span>N/A</span>
                                            @endswitch
                                        </span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Trạng thái:</span>
                                        <span class="detail-value highlight">
                                            @switch($bookingInfo->status)
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
                                            N/A
                                            @endswitch
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- QR Code Section -->
                            <div class="qr-section">
                                <h3 class="section-title">
                                    📱 Mã QR
                                </h3>
                                <div class="qr-code">
                                    <img src="{{ (new QRCode)->render($bookingInfo->booking_code) }}" alt="QR code"
                                        style="width: 100%; height: 100%; border-radius: 0;">
                                </div>
                                <div class="qr-instructions">
                                    <strong>Hướng dẫn sử dụng:</strong><br>
                                    • Xuất trình mã QR này tại quầy vé hoặc cổng vào rạp<br>
                                    • Đảm bảo màn hình sáng và rõ nét<br>
                                    • Có mặt trước giờ chiếu 15 phút để làm thủ tục
                                </div>
                            </div>

                            <!-- Additional Information -->
                            <div class="additional-info">
                                <h4>📝 Lưu Ý Quan Trọng</h4>
                                <ul>
                                    <li>Vé đã mua không thể đổi trả sau khi thanh toán</li>
                                    <li>Vui lòng có mặt trước giờ chiếu 15 phút</li>
                                    <li>Không được mang thức ăn, đồ uống từ bên ngoài vào rạp</li>
                                    <li>Tắt điện thoại hoặc chuyển sang chế độ im lặng trong suốt buổi chiếu</li>
                                    <li>Liên hệ hotline 1900-6017 nếu cần hỗ trợ</li>
                                </ul>
                            </div>

                            <!-- Action Buttons -->
                            <div class="action-buttons" id="actionButtons">
                                <button class="action-btn btn-rate">
                                    <i class="fa-light fa-star"></i> Đánh Giá Phim
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>