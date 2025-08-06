<?php

namespace App\Livewire\Admin\NotificationAdmins;

use Livewire\Component;
use App\Models\UserNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationAdm extends Component
{
    public $notifications = [];
    public $unreadCount = 0;
    public $isOpen = false;
    public $showAll = false;
    public $perPage = 10;

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        $userId = Auth::id();

        $limit = $this->showAll ? 50 : $this->perPage; // Hiển thị 50 thông báo khi xem tất cả

        $userNotifications = UserNotification::with('notification')
            ->where('user_id', $userId)
            ->latest('created_at')
            ->take($limit)
            ->get();

        $this->notifications = $userNotifications->map(function ($userNotification) {
            $notification = $userNotification->notification;

            $notificationData = new \stdClass();
            $notificationData->id = $notification->id;
            $notificationData->title = $notification->title;
            $notificationData->content = $notification->content;
            $notificationData->link = $notification->link;
            $notificationData->thumbnail = $notification->thumbnail;
            $notificationData->created_at = $notification->created_at;

            $pivotData = new \stdClass();
            $pivotData->id = $userNotification->id;
            $pivotData->user_id = $userNotification->user_id;
            $pivotData->notification_id = $userNotification->notification_id;
            $pivotData->is_read = (int)$userNotification->is_read;
            $pivotData->created_at = $userNotification->created_at;
            $pivotData->updated_at = $userNotification->updated_at;

            $notificationData->pivot = $pivotData;
            $notificationData->timeText = $this->getTimeText($userNotification->created_at ?? $userNotification->updated_at);

            return $notificationData;
        })->values();

        $this->unreadCount = $this->notifications->filter(function ($notification) {
            return $notification->pivot->is_read === 0;
        })->count();
    }

    public function getTimeText($timeReference)
    {
        if (!$timeReference) {
            return 'Vừa xong';
        }

        $now = now();
        $diffInMinutes = $timeReference->diffInMinutes($now);
        $diffInHours = $timeReference->diffInHours($now);
        $diffInDays = $timeReference->diffInDays($now);

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
            $months = floor($diffInDays / 30);
            return (int)$months . ' tháng trước';
        }

        return $timeReference->format('d/m/Y');
    }

    public function toggleDropdown()
    {
        $this->isOpen = !$this->isOpen;
        if ($this->isOpen) {
            $this->loadNotifications();
        }
    }

    public function showAllNotifications()
    {
        $this->showAll = true;
        $this->loadNotifications();
    }

    public function showRecentNotifications()
    {
        $this->showAll = false;
        $this->loadNotifications();
    }

    public function handleNotificationClick($link, $userNotificationId)
    {
        if (!$userNotificationId || $userNotificationId <= 0) {
            $this->dispatch('_scAlert', [
                'title' => 'Lỗi',
                'html' => 'Không thể xác định thông báo!',
                'icon' => 'error',
                'timer' => 2000
            ], '');
            return;
        }

        $this->markAsRead($userNotificationId);

        if (empty($link) || $link === '#' || $link === '' || $link === null) {
            $this->dispatch('_scAlert', [
                'title' => 'Thông báo',
                'html' => 'Đường dẫn không hợp lệ hoặc đã bị xóa!',
                'icon' => 'warning',
                'timer' => 3000
            ], '');
            return;
        }

        $isValidUrl = filter_var($link, FILTER_VALIDATE_URL);
        $isValidRelativePath = str_starts_with($link, '/');

        if (!$isValidUrl && !$isValidRelativePath) {
            $this->dispatch('_scAlert', [
                'title' => 'Thông báo',
                'html' => 'Đường dẫn không hợp lệ hoặc đã bị xóa!',
                'icon' => 'warning',
                'timer' => 3000
            ], '');
            return;
        }

        if ($isValidRelativePath) {
            $link = url($link);
        }

        return redirect()->away($link);
    }

    public function markAsRead($userNotificationId)
    {
        $userId = Auth::id();

        $userNotification = UserNotification::where('id', $userNotificationId)
            ->where('user_id', $userId)
            ->first();

        if ($userNotification) {
            $userNotification->timestamps = false;
            $userNotification->update(['is_read' => 1]);
            $userNotification->timestamps = true;

            $this->updateNotificationStatus($userNotificationId);
        }
    }

    public function markAllAsRead()
    {
        $userId = Auth::id();

        UserNotification::where('user_id', $userId)
            ->where('is_read', 0)
            ->update([
                'is_read' => 1,
                'updated_at' => DB::raw('updated_at')
            ]);

        $this->updateAllNotificationsStatus();
    }

    private function updateNotificationStatus($userNotificationId)
    {
        foreach ($this->notifications as $notification) {
            if ($notification->pivot && $notification->pivot->id == $userNotificationId) {
                $notification->pivot->is_read = 1;
                break;
            }
        }

        $this->unreadCount = $this->notifications->filter(function ($notification) {
            return $notification->pivot && $notification->pivot->is_read === 0;
        })->count();
    }

    private function updateAllNotificationsStatus()
    {
        foreach ($this->notifications as $notification) {
            $notification->pivot->is_read = 1;
        }

        $this->unreadCount = 0;
    }

    public function refreshNotifications()
    {
        $this->loadNotifications();
    }

    public function render()
    {
        return view('livewire.admin.notification-admins.notification-adm');
    }
}
