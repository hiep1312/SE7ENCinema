<?php

namespace App\Livewire\Admin\Banners;

use App\Models\Banner;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class BannerEdit extends Component
{
    use WithFileUploads;

    public $banner;
    public $title = '';
    public $image;
    public $current_image = '';
    public $link = '';
    public $start_date = '';
    public $end_date = '';
    public $status = 'active';
    public $priority = 0;
    public $used_priorities = [];
    public $available_priorities = [];

    protected $messages = [
        'title.required' => 'Tiêu đề banner là bắt buộc',
        'title.max' => 'Tiêu đề không được vượt quá 255 ký tự',
        'title.unique' => 'Tiêu đề này đã tồn tại trong hệ thống',
        'image.image' => 'File phải là hình ảnh',
        'image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif',
        'image.max' => 'Kích thước hình ảnh không được vượt quá 2MB',
        'link.url' => 'Link phải là một URL hợp lệ',
        'start_date.required' => 'Ngày bắt đầu là bắt buộc',
        'end_date.required' => 'Ngày kết thúc là bắt buộc',
        'end_date.after' => 'Ngày kết thúc phải sau ngày bắt đầu',
        'end_date.after_or_equal' => 'Ngày kết thúc không được nhỏ hơn ngày và giờ hiện tại',
        'priority.required' => 'Độ ưu tiên là bắt buộc',
        'priority.min' => 'Độ ưu tiên tối thiểu là 0',
        'priority.max' => 'Độ ưu tiên tối đa là 100',
        'priority.unique' => 'Độ ưu tiên này đã được sử dụng bởi banner khác',
    ];

    public function mount(Banner $banner)
    {
        $this->banner = $banner;
        $this->fill([
            'title' => $banner->title,
            'current_image' => $banner->image,
            'link' => $banner->link,
            'start_date' => $banner->start_date->format('Y-m-d\TH:i'),
            'end_date' => $banner->end_date->format('Y-m-d\TH:i'),
            'status' => $banner->status,
            'priority' => $banner->priority,
        ]);
        $this->loadPriorities();
    }

    public function loadPriorities()
    {
        $this->used_priorities = Banner::where('id', '!=', $this->banner->id)->pluck('priority')->toArray();
        $this->available_priorities = [];

        for ($i = 0; $i <= 100; $i++) {
            if ($i == $this->banner->priority) {
                $this->available_priorities[$i] = 'current';
            } else {
                $this->available_priorities[$i] = in_array($i, $this->used_priorities) ? 'x' : $i;
            }
        }
    }

    // Dynamic validation rules
    protected function rules()
    {
        // Kiểm tra xem có phải đang chỉnh sửa banner đã tồn tại và ngày kết thúc không thay đổi
        $isEndDateUnchanged = $this->end_date === $this->banner->end_date->format('Y-m-d\TH:i');

        return [
            'title' => ['required', 'string', 'max:255', Rule::unique('banners', 'title')->ignore($this->banner->id)],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'nullable|url',
            'start_date' => 'required|date',
            'end_date' => $isEndDateUnchanged
                ? 'required|date|after:start_date'
                : 'required|date|after:start_date|after_or_equal:now',
            'status' => 'required|in:active,inactive',
            'priority' => ['required', 'integer', 'min:0', 'max:100', Rule::unique('banners', 'priority')->ignore($this->banner->id)],
        ];
    }

    public function updatedImage()
    {
        $this->validateOnly('image');
    }

    public function updatedTitle()
    {
        $this->validateOnly('title');
    }

    public function updatedLink()
    {
        if ($this->link) {
            $this->validateOnly('link');
        }
    }

    public function updatedPriority()
    {
        $this->validateOnly('priority');
        $this->loadPriorities(); // Reload priorities when changed
    }

    public function updatedStartDate()
    {
        $this->validateOnly('start_date');
        // Validate lại end_date khi start_date thay đổi
        if ($this->end_date) {
            $this->validateOnly('end_date');
        }
    }

    public function updatedEndDate()
    {
        $this->validateOnly('end_date');
    }

    public function updateBanner()
    {
        $this->validate();

        try {
            $data = [
                'title' => $this->title,
                'link' => $this->link ?: null,
                'start_date' => Carbon::parse($this->start_date),
                'end_date' => Carbon::parse($this->end_date),
                'status' => $this->status,
                'priority' => $this->priority,
            ];

            if ($this->image) {
                // Xóa ảnh cũ nếu có
                if ($this->current_image && file_exists(public_path($this->current_image))) {
                    unlink(public_path($this->current_image));
                }
                $imagePath = $this->image->store('banners', 'public');
                $data['image'] = 'storage/' . $imagePath;
            }

            $this->banner->update($data);

            session()->flash('success', 'Cập nhật banner thành công!');
            return redirect()->route('admin.banners.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Có lỗi xảy ra trong quá trình cập nhật banner. Vui lòng thử lại!');
        }
    }

    #[Title('Cập nhật Banner - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.admin.banners.banner-edit', [
            'available_priorities' => $this->available_priorities
        ]);
    }
}
