<div class="scRender container
">
<div class="tbt "
   style="clear: both;
   " >
 </div>
    <div class="all-notifications-wrapper" style="
     background: linear-gradient(180deg, #2c2c54 0%, #1a1a2e 100%); border-radius: 18px; box-shadow: 0 8px 32px rgba(0,0,0,0.18); padding: 0 0 24px 0; margin: 20px auto 20px auto
    ">

    <div class="all-notifications-header d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #ff4757 0%, #c44569 100%); border-radius: 18px 18px 0 0; padding: 24px 32px 18px 32px; color: #fff; position: relative;
    ">
        <h3 style="font-weight: 700; font-size: 1.5rem; margin: 0;">Thông báo</h3>
        <div x-data="{ open: false }" class="position-relative" style="margin-left: auto;">
            <button @click="open = !open" class="btn btn-link p-0" style="color: #fff; font-size: 1.5rem;">
                <i class="fa-solid fa-ellipsis"></i>
            </button>
            <div x-show="open" @click.away="open = false" style="position: absolute; right: 0; top: 36px; min-width: 220px; background: #fff; color: #222; border-radius: 12px; box-shadow: 0 4px 24px rgba(0,0,0,0.15); z-index: 100; overflow: hidden;">
                <button wire:click="markAllAsRead" class="dropdown-item d-flex align-items-center" style="padding: 12px 18px; width: 100%; background: none; border: none; text-align: left; font-size: 15px;">
                    <i class="fa-solid fa-check-double me-2" style="color: #ff4757;"></i> Đánh dấu tất cả là đã đọc
                </button>
                <button class="dropdown-item d-flex align-items-center" style="padding: 12px 18px; width: 100%; background: none; border: none; text-align: left; font-size: 15px;">
                    <i class="fa-solid fa-gear me-2" style="color: #888;"></i> Cài đặt thông báo
                </button>
            </div>
        </div>
    </div>
    <div class="notification-tabs d-flex align-items-center gap-3" style="background: linear-gradient(180deg, #40407a 0%, #2c2c54 100%); border-bottom: 1px solid rgba(255, 71, 87, 0.2); padding: 12px 32px 0 32px;">
        <div x-data="{ tab: 'all' }" class="w-100">
            <div class="d-flex gap-3 mb-2">
                <button class="btn" :class="{ 'active-tab': tab === 'all' }" @click="tab = 'all'" style="border-radius: 24px; padding: 8px 32px; font-weight: 600; background: none; color: #fff; border: none; font-size: 16px;">
                    Tất cả
                </button>
                <button class="btn" :class="{ 'active-tab': tab === 'unread' }" @click="tab = 'unread'" style="border-radius: 24px; padding: 8px 32px; font-weight: 600; background: none; color: #fff; border: none; font-size: 16px;">
                    Chưa đọc
                </button>
            </div>
            <div class="all-notifications-content notification-content" style="padding: 0 0 0 0; min-height: 320px;">
                <div x-show="tab === 'all'">
                    @forelse($notifications as $notification)
                        <div class="notification-item {{ (isset($notification->pivot) && !$notification->pivot->is_read) ? 'unread' : 'read' }}" style="border-radius: 0;">
                            <div class="notification-avatar">
                                @if($notification->thumbnail)
                                    <img src="{{ asset('storage/' . $notification->thumbnail) }}" alt="Avatar" class="rounded-circle">
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
                    @empty
                        <div class="notification-empty">
                            <i class="mdi mdi-bell-off-outline"></i>
                            <h6>Không có thông báo</h6>
                            <p>Bạn chưa có thông báo nào.</p>
                        </div>
                    @endforelse
                </div>
                <div x-show="tab === 'unread'">
                    @php $unread = $notifications->filter(fn($n) => isset($n->pivot) && !$n->pivot->is_read); @endphp
                    @forelse($unread as $notification)
                        <div class="notification-item unread" style="border-radius: 0;">
                            <div class="notification-avatar">
                                @if($notification->thumbnail)
                                    <img src="{{ asset('storage/' . $notification->thumbnail) }}" alt="Avatar" class="rounded-circle">
                                @else
                                    <div class="system-avatar bg-info text-white rounded-circle">
                                        <i class="mdi mdi-bell-outline"></i>
                                    </div>
                                @endif
                                <div class="notification-badge"></div>
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
                    @empty
                        <div class="notification-empty">
                            <i class="mdi mdi-bell-off-outline"></i>
                            <h6>Không có thông báo chưa đọc</h6>
                            <p>Bạn đã đọc tất cả thông báo.</p>
                        </div>
                    @endforelse
                </div>
            </div>
            @if($hasMore)
                <div class="text-center my-3">
                    <button wire:click="loadMore" class="btn btn-outline-danger" style="border-radius: 20px; padding: 12px 32px; font-weight: 600;">Hiển thị thêm thông báo trước đó</button>
                </div>
            @endif
        </div>
    </div>
    <style>
        .active-tab {
            background: linear-gradient(135deg, #ff4757 0%, #c44569 100%) !important;
            color: #fff !important;
            box-shadow: 0 4px 15px rgba(255, 71, 87, 0.25);
        }
    </style>
</div>
</div>
