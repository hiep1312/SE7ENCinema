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

        // Cache current time for better performance
        $now = now();

        $this->notifications = $userNotifications->map(function ($userNotification) use ($now) {
            $notification = $userNotification->notification;

            // Tạo object mới thay vì thay đổi trực tiếp
            $notificationData = (object) [
                'id' => $notification->id,
                'title' => $notification->title,
                'content' => $notification->content,
                'link' => $notification->link,
                'thumbnail' => $notification->thumbnail,
                'created_at' => $notification->created_at,
                'updated_at' => $notification->updated_at,
                'pivot' => (object) [
                    'id' => $userNotification->id,
                    'user_id' => $userNotification->user_id,
                    'notification_id' => $userNotification->notification_id,
                    'is_read' => (int)$userNotification->is_read, // Đảm bảo là number
                    'created_at' => $userNotification->created_at,
                    'updated_at' => $userNotification->updated_at
                ],
                'timeReference' => $userNotification->created_at ?? $userNotification->updated_at,
                'timeText' => $this->getTimeText($userNotification->created_at ?? $userNotification->updated_at)
            ];

            return $notificationData;
        });

        $this->unreadNotifications = $this->notifications->filter(function ($notification) {
            return $notification->pivot->is_read === 0; // So sánh với number 0
        });

        $this->unreadCount = $this->unreadNotifications->count();

        // Phân loại thông báo mới/cũ cho tất cả (sử dụng created_at)
        $this->newNotifications = $this->notifications->filter(function ($notification) use ($now) {
            $createdAt = $notification->pivot->created_at ?? null;
            return $createdAt && $createdAt->diffInHours($now) < 2;
        })->values();

        $this->oldNotifications = $this->notifications->filter(function ($notification) use ($now) {
            $createdAt = $notification->pivot->created_at ?? null;
            return !$createdAt || $createdAt->diffInHours($now) >= 2;
        })->values();

        // Phân loại thông báo chưa đọc mới/cũ (sử dụng created_at)
        $this->newUnreadNotifications = $this->unreadNotifications->filter(function ($notification) use ($now) {
            $createdAt = $notification->pivot->created_at ?? null;
            return $createdAt && $createdAt->diffInHours($now) < 2;
        })->values();

        $this->oldUnreadNotifications = $this->unreadNotifications->filter(function ($notification) use ($now) {
            $createdAt = $notification->pivot->created_at ?? null;
            return !$createdAt || $createdAt->diffInHours($now) >= 2;
        })->values();
    }

    // Hàm tính toán thời gian - tối ưu hơn
    public function getTimeText($timeReference)
    {
        if (!$timeReference) {
            return 'Vừa xong';
        }

        $now = now();
        $diffInMinutes = $timeReference->diffInMinutes($now);
        $diffInHours = $timeReference->diffInHours($now);
        $diffInDays = $timeReference->diffInDays($now);

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
            $months = floor($diffInDays / 30);
            return (int)$months . ' tháng trước';
        }

        // Trên 1 năm - hiển thị ngày cụ thể
        return $timeReference->format('d/m/Y');
    }

    // Bỏ các computed properties không cần thiết
    public function getNewNotificationsProperty()
    {
        return $this->newNotifications;
    }

    public function getOldNotificationsProperty()
    {
        return $this->oldNotifications;
    }

    public function getNewUnreadNotificationsProperty()
    {
        return $this->newUnreadNotifications;
    }

    public function getOldUnreadNotificationsProperty()
    {
        return $this->oldUnreadNotifications;
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
        // Không load lại notifications khi switch tab, chỉ thay đổi tab
    }

    public function handleNotificationClick($link, $userNotificationId)
    {
        // Kiểm tra userNotificationId hợp lệ
        if (!$userNotificationId || $userNotificationId <= 0) {
            $this->dispatch('_scAlert', [
                'title' => 'Lỗi',
                'html' => 'Không thể xác định thông báo!',
                'icon' => 'error',
                'timer' => 2000
            ], '');
            return;
        }

        // Luôn đánh dấu đã đọc trước, bất kể link có hợp lệ hay không
        $this->markAsRead($userNotificationId);

        // Kiểm tra và xử lý link
        if (empty($link) || $link === '#' || $link === '' || $link === null) {
            $this->dispatch('_scAlert', [
                'title' => 'Thông báo',
                'html' => 'Đường dẫn không hợp lệ hoặc đã bị xóa!',
                'icon' => 'warning',
                'timer' => 3000
            ], '');
            return;
        }

        // Kiểm tra format link - cho phép cả URL tuyệt đối và relative path
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

        // Nếu là relative path, thêm domain
        if ($isValidRelativePath) {
            $link = url($link);
        }

        // Chuyển hướng trực tiếp
        return redirect()->away($link);
    }

    public function markAsRead($userNotificationId)
    {
        $userId = Auth::id();

        // Kiểm tra xem userNotification có thuộc về user hiện tại không
        $userNotification = UserNotification::where('id', $userNotificationId)
            ->where('user_id', $userId)
            ->first();

        if ($userNotification) {
            // Luôn luôn update is_read thành 1 khi click vào thông báo
            $userNotification->timestamps = false;
            $userNotification->update(['is_read' => 1]); // Set thành number 1
            $userNotification->timestamps = true;

            // Cập nhật trạng thái local mà không load lại toàn bộ
            $this->updateNotificationStatus($userNotificationId);
        }
    }

    public function markAllAsRead()
    {
        $userId = Auth::id();

        // Tắt timestamps để không thay đổi updated_at
        UserNotification::where('user_id', $userId)
            ->where('is_read', 0) // So sánh với number 0
            ->update([
                'is_read' => 1, // Set thành number 1
                'updated_at' => DB::raw('updated_at') // Giữ nguyên updated_at
            ]);

        // Cập nhật trạng thái local mà không load lại toàn bộ
        $this->updateAllNotificationsStatus();
    }

    // Cập nhật trạng thái local cho một notification
    private function updateNotificationStatus($userNotificationId)
    {
        foreach ($this->notifications as $notification) {
            if ($notification->pivot && $notification->pivot->id == $userNotificationId) {
                $notification->pivot->is_read = 1; // Đảm bảo là number 1
                break;
            }
        }

        // Cập nhật lại các collections
        $this->unreadNotifications = $this->notifications->filter(function ($notification) {
            return $notification->pivot && $notification->pivot->is_read === 0; // So sánh với number 0
        });

        $this->unreadCount = $this->unreadNotifications->count();

        // Cập nhật lại các collections phân loại
        $now = now();
        $this->newUnreadNotifications = $this->unreadNotifications->filter(function ($notification) use ($now) {
            $createdAt = $notification->pivot ? $notification->pivot->created_at : null;
            return $createdAt && $createdAt->diffInHours($now) < 2;
        })->values();

        $this->oldUnreadNotifications = $this->unreadNotifications->filter(function ($notification) use ($now) {
            $createdAt = $notification->pivot ? $notification->pivot->created_at : null;
            return !$createdAt || $createdAt->diffInHours($now) >= 2;
        })->values();
    }

    // Cập nhật trạng thái local cho tất cả notifications
    private function updateAllNotificationsStatus()
    {
        foreach ($this->notifications as $notification) {
            $notification->pivot->is_read = 1; // Đảm bảo là number 1
        }

        $this->unreadNotifications = collect();
        $this->unreadCount = 0;
        $this->newUnreadNotifications = collect();
        $this->oldUnreadNotifications = collect();
    }

    public function loadMore()
    {
        // Khi bấm loadMore, sẽ lấy toàn bộ thông báo còn lại (không phân trang nữa)
        $this->perPage = 10000; // Đặt giá trị lớn để lấy hết
        $this->page = 1;
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
