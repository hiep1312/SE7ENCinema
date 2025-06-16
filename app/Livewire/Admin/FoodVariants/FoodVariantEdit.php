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

class FoodVariantEdit extends Component
{
    use WithFileUploads;

    public $variantItem = null;
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

    public function mount(FoodVariant $variant)
    {
        $this->variantItem = $variant;
        $this->fill($variant->only('name', 'price', 'limit', 'status'));
        $this->quantity = $variant->quantity_available;
        $this->foodItemId = $variant->food_item_id;

        $this->updatedFoodItemId();
    }

    public function updatedFoodItemId()
    {
        !$this->foodItemId ?: ($this->relatedVariants = FoodVariant::where('food_item_id', $this->foodItemId)
            ->where('id', '!=', $this->variantItem->id)
            ->orderBy('created_at', 'desc')
            ->get());
    }

    public function updateVariant()
    {
        $this->validate();

        if ($this->limit > 0 && $this->quantity > $this->limit) {
            $this->addError('quantity', 'Số lượng không được vượt quá giới hạn nhập.');
            return;
        }

        $imagePath = $this->variantItem->image;
        if($this->image && $this->image instanceof UploadedFile):
            !Storage::disk('public')->exists($imagePath) ?: Storage::disk('public')->delete($imagePath);
            $imagePath = $this->image->store('food_variants', 'public');
        endif;

        $this->variantItem->update([
            'food_item_id' => $this->foodItemId,
            'name' => $this->name,
            'image' => $imagePath,
            'price' => $this->price,
            'quantity_available' => $this->quantity,
            'limit' => $this->limit,
            'status' => $this->status,
        ]);

        return redirect()->route('admin.food_variants.index')->with('success', 'Cập nhật biến thể thành công!');
    }

    #[Layout('components.layouts.admin')]
    #[Title('Chỉnh sửa Biến Thể')]

    public function render()
    {
        $foods = FoodItem::all();
        return view('livewire.admin.food-variants.food-variant-edit', compact('foods'));
    }
}
