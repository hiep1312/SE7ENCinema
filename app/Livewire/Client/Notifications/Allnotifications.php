<?php

namespace App\Livewire\Client\Notifications;

use Livewire\Component;
use App\Models\UserNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Allnotifications extends Component
{
    public $tab = 'all';
    public $notifications;
    public $unreadNotifications;
    public $unreadCount = 0;
    public $hasMore = false;
    public $page = 1;
    public $perPage = 10;
    public $newNotifications = [];
    public $oldNotifications = [];
    public $newUnreadNotifications = [];
    public $oldUnreadNotifications = [];

    public function mount()
    {
        $this->loadNotifications(true);
    }

    public function loadNotifications($reset = false)
    {
        $userId = Auth::id();
        if ($reset) {
            $this->page = 1;
        }
        $userNotifications = UserNotification::with('notification')
            ->where('user_id', $userId)
            ->latest('created_at')
            ->get(); // Bỏ take, lấy tất cả
        // Lọc thông báo chỉ lấy trong vòng 1 tháng
        $userNotifications = $userNotifications->filter(function($userNotification) {
            $timeReference = $userNotification->created_at ?? $userNotification->updated_at;
            return $timeReference && $timeReference->diffInDays(now()) < 30;
        });
        $this->notifications = $userNotifications->map(function($userNotification) {
            $notification = $userNotification->notification;
            $notification->pivot = $userNotification;
            $timeReference = $userNotification->created_at ?? $userNotification->updated_at;
            if ($timeReference) {
                $diffInHours = $timeReference->diffInHours(now());
                if ($diffInHours < 2) {
                    $notification->timeText = $timeReference->diffForHumans();
                } else {
                    $notification->timeText = $this->formatTimeText($timeReference);
                }
            } else {
                $notification->timeText = 'Vừa xong';
            }
            return $notification;
        })->values();
        $this->unreadNotifications = $this->notifications->filter(function($notification) {
            return !$notification->pivot->is_read;
        });
        $this->unreadCount = $this->unreadNotifications->count();
        // Phân loại thông báo mới/cũ cho tất cả
        $this->newNotifications = $this->notifications->filter(function($notification) {
            $createdAt = $notification->pivot->created_at ?? null;
            return $createdAt && $createdAt->diffInHours(now()) < 2;
        })->values();
        $this->oldNotifications = $this->notifications->filter(function($notification) {
            $createdAt = $notification->pivot->created_at ?? null;
            return !$createdAt || $createdAt->diffInHours(now()) >= 2;
        })->values();
        // Phân loại thông báo chưa đọc mới/cũ
        $this->newUnreadNotifications = $this->unreadNotifications->filter(function($notification) {
            $createdAt = $notification->pivot->created_at ?? null;
            return $createdAt && $createdAt->diffInHours(now()) < 2;
        })->values();
        $this->oldUnreadNotifications = $this->unreadNotifications->filter(function($notification) {
            $createdAt = $notification->pivot->created_at ?? null;
            return !$createdAt || $createdAt->diffInHours(now()) >= 2;
        })->values();
    }

    public function switchTab($tab)
    {
        $this->tab = $tab;
        $this->loadNotifications();
    }

    public function markAsRead($userNotificationId)
    {
        $userNotification = UserNotification::find($userNotificationId);
        if ($userNotification && !$userNotification->is_read) {
            $userNotification->update(['is_read' => true]);
            $this->loadNotifications();
            $this->dispatch('notification-read');
        }
    }

    public function markAllAsRead()
    {
        $userId = Auth::id();
        UserNotification::where('user_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);
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

    private function formatTimeText($datetime)
    {
        $diffInMinutes = $datetime->diffInMinutes(now());
        $diffInHours = $datetime->diffInHours(now());
        $diffInDays = $datetime->diffInDays(now());
        $diffInMonths = floor($diffInDays / 30);
        $diffInYears = floor($diffInDays / 365);
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
        if ($diffInDays < 7) {
            return (int)$diffInDays . ' ngày trước';
        }
        if ($diffInDays < 30) {
            $weeks = floor($diffInDays / 7);
            return (int)$weeks . ' tuần trước';
        }
        if ($diffInDays < 365) {
            return (int)$diffInMonths . ' tháng trước';
        }
        return $datetime->format('d/m/Y');
    }

    public function render()
    {
        return view('livewire.client.notifications.allnotifications');
    }
}
