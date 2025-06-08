<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

class UserEdit extends Component
{
    use WithFileUploads;

    public $user;
    /* public $email = '';
    public $name = ''; */
    public $phone = null;
    public $address = null;
    public $avatar = null;
    public $birthday = null;
    public $gender = 'other';
    public $role = 'user';
    public $status = 'active';

    protected $rules = [
        'phone' => 'nullable|numeric',
        'address' => 'nullable|string|max:500',
        'avatar' => 'nullable|image|max:20480',
        'birthday' => 'nullable|date|before:-5 years',
        'gender' => 'required|in:man,woman,other',
        'role' => 'required|in:user,staff,admin',
        'status' => 'required|in:active,inactive,banned',
    ];

    protected $messages = [
        'phone.numeric' => 'Số điện thoại không hợp lệ.',
        'address.string' => 'Địa chỉ phải là một chuỗi ký tự.',
        'address.max' => 'Địa chỉ không được vượt quá 500 ký tự.',
        'avatar.image' => 'Ảnh đại diện phải là một tệp hình ảnh.',
        'avatar.max' => 'Ảnh đại diện không được vượt quá 20MB.',
        'birthday.date' => 'Ngày sinh phải đúng định dạng ngày tháng.',
        'birthday.before' => 'Ngày sinh phải cách hiện tại ít nhất 5 năm.',
        'gender.required' => 'Vui lòng chọn giới tính.',
        'gender.in' => 'Giới tính không hợp lệ. Chọn "nam", "nữ" hoặc "khác".',
        'role.required' => 'Vui lòng chọn vai trò.',
        'role.in' => 'Vai trò không hợp lệ. Chọn "người dùng", "nhân viên" hoặc "quản trị viên".',
        'status.required' => 'Vui lòng chọn trạng thái tài khoản.',
        'status.in' => 'Trạng thái không hợp lệ. Chọn "đang hoạt động", "không hoạt động" hoặc "bị cấm".',
    ];

    public function mount(User $user)
    {
        $this->user = $user;
        $this->fill($user->only('email', 'name', 'phone', 'address', 'birthday', 'gender', 'role', 'status'));
    }

    public function updateUser()
    {
        $this->validate();

        $avatarPath = $this->user->avatar;
        if($this->avatar && $this->avatar instanceof UploadedFile):
            !Storage::disk('public')->exists($avatarPath) ?: Storage::disk('public')->delete($avatarPath);
            $avatarPath = $this->avatar->store('users', 'public');
        endif;

        $this->user->update([
            'phone' => $this->phone,
            'address' => $this->address,
            'avatar' => $avatarPath,
            'birthday' => $this->birthday,
            'gender' => $this->gender,
            'role' => $this->role,
            'status' => $this->status,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Cập nhật người dùng thành công!');
    }

    #[Title('Cập nhật người dùng - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.admin.users.user-edit');
    }
}
