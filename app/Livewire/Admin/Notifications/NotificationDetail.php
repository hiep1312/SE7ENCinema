<?php

namespace App\Livewire\Admin\Notifications;

use App\Models\Notification;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class NotificationDetail extends Component
{
    use WithPagination;

    public $notificationId;
    public $notification;
    public $tabCurrent = 'overview';

    public function mount(int $notification){
        $this->notificationId = $notification;
    }

    public function hasReader(int $notificationId){
        return Notification::find($notificationId)->users()->wherePivot('is_read', true)->exists();
    }

    public function realTimeUpdate(){
        $this->notification = Notification::with('users')->withCount(['users' => fn($query) => $query->where('user_notifications.is_read', true)])->findOrFail($this->notificationId);
    }

    #[Title('Chi tiết thông báo - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        $this->realTimeUpdate();
        $users = $this->notification->users()->orderByDesc('user_notifications.updated_at')->paginate(15);
        return view('livewire.admin.notifications.notification-detail', compact('users'));
    }
}
