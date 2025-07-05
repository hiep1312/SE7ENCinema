<?php

namespace App\Livewire\Admin\FoodVariants;

use Livewire\Component;
use App\Models\FoodItem;
use App\Models\FoodVariant;
use App\Models\FoodVariantAttributeValue; // Không cần thiết nếu bạn không trực tiếp thao tác với model này
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\FoodAttribute;
use App\Models\FoodAttributeValue;
use Illuminate\Support\Str;

class FoodVariantEdit extends Component
{
    use WithFileUploads;

    public $foodItem;
    public $variant;
    public $name = '';
    public $price = 0;
    public $quantity_available = 0;
    public $limit = 0;
    public $image = null;
    public $currentImage = null;
    public $status = 'activate';
    public $variantId = null;
    public $attributeVariant = [];

    protected $rules = [
        'price' => 'required|numeric|min:0',
        'quantity_available' => 'required|integer|min:0',
        'limit' => 'nullable|integer|min:0',
        'image' => 'nullable|image|max:2048',
        'status' => 'in:available,out_of_stock,hidden',

        'attributeVariant.*.attribute_name' => 'required|string|max:255',
        'attributeVariant.*.value' => 'required|string|max:255',
    ];


    protected $messages = [
        'price.required' => 'Giá là bắt buộc.',
        'quantity_available.required' => 'Số lượng có sẵn là bắt buộc.',
        'limit.integer' => 'Giới hạn phải là một số nguyên.',
        'image.image' => 'Tệp tải lên phải là hình ảnh.',
        'image.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',
        'status.in' => 'Trạng thái không hợp lệ.',
        'attributeVariant.*.attribute_name.required' => 'Thuộc tính là bắt buộc.',
        'attributeVariant.*.value.required' => 'Giá trị thuộc tính là bắt buộc.',
        'attributeVariant.*.attribute_name.string' => 'Tên thuộc tính phải là một chuỗi.',
        'attributeVariant.*.value.string' => 'Giá trị thuộc tính phải là một chuỗi.',
        'attributeVariant.*.attribute_name.max' => 'Tên thuộc tính không được vượt quá 255 ký tự.',
        'attributeVariant.*.value.max' => 'Giá trị thuộc tính không được vượt quá 255 ký tự.',
    ];




    public function mount(FoodVariant $variant)
    {
        $this->foodItem = FoodItem::find($variant->food_item_id);

        $this->variant = $variant;
        $this->variantId = $variant->id;



        $this->currentImage = $variant->image;
        $this->image = null;



        $this->name = $this->foodItem->name;
        $this->status = $this->variant->status;
        $this->price = $this->variant->price;
        $this->quantity_available = $this->variant->quantity_available;
        $this->limit = $this->variant->limit;

        $this->attributeVariant = $variant->attributeValues->map(function ($value) {
            return [
                'attribute_name' => $value->attribute->name,
                'value' => $value->value,
            ];
        })->toArray();
    }

    public function addAttribute()
    {
        $this->attributeVariant[] = [
            'attribute_name' => '',
            'value' => '',
        ];
    }




    public function removeAttribute($index)
    {
        unset($this->attributeVariant[$index]);
        $this->attributeVariant = array_values($this->attributeVariant);
    }


    public function save()
    {
        $rules = $this->rules;
        if (! ($this->image instanceof UploadedFile)) {
            unset($rules['image']);
        }

        $this->validate($rules);

        // Kiểm tra limit >= quantity_available
        if ($this->limit !== null && $this->limit < $this->quantity_available) {
            session()->flash('error', 'Giới hạn không được nhỏ hơn số lượng có sẵn.');
            return;
        }



        // Kiểm tra cặp thuộc tính - giá trị có trùng không (vì đây là 1 biến thể, check mảng tự lặp)
        $combos = [];
        $attributeNames = [];

        foreach ($this->attributeVariant as $pair) {
            $attributeName = trim(strtolower($pair['attribute_name']));
            $value = trim(strtolower($pair['value']));

            // Check trùng cặp attribute_name + value
            $comboKey = $attributeName . '-' . $value;
            if (in_array($comboKey, $combos)) {
                session()->flash('error', 'Thuộc tính & giá trị bị trùng lặp. Vui lòng kiểm tra lại.');
                return;
            }
            $combos[] = $comboKey;

            // Check trùng attribute_name
            if (in_array($attributeName, $attributeNames)) {
                session()->flash('error', 'Mỗi thuộc tính chỉ được thêm 1 lần. Vui lòng kiểm tra lại.');
                return;
            }
            $attributeNames[] = $attributeName;
        }

        
        $inputCombos = collect($this->attributeVariant)->map(function ($pair) {
            return trim(strtolower($pair['attribute_name'])) . '-' . trim(strtolower($pair['value']));
        })->sort()->values()->all();

        
        $otherVariants = FoodVariant::where('food_item_id', $this->foodItem->id)
            ->where('id', '!=', $this->variant->id)
            ->get();

        foreach ($otherVariants as $other) {
            $otherCombos = $other->attributeValues->map(function ($val) {
                return trim(strtolower($val->attribute->name)) . '-' . trim(strtolower($val->value));
            })->sort()->values()->all();

            if ($otherCombos === $inputCombos) {
                session()->flash('error', 'Đã tồn tại biến thể khác có cùng tất cả thuộc tính & giá trị.');
                return;
            }
        }


        if ($this->getErrorBag()->isNotEmpty()) return;

       
        $variant = $this->variant;

        
        $variant->fill([
            'food_item_id' => $this->foodItem->id,
            'price' => $this->price,
            'quantity_available' => $this->quantity_available,
            'limit' => $this->limit ?: null,
            'status' => $this->status,
        ]);

        // Xử lý ảnh
        if ($this->image instanceof UploadedFile) {
            if ($this->currentImage && Storage::disk('public')->exists($this->currentImage)) {
                Storage::disk('public')->delete($this->currentImage);
            }
            $variant->image = $this->image->store('food_variants', 'public');
        } else {
            $variant->image = $this->currentImage; // Giữ link cũ
        }

        // Tạo SKU mới
        $variant->sku = $this->parseSku([
            'attribute_values' => collect($this->attributeVariant)->map(function ($item) {
                return [
                    'attribute' => $item['attribute_name'],
                    'value' => $item['value'],
                ];
            })->toArray(),
        ]);

        $variant->save();

        // Duyệt & tạo/sửa thuộc tính, giá trị
        $ids = [];

        foreach ($this->attributeVariant as $pair) {
            $attr = FoodAttribute::firstOrCreate([
                'food_item_id' => $this->foodItem->id,
                'name' => $pair['attribute_name'],
            ]);

            $val = FoodAttributeValue::firstOrCreate([
                'food_attribute_id' => $attr->id,
                'value' => $pair['value'],
            ]);

            $ids[] = $val->id;
        }

        
        $variant->attributeValues()->sync($ids);

        return redirect()->route('admin.food_variants.index')->with('success', 'Sửa biến thể món ăn thành công!');
    }




    protected function parseSku($variant)
    {
        $parts = [];

        $parts[] = Str::slug($this->foodItem->name);

        foreach ($variant['attribute_values'] as $pair) {
            $parts[] = Str::slug($pair['attribute']);
            $parts[] = Str::slug($pair['value']);
        }

        return implode('-', $parts);
    }




    #[Title('Chỉnh sửa biến Thể')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.admin.food-variants.food-variant-edit');
    }
}
