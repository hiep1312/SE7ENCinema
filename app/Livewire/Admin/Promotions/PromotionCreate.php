<?php

namespace App\Livewire\Admin\Promotions;

use App\Models\Promotion;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;


class PromotionCreate extends Component
{
    public $title = '';
    public $description = null;
    public $start_date = null;
    public $end_date = null;
    public $discount_type = 'fixed_amount';
    public $discount_value = null;
    public $code = '';
    public $usage_limit = null;
    public $min_purchase = null;
    public $status = 'active';

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'start_date' => 'required|date_format:Y-m-d\TH:i|after_or_equal:now',
        'end_date' => 'required|date_format:Y-m-d\TH:i|after:start_date',
        'discount_type' => 'required|in:percentage,fixed_amount',
        'discount_value' => 'required|integer|min:0',
        'code' => 'required|unique:promotions,code|min:4|max:20',
        'usage_limit' => 'nullable|integer|min:1',
        'min_purchase' => 'nullable|integer|min:0',
        // 'status' => 'required|in:active,inactive',
    ];

    protected $messages = [
        'title.required' => 'Tiêu đề mã giảm giá là bắt buộc.',
        'title.string' => 'Tiêu đề mã giảm giá phải là chuỗi.',
        'title.max' => 'Tiêu đề mã giảm giá không được vượt quá 255 ký tự.',
        'description.string' => 'Mô tả mã giảm giá phải là chuỗi.',
        'start_date.required' => 'Thời gian bắt đầu là bắt buộc.',
        'start_date.date_format' => 'Thời gian bắt đầu không đúng định dạng (Y-m-d\TH:i).',
        'start_date.after_or_equal' => 'Thời gian bắt đầu phải từ thời điểm hiện tại trở đi.',
        'end_date.required' => 'Thời gian kết thúc là bắt buộc.',
        'end_date.date_format' => 'Thời gian kết thúc không đúng định dạng (Y-m-d\TH:i).',
        'end_date.after' => 'Thời gian kết thúc phải sau thời gian bắt đầu.',
        'discount_type.required' => 'Loại giảm giá là bắt buộc.',
        'discount_type.in' => 'Loại giảm giá không hợp lệ. Chỉ chấp nhận: phần trăm hoặc cố định.',
        'discount_value.required' => 'Giá trị giảm giá là bắt buộc.',
        'discount_value.integer' => 'Giá trị giảm giá phải là số nguyên.',
        'discount_value.min' => 'Giá trị giảm giá phải lớn hơn hoặc bằng 0.',
        'discount_value.max' => 'Phần trăm giảm giá không được vượt quá 100%.',
        'code.required' => 'Mã giảm giá là bắt buộc.',
        'code.unique' => 'Mã giảm giá đã tồn tại.',
        'code.min' => 'Mã giảm giá phải có ít nhất 4 ký tự.',
        'code.max' => 'Mã giảm giá không được vượt quá 20 ký tự.',
        'usage_limit.integer' => 'Giới hạn sử dụng phải là số nguyên.',
        'usage_limit.min' => 'Giới hạn sử dụng phải lớn hơn hoặc bằng 1.',
        'min_purchase.integer' => 'Giá trị đơn hàng tối thiểu phải là số nguyên.',
        'min_purchase.min' => 'Giá trị đơn hàng tối thiểu phải lớn hơn hoặc bằng 0.',
        /* 'status.required' => 'Trạng thái mã giảm giá là bắt buộc.',
        'status.in' => 'Trạng thái mã giảm giá không hợp lệ. Chỉ chấp nhận: hoạt động hoặc ngừng hoạt động.', */
    ];

    public function updatedDiscountType(string $type){
        $changeValue = fn(int|float $value) => $type === "percentage" ? round($value * pow(10, 2 - floor(log10(abs($value))) - 1)) : ($value * 1000);

        $this->discount_value = $this->discount_value ? $changeValue($this->discount_value) : null;
    }

    public function createPromotion()
    {
        if($this->discount_type === 'percentage') $this->rules['discount_value'] .= "|max:100";
        $this->validate();

        Promotion::create([
            'title' => $this->title,
            'description' => $this->description,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'discount_type' => $this->discount_type,
            'discount_value' => $this->discount_value,
            'code' => $this->code,
            'usage_limit' => $this->usage_limit,
            'min_purchase' => $this->min_purchase,
            'status' => $this->status,
        ]);

       return redirect()->route('admin.promotions.index')->with('success', 'Tạo mã giảm giá mới thành công!');
    }

    #[Title('Tạo mã giảm giá - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.admin.promotions.promotion-create');
    }
}
