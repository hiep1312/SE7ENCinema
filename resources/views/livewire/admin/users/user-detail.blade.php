@push('styles')
    @vite('resources/css/components/user-detail.css')
@endpush
<div>
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-times me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Breadcrumb --}}
    <nav class="breadcrumb">
        <a href="{{ route('admin.users.index') }}">Dashboard</a>
        <span class="breadcrumb-separator">›</span>
        <a href="{{ route('admin.users.index') }}">Customers</a>
        <span class="breadcrumb-separator">›</span>
        <span>{{ $user->name }}</span>
    </nav>

    {{-- Page Header --}}
    <div class="page-header">
        <h1 class="page-title">Customer details</h1>
        <div class="header-actions">
            <button class="btn btn-danger" wire:click="deleteCustomer"
                wire:confirm="Are you sure you want to delete this customer?">
                <i class="fas fa-trash"></i>
                Delete customer
            </button>
            <button class="btn btn-secondary" wire:click="resetPassword"
                wire:confirm="Send password reset email to this customer?">
                <i class="fas fa-key"></i>
                Reset password
            </button>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="main-content">
        {{-- Customer Info --}}
        <div class="customer-card">
            <div class="customer-profile">
                @if($customer->avatar)
                    <img src="{{ Storage::url($customer->avatar) }}" alt="Customer Avatar" class="avatar">
                @else
                    <div class="avatar-placeholder">
                        <i class="fas fa-user"></i>
                    </div>
                @endif
                <h2 class="customer-name">{{ $customer->name }}</h2>
                <p class="join-date">Joined {{ $this->formatJoinDate() }}</p>
                <div class="social-links">
                    @if($customer->linkedin_url)
                        <a href="{{ $customer->linkedin_url }}" target="_blank"><i class="fab fa-linkedin"></i></a>
                    @endif
                    @if($customer->facebook_url)
                        <a href="{{ $customer->facebook_url }}" target="_blank"><i class="fab fa-facebook"></i></a>
                    @endif
                    @if($customer->twitter_url)
                        <a href="{{ $customer->twitter_url }}" target="_blank"><i class="fab fa-twitter"></i></a>
                    @endif
                </div>
            </div>

            <div class="stats">
                <div class="stat-item">
                    <div class="stat-label">Following</div>
                    <div class="stat-value">{{ $customerStats['following'] }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Projects</div>
                    <div class="stat-value">{{ $customerStats['projects'] }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Completion</div>
                    <div class="stat-value">{{ $customerStats['completion'] }}</div>
                </div>
            </div>

            <div class="address-section">
                <div class="section-title">
                    Default Address
                    <i class="fas fa-edit edit-icon" wire:click="$dispatch('edit-address')"></i>
                </div>
                <div class="address-info">
                    <div class="info-item">
                        <div class="info-label">Address</div>
                        <div class="info-value">
                            {{ $customer->address_line_1 }}<br>
                            @if($customer->address_line_2)
                                {{ $customer->address_line_2 }}<br>
                            @endif
                            {{ $customer->city }}, {{ $customer->state }}<br>
                            {{ $customer->country }}
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Email</div>
                        <div class="info-value">
                            <a href="mailto:{{ $customer->email }}">{{ $customer->email }}</a>
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Phone</div>
                        <div class="info-value">{{ $customer->phone ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Orders and Wishlist --}}
        <div>
            {{-- Orders Section --}}
            <div class="orders-section">
                <div class="section-header">
                    <h2>Orders</h2>
                    <span class="count-badge">({{ $customer->orders_count }})</span>
                </div>

                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>ORDER #</th>
                                <th>TOTAL $</th>
                                <th>PAYMENT STATUS</th>
                                <th>FULFILMENT STATUS</th>
                                <th>DELIVERY TYPE</th>
                                <th>DATE</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td>
                                        <a href="#" class="order-id" wire:click="viewOrder({{ $order->id }})">
                                            #{{ $order->id }}
                                        </a>
                                    </td>
                                    <td>${{ number_format($order->total, 2) }}</td>
                                    <td>
                                        <span
                                            class="status-badge {{ $this->getStatusBadgeClass($order->payment_status, 'payment') }}">
                                            {{ strtoupper($order->payment_status) }}
                                            {{ $this->getStatusIcon($order->payment_status, 'payment') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span
                                            class="fulfillment-badge {{ $this->getStatusBadgeClass($order->fulfillment_status, 'fulfillment') }}">
                                            {{ strtoupper(str_replace('_', ' ', $order->fulfillment_status)) }}
                                            {{ $this->getStatusIcon($order->fulfillment_status, 'fulfillment') }}
                                        </span>
                                    </td>
                                    <td>{{ $order->delivery_type ?? 'Standard shipping' }}</td>
                                    <td>{{ $order->created_at->format('M j, g:i A') }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <i class="fas fa-ellipsis-h actions-btn" data-bs-toggle="dropdown"></i>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#"
                                                        wire:click="viewOrder({{ $order->id }})">View Order</a></li>
                                                <li><a class="dropdown-item" href="#">Edit Order</a></li>
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li><a class="dropdown-item text-danger" href="#">Cancel Order</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        No orders found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="pagination">
                    <div class="pagination-info">
                        {{ $orders->firstItem() }} to {{ $orders->lastItem() }} items of {{ $orders->total() }}
                        @unless($showAllOrders)
                            &nbsp;&nbsp;<a href="#" wire:click="viewAllOrders">View all ›</a>
                        @endunless
                    </div>
                    <div class="pagination-controls">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>

            {{-- Wishlist Section --}}
            <div class="orders-section" style="margin-top: 30px;">
                <div class="section-header">
                    <h2>Wishlist</h2>
                    <span class="count-badge">({{ $customer->wishlist_items_count }})</span>
                </div>

                <div class="wishlist-container">
                    @forelse($wishlistItems as $item)
                        <div class="wishlist-item">
                            <div class="product-image">
                                @if($item->product->image)
                                    <img src="{{ Storage::url($item->product->image) }}" alt="{{ $item->product->name }}">
                                @else
                                    <i class="fas fa-box text-gray-400"></i>
                                @endif
                            </div>
                            <div class="product-info">
                                <div class="product-name">{{ $item->product->name }}</div>
                                <div class="product-details">
                                    @if($item->product->color){{ $item->product->color }}@endif
                                    @if($item->product->size) • {{ $item->product->size }}@endif
                                </div>
                            </div>
                            <div class="price">${{ number_format($item->product->price, 2) }}</div>
                            <div class="price">${{ number_format($item->product->price, 2) }}</div>
                            <div class="dropdown">
                                <i class="fas fa-ellipsis-h actions-btn" data-bs-toggle="dropdown"></i>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Move to Cart</a></li>
                                    <li><a class="dropdown-item" href="#">View Product</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item text-danger" href="#">Remove from Wishlist</a></li>
                                </ul>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-heart text-gray-300 mb-2" style="font-size: 2rem;"></i>
                            <p>No items in wishlist</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Loading States --}}
    <div wire:loading wire:target="deleteCustomer,resetPassword"
        class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center"
        style="background: rgba(0,0,0,0.7); z-index: 9999;">
        <div class="bg-white p-4 rounded shadow text-center">
            <div class="spinner-border text-primary mb-2" role="status">
                <span class="visually-hidden">Processing...</span>
            </div>
            <div>Processing...</div>
        </div>
    </div>
</div>