<div class="scRender">
    <!-- Notification Bell Button -->
    <button class="notification-bell" type="button" data-bs-toggle="offcanvas" data-bs-target="#notificationOffcanvas" aria-controls="notificationOffcanvas">
        <i class="fa-regular fa-bells"></i>
        @if($unreadCount > 0)
            <span class="notification-count">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
        @endif
    </button>

    <!-- Offcanvas Notification Panel -->
    <div class="offcanvas offcanvas-end notification-offcanvas" tabindex="-1" id="notificationOffcanvas" aria-labelledby="notificationOffcanvasLabel">
        <!-- Offcanvas Header -->
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="notificationOffcanvasLabel">Thông báo</h5>
            <div class="notification-header-actions">
                <button type="button" wire:click="markAllAsRead" title="Đánh dấu tất cả đã đọc">
                    <i class="fa-duotone fa-solid fa-ballot-check"></i>
                </button>
                <button type="button"
                class="btn-close button2
                " data-bs-dismiss="offcanvas" aria-label="Close">
                    <i class="fa-solid fa-xmark-large"></i>
                </button>
            </div>
        </div>

        <!-- Offcanvas Body -->
        <div class="offcanvas-body p-0">
            <div x-data="{ tab: '{{ $activeTab }}' }">
                <!-- Notification Tabs -->
                <div class="notification-tabs">
                    <ul class="nav nav-pills nav-fill" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link" :class="{ 'active': tab === 'all' }" @click="tab = 'all'">
                                Tất cả
                                @if($unreadCount > 0)
                                    <span class="badge bg-danger">{{ $unreadCount }}</span>
                                @endif
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" :class="{ 'active': tab === 'unread' }" @click="tab = 'unread'">
                                Chưa đọc
                                @if($unreadCount > 0)
                                    <span class="badge bg-danger">{{ $unreadCount }}</span>
                                @endif
                            </button>
                        </li>
                    </ul>
                </div>

                <!-- Notification Content -->
                <div class="notification-content">
                    <div class="notification-list">
                        <div x-show="tab === 'all'">
                            @foreach($notifications as $notification)
                                <div class="notification-item {{ (isset($notification->pivot) && !$notification->pivot->is_read) ? 'unread' : 'read' }}"
                                     wire:click="markAsRead({{ isset($notification->pivot) ? $notification->pivot->id : 0 }})">
                                    <div class="notification-avatar">
                                        @if($notification->thumbnail)
                                            <img src="{{ asset('storage/' . $notification->thumbnail) }}"
                                                 alt="Avatar"
                                                 class="rounded-circle">
                                        @else
                                            <div class="system-avatar bg-info text-white rounded-circle">
                                                <i class="mdi mdi-bell-outline"></i>
                                            </div>
                                        @endif
                                        @if(isset($notification->pivot) && !$notification->pivot->is_read)
                                            <div class="notification-badge"></div>
                                        @endif
                                    </div>

                                    <div class="notification-body">
                                        <div class="notification-content-text">
                                            <strong>{{ $notification->title }}</strong>
                                            <div>{{ $notification->content }}</div>
                                        </div>
                                        <div class="notification-time">
                                            <i class="mdi mdi-clock-outline"></i>
                                            {{ isset($notification->pivot) ? $notification->pivot->updated_at->diffForHumans() : '' }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div x-show="tab === 'unread'">
                            @foreach($unreadNotifications as $notification)
                                <div class="notification-item {{ (isset($notification->pivot) && !$notification->pivot->is_read) ? 'unread' : 'read' }}"
                                     wire:click="markAsRead({{ isset($notification->pivot) ? $notification->pivot->id : 0 }})">
                                    <div class="notification-avatar">
                                        @if($notification->thumbnail)
                                            <img src="{{ asset('storage/' . $notification->thumbnail) }}"
                                                 alt="Avatar"
                                                 class="rounded-circle">
                                        @else
                                            <div class="system-avatar bg-info text-white rounded-circle">
                                                <i class="mdi mdi-bell-outline"></i>
                                            </div>
                                        @endif
                                        @if(isset($notification->pivot) && !$notification->pivot->is_read)
                                            <div class="notification-badge"></div>
                                        @endif
                                    </div>

                                    <div class="notification-body">
                                        <div class="notification-content-text">
                                            <strong>{{ $notification->title }}</strong>
                                            <div>{{ $notification->content }}</div>
                                        </div>
                                        <div class="notification-time">
                                            <i class="mdi mdi-clock-outline"></i>
                                            {{ isset($notification->pivot) ? $notification->pivot->updated_at->diffForHumans() : '' }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Notification Footer -->
                <div class="notification-footer">
                    <a href="{{ route('client.notifications.allnotification') }}" class="btn btn-link text-center w-100">
                        Xem tất cả thông báo
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@script

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Khi offcanvas mở, gọi Livewire refreshNotifications
        var offcanvas = document.getElementById('notificationOffcanvas');
        if (offcanvas) {
            offcanvas.addEventListener('shown.bs.offcanvas', function () {
                @this.call('refreshNotifications');
            });
        }

        // Auto refresh notifications every 30 seconds
        setInterval(function() {
            @this.call('refreshNotifications');
        }, 30000);
    });
</script>

@endscript
