<?php

namespace App\Livewire\Admin\FoodVariants;

use App\Models\FoodAttribute;
use App\Models\FoodAttributeValue;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\FoodVariant;
use App\Models\FoodItem;
use App\Models\FoodVariantAttributeValue;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class FoodVariantCreate extends Component
{
    use WithFileUploads;

    public $foodItemId = null;
    public $relatedVariants = [];
    public $image = null;

    public $variantAttributes = [];
    public $newAttributeName = '';
    public $newAttributeValues = '';

    public $generatedVariants = [];
    public string $variantTab = 'attributes';
    public $expandedVariants = [];
    public string $variantCreateMode = '';
    public $manualAttributeValues = [];
    public $editingIndex = null;

    protected $rules = [
        'foodItemId' => 'required|exists:food_items,id',
        'generatedVariants.*.price' => 'required|numeric|min:0',
        'generatedVariants.*.quantity' => 'required|integer|min:0',
        'generatedVariants.*.limit' => 'nullable|integer|min:0',
        'generatedVariants.*.status' => 'required|in:available,unavailable',
        'generatedVariants.*.image' => 'nullable|image|max:1024',
    ];

    protected $messages = [
        'foodItemId.required' => 'Vui lòng chọn món ăn gốc.',
        'foodItemId.exists' => 'Món ăn gốc không tồn tại.',
        'generatedVariants.*.price.numeric' => 'Giá phải là một số.',
        'generatedVariants.*.price.min' => 'Giá không được âm.',
        'generatedVariants.*.price.required' => 'Giá là bắt buộc.',
        'generatedVariants.*.quantity.required' => 'Số lượng là bắt buộc.',
        'generatedVariants.*.quantity.integer' => 'Số lượng phải là một số nguyên.',
        'generatedVariants.*.quantity.min' => 'Số lượng không được âm.',
        'generatedVariants.*.limit.integer' => 'Giới hạn phải là một số nguyên.',
        'generatedVariants.*.limit.min' => 'Giới hạn không được âm.',
        'generatedVariants.*.status.required' => 'Trạng thái là bắt buộc.',
        'generatedVariants.*.status.in' => 'Trạng thái không hợp lệ.',
        'generatedVariants.*.image.image' => 'Ảnh biến thể phải là định dạng ảnh hợp lệ.',
        'generatedVariants.*.image.max' => 'Kích thước ảnh biến thể không được vượt quá 1MB.',
    ];
    

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

        $lowerValues = array_map('mb_strtolower', $values);
        if (count($lowerValues) !== count(array_unique($lowerValues))) {
            $this->addError('newAttributeValues', 'Các giá trị không được trùng nhau (không phân biệt hoa thường).');
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

    public function removeGeneratedVariant($index)
    {
        if (isset($this->generatedVariants[$index])) {
            unset($this->generatedVariants[$index]);
            $this->generatedVariants = array_values($this->generatedVariants);
            $this->expandedVariants = array_fill(0, count($this->generatedVariants), false);
        }
    }

    public function removeAttribute($index)
    {
        unset($this->variantAttributes[$index]);
        $this->variantAttributes = array_values($this->variantAttributes);
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

        if ($this->editingIndex !== null && $name && count($values)) {
            $this->variantAttributes[$this->editingIndex] = [
                'name' => $name,
                'values' => $values
            ];
            $this->newAttributeName = '';
            $this->newAttributeValues = '';
            $this->editingIndex = null;
        }
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
        foreach ($this->variantAttributes as $attr) {
            $attrName = $attr['name'];
            if (empty($this->manualAttributeValues[$attrName])) {
                $this->addError("manualAttributeValues.$attrName", "Hãy chọn giá trị cho $attrName.");
                return;
            }
        }

        $newPair = [];
        foreach ($this->variantAttributes as $attr) {
            $attrName = $attr['name'];
            $newPair[] = [
                'attribute' => $attrName,
                'value' => $this->manualAttributeValues[$attrName],
            ];
        }

        foreach ($this->generatedVariants as $variant) {
            if ($variant['attribute_values'] == $newPair) {
                $this->addError('manualAttributeValues', 'Biến thể này đã tồn tại.');
                return;
            }
        }

        array_unshift($this->generatedVariants, [
            'attribute_values' => $newPair,
            'price' => null,
            'quantity' => null,
            'limit' => null,
            'status' => 'available',
            'image' => null,
        ]);

        $this->manualAttributeValues = [];
        $this->resetErrorBag(['manualAttributeValues']);
        $this->variantCreateMode = '';

        $this->expandedVariants = array_fill(0, count($this->generatedVariants), false);
    }


    public function createVariant()
    {
        $this->validate();

        if (empty($this->generatedVariants)) {
            $this->addError('generatedVariants', 'Bạn chưa tạo biến thể.');
            return;
        }

        foreach ($this->generatedVariants as $index => $variant) {
            $quantity = (int) $variant['quantity'];
            $limit = trim((string) $variant['limit']) === '' ? null : (int) $variant['limit'];

            if (!is_null($limit) && $limit < $quantity) {
                $this->addError("generatedVariants.$index.limit", "Giới hạn không thể nhỏ hơn số lượng.");
                return;
            }
        }

        $food = FoodItem::findOrFail($this->foodItemId);
        $existingVariants = FoodVariant::where('food_item_id', $food->id)->get();

        foreach ($existingVariants as $existingVariant) {
            $hasAttributeValue = FoodVariantAttributeValue::where('food_variant_id', $existingVariant->id)->exists();
            if (!$hasAttributeValue) {
                $existingVariant->forceDelete();
            }
        }

        foreach ($this->generatedVariants as $variant) {
            $variantImagePath = $variant['image'] ? $variant['image']->store('food_variants', 'public') : null;

            $skuParts = [$food->name];
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

    public function resetEditing() {
        $this->reset(['editingIndex', 'newAttributeName', 'newAttributeValues']);
    }

    #[Title('Tạo biến thể - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        $foods = FoodItem::all();
        return view('livewire.admin.food-variants.food-variant-create', compact('foods'));
    }
}
