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

    protected $messages = [
        'title.required' => 'Tiêu đề khuyến mãi là bắt buộc.',
        'title.max' => 'Tiêu đề không được vượt quá 255 ký tự.',
        'code.required' => 'Mã khuyến mãi là bắt buộc.',
        'code.min' => 'Mã khuyến mãi phải có ít nhất 4 ký tự.',
        'code.max' => 'Mã khuyến mãi không được vượt quá 20 ký tự.',
        'code.unique' => 'Mã khuyến mãi đã tồn tại.',
        'description.max' => 'Mô tả không được vượt quá 255 ký tự.',
        'start_date.required' => 'Thời gian bắt đầu là bắt buộc.',
        'start_date.after_or_equal' => 'Thời gian bắt đầu phải từ hiện tại trở đi.',
        'end_date.required' => 'Thời gian kết thúc là bắt buộc.',
        'end_date.after' => 'Thời gian kết thúc phải sau thời gian bắt đầu.',
        'usage_limit.min' => 'Giới hạn sử dụng phải ít nhất 1 lần.',
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

        if ($this->discount_type === 'percentage') {
            $rules['discount_value'] = 'required|numeric|min:1|max:100';
        } elseif ($this->discount_type === 'fixed_amount') {
            $rules['discount_value'] = 'required|numeric|min:1';
        }

        $this->validate($rules);

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
