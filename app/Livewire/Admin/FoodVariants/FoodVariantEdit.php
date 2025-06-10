<?php

namespace App\Livewire\Admin\FoodVariants;

use App\Models\FoodItem;
use App\Models\FoodVariant;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.admin')]
#[Title('Chỉnh sửa Biến Thể')]

class FoodVariantEdit extends Component
{
    use WithFileUploads;

    public $Foods = [];
    public $foodItemId = null;
    public $variantId = null;
    public $name = '';
    public $price = 0;
    public $image = null;       // file upload mới
    public $imageUrl = null;    // ảnh cũ (string path)
    public $quantity = 0;
    public $limit = null;
    public $status = 'available';
    public $RelatedVariant = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'image' => 'nullable|image|max:20480', // max 20MB
        'quantity' => 'required|integer|min:0',
        'limit' => 'nullable|integer|min:0',
        'status' => 'required|in:available,out_of_stock,hidden',
        'foodItemId' => 'required|exists:food_items,id',
    ];

    protected $messages = [
        'name.required' => 'Tên biến thể là bắt buộc.',
        'name.max' => 'Tên biến thể không được vượt quá 255 ký tự.',
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
        'foodItemId.required' => 'Bạn chưa chọn món ăn.',
    ];

    public function mount($variant)
    {
        $this->Foods = FoodItem::all();

        $this->variantId = $variant;
        $variantData = FoodVariant::findOrFail($this->variantId);

        $this->name = $variantData->name;
        $this->foodItemId = $variantData->food_item_id;
        $this->price = $variantData->price;
        $this->imageUrl = $variantData->image;
        $this->quantity = $variantData->quantity_available;
        $this->limit = $variantData->limit;
        $this->status = $variantData->status;

        $this->getVariants();
    }

    public function updatedFoodItemId()
    {
        $this->getVariants();
    }

    public function getVariants()
    {
        if ($this->foodItemId) {
            $this->RelatedVariant = FoodVariant::where('food_item_id', $this->foodItemId)
                ->where('id', '!=', $this->variantId) // Loại trừ biến thể đang sửa
                ->orderBy('created_at', 'desc')
                ->get();
        }
    }


    public function save()
    {
        $this->validate();

        if ($this->limit > 0 && $this->quantity > $this->limit) {
            $this->addError('limit', 'Số lượng không được vượt quá giới hạn.');
            return;
        }

        $variant = FoodVariant::findOrFail($this->variantId);

        // Upload ảnh mới nếu có
        if ($this->image instanceof UploadedFile) {
            if ($variant->image && Storage::disk('public')->exists($variant->image)) {
                Storage::disk('public')->delete($variant->image);
            }

            $imagePath = $this->image->store('food_variant_images', 'public');
            $variant->image = $imagePath;
        }

        // Cập nhật thông tin
        $variant->name = $this->name;
        $variant->food_item_id = $this->foodItemId;
        $variant->price = $this->price;
        $variant->quantity_available = $this->quantity;
        $variant->limit = $this->limit;
        $variant->status = $this->status;
        $variant->save();

        session()->flash('success', 'Cập nhật biến thể thành công!');
        return redirect()->route('admin.foods_variants.index');
    }

    public function render()
    {
        return view('livewire.admin.food-variants.food-variant-edit');
    }
}
