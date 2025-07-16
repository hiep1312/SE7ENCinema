<?php

namespace App\Livewire\Client\User;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Booking;

class UserInformation extends Component
{
    use WithFileUploads;
    public $user;
    public $name = '';
    public $tabCurrent = 'info';
    // public $email = '';
    public $phone = '';
    public $birthday = null;
    public $gender = '';
    public $avatar = null;
    public $address = '';
    public $dateFilter = '';
    public $statusFilter = '';
    public function mount(int $user)
    {
        $this->user = User::with('bookings.showtime.movie', 'bookings.seats')->findOrFail($user);
        $this->fill($this->user->only([
            'name',
            // 'email',
            'phone',
            'birthday',
            'gender',
            'address',
        ]));
        $this->birthday = !$this->birthday ?: $this->birthday->format('Y-m-d');
    }
    protected $rules = [
        'phone' => 'nullable|numeric',
        'address' => 'nullable|string|max:500',
        'avatar' => 'nullable|image|max:20480',
        'birthday' => 'nullable|date|before:-5 years',
        'gender' => 'in:man,woman,other',
        // 'email' => 'required|in:user,staff,admin',
        'name' => 'required|min:8|max:255|regex:/^[^\d]/',
    ];
    protected $messages = [
        'phone.numeric' => 'Số điện thoại không hợp lệ.',
        'address.string' => 'Địa chỉ phải là một chuỗi ký tự.',
        'address.max' => 'Địa chỉ không được vượt quá 500 ký tự.',
        'avatar.image' => 'Ảnh đại diện phải là một tệp hình ảnh.',
        'avatar.max' => 'Ảnh đại diện không được vượt quá 20MB.',
        'birthday.date' => 'Ngày sinh phải đúng định dạng ngày tháng.',
        'birthday.before' => 'Ngày sinh phải cách hiện tại ít nhất 5 năm.',
        'gender.in' => 'Giới tính không hợp lệ. Chọn "nam", "nữ" hoặc "khác".',
        'name.required' => 'Vui lòng không để trống tên.',
        'name.max' => 'Tên quá dài.',
        'name.min' => 'Tên quá ngắn.',
        'name.regex' => 'Không được để số ở đầu.',

    ];
    public function update()
    {
        $this->validate();
        $avatarPath = $this->user->avatar;
        if ($this->avatar && $this->avatar instanceof UploadedFile):
            !Storage::disk('public')->exists($avatarPath) ?: Storage::disk('public')->delete($avatarPath);
            $avatarPath = $this->avatar->store('users', 'public');
        endif;
        $this->user->update([
            'name' => $this->name,
            'phone' => $this->phone,
            'birthday' => $this->birthday,
            'gender' => $this->gender,
            'avatar' => $avatarPath,
            'address' => $this->address,
        ]);

        return redirect()->route('userInfo', [$this->user->id])->with('success', 'Cập nhật người dùng thành công!');
    }
    #[Layout('components.layouts.client')]
    public function render()
    {
        $filteredBookings = Booking::with(['showtime.movie', 'seats'])
            ->where('user_id', $this->user->id)
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->dateFilter, function ($query) {
                $query->whereDate('created_at', $this->dateFilter);
            })
            ->get();
        return view('livewire.client.user.user-information', [
            'bookings' => $filteredBookings,
        ]);
    }
}
