<?php

namespace App\Livewire\Admin\Notifications;

use App\Models\Notification;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class NotificationIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $userFilter = '';
    public $readFilter = '';

    public function deleteNotification(array $status, int $notificationId)
    {
        if(!$status['isConfirmed']) return;
        $notification = Notification::find($notificationId);

        // Xóa tất cả người dùng liên kết trước
        $notification->users()->detach();

        $notification->delete();
        session()->flash('success', 'Xóa thông báo thành công!');
    }

    public function resetFilters()
    {
        $this->reset(['search', 'userFilter', 'readFilter']);
        $this->resetPage();
    }

    public function hasReader(int $notificationId){
        return Notification::find($notificationId)->users()->wherePivot('is_read', true)->exists();
    }

    #[Title('Danh sách thông báo - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        $query = Notification::with('users')->when($this->search, function ($query){
            $query->where(function ($subQuery){
                $subQuery->whereLike('title', '%' . trim($this->search) . '%')
                    ->orWhereLike('content', '%' . trim($this->search) . '%');
            });
        })->withCount(['users' => function ($query){
            $query->where('user_notifications.is_read', true);
        }])->when($this->readFilter !== '', function($query){
            $fnCondition = fn($q) => $q->where('user_notifications.is_read', true);
            $this->readFilter ? $query->whereHas('users', $fnCondition) : $query->whereDoesntHave('users', $fnCondition);
        });

        $users = User::select('id', 'name', 'email')->whereIn('id', $query->get()->flatMap(fn($notification) => $notification->users->pluck('id'))->unique())->get();
        $query->when($this->userFilter, fn($query) => $query->whereHas('users', fn($q) => $q->where('users.id', $this->userFilter)));
        $notifications = $query->orderByDesc('created_at')->paginate(20);

        return view('livewire.admin.notifications.notification-index', compact('notifications', 'users'));
    }
}
