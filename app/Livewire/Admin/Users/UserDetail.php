<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;

class UserDetail extends Component
{
    public $userId;
    public $user;
    public function mount($userId)
    {
        $this->userId = $userId;

    }
    public function loadUser()
    {
        $this->user = User::findOrFail($this->userId);
    }
    public function render()
    {

        return view('livewire.admin.users.user-detail');
    }
}
