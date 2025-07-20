<?php

namespace App\Livewire\Client\Notifications;

use Livewire\Component;
use App\Models\Notification;
use App\Models\UserNotification;
use Illuminate\Support\Facades\Auth;

class NotificationIndex extends Component
{
    public $activeTab = 'all';
    public $notifications;
    public $unreadNotifications;
    public $unreadCount = 0;

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        $userId = Auth::id();

        $userNotifications = UserNotification::with('notification')
            ->where('user_id', $userId)
            ->latest('updated_at')
            ->take(30)
            ->get();

        $this->notifications = $userNotifications->map(function($userNotification) {
            $notification = $userNotification->notification;
            $notification->pivot = $userNotification;
            return $notification;
        });

        $this->unreadNotifications = $this->notifications->filter(function($notification) {
            return !$notification->pivot->is_read;
        });

        $this->unreadCount = $this->unreadNotifications->count();
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function markAsRead($userNotificationId)
    {
        $userNotification = UserNotification::find($userNotificationId);
        if ($userNotification && !$userNotification->is_read) {
            $userNotification->update(['is_read' => true]);
            $this->loadNotifications();

            // Dispatch browser event to update UI
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

        // Dispatch browser event to update UI
        $this->dispatch('all-notifications-read');
    }

    public function refreshNotifications()
    {
        $this->loadNotifications();
    }

    public function render()
    {
        return view('livewire.client.notifications.notification-index');
    }
}
