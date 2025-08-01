<?php

namespace App\Livewire\Client\User;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\BookingSeat;
use Livewire\WithPagination;

class UserInformation extends Component
{
    use WithFileUploads, WithPagination;
    public $user;
    public $bookingInfo;
    public $modal = false;
    public $name = '';
    public $tabCurrent = 'info';
    public $email = '';
    public $phone = '';
    public $birthday = null;
    public $gender = '';
    public $avatar = null;
    public $address = '';
    public $dateFilter = '';
    public $statusFilter = '';
    public $nameFilter = '';
    public $currentPassword = '';
    public $newPassword = '';
    public $confirmPassword = '';
    protected $rules = [
        'phone' => 'nullable|numeric',
        'address' => 'nullable|string|max:500',
        'avatar' => 'nullable|image|max:20480',
        'birthday' => 'nullable|before:-5 years',
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
        'birthday.before' => 'Ngày sinh phải cách hiện tại ít nhất 5 năm.',
        'gender.in' => 'Giới tính không hợp lệ. Chọn "nam", "nữ" hoặc "khác".',
        'name.required' => 'Vui lòng không để trống tên.',
        'name.max' => 'Tên quá dài.',
        'name.min' => 'Tên quá ngắn.',
        'name.regex' => 'Không được để số ở đầu.',

    ];
    public function mount()
    {
        $this->user = User::with('bookings.showtime.movie', 'bookings.seats')->findOrFail(Auth::id());
        if (session('isConfirmed')) {
            $this->delete();
        }
        $this->fill($this->user->only([
            'name',
            'email',
            'phone',
            'birthday',
            'gender',
            'address',
        ]));
        $this->birthday = !$this->birthday ?: $this->birthday->format('Y-m-d');
    }

    public function delete()
    {
        if ($this->user) {
            $this->user->delete();
            Auth::logout();
            return redirect('/login');
        }
    }
    public function update()
    {
        $this->validate();
        $avatarPath = $this->user->avatar;
        if ($this->avatar && $this->avatar instanceof UploadedFile):
            if ($avatarPath && Storage::disk('public')->exists($avatarPath)) {
                Storage::disk('public')->delete($avatarPath);
            }
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
    public function changePassword()
    {
        $this->validate(
            [
                'newPassword' => 'required|string|min:8|max:255',
                'confirmPassword' => 'required|same:newPassword',
            ],
            [
                'newPassword.required' => 'Vui lòng nhập mật khẩu.',
                'newPassword.string' => 'Mật khẩu phải là chuỗi ký tự.',
                'newPassword.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
                'newPassword.max' => 'Mật khẩu không được vượt quá 255 ký tự.',
                'confirmPassword.required' => 'Vui lòng xác nhận mật khẩu.',
                'confirmPassword.same' => 'Mật khẩu xác nhận không khớp.',
            ]
        );
        if (Hash::check($this->currentPassword, $this->user->password)) {
            $this->user->update([
                'password' => bcrypt($this->newPassword),
            ]);
            return redirect()->route('userInfo', [$this->user->id])->with('success', 'Đổi mật khẩu thành công!');
        } else {
            session()->flash('error', 'Mật khẩu hiện tại không đúng.');
            return;
        }
    }
    public function openModal()
    {
        $this->modal = true;
    }
    public function closeModal()
    {
        $this->modal = false;
    }
    public function detailBooking($id)
    {
        $this->tabCurrent = 'booking-info';
        $this->bookingInfo = Booking::with('showtime.movie.ratings', 'showtime.movie.genres', 'showtime.room', 'user', 'seats', 'promotionUsages', 'foodOrderItems.variant.foodItem', 'foodOrderItems.variant.attributeValues.attribute',)->findOrFail($id);
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
                $query->whereDate('created_at', '>=', $this->dateFilter);
            })
            ->when($this->nameFilter, function ($query) {
                $query->whereHas('showtime.movie', function ($q) {
                    $q->where('title', 'like', '%' . $this->nameFilter . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('livewire.client.user.user-information', [
            'bookings' => $filteredBookings,
        ]);
    }
}
