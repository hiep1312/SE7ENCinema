<?php

namespace App\Livewire\Admin\Promotions;

use App\Models\Promotion;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class PromotionEdit extends Component
{
    public $promotionId;
    public $title;
    public $code;
    public $description;
    public $discount_type;
    public $discount_value;
    public $start_date;
    public $end_date;
    public $usage_limit;
    public $min_purchase;
    public $status;

    public function mount(Promotion $promotion)
    {
        $promotion = Promotion::findOrFail($promotion->id);
        $this->promotionId = $promotion->id;
        $this->title = $promotion->title;
        $this->code = $promotion->code;
        $this->description = $promotion->description;
        $this->discount_type = $promotion->discount_type;
        $this->discount_value = $promotion->discount_value;
        $this->start_date = $promotion->start_date->format('Y-m-d\TH:i');
        $this->end_date = $promotion->end_date->format('Y-m-d\TH:i');
        $this->usage_limit = $promotion->usage_limit;
        $this->min_purchase = $promotion->min_purchase;
        $this->status = $promotion->status;
    }

    protected $rules = [
        'title' => 'required|string|max:255',
        'code' => 'required|min:4|max:20',
        'description' => 'nullable|string|max:255',
        'discount_type' => 'required|in:percentage,fixed_amount',
        'discount_value' => 'required|numeric|min:0',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date|after_or_equal:today',
        'usage_limit' => 'required|integer|min:1',
        'min_purchase' => 'required|numeric|min:0',
        'status' => 'required|in:active,inactive,expired',
    ];

    protected $messages = [
        'title.required' => 'Tiêu đề khuyến mãi là bắt buộc.',
        'title.max' => 'Tiêu đề không được vượt quá 255 ký tự.',
        'code.required' => 'Mã khuyến mãi là bắt buộc.',
        'code.min' => 'Mã khuyến mãi phải có ít nhất 4 ký tự.',
        'code.max' => 'Mã khuyến mãi không được vượt quá 20 ký tự.',
        'description.max' => 'Mô tả không được vượt quá 255 ký tự.',
        'start_date.required' => 'Thời gian bắt đầu là bắt buộc.',
        'end_date.required' => 'Thời gian kết thúc là bắt buộc.',
        'end_date.after' => 'Thời gian kết thúc phải sau thời gian bắt đầu.',
        'end_date.after_or_equal' => 'Thời gian kết thúc phải sau thời gian hiện tại.',
        'usage_limit.required' => 'Giới hạn sử dụng là bắt buộc.',
        'usage_limit.min' => 'Giới hạn sử dụng phải ít nhất 1 lần.',
        'min_purchase.required' => 'Giá trị đơn hàng tối thiểu là bắt buộc.',
        'discount_value.required' => 'Giá trị giảm giá là bắt buộc.',
        'discount_value.numeric' => 'Giá trị giảm giá phải là số.',
        'discount_value.min' => 'Giá trị giảm giá phải lớn hơn 0.',
        'discount_value.max' => 'Giá trị giảm giá không hợp lệ.',
    ];

    // Validation real-time khi thay đổi discount_value
    public function updatedDiscountValue()
    {
        if ($this->discount_type === 'percentage') {
            $this->validate([
                'discount_value' => 'required|numeric|min:1|max:100'
            ]);
        } elseif ($this->discount_type === 'fixed_amount') {
            $this->validate([
                'discount_value' => 'required|numeric|min:1'
            ]);
        }
    }

    // Reset discount_value khi thay đổi loại giảm giá (optional)
    public function updatedDiscountType()
    {
        // Có thể bỏ comment dòng này nếu muốn reset value khi đổi loại
        $this->discount_value = '';
        // Clear error messages khi đổi loại
        $this->resetErrorBag('discount_value');
    }

    public function save()
    {
        // Validate với rules riêng biệt cho discount_value
        $rules = $this->rules;

        if ($this->discount_type === 'percentage') {
            $rules['discount_value'] = 'required|numeric|min:1|max:100';
        } elseif ($this->discount_type === 'fixed_amount') {
            $rules['discount_value'] = 'required|numeric|min:1';
        }

        $this->validate($rules);

        $promotion = Promotion::findOrFail($this->promotionId);
        $promotion->update([
            'title' => $this->title,
            'code' => $this->code,
            'description' => $this->description,
            'discount_type' => $this->discount_type,
            'discount_value' => $this->discount_value,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'usage_limit' => $this->usage_limit,
            'min_purchase' => $this->min_purchase,
            'status' => $this->status,
        ]);

        session()->flash('success', 'Cập nhật khuyến mãi thành công!');
        return redirect()->route('admin.promotions.index');
    }

    #[Title('Chỉnh sửa khuyến mãi - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        $promotion = Promotion::with(['usages.booking.user'])->findOrFail($this->promotionId);
        return view('livewire.admin.promotions.promotion-edit', compact('promotion'));
    }
}
