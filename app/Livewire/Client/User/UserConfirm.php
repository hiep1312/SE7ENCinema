<?php

namespace App\Livewire\Client\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class UserConfirm extends Component
{
    public $password;
    public function confirm()
    {
        if (!Hash::check($this->password, Auth::user()->password)) {
            session()->flash('error', 'Mật khẩu không chính xác.');
            return;
        }
        return redirect()->route('userInfo', ['user' => Auth::id()])
                         ->with('isConfirmed', true);
    }
    public function render()
    {
        $user = Auth::user();
        return view('livewire.client.user.user-confirm', [
            'user' => $user,
        ]);
    }
}
