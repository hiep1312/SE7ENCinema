<?php

namespace App\Livewire\Client\Notifications;

use Livewire\Component;
use App\Models\UserNotification;
use Illuminate\Support\Facades\Auth;

class Allnotifications extends Component
{
    public $notifications = [];
    public $page = 1;
    public $perPage = 10;
    public $hasMore = false;

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
            ->skip(0)
            ->take($this->page * $this->perPage + 1)
            ->get();

        $this->hasMore = $userNotifications->count() > $this->page * $this->perPage;
        $userNotifications = $userNotifications->take($this->page * $this->perPage);

        $this->notifications = $userNotifications->map(function($userNotification) {
            $notification = $userNotification->notification;
            $notification->pivot = $userNotification;
            return $notification;
        });
    }

    public function loadMore()
    {
        $this->page++;
        $this->loadNotifications();
    }

    public function render()
    {
        return view('livewire.client.notifications.allnotifications');
    }
}
