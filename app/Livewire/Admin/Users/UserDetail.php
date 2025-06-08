<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class UserDetail extends Component
{
    use WithPagination;
    public User $user;
    public $tabCurrent = 'overview';
    public $totalDowntimeSeconds;

    public function getTotalDowntime(){
        $lastActive = $this->user->updated_at;
        $downtime = now()->subDays(90)->diffInSeconds($lastActive, true);
        $this->totalDowntimeSeconds = $downtime;
    }

    public function realTimeUserUpdate(){
        $this->user = User::with('bookings')->find($this->user->id);
    }

    #[Title('Chi tiết người dùng - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        $totalMoviesWatchedIn30Days = $this->user->bookings()
            ->where('status', 'paid')
            ->whereDate('created_at', '>=', now()->subDays(30))
            ->with('showtime.movie')
            ->get()
            ->pluck('showtime.movie.id')
            ->unique()
            ->count();
        $checkUserAboutToDeleted = $this->user->where(function ($query) {
                $query->where('status', 'inactive')
                    ->orWhere('status', 'banned');
            })
            ->where('updated_at', '<=', now()->subDays(60))
            ->where('updated_at', '>', now()->subDays(90))
            ->exists();
        $this->getTotalDowntime();

        $bookings = $this->user->bookings()->with('showtime.movie', 'showtime.room', 'foodOrderItems')->orderBy('status', 'asc')->orderBy('created_at', 'desc')->paginate(15);
        $ratings = $this->user->ratings()->with('movie')->orderBy('created_at', 'desc')->paginate(10, ['*'], 'ratings');
        $comments = $this->user->comments()->with('movie')->orderBy('created_at', 'desc')->paginate(10, ['*'], 'comments');

        return view('livewire.admin.users.user-detail', compact('totalMoviesWatchedIn30Days', 'bookings', 'ratings', 'comments', 'checkUserAboutToDeleted'));
    }
}
