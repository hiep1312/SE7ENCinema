@php
$isAllNotifications = request()->routeIs('client.notifications.allnotification');
@endphp

<div class="scRender scNotificationIndex" wire:poll.30s="refreshNotifications"
     x-data="{
        openNotification(link, event, notificationId) {
            if (!link || link === '#' || link === '' || link === null || link === undefined) {
                event.preventDefault();
                event.stopPropagation();
                alert('Đường dẫn không hợp lệ hoặc đã bị xóa!');
                return false;
            }
            try {
                if (!link.startsWith('http') && !link.startsWith('/')) {
                    event.preventDefault();
                    event.stopPropagation();
                    alert('Đường dẫn không hợp lệ hoặc đã bị xóa!');
                    return false;
                }
            } catch (e) {
                event.preventDefault();
                event.stopPropagation();
                alert('Đường dẫn không hợp lệ hoặc đã bị xóa!');
                return false;
            }
            if (notificationId) {
                $wire.markAsRead(notificationId);
            }
            window.open(link, '_blank');
        }
     }">

  @if(!$isAllNotifications)
    <!-- Notification Bell Button -->
    <button class="notification-bell" type="button" wire:click="toggleOffcanvas">
      <i class="fa-solid fa-bell"></i>
      @if($unreadCount > 0)
        <span class="notification-count">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
      @endif
    </button>

    <!-- Offcanvas Notification Panel -->
    @if($isOpen)
    <div class="notification-offcanvas full-notifications__container"
         x-show="true"
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-x-10"
         x-transition:enter-end="opacity-100 translate-x-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-x-0"
         x-transition:leave-end="opacity-0 translate-x-10"
         style="overflow-y: hidden; max-height: 100vh; display: flex; flex-direction: column; position: fixed; top: 0; right: 0; z-index: 1050; width: 100%; max-width: 360px; min-width: 320px; background: #fff;"
         @keydown.window.escape="$wire.closeOffcanvas()"
         @click.away="$wire.closeOffcanvas()">

      <!-- Header -->
      <div class="offcanvas-header full-notifications__header" style="display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 8px;">
          <h5 class="offcanvas-title full-notifications__title" style="margin: 0;">Thông báo</h5>
        </div>
        <div class="notification-header-actions full-notifications__actions" style="margin-left: 8px;">
          <button type="button" wire:click="markAllAsRead" title="Đánh dấu tất cả đã đọc" class="full-notifications__menu-btn">
            <i class="fa-solid fa-check-double"></i>
          </button>
          <button type="button" class="btn-close full-notifications__menu-btn" wire:click="closeOffcanvas" aria-label="Close">
            <i class="fa-solid fa-xmark"></i>
          </button>
        </div>
      </div>

      <!-- Body -->
      <div class="offcanvas-body p-0 d-flex flex-column full-notifications__content" style="flex: 1 1 auto; min-height: 0;">
        <div style="display: flex; flex-direction: column; height: 100%; min-height: 0;">

         <!-- Notification Tabs -->
         <div class="notification-tabs full-notifications__nav">
            <ul class="nav nav-pills nav-fill full-notifications__tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link notification-tab-custom {{ $tab === 'all' ? 'active-tab-custom' : '' }}"
                            wire:click="switchTab('all')">
                        Tất cả
                        @if($unreadCount > 0)
                            <span class="badge bg-danger full-notifications__badge">{{ $unreadCount }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link notification-tab-custom {{ $tab === 'unread' ? 'active-tab-custom' : '' }}"
                            wire:click="switchTab('unread')">
                        Chưa đọc
                        @if($unreadCount > 0)
                            <span class="badge bg-danger full-notifications__badge">{{ $unreadCount }}</span>
                        @endif
                    </a>
                </li>
            </ul>
        </div>

        <!-- Content -->
        <div class="notification-content flex-grow-1" style="height: 100%; overflow-y: auto; min-height: 0;">
          {{-- All Notifications --}}
          @if($tab === 'all')
          <div class="full-notifications__tab-content">
            {{-- Section Mới --}}
            @if($this->newNotifications->count() > 0)
            <div class="full-notifications__section">
                <div class="full-notifications__section-header new-section">
                    <h2 class="full-notifications__section-title">Mới</h2>
                </div>
                <div class="full-notifications__list">
                    @foreach($this->newNotifications as $notification)
                        @php
                            $isRead = $notification->pivot->is_read ?? true;
                            $pivotId = $notification->pivot->id ?? 0;
                        @endphp
                        <a href="#" tabindex="0" class="notification__item {{ !$isRead ? 'notification__item--unread' : 'notification__item--read' }}"
                           @click="openNotification('{{ $notification->link ?? '' }}', $event, {{ $pivotId }})">
                            <span class="notification__avatar">
                                @if($notification->thumbnail)
                                    <img src="{{ asset('storage/' . $notification->thumbnail) }}" alt="Avatar" class="full-notifications__avatar-img">
                                @else
                                    <div class="full-notifications__avatar-placeholder">
                                        <i class="fa-solid fa-bell"></i>
                                    </div>
                                @endif
                                {{-- @if(!$isRead)
                                    <div class="full-notifications__unread-indicator"></div>
                                @endif --}}
                            </span>
                            <span class="notification__body">
                                <span class="notification__content">
                                    <strong>{{ $notification->title }}</strong> {{ $notification->content }}
                                </span>
                                <span class="notification__time">{{ $notification->timeText }}</span>
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif
            {{-- Section Trước đó --}}
            @if($this->oldNotifications->count() > 0)
            <div class="full-notifications__section">
                <div class="full-notifications__section-header old-section">
                    <h2 class="full-notifications__section-title">Trước đó</h2>
                </div>
                <div class="full-notifications__list">
                    @foreach($this->oldNotifications as $notification)
                        @php
                            $isRead = $notification->pivot->is_read ?? true;
                            $pivotId = $notification->pivot->id ?? 0;
                        @endphp
                        <a href="#" tabindex="0" class="notification__item {{ !$isRead ? 'notification__item--unread' : 'notification__item--read' }}"
                           @click="openNotification('{{ $notification->link ?? '' }}', $event, {{ $pivotId }})">
                            <span class="notification__avatar">
                                @if($notification->thumbnail)
                                    <img src="{{ asset('storage/' . $notification->thumbnail) }}" alt="Avatar" class="full-notifications__avatar-img">
                                @else
                                    <div class="full-notifications__avatar-placeholder">
                                        <i class="fa-solid fa-bell"></i>
                                    </div>
                                @endif
                                @if(!$isRead)
                                    <div class="full-notifications__unread-indicator"></div>
                                @endif
                            </span>
                            <span class="notification__body">
                                <span class="notification__content">
                                    <strong>{{ $notification->title }}</strong> {{ $notification->content }}
                                </span>
                                <span class="notification__time">{{ $notification->timeText }}</span>
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif
            {{-- Empty state --}}
            @if($this->newNotifications->count() == 0 && $this->oldNotifications->count() == 0)
            <div class="full-notifications__empty">
                <div class="full-notifications__empty-icon">
                    <i class="fa-solid fa-bell-slash"></i>
                </div>
                <h3 class="full-notifications__empty-title">Không có thông báo</h3>
                <p class="full-notifications__empty-text">Bạn chưa có thông báo nào.</p>
            </div>
            @endif
            @if($hasMore)
            <div class="notification__load-more">
                <a wire:click="loadMore"
                        class="notification__load-more-link"
                        wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="loadMore">
                        Hiển thị thêm thông báo trước đó
                    </span>
                    <span wire:loading wire:target="loadMore">
                        <i class="fa-solid fa-spinner fa-spin"></i>
                        Đang tải...
                    </span>
                </a>
            </div>
            @endif
          </div>
          @endif

          {{-- Unread Notifications --}}
          @if($tab === 'unread')
          <div class="full-notifications__tab-content">
            {{-- Section Mới --}}
            @if($this->newUnreadNotifications->count() > 0)
            <div class="full-notifications__section">
                <div class="full-notifications__section-header new-section">
                    <h2 class="full-notifications__section-title">Mới</h2>
                </div>
                <div class="full-notifications__list">
                    @foreach($this->newUnreadNotifications as $notification)
                        @php $pivotId = $notification->pivot->id ?? 0; @endphp
                        <a href="#" tabindex="0" class="notification__item full-notifications__item--unread"
                           @click="openNotification('{{ $notification->link ?? '' }}', $event, {{ $pivotId }})">
                            <span class="notification__avatar">
                                @if($notification->thumbnail)
                                    <img src="{{ asset('storage/' . $notification->thumbnail) }}" alt="Avatar" class="full-notifications__avatar-img">
                                @else
                                    <div class="full-notifications__avatar-placeholder">
                                        <i class="fa-solid fa-bell"></i>
                                    </div>
                                @endif
                                <div class="full-notifications__unread-indicator"></div>
                            </span>
                            <span class="notification__body">
                                <span class="notification__content">
                                    <strong>{{ $notification->title }}</strong> {{ $notification->content }}
                                </span>
                                <span class="notification__time">{{ $notification->timeText }}</span>
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif
            {{-- Section Trước đó --}}
            @if($this->oldUnreadNotifications->count() > 0)
            <div class="full-notifications__section">
                <div class="full-notifications__section-header old-section">
                    <h2 class="full-notifications__section-title">Trước đó</h2>
                </div>
                <div class="full-notifications__list">
                    @foreach($this->oldUnreadNotifications as $notification)
                        @php $pivotId = $notification->pivot->id ?? 0; @endphp
                        <a href="#" tabindex="0" class="notification__item full-notifications__item--unread"
                           @click="openNotification('{{ $notification->link ?? '' }}', $event, {{ $pivotId }})">
                            <span class="notification__avatar">
                                @if($notification->thumbnail)
                                    <img src="{{ asset('storage/' . $notification->thumbnail) }}" alt="Avatar" class="full-notifications__avatar-img">
                                @else
                                    <div class="full-notifications__avatar-placeholder">
                                        <i class="fa-solid fa-bell"></i>
                                    </div>
                                @endif
                                <div class="full-notifications__unread-indicator"></div>
                            </span>
                            <span class="notification__body">
                                <span class="notification__content">
                                    <strong>{{ $notification->title }}</strong> {{ $notification->content }}
                                </span>
                                <span class="notification__time">{{ $notification->timeText }}</span>
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif
            {{-- Empty state --}}
            @if($this->newUnreadNotifications->count() == 0 && $this->oldUnreadNotifications->count() == 0)
            <div class="full-notifications__empty">
                <div class="full-notifications__empty-icon">
                    <i class="fa-solid fa-bell-slash"></i>
                </div>
                <h3 class="full-notifications__empty-title">Không có thông báo chưa đọc</h3>
                <p class="full-notifications__empty-text">Bạn đã đọc tất cả thông báo.</p>
            </div>
            @endif
          </div>
          @endif
        </div>
        <!-- Xem tất cả thông báo link (chỉ hiện ở tab tất cả) -->
        @if($tab === 'all')
        <div class="allntic" style="display: flex; justify-content: flex-end; align-items: center; padding: 8px 16px 0 16px;">
            <a href="{{ route('client.notifications.allnotification') }}" class="text-danger fw-bold allntic1" style="text-decoration: none; font-size: 14px; padding: 10px 0px;">Xem tất cả</a>
        </div>
        @endif
        </div>
      </div>
    </div>
    @endif
  @endif
</div>

@script
<script>
    document.addEventListener('alpine:init', () => {
        // Auto refresh notifications every 30 seconds
        setInterval(function() {
            if (window.Livewire) {
                const element = document.querySelector('[wire\:poll]');
                if (element) {
                    const wireId = element.getAttribute('wire:id');
                    if (wireId) {
                        window.Livewire.find(wireId)?.call('refreshNotifications');
                    }
                }
            }
        }, 30000);
    });
</script>
@endscript
