<?php

namespace App\Livewire\Admin\Notifications;

use App\Models\Notification;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

class NotificationCreate extends Component
{
    use WithFileUploads;

    public $thumbnail = null;
    public $title = '';
    public $content = '';
    public $link = null;

    /* User */
    public $searchUser = '';
    public $usersSelected = [];

    /* Tab */
    public $tabCurrent = 'notificationTo';

    protected $rules = [
        'thumbnail' => 'nullable|image|max:20480',
        'title' => 'required|string|max:255',
        'content' => 'required|string|max:1000',
        'link' => 'nullable|url',

        'usersSelected.*' => 'integer|exists:users,id',
    ];

    protected $messages = [
        'thumbnail.image' => 'Tệp tải lên phải là hình ảnh.',
        'thumbnail.max' => 'Kích thước ảnh không được vượt quá 20MB.',
        'title.required' => 'Tiêu đề không được để trống.',
        'title.string' => 'Tiêu đề phải là một chuỗi ký tự.',
        'title.max' => 'Tiêu đề không được vượt quá 255 ký tự.',
        'content.required' => 'Nội dung không được để trống.',
        'content.string' => 'Nội dung phải là một chuỗi ký tự.',
        'content.max' => 'Nội dung không được vượt quá 1000 ký tự.',
        'link.url' => 'Đường dẫn liên kết không hợp lệ.',

        'usersSelected.*.integer' => 'ID người dùng không hợp lệ.',
        'usersSelected.*.exists' => 'Một hoặc nhiều người dùng đã chọn không tồn tại.',
    ];

    public function createNotification(){
        $this->validate();

        $notificationAdded = Notification::create([
            'thumbnail' => isset($this->thumbnail) ? $this->thumbnail->store('notifications', 'public') : null,
            'title' => $this->title,
            'content' => $this->content,
            'link' => getURLPath($this->link),
        ]);

        $notificationAdded->users()->attach($this->usersSelected);

        return redirect()->route('admin.notifications.index')->with('success', 'Tạo thông báo mới thành công!');
    }

    #[Title('Tạo thông báo - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        $users = User::select('id', 'name', 'email')->when($this->searchUser, function($query){
            $query->whereLike('name', '%' . trim($this->searchUser) . '%')
                ->orWhereLike('email', '%' . trim($this->searchUser) . '%');
        })->get();
        $totalUsers = User::count();
        return view('livewire.admin.notifications.notification-create', compact('users', 'totalUsers'));
    }
}
