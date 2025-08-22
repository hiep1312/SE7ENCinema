<?php

namespace App\Livewire\Admin\Foods;

use App\Models\FoodItem;
use App\Models\FoodVariant;
use App\Models\FoodAttribute;
use App\Models\FoodAttributeValue;
use App\Models\FoodVariantAttributeValue;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class FoodCreate extends Component
{
    use WithFileUploads;

    public $name = '';
    public $description = null;
    public $image = null;
    public $status = 'activate';

    public $variantAttributes = [];
    public $newAttributeName = '';
    public $newAttributeValues = '';

    public $generatedVariants = [];
    public bool $productVariants = false;
    public string $variantTab = 'attributes';
    public $expandedVariants = [];

    public string $variantCreateMode = '';
    public $manualAttributeValues = [];

    public $basePrice = null;
    public $displayBasePrice;
    public $baseQuantity = null;
    public $baseLimit = null;
    public $baseStatus = 'available';

    public $bulkAction = '';
    public $bulkValue = null;
    public $bulkImage = null;

    public $availableAttributes = [];
    public $selectedAttributeId = null;
    public $selectedAttributeValueIds = [];

    public bool $applyToAll = false;
    public $editingIndex = null;

    public function rules()
    {
        $rules = [
            'name' => ['required'],
            'image' => ['nullable', 'image', 'max:20480'],
            'status' => ['required', 'in:activate,discontinued'],

            'generatedVariants.*.price' => ['required', 'numeric', 'min:1'],
            'generatedVariants.*.quantity' => ['required', 'integer', 'min:1'],
            'generatedVariants.*.limit' => ['nullable', 'integer', 'min:0'],
            'generatedVariants.*.status' => ['required', 'in:available,out_of_stock,hidden'],
            'generatedVariants.*.image' => ['nullable', 'image', 'max:20480'],
        ];

        if (!$this->productVariants) {
            $rules['basePrice'] = ['required', 'numeric', 'min:1'];
            $rules['baseQuantity'] = ['required', 'integer', 'min:1'];
            $rules['baseLimit'] = ['nullable', 'integer', 'min:0'];
            $rules['baseStatus'] = ['required', 'in:available,out_of_stock,hidden'];
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [
            'name.required' => 'Tên món ăn là bắt buộc.',
            'image.image' => 'Ảnh phải là một tệp hình ảnh.',
            'image.max' => 'Ảnh không được lớn hơn 20MB.',
            'status.required' => 'Trạng thái là bắt buộc.',
            'status.in' => 'Trạng thái không hợp lệ.',

            'generatedVariants.*.price.numeric' => 'Giá phải là số.',
            'generatedVariants.*.price.required' => 'Giá là bắt buộc.',
            'generatedVariants.*.price.min' => 'Giá phải lớn hơn 0.',
            'generatedVariants.*.quantity.integer' => 'Số lượng phải là số nguyên.',
            'generatedVariants.*.quantity.required' => 'Số lượng là bắt buộc.',
            'generatedVariants.*.quantity.min' => 'Số lượng phải lớn hơn 0.',
            'generatedVariants.*.limit.integer' => 'Giới hạn phải là số nguyên.',
            'generatedVariants.*.limit.min' => 'Giới hạn không được âm.',
            'generatedVariants.*.status.required' => 'Trạng thái là bắt buộc.',
            'generatedVariants.*.status.in' => 'Trạng thái không hợp lệ.',
            'generatedVariants.*.image.image' => 'Ảnh phải là tệp hình ảnh.',
            'generatedVariants.*.image.max' => 'Ảnh không được lớn hơn 20MB.',
        ];

        if (!$this->productVariants) {
            $messages['basePrice.numeric'] = 'Giá cơ bản phải là số.';
            $messages['basePrice.required'] = 'Giá cơ bản là bắt buộc.';
            $messages['basePrice.min'] = 'Giá cơ bản phải lớn hơn 0.';
            $messages['baseQuantity.integer'] = 'Số lượng cơ bản phải là số nguyên.';
            $messages['baseQuantity.required'] = 'Số lượng cơ bản là bắt buộc.';
            $messages['baseQuantity.min'] = 'Số lượng cơ bản phải lớn hơn 0.';
            $messages['baseLimit.integer'] = 'Giới hạn cơ bản phải là số nguyên.';
            $messages['baseLimit.min'] = 'Giới hạn cơ bản không được âm.';
            $messages['baseStatus.required'] = 'Trạng thái cơ bản là bắt buộc.';
            $messages['baseStatus.in'] = 'Trạng thái cơ bản không hợp lệ.';
        }

        return $messages;
    }

    public function mount()
    {
        $this->displayBasePrice = $this->basePrice
            ? number_format($this->basePrice, 0, ',', '.')
            : '';
    }

    public function updateBasePrice($value)
    {
        // Bỏ tất cả ký tự không phải số
        $numericValue = preg_replace('/\D/', '', $value);

        $this->basePrice = $numericValue !== '' ? (int) $numericValue : null;
        $this->displayBasePrice = $this->basePrice
            ? number_format($this->basePrice, 0, ',', '.')
            : '';
    }

    public function addAttribute()
    {
        $name = trim($this->newAttributeName);
        $values = array_filter(array_map('trim', explode('|', $this->newAttributeValues)));

        if (!$name) {
            $this->addError('newAttributeName', 'Tên thuộc tính không được để trống.');
            return;
        }
        if (empty($values)) {
            $this->addError('newAttributeValues', 'Phải nhập ít nhất một giá trị.');
            return;
        }

        foreach ($this->variantAttributes as $attr) {
            if (strcasecmp($attr['name'], $name) === 0) {
                $this->addError('newAttributeName', 'Thuộc tính đã tồn tại, vui lòng nhập tên khác.');
                return;
            }
        }

        $this->variantAttributes[] = [
            'name' => $name,
            'values' => $values
        ];

        $this->newAttributeName = '';
        $this->newAttributeValues = '';
        $this->resetErrorBag(['newAttributeName', 'newAttributeValues']);
    }

    public function removeAttribute($index)
    {
        unset($this->variantAttributes[$index]);
        $this->variantAttributes = array_values($this->variantAttributes);
    }

    public function removeGeneratedVariant($index)
    {
        if (isset($this->generatedVariants[$index])) {
            unset($this->generatedVariants[$index]);
            $this->generatedVariants = array_values($this->generatedVariants);
            $this->expandedVariants = array_fill(0, count($this->generatedVariants), false);
        }
    }

    public function editAttribute($index)
    {
        $this->editingIndex = $index;
        $this->newAttributeName = $this->variantAttributes[$index]['name'];
        $this->newAttributeValues = implode('|', $this->variantAttributes[$index]['values']);
    }

    public function updateAttribute()
    {
        $name = trim($this->newAttributeName);
        $values = array_filter(array_map('trim', explode('|', $this->newAttributeValues)));

        if (!$name) {
            $this->addError('newAttributeName', 'Tên thuộc tính không được để trống.');
            return;
        }
        if (empty($values)) {
            $this->addError('newAttributeValues', 'Phải nhập ít nhất một giá trị.');
            return;
        }

        foreach ($this->variantAttributes as $index => $attr) {
            if ($index !== $this->editingIndex && strcasecmp($attr['name'], $name) === 0) {
                $this->addError('newAttributeName', 'Thuộc tính đã tồn tại, vui lòng nhập tên khác.');
                return;
            }
        }

        $this->variantAttributes[$this->editingIndex] = [
            'name' => $name,
            'values' => $values
        ];

        $this->newAttributeName = '';
        $this->newAttributeValues = '';
        $this->editingIndex = null;
        $this->resetErrorBag(['newAttributeName', 'newAttributeValues']);
    }

    public function moveUp($index)
    {
        if ($index > 0) {
            [$this->variantAttributes[$index - 1], $this->variantAttributes[$index]] =
                [$this->variantAttributes[$index], $this->variantAttributes[$index - 1]];
        }
    }

    public function moveDown($index)
    {
        if ($index < count($this->variantAttributes) - 1) {
            [$this->variantAttributes[$index + 1], $this->variantAttributes[$index]] =
                [$this->variantAttributes[$index], $this->variantAttributes[$index + 1]];
        }
    }

    public function generateVariantsFromAttributes()
    {
        if (empty($this->variantAttributes)) return;

        $combinations = [[]];

        foreach ($this->variantAttributes as $attr) {
            $attrName = $attr['name'];
            $values = $attr['values'];
            $newCombinations = [];

            foreach ($combinations as $combination) {
                foreach ($values as $value) {
                    $newCombinations[] = array_merge($combination, [
                        ['attribute' => $attrName, 'value' => $value],
                    ]);
                }
            }

            $combinations = $newCombinations;
        }

        $this->generatedVariants = [];

        foreach ($combinations as $combo) {
            $this->generatedVariants[] = [
                'attribute_values' => $combo,
                'price' => null,
                'quantity' => null,
                'limit' => null,
                'status' => 'available',
                'image' => null,
            ];
        }

        $this->expandedVariants = array_fill(0, count($this->generatedVariants), false);
    }

    public function addManualVariant()
    {
        // Validate
        foreach ($this->variantAttributes as $attr) {
            $attrName = $attr['name'];
            if (empty($this->manualAttributeValues[$attrName])) {
                $this->addError("manualAttributeValues.$attrName", "Hãy chọn giá trị cho $attrName.");
                return;
            }
        }
        // Ghép cặp
        $newPair = [];
        foreach ($this->variantAttributes as $attr) {
            $attrName = $attr['name'];
            $newPair[] = [
                'attribute' => $attrName,
                'value' => $this->manualAttributeValues[$attrName],
            ];
        }
        // Check duplicate
        foreach ($this->generatedVariants as $variant) {
            if ($variant['attribute_values'] == $newPair) {
                $this->addError('manualAttributeValues', 'Biến thể này đã tồn tại.');
                return;
            }
        }
        // Add
        array_unshift($this->generatedVariants, [
            'attribute_values' => $newPair,
            'price' => null,
            'quantity' => null,
            'limit' => null,
            'status' => 'available',
            'image' => null,
        ]);
        // Reset
        $this->manualAttributeValues = [];
        $this->resetErrorBag(['manualAttributeValues']);
        $this->variantCreateMode = '';
        // Reset accordion mở rộng
        $this->expandedVariants = array_fill(0, count($this->generatedVariants), false);
    }

    public function applyBulkAction()
    {
        if (empty($this->bulkAction)) {
            $this->addError('bulkAction', 'Vui lòng chọn một thao tác.');
            return;
        }

        $valueToApply = ($this->bulkAction === 'image') ? $this->bulkImage : $this->bulkValue;

        if (is_null($valueToApply) && $this->bulkAction !== 'image') {
            $this->addError('bulkValue', 'Vui lòng nhập hoặc chọn giá trị để áp dụng.');
            return;
        }
        if ($this->bulkAction === 'image' && !$this->bulkImage) {
            $this->addError('bulkImage', 'Vui lòng chọn một ảnh để áp dụng.');
            return;
        }

        $fieldMap = [
            'price' => 'price',
            'quantity' => 'quantity',
            'quantity_limit' => 'limit',
            'status' => 'status',
            'image' => 'image',
        ];

        $fieldToUpdate = $fieldMap[$this->bulkAction] ?? null;
        if (!$fieldToUpdate) return;

        foreach ($this->generatedVariants as $index => $variant) {
            // Trường hợp ảnh: chỉ gán cho biến thể đầu tiên nếu chưa có
            if ($this->bulkAction === 'image') {
                if (is_null($variant[$fieldToUpdate])) {
                    $this->generatedVariants[$index][$fieldToUpdate] = $valueToApply;
                    break;
                }
                continue;
            }

            // Nếu được chọn "áp dụng tất cả" => luôn gán
            // Nếu không => chỉ gán khi chưa có giá trị
            if ($this->applyToAll || is_null($variant[$fieldToUpdate])) {
                $this->generatedVariants[$index][$fieldToUpdate] = $valueToApply;
            }
        }

        $this->reset('bulkAction', 'bulkValue', 'bulkImage', 'applyToAll');
        $this->resetErrorBag(['bulkAction', 'bulkValue', 'bulkImage']);
    }

    public function createFood()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            session()->flash('error', 'Bạn không có quyền tạo món ăn.');
            return;
        }

        if ($this->productVariants && empty($this->generatedVariants)) {
            $this->addError('generatedVariants', 'Bạn chưa tạo biến thể.');
            return;
        }

        $validator = Validator::make(
            $this->all(),
            $this->rules(),
            $this->messages()
        );

        $validator->after(function ($validator) {
            if (!$this->productVariants) {
                if (!is_null($this->baseLimit) && $this->baseLimit < $this->baseQuantity) {
                    $validator->errors()->add('baseLimit', 'Giới hạn cơ bản không được nhỏ hơn số lượng.');
                }
            } else {
                foreach ($this->generatedVariants as $index => $variant) {
                    if (!is_null($variant['limit']) && $variant['limit'] < $variant['quantity']) {
                        $validator->errors()->add("generatedVariants.$index.limit", "Giới hạn không được nhỏ hơn số lượng.");
                    }
                }
            }
        });

        $validator->validate();

        $imagePath = $this->image ? $this->image->store('foods', 'public') : null;

        $food = FoodItem::create([
            'name' => $this->name,
            'description' => $this->description,
            'image' => $imagePath,
            'status' => $this->status,
        ]);

        if ($this->productVariants) {
            foreach ($this->generatedVariants as $variant) {
                $variantImagePath = $variant['image'] ? $variant['image']->store('food_variants', 'public') : null;

                $skuParts = [$this->name];
                foreach ($variant['attribute_values'] as $pair) {
                    $skuParts[] = $pair['attribute'];
                    $skuParts[] = $pair['value'];
                }
                $sku = $this->generateUniqueSku(implode('-', $skuParts));

                $foodVariant = FoodVariant::create([
                    'food_item_id' => $food->id,
                    'sku' => $sku,
                    'price' => $variant['price'],
                    'image' => $variantImagePath,
                    'quantity_available' => $variant['quantity'],
                    'limit' => $variant['limit'],
                    'status' => $variant['status'],
                ]);

                $attributeValueIds = [];

                foreach ($variant['attribute_values'] as $pair) {
                    $attribute = FoodAttribute::firstOrCreate([
                        'food_item_id' => $food->id,
                        'name' => $pair['attribute'],
                    ]);

                    $attributeValue = FoodAttributeValue::firstOrCreate([
                        'food_attribute_id' => $attribute->id,
                        'value' => $pair['value'],
                    ]);

                    $attributeValueIds[] = $attributeValue->id;
                }

                foreach ($attributeValueIds as $attributeValueId) {
                    FoodVariantAttributeValue::firstOrCreate([
                        'food_variant_id' => $foodVariant->id,
                        'food_attribute_value_id' => $attributeValueId,
                    ]);
                }
            }
        } else {
            $sku = $this->generateUniqueSku('default-sku');

            FoodVariant::create([
                'food_item_id' => $food->id,
                'sku' => $sku,
                'price' => $this->basePrice,
                'image' => null,
                'quantity_available' => $this->baseQuantity,
                'limit' => $this->baseLimit,
                'status' => $this->baseStatus,
            ]);
        }

        return redirect()->route('admin.foods.index')->with('success', 'Thêm món ăn thành công!');
    }

    private function generateUniqueSku($baseSku)
    {
        $sku = Str::slug($baseSku);
        $counter = 1;

        while (FoodVariant::where('sku', $sku)->exists()) {
            $sku = Str::slug($baseSku) . '-' . $counter;
            $counter++;
        }

        return $sku;
    }

    public function addExistingAttribute()
    {
        if (!$this->selectedAttributeId) {
            $this->addError('selectedAttributeId', 'Vui lòng chọn thuộc tính.');
            return;
        }

        $attribute = FoodAttribute::with('values')->find($this->selectedAttributeId);
        if (!$attribute) {
            $this->addError('selectedAttributeId', 'Thuộc tính không tồn tại.');
            return;
        }

        $values = FoodAttributeValue::whereIn('id', $this->selectedAttributeValueIds)->pluck('value')->toArray();
        if (empty($values)) {
            $this->addError('selectedAttributeValueIds', 'Vui lòng chọn ít nhất một giá trị.');
            return;
        }

        foreach ($this->variantAttributes as $attr) {
            if (strcasecmp($attr['name'], $attribute->name) === 0) {
                $this->addError('selectedAttributeId', 'Thuộc tính này đã được thêm.');
                return;
            }
        }

        $this->variantAttributes[] = [
            'name' => $attribute->name,
            'values' => $values,
        ];

        // Reset chọn
        $this->selectedAttributeId = null;
        $this->selectedAttributeValueIds = [];
        $this->resetErrorBag(['selectedAttributeId', 'selectedAttributeValueIds']);
    }

    public function resetEditing()
    {
        $this->reset(['editingIndex', 'newAttributeName', 'newAttributeValues']);
    }

    #[Title('Tạo món ăn - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        $this->availableAttributes = FoodAttribute::whereNull('food_item_id')
            ->with('values')
            ->get();

        return view('livewire.admin.foods.food-create');
    }
}
