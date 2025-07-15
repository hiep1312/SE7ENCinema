<?php

namespace App\Livewire\Admin\Banners;

use App\Models\Banner;
use Illuminate\Http\UploadedFile;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class BannerEdit extends Component
{
    use WithFileUploads;

    public $banner;
    public $title = '';
    public $image = null;
    public $link = null;
    public $start_date = null;
    public $end_date = null;
    public $status = 'active';
    public $priority = 0;

    protected $rules = [
        'title' => 'required|string|max:255',
        'image' => 'nullable|image|max:20480',
        'link' => 'nullable|url',
        'end_date' => 'nullable|date|after:start_date',
        'status' => 'required|in:active,inactive',
        'priority' => 'required|integer|min:0|max:100|unique:banners,priority',
    ];

    protected $messages = [
        'title.required' => 'Tiêu đề banner là bắt buộc',
        'title.max' => 'Tiêu đề không được vượt quá 255 ký tự',
        'image.image' => 'Ảnh banner phải là một tệp hình ảnh hợp lệ.',
        'image.max' => 'Kích thước ảnh không được vượt quá 20MB',
        'link.url' => 'Đường dẫn liên kết phải là một URL hợp lệ',
        'end_date.date' => 'Ngày giờ kết thúc phải đúng định dạng.',
        'end_date.after_or_equal' => 'Ngày giờ kết thúc không được nhỏ hơn ngày và giờ hiện tại.',
        'end_date.after' => 'Ngày giờ kết thúc phải sau ngày giờ bắt đầu.',
        'status.required' => 'Trạng thái là bắt buộc.',
        'status.in' => 'Trạng thái không hợp lệ. Giá trị hợp lệ: active hoặc inactive.',
        'priority.required' => 'Độ ưu tiên là bắt buộc.',
        'priority.integer' => 'Độ ưu tiên phải là số nguyên.',
        'priority.min' => 'Độ ưu tiên tối thiểu là 0.',
        'priority.max' => 'Độ ưu tiên tối đa là 100.',
        'priority.unique' => 'Độ ưu tiên này đã được sử dụng. Vui lòng chọn giá trị khác.',
    ];

    public function mount(Banner $banner)
    {
        $this->banner = $banner;
        $this->fill($banner->only('title', 'link', 'status', 'priority'));
        $this->start_date = $banner->start_date->format('Y-m-d\TH:i');
        $this->end_date = $banner->end_date?->format('Y-m-d\TH:i');
    }

    public function updateBanner()
    {
        if($this->end_date !== $this->banner->end_date?->format('Y-m-d\TH:i')) $this->rules['end_date'] .= '|after_or_equal:now';
        $this->rules['priority'] .= ',' . $this->banner->id;
        $this->validate();

        $imagePath = $this->banner->image;
        if($this->image && $this->image instanceof UploadedFile):
            !Storage::disk('public')->exists($imagePath) ?: Storage::disk('public')->delete($imagePath);
            $imagePath = $this->image->store('banners', 'public');
        endif;

        $this->banner->update([
            'title' => $this->title,
            'image' => $imagePath,
            'link' => $this->link,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
            'priority' => $this->priority,
        ]);

        return redirect()->route('admin.banners.index')->with('success', 'Cập nhật banner thành công!');
    }

    #[Title('Chỉnh sửa banner - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        $priorityCurrent = [];
        $available = Banner::all()->pluck('priority')->toArray();
        foreach (range(0, 100) as $i) {
            $priorityCurrent[$i] = in_array($i, $available) ? 'x' : $i;
        }
        return view('livewire.admin.banners.banner-edit', compact('priorityCurrent'));
    }
}
