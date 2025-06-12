<?php

namespace App\Livewire\Admin\Banners;

use App\Models\Banner;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class BannerCreate extends Component
{
    use WithFileUploads;

    public $title = '';
    public $image;
    public $link = '';
    public $start_date = '';
    public $end_date = '';
    public $status = 'active';
    public $priority = 0;
    public $used_priorities = [];
    public $available_priorities = [];

    protected $rules = [
        'title' => 'required|string|max:255',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif',
        'link' => 'nullable|string', // Đã thay đổi từ 'url' để chấp nhận đường dẫn tệp
        'start_date' => 'required|date|after_or_equal:today',
        'end_date' => 'required|date|after:start_date',
        'status' => 'required|in:active,inactive',
        'priority' => 'required|integer|min:0|max:100|unique:banners,priority',
    ];

    protected $messages = [
        'title.required' => 'Tiêu đề banner là bắt buộc',
        'title.max' => 'Tiêu đề không được vượt quá 255 ký tự',
        'image.required' => 'Hình ảnh banner là bắt buộc',
        'image.image' => 'File phải là hình ảnh',
        'image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, gif',
        'start_date.after_or_equal' => 'Ngày bắt đầu không được nhỏ hơn ngày hôm nay',
        'end_date.after' => 'Ngày kết thúc phải sau ngày bắt đầu',
        'priority.required' => 'Độ ưu tiên là bắt buộc',
        'priority.min' => 'Độ ưu tiên tối thiểu là 0',
        'priority.max' => 'Độ ưu tiên tối đa là 100',
        'priority.unique' => 'Độ ưu tiên này đã được sử dụng',
    ];

    public function mount()
    {
        $this->start_date = now()->format('Y-m-d\TH:i');
        $this->end_date = now()->addDays(7)->format('Y-m-d\TH:i');
        $this->loadPriorities();
    }

    public function loadPriorities()
    {
        $this->used_priorities = Banner::pluck('priority')->toArray();
        $this->available_priorities = [];

        for ($i = 0; $i <= 100; $i++) {
            $this->available_priorities[$i] = in_array($i, $this->used_priorities) ? 'x' : $i;
        }
    }

    public function updatedImage()
    {
        $this->validateOnly('image');
        if ($this->image) {
            $this->link = $this->image->store('banners', 'public'); // Gán đường dẫn tệp vào link
        }
    }

    public function createBanner()
    {
        $this->validate();

        try {
            $imagePath = $this->image->store('banners', 'public');

            Banner::create([
                'title' => $this->title,
                'image' => 'storage/' . $imagePath,
                'link' => $this->link ?: null,
                'start_date' => Carbon::parse($this->start_date),
                'end_date' => Carbon::parse($this->end_date),
                'status' => $this->status,
                'priority' => $this->priority,
            ]);

            session()->flash('success', 'Tạo banner mới thành công!');
            return redirect()->route('admin.banners.index');
        } catch (\Exception $e) {
            session()->flash('error', 'Có lỗi xảy ra trong quá trình tạo banner. Vui lòng thử lại!');
        }
    }

    #[Title('Tạo Banner - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.admin.banners.banner-create', [
            'available_priorities' => $this->available_priorities
        ]);
    }
}