<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Booking;
use App\Models\Rating;
use App\Models\Comment;
class UserDetail extends Component
{
    use WithPagination;
    public $userId;
    public $user;
    public $bookingsPerPage = 6;
    public $ratingsPerPage = 6;
    public $showAllBookings = false;
    public $showAllRatings = false;
    // protected $paginationTheme = 'bootstrap';
    public function mount($userId)
    {
        $this->userId = $userId;
        $this->loadUser();

    }
    public function loadUser()
    {
        $this->user = User::with([
            'bookings',
            'ratings',
            'comments'
        ])
            ->withCount(['bookings', 'ratings', 'comments'])
            ->findOrFail($this->userId);
    }
    public function formatJoinDate()
    {
        return $this->user->created_at->diffForHumans();
    }
    public function getBookingsProperty()
    {
        return $this->user->bookings()
            ->with('showtime.movie')
            ->latest()
            ->paginate($this->bookingsPerPage, ['*'], 'bookings');
    }
    public function getRatingsProperty()
    {
        return $this->user->ratings()
            ->with('movie')
            ->latest()
            ->paginate($this->ratingsPerPage, ['*'], 'ratings');
    }
    public function getStatusBadgeClass($status, $type = 'payment')
    {
        $classes = [
            'payment' => [
                'paid' => 'status-paid',
                'cancelled' => 'status-cancelled',
                'pending' => 'status-pending',
                'failed' => 'status-failed',
            ],
            'fulfillment' => [
                'credit_card' => 'fulfillment-fulfilled',
                'bank_transfer' => 'fulfillment-partial',
                'e_wallet' => 'fulfillment-ready',
                'cash' => 'fulfillment-delayed',
            ]
        ];

        return $classes[$type][$status] ?? '';
    }
    public function getStatusIcon($status, $type = 'payment')
    {
        $icons = [
            'payment' => [
                'paid' => '✓',
                'cancelled' => '✕',
                'pending' => '◯',
                'failed' => '✕',
            ],
        ];

        return $icons[$type][$status] ?? '';
    }
    public function viewAll($type)
    {
        switch ($type) {
            case 'bookings':
                $this->showAllBookings = true;
                $this->bookingsPerPage = $this->user->bookings_count;
                break;
            case 'ratings':
                $this->showAllRatings = true;
                $this->ratingsPerPage = $this->user->ratings_count;
                break;
        }
    }
    public function viewLess($type)
    {
        switch ($type) {
            case 'bookings':
                $this->showAllBookings = false;
                $this->bookingsPerPage = 6;
                break;
            case 'ratings':
                $this->showAllRatings = false;
                $this->ratingsPerPage = 6;
                break;
        }
    }
    #[Layout('components.layouts.admin')]

    public function render()
    {
        return view('livewire.admin.users.user-detail', [
            'bookings' => $this->bookings,
            'ratings' => $this->ratings,
        ]);
    }
}
