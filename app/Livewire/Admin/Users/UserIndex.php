<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;

class UserIndex extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $listUser = null;
    public $search = '';
    public $role = '';
    public $status = '';
    public $showModal = false;
    protected $listeners = [
        'closeEditModal' => 'closeModal',
        'flashSuccessED' => 'flashSuccessED',
        'flashSuccessCR' => 'flashSuccessCR',
    ];
    public function closeModal()
    {
        $this->showModal = false;
    }

    public $editUser = null;
    public function edit($id)
    {
        $this->showModal = true;
        $this->editUser = $id;
    }
    public function create()
    {
        $this->showModal = true;
        $this->editUser = null;
    }
    public function flashSuccessED()
    {
        session()->flash('success', 'Đã cập nhật');
    }
    public function flashSuccessCR()
    {
        session()->flash('success', 'Đã thêm mới');
    }
    public function delete($id)
    {
        $user = User::findOrFail($id);
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }
        $user->delete();
        session()->flash('success', 'Đã xóa');
    }
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
            ->paginate(10);
        // dd($users);
        return view('livewire.admin.users.user-index', [
            'users' => $users,
        ]);
    }
}
