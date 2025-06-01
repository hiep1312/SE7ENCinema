<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class UserEdit extends Component
{
    use WithFileUploads;
    public $userId;
    public $name, $email, $phone, $address, $birthday, $gender, $role, $status, $avatar;
    public function mount($userId)
    {
        $this->userId = $userId;
        // Tìm kiếm người dùng theo id
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
    }
    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'phone' => 'nullable|string|max:20',
        'address' => 'nullable|string|max:500',
        'birthday' => 'nullable|date',
        'gender' => 'nullable|in:man,woman,other',
        'role' => 'required|in:admin,user,staff',
        'status' => 'required|in:active,inactive,banned',
        'avatar' => 'nullable|image',
    ];

    protected $messages = [
        'name.required' => 'Tên là bắt buộc.',
        'email.required' => 'Email là bắt buộc.',
        'email.email' => 'Email không đúng định dạng.',
        'email.unique' => 'Email đã tồn tại.',
        'gender.required' => 'Giới tính là bắt buộc.',
        'role.required' => 'Vai trò là bắt buộc.',
        'status.required' => 'Trạng thái là bắt buộc.',
        'avatar.image' => 'Avatar phải là file ảnh.',
    ];
    public function updatedAvatar()
    {
        $this->validateOnly('avatar');
    }
    public function save()
    {
        // dd($this->avatar);
        $this->validate([
            'email' => [
                'required',
                'email',
                'unique:users,email,' . $this->userId,
            ]
        ]);
        $avatarPath = $this->avatar;
        // Xóa ảnh cũ nếu có
        if ($this->avatar && Storage::disk('public')->exists($this->avatar)) {
            Storage::disk('public')->delete($this->avatar);
        }
        // tạo tên file mới
        $fileName = 'avatars/' . $this->userId . '_' . time() . '.' . $this->avatar->getClientOriginalExtension();

        $manager = new ImageManager(new Driver());
        $image = $manager->read($this->avatar->getRealPath());

        $user = User::findOrFail($this->userId);
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'avatar' => $this->avatar,
            'address' => $this->address,
            'birthday' => $this->birthday,
            'gender' => $this->gender,
            'role' => $this->role,
            'status' => $this->status,
        ]);
        session()->flash('success', 'Đã cập nhật');
        return redirect()->route('admin.users.index');
    }
    #[Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.admin.users.user-edit');
    }
}
