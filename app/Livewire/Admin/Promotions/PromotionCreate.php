<?php

namespace App\Livewire\Admin\Promotions;

use App\Models\Promotion;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Str;

class PromotionCreate extends Component
{
    public $title;
    public $code;
    public $description;
    public $discount_type = 'percentage';
    public $discount_value;
    public $start_date;
    public $usage_limit;
    public $min_purchase;
    public $end_date;
    public $status = 'active';

    protected $rules = [
        'title' => 'required|string|max:255',
        'code' => 'required|unique:promotions,code|min:4|max:20',
        'description' => 'nullable|string|max:255',
        'discount_type' => 'required|in:percentage,fixed_amount',
        'discount_value' => 'required|numeric|min:0',
        'start_date' => 'required|date|after_or_equal:today',
        'end_date' => 'required|date|after:start_date',
        'usage_limit' => 'nullable|integer|min:1',
        'min_purchase' => 'nullable|numeric|min:0',
        'status' => 'required|in:active,inactive',
    ];
    /// LƯU Ý:  Cái end_date có thể bỏ cái _or_equal nếu để vào nó sẽ cho bằng giờ phút nó vẫn tạo được nên là nên bỏ nhé !!!! bên edit cx v
    protected $messages = [
        'title.required' => 'Tiêu đề khuyến mãi là bắt buộc.',
        'title.min' => 'Tiêu đề phải có ít nhất 5 ký tự.',
        'title.max' => 'Tiêu đề không được vượt quá 100 ký tự.',

        'code.required' => 'Mã khuyến mãi là bắt buộc.',
        'code.min' => 'Mã khuyến mãi phải có ít nhất 3 ký tự.',
        'code.max' => 'Mã khuyến mãi không được vượt quá 15 ký tự.',
        'code.unique' => 'Mã khuyến mãi đã tồn tại.',
        'code.regex' => 'Mã khuyến mãi chỉ được chứa chữ cái viết hoa và số.',

        'description.min' => 'Mô tả phải có ít nhất 10 ký tự.',
        'description.max' => 'Mô tả không được vượt quá 500 ký tự.',

        'start_date.required' => 'Thời gian bắt đầu là bắt buộc.',
        'start_date.after_or_equal' => 'Thời gian bắt đầu phải từ hiện tại trở đi.',

        'end_date.required' => 'Thời gian kết thúc là bắt buộc.',
        'end_date.after' => 'Thời gian kết thúc phải sau thời gian bắt đầu.',
        'end_date.before_or_equal' => 'Thời gian kết thúc không được quá 1 năm kể từ hiện tại.',

        'usage_limit.min' => 'Giới hạn sử dụng phải ít nhất 1 lần.',
        'usage_limit.max' => 'Giới hạn sử dụng không được vượt quá 10,000 lần.',
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

    // Reset discount_value khi thay đổi loại giảm giá
    public function updatedDiscountType()
    {
        $this->discount_value = '';
        $this->resetErrorBag('discount_value');
    }

    public function generateCode()
    {
        $this->code = Str::upper(Str::random(8));
        $this->validateOnly('code');
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

        Promotion::create([
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

        session()->flash('success', 'Tạo khuyến mãi thành công!');
        return redirect()->route('admin.promotions.index');
    }

    #[Title('Tạo khuyến mãi - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.admin.promotions.promotion-create');
    }
}
