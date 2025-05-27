<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserForm extends Component
{
    public $password;
    public $userId;
    public $name, $email, $phone, $address, $birthday, $gender, $role, $status, $avatar;
    public function mount($userId)
    {
        $this->userId = $userId;
        if ($this->userId) {
            $user = User::findOrFail($this->userId);
            $this->name = $user->name;
            $this->email = $user->email;
            $this->phone = $user->phone;
            $this->address = $user->address;
            $this->avatar = $user->avatar;
            $this->birthday = $user->birthday;
            $this->gender = $user->gender;
            $this->role = $user->role;
            $this->status = $user->status;
        } else {
            $this->role = 'user';
            $this->status = 'active';
            $this->gender = 'man';
        }
    }
    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            // 'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'birthday' => 'nullable|date',
            'gender' => 'required|in:man,woman,other',
            'role' => 'required|in:user,staff,admin',
        ];

        if ($this->userId) {
            $rules['password'] = 'nullable|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/';
            $rules['status'] = 'nullable';
            $rules['email'] = 'required|email';

        } else {
            $rules['password'] = 'required|min:8|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/';
            $rules['status'] = 'in:active,inactive,banned';
            $rules['email'] = 'required|email|unique:users,email';
        }


        return $rules;
    }
    public function update()
    {
        $this->validate();
        if ($this->userId) {
            $user = User::findOrFail($this->userId);
            $user->update([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
                'birthday' => $this->birthday,
                'gender' => $this->gender,
                'role' => $this->role,
                'status' => $this->status,
                'password' => $this->password ? Hash::make($this->password) : $user->password,
            ]);

        } else {
            User::create([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
                'birthday' => $this->birthday,
                'gender' => $this->gender,
                'role' => $this->role,
                'status' => $this->status,
                'password' => Hash::make($this->password),
            ]);
        }
        if ($this->userId) {
            $this->dispatch('flashSuccessED');
        } else {
            $this->dispatch('flashSuccessCR');
        }
        $this->dispatch('closeEditModal');

    }
    public function render()
    {
        return view('livewire.admin.users.user-form');
    }
}
