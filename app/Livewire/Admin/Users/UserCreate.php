<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

class UserCreate extends Component
{
    use WithFileUploads;

    public $email = '';
    public $password = '';
    public $confirm_password = '';
    public $name = '';
    public $phone = null;
    public $address = null;
    public $avatar = null;
    public $birthday = null;
    public $gender = 'other';
    public $role = 'user';
    public $status = 'active';

    protected $rules = [
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8|max:255',
        'confirm_password' => 'required|same:password',
        'name' => 'required|string|max:255',
        'phone' => 'nullable|numeric',
        'address' => 'nullable|string|max:500',
        'avatar' => 'nullable|image|max:20480',
        'birthday' => 'nullable|date|before:-5 years',
        'gender' => 'required|in:man,woman,other',
        'role' => 'required|in:user,staff,admin',
        'status' => 'required|in:active,inactive,banned',
    ];

    protected $messages = [
        'email.required' => 'Vui lòng nhập địa chỉ email.',
        'email.email' => 'Địa chỉ email không đúng định dạng.',
        'email.unique' => 'Email này đã được sử dụng.',
        'password.required' => 'Vui lòng nhập mật khẩu.',
        'password.string' => 'Mật khẩu phải là chuỗi ký tự.',
        'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
        'password.max' => 'Mật khẩu không được vượt quá 255 ký tự.',
        'confirm_password.required' => 'Vui lòng xác nhận mật khẩu.',
        'confirm_password.same' => 'Mật khẩu xác nhận không khớp.',
        'name.required' => 'Vui lòng nhập họ và tên.',
        'name.string' => 'Họ và tên phải là chuỗi ký tự.',
        'name.max' => 'Họ và tên không được vượt quá 255 ký tự.',
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

    public function createUser()
    {
        $this->validate();

        $avatarPath = '';
        !$this->avatar ?: ($avatarPath = $this->avatar->store('users', 'public'));

        User::create([
            'email' => $this->email,
            'password' => $this->password,
            'name' => $this->name,
            'phone' => $this->phone,
            'address' => $this->address,
            'avatar' => $avatarPath,
            'birthday' => $this->birthday,
            'gender' => $this->gender,
            'role' => $this->role,
            'status' => $this->status,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Thêm mới người dùng thành công!');
    }

    #[Title('Thêm người dùng - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.admin.users.user-create');
    }
}
