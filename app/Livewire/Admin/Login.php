<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public string $email = '';
    public string $password = '';
    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|min:6',
    ];

    public function login(): void
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            session()->regenerate();
            // Lưu vai trò vào session
            session(['role' => Auth::user()->role]);
            $this->redirect('/quan-ly/suat-chieu', navigate: true); // Sử dụng redirect của Livewire
        }

        $this->addError('email', 'Thông tin đăng nhập không đúng.');
    }

    public function render()
    {
        return view('livewire.admin.login');
    }
}
