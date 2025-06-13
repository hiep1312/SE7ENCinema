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
        'start_date' => 'required|date|',
        'end_date' => 'required|date|after:start_date',
        'usage_limit' => 'required|integer|min:1',
        'min_purchase' => 'required|numeric|min:0',
        'status' => 'required|in:active,inactive,expired',
    ];

    protected $messages = [
        'title.required' => 'Tiêu đề khuyến mãi là bắt buộc.',
        'title.min' => 'Tiêu đề phải có ít nhất 5 ký tự.',
        'title.max' => 'Tiêu đề không được vượt quá 100 ký tự.',

        'code.required' => 'Mã khuyến mãi là bắt buộc.',
        'code.min' => 'Mã khuyến mãi phải có ít nhất 3 ký tự.',
        'code.max' => 'Mã khuyến mãi không được vượt quá 15 ký tự.',
        'code.unique' => 'Mã khuyến mãi đã tồn tại.',
        'code.regex' => 'Mã khuyến mãi chỉ được chứa chữ cái viết hoa và số.',

        'description.required' => 'Mô tả khuyến mãi là bắt buộc.',
        'description.min' => 'Mô tả phải có ít nhất 10 ký tự.',
        'description.max' => 'Mô tả không được vượt quá 500 ký tự.',

        'start_date.required' => 'Thời gian bắt đầu là bắt buộc.',

        'end_date.required' => 'Thời gian kết thúc là bắt buộc.',
        'end_date.after' => 'Thời gian kết thúc phải sau thời gian bắt đầu.',
        'end_date.before_or_equal' => 'Thời gian kết thúc không được quá 1 năm kể từ hiện tại.',

        'usage_limit.required' => 'Giới hạn sử dụng là bắt buộc.',
        'usage_limit.min' => 'Giới hạn sử dụng phải ít nhất 1 lần.',
        'usage_limit.max' => 'Giới hạn sử dụng không được vượt quá 10,000 lần.',

        'min_purchase.required' => 'Giá trị đơn hàng tối thiểu là bắt buộc.',
        'min_purchase.max' => 'Giá trị đơn hàng tối thiểu không được vượt quá 50,000,000đ.',

        'discount_value.required' => 'Giá trị giảm giá là bắt buộc.',
        'discount_value.numeric' => 'Giá trị giảm giá phải là số.',
        'discount_value.min' => 'Giá trị giảm giá phải ít nhất 0.',
    ];

    // Validation real-time khi thay đổi discount_value
    public function updatedDiscountValue()
    {
        if ($this->discount_type === 'percentage') {
            $this->validate([
                'discount_value' => 'required|numeric|min:1|max:100'
            ], [
                'discount_value.required' => 'Phần trăm (%) giảm giá là bắt buộc.',
                'discount_value.numeric' => 'Phần trăm (%) giảm giá phải là số.',
                'discount_value.min' => 'Phần trăm (%) giảm giá phải ít nhất 1%.',
                'discount_value.max' => 'Phần trăm (%) giảm giá không được quá 100%.',
            ]);
        } elseif ($this->discount_type === 'fixed_amount') {
            $this->validate([
                'discount_value' => 'required|numeric|min:1'
            ], [
                'discount_value.required' => 'Số tiền (đ) giảm giá là bắt buộc.',
                'discount_value.numeric' => 'Số tiền (đ) giảm giá phải là số.',
                'discount_value.min' => 'Số tiền (đ) giảm giá phải ít nhất 1đ.',
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
        $messages = $this->messages;

        if ($this->discount_type === 'percentage') {
            $rules['discount_value'] = 'required|numeric|min:1|max:100';
            $messages['discount_value.required'] = 'Phần trăm (%) giảm giá là bắt buộc.';
            $messages['discount_value.numeric'] = 'Phần trăm (%) giảm giá phải là số.';
            $messages['discount_value.min'] = 'Phần trăm (%) giảm giá phải ít nhất 1%.';
            $messages['discount_value.max'] = 'Phần trăm (%) giảm giá không được quá 100%.';
        } elseif ($this->discount_type === 'fixed_amount') {
            $rules['discount_value'] = 'required|numeric|min:1';
            $messages['discount_value.required'] = 'Số tiền (đ) giảm giá là bắt buộc.';
            $messages['discount_value.numeric'] = 'Số tiền (đ) giảm giá phải là số.';
            $messages['discount_value.min'] = 'Số tiền (đ) giảm giá phải ít nhất 1đ.';
        }

        $this->validate($rules, $messages);

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
