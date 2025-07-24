<?php

namespace App\Livewire\Client\Notifications;

use Livewire\Component;
use App\Models\Notification;
use App\Models\UserNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationIndex extends Component
{
    public $tab = 'all';
    public $notifications;
    public $unreadNotifications;
    public $unreadCount = 0;
    public $hasMore = false;
    public $page = 1;
    public $perPage = 10;
    public $isOpen = false;
    public $newNotifications = [];
    public $oldNotifications = [];
    public $newUnreadNotifications = [];
    public $oldUnreadNotifications = [];

    public function mount()
    {
        $this->loadNotifications(true);
    }

    public function hydrate()
    {
        $this->loadNotifications();
    }

    public function loadNotifications($reset = false)
    {
        $userId = Auth::id();
        if ($reset) {
            $this->page = 1;
        }

        $skip = 0;
        $take = $this->page * $this->perPage;

        $userNotifications = UserNotification::with('notification')
            ->where('user_id', $userId)
            ->latest('updated_at')
            ->skip($skip)
            ->take($take + 1)
            ->get();

        $this->hasMore = $userNotifications->count() > $take;
        $userNotifications = $userNotifications->take($take);

        $this->notifications = $userNotifications->map(function($userNotification) {
            $notification = $userNotification->notification;
            $notification->pivot = $userNotification;

            // Sử dụng created_at thay vì updated_at để tránh bị reset khi markAllAsRead
            $timeReference = $userNotification->created_at ?? $userNotification->updated_at;

            if ($timeReference) {
                $diffInHours = $timeReference->diffInHours(now());
                if ($diffInHours < 2) {
                    // Thông báo mới: hiển thị diffForHumans
                    $notification->timeText = $timeReference->diffForHumans();
                } else {
                    // Thông báo cũ: làm tròn
                    $notification->timeText = $this->formatTimeText($timeReference);
                }
            } else {
                $notification->timeText = 'Vừa xong';
            }

            return $notification;
        });

        $this->unreadNotifications = $this->notifications->filter(function($notification) {
            return !$notification->pivot->is_read;
        });

        $this->unreadCount = $this->unreadNotifications->count();

        // Phân loại thông báo mới/cũ cho tất cả (sử dụng created_at)
        $this->newNotifications = $this->notifications->filter(function($notification) {
            $createdAt = $notification->pivot->created_at ?? null;
            return $createdAt && $createdAt->diffInHours(now()) < 2;
        })->values();

        $this->oldNotifications = $this->notifications->filter(function($notification) {
            $createdAt = $notification->pivot->created_at ?? null;
            return !$createdAt || $createdAt->diffInHours(now()) >= 2;
        })->values();

        // Phân loại thông báo chưa đọc mới/cũ (sử dụng created_at)
        $this->newUnreadNotifications = $this->unreadNotifications->filter(function($notification) {
            $createdAt = $notification->pivot->created_at ?? null;
            return $createdAt && $createdAt->diffInHours(now()) < 2;
        })->values();

        $this->oldUnreadNotifications = $this->unreadNotifications->filter(function($notification) {
            $createdAt = $notification->pivot->created_at ?? null;
            return !$createdAt || $createdAt->diffInHours(now()) >= 2;
        })->values();
    }

    // Hàm formatTimeText được cải thiện với logic làm tròn chính xác
    private function formatTimeText($datetime)
    {
        $diffInMinutes = $datetime->diffInMinutes(now());
        $diffInHours = $datetime->diffInHours(now());
        $diffInDays = $datetime->diffInDays(now());
        $diffInMonths = floor($diffInDays / 30);
        $diffInYears = floor($diffInDays / 365);

        // Dưới 1 giờ
        if ($diffInMinutes < 60) {
            if ($diffInMinutes < 1) {
                return 'Vừa xong';
            } elseif ($diffInMinutes < 5) {
                return 'Vài phút trước';
            } elseif ($diffInMinutes < 15) {
                return '15 phút trước';
            } elseif ($diffInMinutes < 30) {
                return '30 phút trước';
            } else {
                return '1 giờ trước';
            }
        }

        // Dưới 1 ngày (24 giờ)
        if ($diffInHours < 24) {
            if ($diffInHours < 2) {
                return '1 giờ trước';
            } elseif ($diffInHours < 6) {
                return '6 giờ trước';
            } elseif ($diffInHours < 12) {
                return '12 giờ trước';
            } else {
                return '1 ngày trước';
            }
        }

        // Dưới 1 tuần
        if ($diffInDays < 7) {
            return (int)$diffInDays . ' ngày trước';
        }

        // Dưới 1 tháng
        if ($diffInDays < 30) {
            $weeks = floor($diffInDays / 7);
            return (int)$weeks . ' tuần trước';
        }

        // Dưới 1 năm
        if ($diffInDays < 365) {
            return (int)$diffInMonths . ' tháng trước';
        }

        // Trên 1 năm - hiển thị ngày cụ thể
        return $datetime->format('d/m/Y');
    }

    public function toggleOffcanvas()
    {
        $this->isOpen = !$this->isOpen;
    }

    public function closeOffcanvas()
    {
        $this->isOpen = false;
    }

    public function switchTab($tab)
    {
        $this->tab = $tab;
        // Refresh notifications when switching tabs to ensure timeText is updated
        $this->loadNotifications();
    }

    public function markAsRead($userNotificationId)
    {
        $userNotification = UserNotification::find($userNotificationId);
        if ($userNotification && !$userNotification->is_read) {
            // Chỉ update is_read, không thay đổi updated_at
            $userNotification->timestamps = false;
            $userNotification->update(['is_read' => 1]);
            $userNotification->timestamps = true;

            $this->loadNotifications();
            $this->dispatch('notification-read');
        }
    }

    public function markAllAsRead()
    {
        $userId = Auth::id();

        // Tắt timestamps để không thay đổi updated_at
        UserNotification::where('user_id', $userId)
            ->where('is_read', false)
            ->update([
                'is_read' => 1,
                'updated_at' => DB::raw('updated_at') // Giữ nguyên updated_at
            ]);

        $this->loadNotifications();
        $this->dispatch('all-notifications-read');
    }

    public function loadMore()
    {
        $this->page++;
        $this->loadNotifications();
    }

    public function refreshNotifications()
    {
        $this->loadNotifications(true);
    }

    public function render()
    {
        return view('livewire.client.notifications.notification-index');
    }
}
