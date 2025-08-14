<?php

namespace App\Livewire\Admin\Foods;

use Livewire\Component;
use App\Models\FoodItem;
use App\Models\FoodAttribute;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Str;

class FoodEdit extends Component
{
    use WithFileUploads;

    public FoodItem $foodItem;

    // --- Trạng thái chính của món ăn ---
    public string $name = '';
    public ?string $description = null;
    public $image = null;
    public string $status = 'activate';

    // --- Trạng thái quản lý thuộc tính ---
    public array $variantAttributes = [];
    public ?int $editingAttributeIndex = null;
    public string $newAttributeName = '';
    public string $newAttributeValues = '';

    // --- Trạng thái quản lý biến thể ---
    public array $variants = [];

    public Collection $availableAttributes;
    public ?int $selectedAttributeId = null;
    public array $selectedAttributeValueIds = [];

    public array $manualAttributeValues = [];
    public bool $showManualVariant = false;

    public string $bulkTarget = '';
    public ?float $bulkPrice = null;
    public ?int $bulkQuantity = null;
    public ?int $bulkLimit = null;
    public ?string $bulkStatus = null;
    public bool $bulkReplace = false;

    protected function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:activate,discontinued',
            'image' => 'nullable|image|max:2048',
            'newAttributeName' => 'required_with:newAttributeValues|string|max:255',
            'newAttributeValues' => 'required_with:newAttributeName|string',
        ];

        foreach ($this->variants as $index => $variant) {
            $rules["variants.{$index}.price"] = 'required|numeric|min:0';
            $rules["variants.{$index}.quantity_available"] = 'required|integer|min:0';
            $rules["variants.{$index}.limit"] = [
                'nullable',
                'integer',
                'min:0',
                function ($attribute, $value, $fail) use ($variant) {
                    if ($value !== null && $variant['quantity_available'] !== null && $value < $variant['quantity_available']) {
                        $fail('Giới hạn không được nhỏ hơn số lượng.');
                    }
                },
            ];
            $rules["variants.{$index}.status"] = 'required|in:available,out_of_stock,hidden';
            if (isset($variant['image']) && $variant['image'] instanceof UploadedFile) {
                $rules["variants.{$index}.image"] = 'image|max:2048';
            }
        }
        return $rules;
    }

    protected array $messages = [
        'name.required' => 'Tên món ăn là bắt buộc.',
        'image.image' => 'Tệp tải lên phải là hình ảnh.',
        'image.max' => 'Ảnh đại diện không được lớn hơn 2MB.',
        'variants.*.price.required' => 'Giá là bắt buộc.',
        'variants.*.price.min' => 'Giá không được âm.',
        'variants.*.quantity_available.required' => 'Số lượng là bắt buộc.',
        'variants.*.quantity_available.min' => 'Số lượng không được âm.',
        'variants.*.status.required' => 'Trạng thái là bắt buộc.',
        'newAttributeName.required_with' => 'Tên thuộc tính không được để trống.',
        'newAttributeValues.required_with' => 'Giá trị thuộc tính không được để trống.',
    ];

    public function mount(FoodItem $food): void
    {
        $this->foodItem = $food->load(['variants.attributeValues.attribute']);
        $this->fill($this->foodItem->only(['name', 'description', 'status']));
        $this->initializeAttributesAndVariants();

        $this->availableAttributes = FoodAttribute::with('values')
            ->whereNull('food_item_id')
            ->get();

        foreach ($this->variants as $i => $variant) {
            $this->variants[$i]['price'] = number_format((int) $variant['price'], 0, ',', '.');
        }
    }

    public function formatPrice($index)
    {
        $value = preg_replace('/\D/', '', $this->variants[$index]['price']); // bỏ ký tự không phải số
        $this->variants[$index]['price'] = number_format((int) $value, 0, ',', '.');
    }


    public function applyBulkValues(): void
    {
        foreach ($this->variants as $index => &$variant) {
            if ($this->bulkReplace || !$variant['price']) {
                $variant['price'] = $this->bulkPrice;
            }
            if ($this->bulkReplace || !$variant['quantity_available']) {
                $variant['quantity_available'] = $this->bulkQuantity;
            }
            if ($this->bulkReplace || !$variant['limit']) {
                $variant['limit'] = $this->bulkLimit;
            }
            if ($this->bulkReplace || !$variant['status']) {
                $variant['status'] = $this->bulkStatus ?? 'available';
            }
        }
        session()->flash('success_general', 'Đã áp dụng giá trị cho các biến thể.');
    }

    public function addManualVariant(): void
    {
        foreach ($this->variantAttributes as $attr) {
            if (empty($this->manualAttributeValues[$attr['name']])) {
                $this->addError("manualAttributeValues.{$attr['name']}", "Vui lòng chọn {$attr['name']}");
                return;
            }
        }

        $newCombo = collect($this->manualAttributeValues)->sortKeys()->all();
        foreach ($this->variants as $variant) {
            $existingCombo = collect($variant['attribute_values'])->mapWithKeys(fn($item) => [
                $item['attribute'] => $item['value']
            ])->sortKeys()->all();

            if ($newCombo == $existingCombo) {
                session()->flash('attribute_error', 'Biến thể này đã tồn tại.');
                return;
            }
        }

        $this->variants[] = [
            'price' => null,
            'quantity_available' => null,
            'limit' => null,
            'status' => 'available',
            'attribute_values' => collect($this->manualAttributeValues)
                ->map(fn($val, $attr) => ['attribute' => $attr, 'value' => $val])
                ->values()
                ->toArray(),
            'image' => null,
        ];

        $this->manualAttributeValues = [];
        session()->flash('attribute_success', 'Thêm biến thể thủ công thành công.');
    }

    public function addExistingAttribute(): void
    {
        if (!$this->selectedAttributeId || empty($this->selectedAttributeValueIds)) {
            $this->addError('selectedAttributeId', 'Hãy chọn thuộc tính và ít nhất một giá trị.');
            return;
        }

        $attr = $this->availableAttributes->find($this->selectedAttributeId);
        if (!$attr) return;

        $isDuplicate = collect($this->variantAttributes)->contains(function ($item) use ($attr) {
            return $item['id'] === $attr->id || strcasecmp($item['name'], $attr->name) === 0;
        });

        if ($isDuplicate) {
            $this->addError('selectedAttributeId', 'Thuộc tính này đã được thêm.');
            return;
        }

        $values = $attr->values
            ->whereIn('id', $this->selectedAttributeValueIds)
            ->pluck('value')
            ->unique()
            ->values()
            ->all();

        $newAttribute = [
            'id' => null,
            'name' => $attr->name,
            'values' => $values,
        ];

        $this->variantAttributes[] = $newAttribute;

        $this->reset(['selectedAttributeId', 'selectedAttributeValueIds']);
    }

    private function initializeAttributesAndVariants(): void
    {
        $activeAttributeValues = $this->foodItem->variants->flatMap(fn($variant) => $variant->attributeValues)->unique('id');

        $this->variantAttributes = $activeAttributeValues->groupBy('attribute.name')
            ->map(fn($values, $name) => [
                'id' => $values->first()->attribute->id,
                'name' => $name,
                'values' => $values->pluck('value')->unique()->sort()->values()->all(),
            ])->values()->toArray();

        $this->variants = $this->foodItem->variants->map(fn($variant) => [
            'id' => $variant->id,
            'price' => $variant->price,
            'quantity_available' => $variant->quantity_available,
            'limit' => $variant->limit,
            'status' => $variant->status,
            'existing_image' => $variant->image,
            'image' => null,
            'attribute_values' => $variant->attributeValues->map(fn($v) => [
                'attribute' => $v->attribute->name,
                'value' => $v->value
            ])->toArray(),
        ])->toArray();
    }

    public function addOrUpdateAttribute(): void
    {
        $this->validate([
            'newAttributeName' => 'required|string|max:255',
            'newAttributeValues' => 'required|string'
        ]);

        $values = collect(explode(',', $this->newAttributeValues))
            ->map(fn($value) => trim($value))
            ->filter()
            ->unique()
            ->values();

        if ($values->isEmpty()) {
            $this->addError('newAttributeValues', 'Cần có ít nhất một giá trị hợp lệ.');
            return;
        }

        $newName = trim($this->newAttributeName);
        $isDuplicate = collect($this->variantAttributes)
            ->some(fn($attr, $index) => strcasecmp($attr['name'], $newName) === 0 && $index !== $this->editingAttributeIndex);

        if ($isDuplicate) {
            $this->addError('newAttributeName', 'Thuộc tính này đã tồn tại.');
            return;
        }

        $newAttribute = [
            'id' => $this->editingAttributeIndex !== null ? $this->variantAttributes[$this->editingAttributeIndex]['id'] : null,
            'name' => $newName,
            'values' => $values->all(),
        ];

        if ($this->editingAttributeIndex !== null) {
            $this->variantAttributes[$this->editingAttributeIndex] = $newAttribute;
            session()->flash('attribute_success', 'Cập nhật thuộc tính thành công!');
        } else {
            $this->variantAttributes[] = $newAttribute;
            session()->flash('attribute_success', 'Thêm thuộc tính thành công!');
        }

        $this->cancelEditAttribute();
    }

    public function editAttribute(int $index): void
    {
        if (!isset($this->variantAttributes[$index])) return;
        $attribute = $this->variantAttributes[$index];
        $this->editingAttributeIndex = $index;
        $this->newAttributeName = $attribute['name'];
        $this->newAttributeValues = implode(', ', $attribute['values']);
    }

    public function cancelEditAttribute(): void
    {
        $this->reset(['editingAttributeIndex', 'newAttributeName', 'newAttributeValues']);
        $this->resetErrorBag(['newAttributeName', 'newAttributeValues']);
    }

    public function removeAttribute(int $index): void
    {
        unset($this->variantAttributes[$index]);
        $this->variantAttributes = array_values($this->variantAttributes);
        session()->flash('attribute_success', 'Đã xóa thuộc tính. Hãy tạo lại các biến thể.');
    }

    public function generateVariants(): void
    {
        if (empty($this->variantAttributes)) {
            $this->variants = [];
            return;
        }

        $oldVariantsMap = collect($this->variants)->keyBy(function ($variant) {
            return collect($variant['attribute_values'])
                ->sortBy('attribute')
                ->map(fn($a) => Str::slug($a['attribute']) . ':' . Str::slug($a['value']))
                ->implode(';');
        });

        $attributes = array_column($this->variantAttributes, 'values');
        $combinations = $this->getCartesianProduct($attributes);

        $this->variants = collect($combinations)->map(function ($combination) use ($oldVariantsMap) {
            $attributeValues = collect($combination)->map(fn($value, $key) => [
                'attribute' => $this->variantAttributes[$key]['name'],
                'value' => $value,
            ])->all();

            $foundData = $this->findBestMatchingVariant($attributeValues, $oldVariantsMap);

            return [
                'id' => $foundData['id'] ?? null,
                'price' => $foundData['price'] ?? null,
                'quantity_available' => $foundData['quantity_available'] ?? null,
                'limit' => $foundData['limit'] ?? null,
                'status' => $foundData['status'] ?? 'available',
                'existing_image' => $foundData['existing_image'] ?? null,
                'image' => null,
                'attribute_values' => $attributeValues,
            ];
        })->all();

        session()->flash('success_general', 'Đã tạo các biến thể! Vui lòng điền thông tin chi tiết.');
    }

    protected function findBestMatchingVariant(array $newAttributeValues, Collection $oldVariantsMap)
    {
        $newAttrsSimple = collect($newAttributeValues)->pluck('value', 'attribute')->sortKeys()->all();
        $newAttrsCount = count($newAttrsSimple);
        $exactKey = collect($newAttrsSimple)->map(fn($v, $k) => Str::slug($k) . ':' . Str::slug($v))->implode(';');

        if ($oldVariantsMap->has($exactKey)) {
            return $oldVariantsMap->get($exactKey);
        }

        foreach ($oldVariantsMap as $oldVariant) {
            $oldAttrsSimple = collect($oldVariant['attribute_values'])->pluck('value', 'attribute')->sortKeys()->all();
            $oldAttrsCount = count($oldAttrsSimple);

            if ($oldAttrsCount === 0) continue;

            $largerAttrs = ($newAttrsCount > $oldAttrsCount) ? $newAttrsSimple : $oldAttrsSimple;
            $smallerAttrs = ($newAttrsCount > $oldAttrsCount) ? $oldAttrsSimple : $newAttrsSimple;

            if (empty(array_diff_key($smallerAttrs, $largerAttrs))) {
                $match = true;
                foreach ($smallerAttrs as $key => $value) {
                    if (!isset($largerAttrs[$key]) || $largerAttrs[$key] !== $value) {
                        $match = false;
                        break;
                    }
                }
                if ($match) return $oldVariant;
            }
        }
        return null;
    }

    protected function getCartesianProduct(array $arrays): array
    {
        $result = [[]];
        foreach ($arrays as $key => $values) {
            $append = [];
            foreach ($result as $product) {
                foreach ($values as $value) {
                    $product[$key] = $value;
                    $append[] = $product;
                }
            }
            $result = $append;
        }
        return $result;
    }

    public function removeVariant($index): void
    {
        unset($this->variants[$index]);
        $this->variants = array_values($this->variants);
    }

    public function save()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            $this->saveFoodItemDetails();
            $attributeValueMap = $this->syncAttributesAndValues();
            $this->syncVariants($attributeValueMap);

            DB::commit();
            session()->flash('success', 'Cập nhật món ăn thành công!');
            return $this->redirect(route('admin.foods.index'), navigate: true);
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Đã xảy ra lỗi khi lưu: ' . $e->getMessage());
        }
    }

    private function saveFoodItemDetails(): void
    {
        $this->foodItem->update([
            'name' => trim($this->name),
            'description' => $this->description,
            'status' => $this->status,
        ]);

        if ($this->image instanceof UploadedFile) {
            if ($this->foodItem->image) {
                Storage::disk('public')->delete($this->foodItem->image);
            }
            $this->foodItem->image = $this->image->store('foods', 'public');
            $this->foodItem->save();
        }
    }

    // Tối ưu: Tách logic đồng bộ thuộc tính và giá trị
    private function syncAttributesAndValues(): Collection
    {
        $activeAttrIds = [];
        $activeValueIdsByAttr = [];

        foreach ($this->variantAttributes as $attrData) {
            $attribute = FoodAttribute::updateOrCreate(
                ['id' => $attrData['id']],
                ['food_item_id' => $this->foodItem->id, 'name' => trim($attrData['name'])]
            );
            $activeAttrIds[] = $attribute->id;

            $activeValueIds = [];
            foreach ($attrData['values'] as $value) {
                $attrValue = $attribute->values()->updateOrCreate(
                    ['value' => trim($value)],
                    ['value' => trim($value)]
                );
                $activeValueIds[] = $attrValue->id;
            }
            $activeValueIdsByAttr[$attribute->id] = $activeValueIds;
        }

        // Xóa các thuộc tính không còn sử dụng
        $this->foodItem->attributes()->whereNotIn('id', $activeAttrIds)->delete();
        // Xóa các giá trị thuộc tính không còn sử dụng
        foreach ($activeValueIdsByAttr as $attributeId => $valueIds) {
            FoodAttribute::find($attributeId)->values()->whereNotIn('id', $valueIds)->delete();
        }

        // Tải lại quan hệ và tạo map để dùng trong syncVariants
        $this->foodItem->load('attributes.values');
        return $this->foodItem->attributes->flatMap(fn($attr) => $attr->values)
            ->keyBy(fn($v) => Str::slug($v->attribute->name) . ':' . Str::slug($v->value));
    }

    // Tối ưu: Tách logic đồng bộ biến thể
    private function syncVariants(Collection $attrValueMap): void
    {
        $activeVariantIds = [];

        foreach ($this->variants as $vData) {
            $sku = '';
            $attempt = 0;
            $isSkuUnique = false;

            // Vòng lặp để sinh SKU duy nhất
            while (!$isSkuUnique) {
                $sku = $this->generateSku($vData, $attempt);

                // Kiểm tra trong database: cả các bản ghi đang hoạt động và đã soft-delete
                $existingVariant = $this->foodItem->variants()
                    ->withTrashed()
                    ->where('sku', $sku)
                    ->when(isset($vData['id']), fn($q) => $q->where('id', '!=', $vData['id']))
                    ->first();

                if (!$existingVariant) {
                    $isSkuUnique = true;
                } else {
                    $attempt++;
                }
            }

            $variantData = [
                'price' => $vData['price'],
                'quantity_available' => $vData['quantity_available'],
                'limit' => $vData['limit'] ?: null,
                'status' => $vData['status'],
                'sku' => $sku,
            ];

            if (isset($vData['image']) && $vData['image'] instanceof UploadedFile) {
                if (!empty($vData['existing_image'])) Storage::disk('public')->delete($vData['existing_image']);
                $variantData['image'] = $vData['image']->store('food_variants', 'public');
            }

            // Tìm hoặc tạo biến thể: ưu tiên tìm theo ID để cập nhật nếu đã có.
            // Nếu không có ID hoặc ID không khớp, sẽ tạo mới với SKU đã được kiểm tra.
            $variant = $this->foodItem->variants()->updateOrCreate(
                ['id' => $vData['id']],
                $variantData
            );

            // Đảm bảo biến thể được liên kết với các giá trị thuộc tính
            $valueIdsToSync = collect($vData['attribute_values'])
                ->map(function ($pair) use ($attrValueMap) {
                    $key = Str::slug($pair['attribute']) . ':' . Str::slug($pair['value']);
                    return $attrValueMap->get($key)?->id;
                })->filter()->all();

            $variant->attributeValues()->sync($valueIdsToSync);
            $activeVariantIds[] = $variant->id;
        }

        // Xóa các biến thể không còn trong danh sách (soft-delete chúng)
        $this->foodItem->variants()->whereNotIn('id', $activeVariantIds)->each(function ($variant) {
            if ($variant->image) {
                Storage::disk('public')->delete($variant->image);
            }
            $variant->delete();
        });
    }

    // Trong class FoodEdit
    private function generateSku(array $variantData, int $attempt = 0): string
    {
        $parts = [Str::slug(trim($this->name))];
        $sortedValues = collect($variantData['attribute_values'])->sortBy('attribute')->pluck('value');

        foreach ($sortedValues as $value) {
            $parts[] = Str::slug(trim($value));
        }

        $baseSku = implode('-', $parts);

        // Nếu có số lần thử, thêm hậu tố vào SKU
        if ($attempt > 0) {
            return $baseSku . '-' . $attempt;
        }

        return $baseSku;
    }

    #[Title('Chỉnh sửa món ăn - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.admin.foods.food-edit');
    }
}
