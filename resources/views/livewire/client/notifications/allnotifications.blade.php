@php
$isAllNotifications = request()->routeIs('client.notifications.allnotification');
@endphp

<div class="scRender scAllNotifications full-notifications" wire:poll.15s="refreshNotifications"
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
     {{-- <div class="tbt "
     style="clear: both;
     " >
   </div> --}}
    <div class="full-notifications__container">
        <!-- Header -->
        <div class="full-notifications__header">
            <div class="header-left">
                <a href="javascript:history.back()" class="back-btn">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <h1 class="full-notifications__title">Thông báo</h1>
            </div>
            <div class="full-notifications__actions" x-data="{ dropdownOpen: false }">
                <button @click="dropdownOpen = !dropdownOpen"
                        class="full-notifications__menu-btn"
                        type="button">
                    <i class="fa-solid fa-ellipsis"></i>
                </button>
                <!-- Dropdown Menu -->
                <div x-show="dropdownOpen"
                     @click.away="dropdownOpen = false"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="full-notifications__dropdown">
                    <button wire:click="markAllAsRead"
                            class="full-notifications__dropdown-item"
                            @click="dropdownOpen = false">
                        <i class="fa-solid fa-check-double"></i>
                        <span>Đánh dấu tất cả là đã đọc</span>
                    </button>
                    <button wire:click="refreshNotifications"
                            class="full-notifications__dropdown-item"
                            @click="dropdownOpen = false">
                        <i class="fa-solid fa-refresh"></i>
                        <span>Làm mới thông báo</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="full-notifications__nav">
            <div class="full-notifications__tabs" x-data="{ activeTab: @entangle('tab') }">
                <button class="full-notifications__tab"
                        :class="{ 'full-notifications__tab--active': activeTab === 'all' }"
                        @click="activeTab = 'all'"
                        wire:click="switchTab('all')">
                    <span class="full-notifications__tab-text">Tất cả</span>
                </button>
                <button class="full-notifications__tab"
                        :class="{ 'full-notifications__tab--active': activeTab === 'unread' }"
                        @click="activeTab = 'unread'"
                        wire:click="switchTab('unread')">
                    <span class="full-notifications__tab-text">Chưa đọc</span>
                    @if($unreadCount > 0)
                        <span class="full-notifications__badge">{{ $unreadCount }}</span>
                    @endif
                </button>
            </div>
        </div>

        <!-- Content Area -->
        <div class="full-notifications__content">
            <!-- All Notifications Tab -->
            @if($tab === 'all')
            <div class="full-notifications__tab-content">
                {{-- Section Mới --}}
                @if($newNotifications->count() > 0)
                <div class="full-notifications__section">
                    <div class="full-notifications__section-header new-section">
                        <h2 class="full-notifications__section-title">Mới</h2>
                    </div>
                    <div class="full-notifications__list">
                        @foreach($newNotifications as $notification)
                            @php
                                $isRead = $notification->pivot->is_read ?? true;
                                $pivotId = $notification->pivot->id ?? 0;
                            @endphp
                            <div class="full-notifications__item {{ !$isRead ? 'full-notifications__item--unread' : '' }}"
                                 @click="openNotification('{{ $notification->link ?? '' }}', $event, {{ $pivotId }})">
                                <div class="full-notifications__avatar">
                                    @if($notification->thumbnail)
                                        <img src="{{ asset('storage/' . $notification->thumbnail) }}"
                                             alt="Avatar"
                                             class="full-notifications__avatar-img">
                                    @else
                                        <div class="full-notifications__avatar-placeholder">
                                            <i class="fa-solid fa-bell"></i>
                                        </div>
                                    @endif
                                    @if(!$isRead)
                                        <div class="full-notifications__unread-indicator"></div>
                                    @endif
                                </div>
                                <div class="full-notifications__body">
                                    <div class="full-notifications__text">
                                        <div class="full-notifications__text-content">
                                            <strong>{{ $notification->title }}</strong> {{ $notification->content }}
                                        </div>
                                    </div>
                                    <div class="full-notifications__meta">
                                        <span class="full-notifications__time">{{ $notification->timeText }}</span>
                                    </div>
                                </div>
                                @if(!$isRead)
                                <div class="full-notifications__actions">
                                    <button wire:click.stop="markAsRead({{ $pivotId }})"
                                            class="mark-read-btn"
                                            title="Đánh dấu đã đọc">
                                        <i class="fa-solid fa-circle"></i>
                                    </button>
                                </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Section Trước đó --}}
                @if($oldNotifications->count() > 0)
                <div class="full-notifications__section">
                    <div class="full-notifications__section-header old-section">
                        <h2 class="full-notifications__section-title">Trước đó</h2>
                    </div>
                    <div class="full-notifications__list">
                        @foreach($oldNotifications as $notification)
                            @php
                                $isRead = $notification->pivot->is_read ?? true;
                                $pivotId = $notification->pivot->id ?? 0;
                            @endphp
                            <div class="full-notifications__item {{ !$isRead ? 'full-notifications__item--unread' : '' }}"
                                 @click="openNotification('{{ $notification->link ?? '' }}', $event, {{ $pivotId }})">
                                <div class="full-notifications__avatar">
                                    @if($notification->thumbnail)
                                        <img src="{{ asset('storage/' . $notification->thumbnail) }}"
                                             alt="Avatar"
                                             class="full-notifications__avatar-img">
                                    @else
                                        <div class="full-notifications__avatar-placeholder">
                                            <i class="fa-solid fa-bell"></i>
                                        </div>
                                    @endif
                                    @if(!$isRead)
                                        <div class="full-notifications__unread-indicator"></div>
                                    @endif
                                </div>
                                <div class="full-notifications__body">
                                    <div class="full-notifications__text">
                                        <div class="full-notifications__text-content">
                                            <strong>{{ $notification->title }}</strong> {{ $notification->content }}
                                        </div>
                                    </div>
                                    <div class="full-notifications__meta">
                                        <span class="full-notifications__time">{{ $notification->timeText }}</span>
                                    </div>
                                </div>
                                @if(!$isRead)
                                <div class="full-notifications__actions">
                                    <button wire:click.stop="markAsRead({{ $pivotId }})"
                                            class="mark-read-btn"
                                            title="Đánh dấu đã đọc">
                                        <i class="fa-solid fa-circle"></i>
                                    </button>
                                </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Empty state --}}
                @if($newNotifications->count() == 0 && $oldNotifications->count() == 0)
                <div class="full-notifications__empty">
                    <div class="full-notifications__empty-icon">
                        <i class="fa-solid fa-bell-slash"></i>
                    </div>
                    <h3 class="full-notifications__empty-title">Không có thông báo</h3>
                    <p class="full-notifications__empty-text">Bạn chưa có thông báo nào trong 30 ngày qua.</p>
                </div>
                @endif
            </div>
            @endif

            <!-- Unread Notifications Tab -->
            @if($tab === 'unread')
            <div class="full-notifications__tab-content">
                {{-- Section Mới --}}
                @if($newUnreadNotifications->count() > 0)
                <div class="full-notifications__section">
                    <div class="full-notifications__section-header new-section">
                        <h2 class="full-notifications__section-title">Mới</h2>
                    </div>
                    <div class="full-notifications__list">
                        @foreach($newUnreadNotifications as $notification)
                            @php $pivotId = $notification->pivot->id ?? 0; @endphp
                            <div class="full-notifications__item full-notifications__item--unread"
                                 @click="openNotification('{{ $notification->link ?? '' }}', $event, {{ $pivotId }})">
                                <div class="full-notifications__avatar">
                                    @if($notification->thumbnail)
                                        <img src="{{ asset('storage/' . $notification->thumbnail) }}"
                                             alt="Avatar"
                                             class="full-notifications__avatar-img">
                                    @else
                                        <div class="full-notifications__avatar-placeholder">
                                            <i class="fa-solid fa-bell"></i>
                                        </div>
                                    @endif
                                    <div class="full-notifications__unread-indicator"></div>
                                </div>
                                <div class="full-notifications__body">
                                    <div class="full-notifications__text">
                                        <div class="full-notifications__text-content">
                                            <strong>{{ $notification->title }}</strong> {{ $notification->content }}
                                        </div>
                                    </div>
                                    <div class="full-notifications__meta">
                                        <span class="full-notifications__time">{{ $notification->timeText }}</span>
                                    </div>
                                </div>
                                <div class="full-notifications__actions">
                                    <button wire:click.stop="markAsRead({{ $pivotId }})"
                                            class="mark-read-btn"
                                            title="Đánh dấu đã đọc">
                                        <i class="fa-solid fa-circle"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Section Trước đó --}}
                @if($oldUnreadNotifications->count() > 0)
                <div class="full-notifications__section">
                    <div class="full-notifications__section-header old-section">
                        <h2 class="full-notifications__section-title">Trước đó</h2>
                    </div>
                    <div class="full-notifications__list">
                        @foreach($oldUnreadNotifications as $notification)
                            @php $pivotId = $notification->pivot->id ?? 0; @endphp
                            <div class="full-notifications__item full-notifications__item--unread"
                                 @click="openNotification('{{ $notification->link ?? '' }}', $event, {{ $pivotId }})">
                                <div class="full-notifications__avatar">
                                    @if($notification->thumbnail)
                                        <img src="{{ asset('storage/' . $notification->thumbnail) }}"
                                             alt="Avatar"
                                             class="full-notifications__avatar-img">
                                    @else
                                        <div class="full-notifications__avatar-placeholder">
                                            <i class="fa-solid fa-bell"></i>
                                        </div>
                                    @endif
                                    <div class="full-notifications__unread-indicator"></div>
                                </div>
                                <div class="full-notifications__body">
                                    <div class="full-notifications__text">
                                        <div class="full-notifications__text-content">
                                            <strong>{{ $notification->title }}</strong> {{ $notification->content }}
                                        </div>
                                    </div>
                                    <div class="full-notifications__meta">
                                        <span class="full-notifications__time">{{ $notification->timeText }}</span>
                                    </div>
                                </div>
                                <div class="full-notifications__actions">
                                    <button wire:click.stop="markAsRead({{ $pivotId }})"
                                            class="mark-read-btn"
                                            title="Đánh dấu đã đọc">
                                        <i class="fa-solid fa-circle"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Empty state --}}
                @if($newUnreadNotifications->count() == 0 && $oldUnreadNotifications->count() == 0)
                <div class="full-notifications__empty">
                    <div class="full-notifications__empty-icon">
                        <i class="fa-solid fa-check-circle"></i>
                    </div>
                    <h3 class="full-notifications__empty-title">Tuyệt vời!</h3>
                    <p class="full-notifications__empty-text">Bạn đã đọc tất cả thông báo.</p>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>

@script
<script>
    document.addEventListener('alpine:init', () => {
        setInterval(function() {
            if (window.Livewire) {
                const element = document.querySelector('[wire\\:poll]');
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
