<div class="scRender scNotificationAdm notification-admin" wire:poll.10s="refreshNotifications">
    <!-- Notification Bell Button -->
    <button class="notification-admin__bell" type="button" wire:click="toggleDropdown">
        <i class="mdi mdi-bell"></i>
        @if($unreadCount > 0)
            <span class="notification-admin__count">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
        @endif
    </button>

    <!-- Dropdown Notification Panel -->
    @if($isOpen)
    <div class="notification-admin__dropdown show">
        <div class="notification-admin__header">
            <h6 class="notification-admin__title">Thông báo</h6>
            <div class="notification-admin__actions">
                <button type="button" wire:click="markAllAsRead" class="notification-admin__action-btn" title="Đánh dấu tất cả đã đọc">
                    <i class="mdi mdi-check-all"></i>
                </button>
                <button type="button" class="notification-admin__close-btn" wire:click="toggleDropdown" aria-label="Close">
                    <i class="mdi mdi-close"></i>
                </button>
            </div>
        </div>

        <div class="notification-admin__content">
            @forelse($notifications as $notification)
                @php
                    $isRead = $notification->pivot->is_read === 1;
                    $pivotId = $notification->pivot->id ?? 0;
                @endphp
                <div class="notification-admin__item {{ !$isRead ? 'notification-admin__item--unread' : 'notification-admin__item--read' }}"
                     wire:click="handleNotificationClick('{{ $notification->link ?? '' }}', {{ $pivotId }})"
                     style="cursor: pointer;">
                    <div class="notification-admin__thumbnail">
                        @if($notification->thumbnail)
                            <img src="{{ asset('storage/' . $notification->thumbnail) }}"
                                 alt="Thumbnail"
                                 class="notification-admin__thumbnail-img">
                        @else
                            <div class="notification-admin__thumbnail-placeholder">
                                <i class="mdi mdi-bell"></i>
                            </div>
                        @endif
                        @if(!$isRead)
                            <div class="notification-admin__unread-indicator"></div>
                        @endif
                    </div>
                    <div class="notification-admin__body">
                        <div class="notification-admin__text">
                            <p class="notification-admin__subject mb-1">{{ Str::limit($notification->title, 30, '...') }}</p>
                            <p class="notification-admin__content-text text-muted ellipsis mb-0">{{ Str::limit($notification->content ?? 'Không có nội dung', 50, '...') }}</p>
                        </div>
                        <div class="notification-admin__meta">
                            <span class="notification-admin__time text-muted">{{ $notification->timeText }}</span>
                        </div>
                    </div>
                </div>
                @if(!$loop->last)
                    <div class="notification-admin__divider"></div>
                @endif
            @empty
                <div class="notification-admin__empty">
                    <div class="notification-admin__empty-icon">
                        <i class="mdi mdi-bell-slash"></i>
                    </div>
                    <h6 class="notification-admin__empty-title">Không có thông báo</h6>
                    <p class="notification-admin__empty-text">Bạn chưa có thông báo nào.</p>
                </div>
            @endforelse
        </div>

        @if($notifications->count() > 0)
        <div class="notification-admin__footer">
            @if($showAll)
                <button type="button" wire:click="showRecentNotifications" class="notification-admin__view-all">
                    Xem thông báo gần đây
                </button>
            @else
                <button type="button" wire:click="showAllNotifications" class="notification-admin__view-all">
                    Xem tất cả thông báo
                </button>
            @endif
        </div>
        @endif
    </div>
    @endif
</div>

@script
<script>
    document.addEventListener('livewire:init', () => {
        // Add keyboard support for closing dropdown
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const notificationComponent = document.querySelector('[wire\\:id]');
                if (notificationComponent) {
                    const wireId = notificationComponent.getAttribute('wire:id');
                    if (wireId) {
                        Livewire.find(wireId)?.call('toggleDropdown');
                    }
                }
            }
        });

        // Add click outside to close dropdown
        document.addEventListener('click', function(event) {
            const dropdown = document.querySelector('.notification-admin__dropdown');
            const bellButton = document.querySelector('.notification-admin__bell');

            if (dropdown && !dropdown.contains(event.target) && !bellButton.contains(event.target)) {
                const notificationComponent = document.querySelector('[wire\\:id]');
                if (notificationComponent) {
                    const wireId = notificationComponent.getAttribute('wire:id');
                    if (wireId) {
                        Livewire.find(wireId)?.call('toggleDropdown');
                    }
                }
            }
        });
    });
</script>
@endscript
