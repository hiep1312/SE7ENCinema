<?php

namespace App\Livewire\Admin\Food;

use Livewire\Component;
use App\Models\FoodItem;
use App\Models\FoodVariant;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile as SupportFileUploadsTemporaryUploadedFile;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;



class FoodEdit extends Component
{
    use WithFileUploads;  // thêm dòng này

    public $foodItem;
    public $name;
    public $description;
    public $status;
    public $image;
    public $existingImagePath;
    public $variants = [];

    public function mount($id)
    {
        $this->foodItem = FoodItem::with('variants')->findOrFail($id);
        $this->name = $this->foodItem->name;
        $this->description = $this->foodItem->description;
        $this->status = $this->foodItem->status;
        $this->existingImagePath = $this->foodItem->image;
        $this->variants = $this->foodItem->variants->map(function ($variant) {
            return [
                'id' => $variant->id,
                'name' => $variant->name,
                'price' => $variant->price,
                'quantity_available' => $variant->quantity_available,
                'limit' => $variant->limit,
                'status' => $variant->status,
                'image' => null, // ảnh mới (nếu có)
                'existing_image' => $variant->image, // lưu ảnh cũ
            ];
        })->toArray();
    }

    public function addVariant()
    {
        $this->variants[] = ['name' => '', 'price' => '', 'quantity_available' => '', 'limit' => '', 'status' => 'available', 'image' => null, 'existing_image' => null,];
    }


    public function removeVariant($index)
    {
        unset($this->variants[$index]);
        $this->variants = array_values($this->variants); // Reset index
    }



    public function deleteVariant($index)
    {
        $variant = $this->variants[$index];

        // Xoá ảnh nếu có
        if (isset($variant['image']) && is_string($variant['image']) && Storage::disk('public')->exists($variant['image'])) {
            Storage::disk('public')->delete($variant['image']);
        }

        // Xoá trong database nếu có ID
        if (isset($variant['id'])) {
            FoodVariant::where('id', $variant['id'])->delete();
        }

        // Xoá khỏi mảng
        unset($this->variants[$index]);
        $this->variants = array_values($this->variants);
    }




    public function update()
    {
        // Validate món ăn chính
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'status' => ['required', Rule::in(['activate', 'discontinued'])],
            'image' => ['nullable'],
        ], [
            'name.required' => 'Tên món không được để trống.',
            'name.max' => 'Tên món không được vượt quá 255 ký tự.',
            'description.required' => 'Mô tả món ăn là bắt buộc.',
            'status.required' => 'Trạng thái là bắt buộc.',
            'status.in' => 'Trạng thái không hợp lệ.',
        ]);

        // Validate từng biến thể trước, nếu có lỗi thì dừng ngay
        foreach ($this->variants as $index => $variant) {
            Validator::make($variant, [
                'name' => ['required', 'string', 'max:255'],
                'price' => ['required', 'numeric', 'min:0'],
                'quantity_available' => ['required', 'integer', 'min:0'],
                'limit' => ['required', 'integer', 'min:0'],
                'status' => ['required', Rule::in(['available', 'out_of_stock', 'hidden'])],
                'image' => ['nullable'],
            ], [
                'name.required' => "Tên biến thể #$index không được để trống.",
                'name.max' => "Tên biến thể #$index không vượt quá 255 ký tự.",
                'price.required' => "Giá biến thể #$index là bắt buộc.",
                'price.numeric' => "Giá biến thể #$index phải là số.",
                'price.min' => "Giá biến thể #$index phải lớn hơn hoặc bằng 0.",
                'quantity_available.required' => "Số lượng biến thể #$index là bắt buộc.",
                'quantity_available.integer' => "Số lượng biến thể #$index phải là số nguyên.",
                'quantity_available.min' => "Số lượng biến thể #$index phải lớn hơn hoặc bằng 0.",
                'limit.integer' => "Giới hạn biến thể #$index phải là số nguyên.",
                'limit.required' => "Giới hạn biến thể #$index là bắt buộc.",
                'limit.min' => "Giới hạn biến thể #$index phải lớn hơn hoặc bằng 0.",
                'status.required' => "Trạng thái biến thể #$index là bắt buộc.",
                'status.in' => "Trạng thái biến thể #$index không hợp lệ.",
            ])->validateWithBag("variants.$index");
        }

        // Xử lý ảnh món ăn
        if ($this->image && is_object($this->image)) {
            if ($this->foodItem->image && Storage::disk('public')->exists($this->foodItem->image)) {
                Storage::disk('public')->delete($this->foodItem->image);
            }
            $path = $this->image->store('food_images', 'public');
            $this->foodItem->image = $path;
        }

        $this->foodItem->update([
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
        ]);

        // Lưu từng biến thể (đã được validate ở bước trên)
        foreach ($this->variants as $index => $variant) {
            $foodVariant = isset($variant['id']) ? FoodVariant::find($variant['id']) : new FoodVariant();
            $foodVariant->food_item_id = $this->foodItem->id;
            $foodVariant->name = $variant['name'];
            $foodVariant->price = $variant['price'];
            $foodVariant->quantity_available = $variant['quantity_available'];
            $foodVariant->limit = $variant['limit'] ?? null;
            $foodVariant->status = $variant['status'];

            if (isset($variant['image']) && $variant['image'] instanceof SupportFileUploadsTemporaryUploadedFile) {
                // Nếu có ảnh mới → xóa ảnh cũ nếu có
                if ($foodVariant->image && Storage::disk('public')->exists($foodVariant->image)) {
                    Storage::disk('public')->delete($foodVariant->image);
                }
                $foodVariant->image = $variant['image']->store('food_variant_images', 'public');
            } elseif (isset($variant['existing_image'])) {
                // Nếu không có ảnh mới → giữ ảnh cũ
                $foodVariant->image = $variant['existing_image'];
            } else {
                $foodVariant->image = null;
            }


            $foodVariant->save();

            $this->variants[$index]['id'] = $foodVariant->id;
            $this->variants[$index]['image'] = null;
            $this->variants[$index]['existing_image'] = $foodVariant->image;
        }

        session()->flash('success', 'Cập nhật món ăn thành công!');

        // Reset biến giữ file tạm
        $this->reset('image');
        //cập nhật lại đường dẫn ảnh đã tồn tại
        $this->existingImagePath = $this->foodItem->image;
    }






    public function render()
    {
        return view('livewire.admin.food.food-edit');
    }
}
