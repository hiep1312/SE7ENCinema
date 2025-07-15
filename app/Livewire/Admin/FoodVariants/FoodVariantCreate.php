<?php

namespace App\Livewire\Admin\FoodVariants;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\FoodVariant;
use App\Models\FoodItem;
use Livewire\WithFileUploads;

class FoodVariantCreate extends Component
{
    use WithFileUploads;

    public $foodItemId = null;
    public $name = '';
    public $image = null;
    public $price = null;
    public $quantity = null;
    public $limit = null;
    public $status = 'available';
    public $relatedVariants = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'foodItemId' => 'required|exists:food_items,id',
        'price' => 'required|numeric|min:0',
        'image' => 'nullable|image|max:20480',
        'quantity' => 'required|integer|min:0',
        'limit' => 'nullable|integer|min:0',
        'status' => 'required|in:available,out_of_stock,hidden',
    ];

    protected $messages = [
        'name.required' => 'Tên biến thể là bắt buộc.',
        'name.max' => 'Tên biến thể không được vượt quá 255 ký tự.',
        'foodItemId.required' => 'Món ăn liên kết là bắt buộc.',
        'foodItemId.exists' => 'Món ăn liên kết không tồn tại.',
        'price.required' => 'Giá biến thể là bắt buộc.',
        'price.numeric' => 'Giá biến thể phải là số.',
        'price.min' => 'Giá biến thể không được nhỏ hơn 0.',
        'image.image' => 'Ảnh biến thể phải là một tệp hình ảnh hợp lệ.',
        'image.max' => 'Ảnh biến thể không được vượt quá 20MB.',
        'quantity.required' => 'Số lượng là bắt buộc.',
        'quantity.integer' => 'Số lượng phải là số nguyên.',
        'quantity.min' => 'Số lượng không được nhỏ hơn 0.',
        'limit.integer' => 'Giới hạn số lượng nhập phải là số nguyên.',
        'limit.min' => 'Giới hạn số lượng nhập không được nhỏ hơn 0.',
        'status.required' => 'Trạng thái biến thể là bắt buộc.',
        'status.in' => 'Trạng thái biến thể không hợp lệ.',
    ];

    public function updatedFoodItemId()
    {
        !$this->foodItemId ?: ($this->relatedVariants = FoodVariant::where('food_item_id', $this->foodItemId)
            ->orderBy('created_at', 'desc')
            ->get());
    }

    public function createVariant()
    {
        $this->validate();

        if ($this->limit > 0 && $this->quantity > $this->limit) {
            $this->addError('quantity', 'Số lượng không được vượt quá giới hạn nhập.');
            return;
        }

        FoodVariant::create([
            'food_item_id' => $this->foodItemId,
            'name' => $this->name,
            'image' => $this->image ? $this->image->store('food_variants', 'public') : null,
            'price' => $this->price,
            'quantity_available' => $this->quantity,
            'limit' => $this->limit,
            'status' => $this->status,
        ]);

        return redirect()->route('admin.food_variants.index')->with('success', 'Thêm mới biến thể món ăn thành công!');
    }

    #[Title('Tạo biến thể món ăn - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        $foods = FoodItem::all();
        return view('livewire.admin.food-variants.food-variant-create', compact('foods'));
    }
}
