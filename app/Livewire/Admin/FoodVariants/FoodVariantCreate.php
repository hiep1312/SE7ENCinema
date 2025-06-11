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

    public $Foods = [];
    public $foodItemId = null;
    public $name = '';
    public $price = 0;
    public $image = null;
    public $quantity = 0;
    public $limit = null;
    public $status = 'available';
    public $RelatedVariant = [];

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
        'foodItemId.required' => 'Món ăn là bắt buộc.',
        'foodItemId.exists' => 'Món ăn không tồn tại.',
        'foodItemId.integer' => 'Món ăn không hợp lệ.',
        'foodItemId.min' => 'Món ăn không hợp lệ.',
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

    #[Layout('components.layouts.admin')]
    #[Title('Tạo Biến Thể Món Ăn')]

    public function mount()
    {
        $this->Foods = FoodItem::all();
    }

    public function updatedFoodItemId()
    {
        $this->getVariants();
    }

    public function getVariants()
    {
        if ($this->foodItemId) {
            $this->RelatedVariant = FoodVariant::where('food_item_id', $this->foodItemId)
                ->orderBy('created_at', 'desc')
                ->get();
        }
    }



    public function createVariant()
    {
        $this->validate();

        if ($this->limit > 0 && $this->quantity > $this->limit) {
            $this->addError('quantity', 'Số lượng không được vượt quá giới hạn.');
            return;
        }

        FoodVariant::create([
            'food_item_id' => $this->foodItemId,
            'name' => $this->name,
            'price' => $this->price,
            'image' => $this->image ? $this->image->store('food_variant_image', 'public') : null,
            'quantity_available' => $this->quantity,
            'limit' => $this->limit,
            'status' => $this->status,
        ]);

        session()->flash('success', 'Biến thể món ăn đã được tạo thành công.');
        return redirect()->route('admin.foods_variants.index');
    }



    public function render()
    {
        return view('livewire.admin.food-variants.food-variant-create');
    }
}
