<?php

namespace App\Livewire\Admin\Users;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;

class UserIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search = '';
    public $role = '';
    public $status = '';
    protected $listeners = [
        'flashSuccessED' => 'flashSuccessED',
        'flashSuccessCR' => 'flashSuccessCR',
    ];
    protected $queryString = [
        'search' => ['as' => 's'],
        'status',
        'role'
    ];
    public function resetFilters()
    {
        $this->reset(['search', 'role', 'status']);
        $this->resetPage();
    }
    #[Layout('components.layouts.admin')]

    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->role, function ($query) {
                $query->where('role', $this->role);
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        // dd($users);
        return view('livewire.admin.users.user-index', [
            'users' => $users,
        ]);
    }
}
