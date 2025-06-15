<?php

namespace App\Livewire\Admin\Users;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\User;
use Livewire\Attributes\Title;
use Livewire\WithPagination;

class UserIndex extends Component
{
    use WithPagination;

    public $showDeleted = false;
    public $search = '';
    public $roleFilter = '';
    public $statusFilter = '';

    public function realtimeCheckOperation(){
        $users = User::with('ratings')
            ->where(function ($query) {
                $query->where('status', 'inactive')
                    ->orWhere('status', 'banned');
            })
            ->where('updated_at', '<=', now()->subDays(90))
            ->get();

        $users->each(function(User $user) {
            $user->ratings()->delete();
            $user->delete();
        });
    }

    public function forceDeleteUser(array $status, int $userId)
    {
        if(!$status['isConfirmed']) return;
        $user = User::onlyTrashed()->find($userId);

        // Xóa cứng người dùng
        $user->forceDelete();
        session()->flash('success', 'Xóa vĩnh viễn người dùng thành công!');
    }

    public function resetFilters()
    {
        $this->reset(['search', 'roleFilter', 'statusFilter']);
        $this->resetPage();
    }

    #[Title('Danh sách người dùng - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        $query = User::query()->when($this->search, function($query) {
            $query->withTrashed();
            $query->where(function($query){
                $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%')
                ->orWhere('phone', 'like', '%' . $this->search . '%')
                ->orWhere('address', 'like', '%' . $this->search . '%');
            });
        })->when($this->roleFilter, function($query) {
            $query->where('role', $this->roleFilter);
        });

        if($this->showDeleted) $query->onlyTrashed();
        else !$this->statusFilter ?: $query->where('status', $this->statusFilter);

        $users = $query->orderBy($this->showDeleted ? 'deleted_at' : 'created_at', 'desc')->paginate(20);

        return view('livewire.admin.users.user-index', compact('users'));
    }
}
