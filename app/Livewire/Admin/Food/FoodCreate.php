<?php

namespace App\Livewire\Admin\Food;

use App\Models\FoodItem;
use App\Models\FoodVariant;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class FoodCreate extends Component
{
    use WithFileUploads;


    public $name;
    public $description;
    public $photo;
    public $status = 'activate'; // default status
    public $variants = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'required|string|max:255',
        'photo' => 'nullable|image',
        'status' => 'required|in:activate,discontinued',

        // Validate variants: mỗi biến thể là 1 mảng có các thuộc tính cần thiết
        'variants' => 'array',
        'variants.*.name' => 'required|string|max:255',
        'variants.*.price' => 'required|numeric|min:0',
        'variants.*.quantity' => 'required|integer|min:0',
        'variants.*.limit' => 'required|integer|min:0',
        'variants.*.status' => 'required|in:available,out_of_stock,hidden',
        'variants.*.image' => 'nullable|image',
    ];

    protected $messages = [
        'name.required' => 'Tên món ăn là bắt buộc.',
        'name.max' => 'Tên món ăn không được vượt quá 255 ký tự.',
        'description.required' => 'Mô tả món ăn là bắt buộc.',
        'description.max' => 'Mô tả món ăn không được vượt quá 255 ký tự.',
        'photo.image' => 'Ảnh món ăn phải là một tệp hình ảnh hợp lệ.',
        'status.in' => 'Trạng thái món ăn không hợp lệ.',

        'variants.*.name.required' => 'Tên biến thể là bắt buộc.',
        'variants.*.name.max' => 'Tên biến thể không được vượt quá 255 ký tự.',
        'variants.*.price.required' => 'Giá biến thể là bắt buộc.',
        'variants.*.price.numeric' => 'Giá biến thể phải là số.',
        'variants.*.price.min' => 'Giá biến thể không được nhỏ hơn 0.',
        'variants.*.quantity.required' => 'Số lượng là bắt buộc.',
        'variants.*.quantity.integer' => 'Số lượng phải là số nguyên.',
        'variants.*.quantity.min' => 'Số lượng không được nhỏ hơn 0.',
        'variants.*.limit.required' => 'Giơi hạn số lượng là bắt buộc.',
        'variants.*.limit.integer' => 'Giới hạn số lượng phải là số nguyên.',
        'variants.*.limit.min' => 'Giới hạn số lượng không được nhỏ hơn 0.',
        'variants.*.status.in' => 'Trạng thái biến thể không hợp lệ.',
        'variants.*.image.image' => 'Ảnh biến thể phải là một tệp hình ảnh hợp lệ.',
    ];

    public function addVariant()
    {
        $this->variants[] = ['name' => '', 'price' => '', 'quantity' => '', 'limit' => '', 'status' => 'available', 'image' => null];
    }


    public function removeVariant($index)
    {
        unset($this->variants[$index]);
        $this->variants = array_values($this->variants); // Reset index
    }



    public function save()
    {
        $this->validate();


        $imagePath = null;
        if ($this->photo) {
            $imagePath = $this->photo->store('food_images', 'public'); // lưu vào storage/app/public/food_images
        }

        $food = FoodItem::create([
            'name' => $this->name,
            'description' => $this->description,
            'image' => $imagePath,
            'status' => $this->status,
        ]);

        foreach ($this->variants as $variant) {
            $variantImagePath = null;

            if (isset($variant['image']) && is_object($variant['image'])) {
                $variantImagePath = $variant['image']->store('food_variant_images', 'public');
            }

            FoodVariant::create([
                'food_item_id' => $food->id,
                'name' => $variant['name'],
                'price' => $variant['price'],
                'quantity_available' => $variant['quantity'],
                'limit' => $variant['limit'],
                'status' => $variant['status'] ?? 'available',
                'image' => $variantImagePath,
            ]);
        }

        session()->flash('success', 'Thêm món ăn thành công!');
        return redirect()->route('admin.food.list');
    }

    public function render()
    {
        return view('livewire.admin.food.food-create');
    }
}
