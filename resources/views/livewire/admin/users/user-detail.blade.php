{{-- @push('styles')
    @vite('resources/css/components/user-detaill.css')
@endpush --}}
<div>
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-times me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    {{-- Page Header --}}
    <div class="page-header mt-3">
        <h1 class="page-title">Chi tiết tài khoản</h1>
    </div>

    {{-- Main Content --}}
    <div class="main-content">
        {{-- Customer Info --}}
        <div class="customer-card">
            <div class="customer-profile">
                @if($user->avatar != null)
                    <img src="{{ Storage::url($user->avatar) }}" alt="Customer Avatar" class="avatar">
                @else
                    <div class="bg-light rounded-circle border border-3 border-primary d-flex align-items-center justify-content-center shadow"
                        style="width: 120px; height: 120px;">
                        <i class="fas fa-user text-muted fs-1"></i>
                    </div>
                @endif
                <h2 class="customer-name">{{ $user->name }}</h2>
                <p class="join-date">Đuợc tạo từ {{ $this->formatJoinDate() }}</p>
            </div>
            <div class="address-section">
                <div class="section-title">
                    Địa chỉ mặc định
                    <i class="fas fa-edit edit-icon" wire:click="$dispatch('edit-address')"></i>
                </div>
                <div class="address-info">
                    <div class="info-item">
                        <div class="info-label">Địa chỉ</div>
                        <div class="info-value">
                            {{ $user->address}}<br>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Email</div>
                        <div class="info-value">
                            <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Số điện thoại</div>
                        <div class="info-value">{{ $user->phone ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            {{-- booking --}}
            <div class="orders-section">
                <div class="section-header">
                    <h2>Hóa đơn</h2>
                </div>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Mã hóa đơn #</th>
                                <th>Phim</th>
                                <th>Tổng tiền </th>
                                <th>Trạng thái đơn hàng</th>
                                <th>Phương thức thanh toán</th>
                                <th>Ngày tạo đơn</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $booking)
                                <tr>
                                    <td>
                                        <a href="#" class="order-id" wire:click="viewBooking({{ $booking->id }})">
                                            #{{ $booking->booking_code }}
                                        </a>
                                    </td>
                                    <td>{{ $booking->showtime->movie->title ?? 'Standard shipping' }}</td>
                                    <td>{{ number_format($booking->total_price, 0) }} VNĐ</td>
                                    <td>
                                        <span
                                            class="status-badge {{ $this->getStatusBadgeClass($booking->status, 'payment') }}">
                                            @switch($booking->status)
                                                @case('paid')
                                                    Đã thanh toán
                                                    @break
                                                @case('confirmed')
                                                    Đã xác nhận
                                                    @break
                                                @case('pending')
                                                    Chờ xử lý
                                                    @break
                                                @default
                                                    Unknown
                                                    @break
                                            @endswitch
                                            {{ $this->getStatusIcon($booking->status, 'payment') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span
                                            class="fulfillment-badge {{ $this->getStatusBadgeClass($booking->payment_method, 'fulfillment') }}">
                                            @switch($booking->payment_method)
                                                @case('credit_card')
                                                    Thẻ tín dụng
                                                    @break
                                                @case('bank_transfer')
                                                    Chuyển khoản ngân hàng
                                                    @break
                                                @case('e_wallet')
                                                    Ví điện tử
                                                    @break
                                                @case('cash')
                                                    Tiền mặt
                                                    @break
                                                @default
                                                    Unknown
                                                    @break
                                            @endswitch
                                            {{ $this->getStatusIcon($booking->payment_method, 'fulfillment') }}
                                        </span>
                                    </td>
                                    <td>{{ $booking->created_at->format('M j, g:i A') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="fa-solid fa-ticket text-gray-300 m-2" style="font-size: 3rem;"></i>
                                        <p>Không tìm thấy hóa đơn nào</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="pagination">
                    <div class="pagination-info">
                        {{ $bookings->firstItem() }} to {{ $bookings->lastItem() }} items of {{ $bookings->total() }}
                        @unless($showAllBookings)
                        &nbsp;&nbsp;<a href="#" wire:click.prevent="viewAll('bookings')">View all ›</a>
                        @endunless
                        @unless(!$showAllBookings)
                        &nbsp;&nbsp;<a href="#" wire:click.prevent="viewLess('bookings')">View less ›</a>
                        @endunless
                    </div>
                    {{-- <div class="pagination-controls">
                        {{ $bookings->links('b') }}
                    </div> --}}
                </div>
            </div>
            {{-- Đánh giá --}}
            <div class="orders-section mt-4">
                <div class="section-header">
                    <h2>Đánh giá</h2>
                </div>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Phim</th>
                                <th>Đánh giá</th>
                                <th>Nhận xét </th>
                                <th>Ngày đánh giá</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ratings as $rating)
                                <tr>
                                    <td>
                                        <a href="#" class="order-id">
                                            {{ $rating->movie->title ?? 'N/A' }}
                                        </a>
                                    </td>               
                                    <td class="align-middle rating white-space-nowrap fs-6">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if($i <= $rating->score)
                                        <span class="fa fa-star text-warning"></span>
                                        @else
                                        <span class="fa-regular fa-star text-warning"></span>
                                        @endif
                                    @endfor
                                    </td>    
                                    <td>{{$rating->review}}</td>
                                    <td>{{$rating->created_at->format('M j, g:i A') ?? 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="fa-solid fa-comments text-gray-300 m-2" style="font-size: 3rem;"></i>
                                        <p>Không tìm thấy đánh giá nào</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="pagination">
                    <div class="pagination-info">
                        {{ $ratings->firstItem() ?? 0 }} to {{ $ratings->lastItem() ?? 0 }} items of {{ $ratings->total() }}
                        @unless($showAllRatings)
                        &nbsp;&nbsp;<a href="#" wire:click.prevent="viewAll('ratings')">View all ›</a>
                        @endunless
                        @unless(!$showAllRatings)
                        &nbsp;&nbsp;<a href="#" wire:click.prevent="viewLess('ratings')">View less ›</a>
                        @endunless
                    </div>
                    {{-- <div class="pagination-controls">
                        {{ $bookings->links('b') }}
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
<style>
        .customer-details {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #0f1419;
            color: #ffffff;
            line-height: 1.6;
            min-height: 100vh;
            padding: 20px;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            font-size: 14px;
            color: #6b7280;
        }

        .breadcrumb a {
            color: #6b7280;
            text-decoration: none;
        }

        .breadcrumb a:hover {
            color: #ffffff;
        }

        .breadcrumb-separator {
            margin: 0 8px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 600;
            color: #ffffff;
        }

        .header-actions {
            display: flex;
            gap: 12px;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-danger {
            background: #dc2626;
            color: white;
        }

        .btn-secondary {
            background: #374151;
            color: white;
        }

        .btn:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        .main-content {
            display: grid;
            grid-template-columns: 400px 1fr;
            gap: 30px;
        }

        @media (max-width: 1024px) {
            .main-content {
                grid-template-columns: 1fr;
            }
        }

        .customer-card {
            background: rgba(31, 41, 55, 0.5);
            border: 1px solid rgba(75, 85, 99, 0.3);
            border-radius: 12px;
            padding: 24px;
            backdrop-filter: blur(10px);
            height: fit-content;
        }

        .customer-profile {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin-bottom: 24px;
        }

        .avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin-bottom: 16px;
            border: 3px solid #3b82f6;
            object-fit: cover;
        }

        .avatar-placeholder {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin-bottom: 16px;
            border: 3px solid #3b82f6;
            background: rgba(55, 65, 81, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: #6b7280;
        }

        .customer-name {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 4px;
            color: #ffffff;
        }

        .join-date {
            color: #9ca3af;
            font-size: 14px;
            margin-bottom: 16px;
        }

        .social-links {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
        }

        .social-links a {
            color: #6b7280;
            font-size: 18px;
            transition: color 0.2s;
        }

        .social-links a:hover {
            color: #3b82f6;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-item {
            text-align: center;
        }

        .stat-label {
            font-size: 12px;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-value {
            font-size: 24px;
            font-weight: 600;
            color: #ffffff;
        }

        .address-section {
            border-top: 1px solid rgba(75, 85, 99, 0.3);
            padding-top: 24px;
        }

        .section-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 16px;
            color: #ffffff;
        }

        .edit-icon {
            color: #6b7280;
            cursor: pointer;
            transition: color 0.2s;
        }

        .edit-icon:hover {
            color: #3b82f6;
        }

        .address-info {
            space-y: 12px;
        }

        .info-item {
            margin-bottom: 12px;
        }

        .info-label {
            font-size: 14px;
            color: #9ca3af;
            font-weight: 500;
        }

        .info-value {
            color: #ffffff;
            margin-top: 2px;
        }

        .info-value a {
            color: #3b82f6;
            text-decoration: none;
        }

        .info-value a:hover {
            text-decoration: underline;
        }

        .orders-section {
            background: rgba(31, 41, 55, 0.5);
            border: 1px solid rgba(75, 85, 99, 0.3);
            border-radius: 12px;
            padding: 24px;
            backdrop-filter: blur(10px);
        }

        .section-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .section-header h2 {
            font-size: 20px;
            font-weight: 600;
            color: #ffffff;
        }

        .count-badge {
            background: rgba(107, 114, 128, 0.3);
            color: #9ca3af;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 14px;
            margin-left: 8px;
        }

        .table-container {
            overflow-x: auto;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 12px 16px;
            font-size: 12px;
            font-weight: 600;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid rgba(75, 85, 99, 0.3);
        }

        td {
            padding: 16px;
            border-bottom: 1px solid rgba(75, 85, 99, 0.2);
            vertical-align: middle;
            color: #ffffff;
        }

        tr:hover {
            background: rgba(55, 65, 81, 0.3);
        }

        .order-id {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 500;
            cursor: pointer;
        }

        .order-id:hover {
            text-decoration: underline;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-paid {
            background: rgba(34, 197, 94, 0.2);
            color: #22c55e;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }

        .status-cancelled {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .status-pending {
            background: rgba(245, 158, 11, 0.2);
            color: #f59e0b;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .status-failed {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .fulfillment-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }

        .fulfillment-fulfilled {
            background: rgba(34, 197, 94, 0.2);
            color: #22c55e;
        }

        .fulfillment-partial {
            background: rgba(245, 158, 11, 0.2);
            color: #f59e0b;
        }

        .fulfillment-ready {
            background: rgba(59, 130, 246, 0.2);
            color: #3b82f6;
        }

        .fulfillment-cancelled {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }

        .fulfillment-delayed {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }

        .pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 20px;
        }

        .pagination-info {
            color: #9ca3af;
            font-size: 14px;
        }

        .pagination-info a {
            color: #3b82f6;
            text-decoration: none;
        }

        .pagination-info a:hover {
            text-decoration: underline;
        }

        .wishlist-container {
            max-height: 400px;
            overflow-y: auto;
        }

        .wishlist-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid rgba(75, 85, 99, 0.2);
        }

        .wishlist-item:last-child {
            border-bottom: none;
        }

        .product-image {
            width: 48px;
            height: 48px;
            border-radius: 6px;
            background: rgba(55, 65, 81, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-info {
            flex: 1;
        }

        .product-name {
            font-weight: 500;
            margin-bottom: 2px;
            color: #ffffff;
        }

        .product-details {
            font-size: 12px;
            color: #9ca3af;
        }

        .price {
            font-weight: 600;
            color: #ffffff;
        }

        .actions-btn {
            color: #6b7280;
            cursor: pointer;
            padding: 4px;
            transition: color 0.2s;
        }

        .actions-btn:hover {
            color: #ffffff;
        }

        .dropdown-menu {
            background: rgba(31, 41, 55, 0.95);
            border: 1px solid rgba(75, 85, 99, 0.3);
            backdrop-filter: blur(10px);
        }

        .dropdown-item {
            color: #ffffff;
        }

        .dropdown-item:hover {
            background: rgba(55, 65, 81, 0.5);
            color: #ffffff;
        }

        .text-danger {
            color: #ef4444 !important;
        }
</style>
</div>