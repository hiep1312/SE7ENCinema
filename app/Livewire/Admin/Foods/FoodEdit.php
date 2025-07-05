<?php

namespace App\Livewire\Admin\Foods;

use Livewire\Component;
use App\Models\FoodItem;
use App\Models\FoodVariant;
use App\Models\FoodAttribute;
use App\Models\FoodAttributeValue;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Str;

#[Title('Chỉnh sửa món ăn - SE7ENCinema')]
#[Layout('components.layouts.admin')]
class FoodEdit extends Component
{
    use WithFileUploads;

    public FoodItem $foodItem;

    // Food Item Properties
    public string $name = '';
    public ?string $description = null;
    public $image = null;
    public string $status = 'activate';

    // Attribute Management Properties
    public array $variantAttributes = [];
    public ?int $editingAttributeIndex = null;
    public string $newAttributeName = '';
    public string $newAttributeValues = '';

    // Variant Management Properties
    public array $variants = [];

    protected function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:activate,discontinued',
            'image' => 'nullable|image|max:2048',
        ];

        foreach ($this->variants as $index => $variant) {
            $rules["variants.$index.price"] = 'required|numeric|min:0';
            $rules["variants.$index.quantity_available"] = 'required|integer|min:0';

            $rules["variants.$index.limit"] = [
                'nullable',
                'integer',
                'min:0',
                function ($attribute, $value, $fail) use ($variant) {
                    if ($value !== null && $value < $variant['quantity_available']) {
                        $fail('Giới hạn không được nhỏ hơn số lượng.');
                    }
                },
            ];

            $rules["variants.$index.status"] = 'required|in:available,out_of_stock,hidden';

            if (isset($variant['image']) && $variant['image'] instanceof UploadedFile) {
                $rules["variants.$index.image"] = 'nullable|image|max:2048';
            }
        }

        if ($this->image instanceof UploadedFile) {
            $rules['image'] = 'nullable|image|max:2048';
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
        'variants.*.limit.min' => 'Giới hạn không được âm.',
        'variants.*.limit.integer' => 'Giới hạn phải là số nguyên.',
        'variants.*.status.required' => 'Trạng thái là bắt buộc.',
        'variants.*.image.max' => 'Ảnh biến thể không được lớn hơn 2MB.',
        'newAttributeName.required' => 'Tên thuộc tính không được để trống.',
        'newAttributeValues.required' => 'Giá trị thuộc tính không được để trống.',
    ];

    public function mount(FoodItem $food): void
    {

        $this->foodItem = $food->load([
            'variants' => function ($query) {
                $query->with(['attributeValues.attribute']);
            }
        ]);

        $this->name = $this->foodItem->name;
        $this->description = $this->foodItem->description;
        $this->status = $this->foodItem->status;


        $activeAttributeValues = $this->foodItem->variants->flatMap(function ($variant) {
            return $variant->attributeValues;
        })->unique('id');

        $groupedByAttributeName = $activeAttributeValues->groupBy('attribute.name');

        $this->variantAttributes = $groupedByAttributeName->map(function ($values, $attributeName) {

            $attributeId = $values->first()->attribute->id;

            return [
                'id' => $attributeId,
                'name' => $attributeName,
                'values' => $values->pluck('value')->unique()->sort()->values()->all(),
            ];
        })->values()->toArray();


        // Logic để hiển thị danh sách biến thể ở dưới vẫn đúng vì nó bắt đầu từ $this->foodItem->variants
        // là danh sách các biến thể đang hoạt động.
        $this->variants = $this->foodItem->variants->map(
            fn($variant) => [
                'id' => $variant->id,
                'price' => $variant->price,
                'quantity_available' => $variant->quantity_available,
                'limit' => $variant->limit,
                'status' => $variant->status,
                'existing_image' => $variant->image,
                'image' => null,
                'attribute_values' => $variant->attributeValues->map(
                    fn($value) => ['attribute' => $value->attribute->name, 'value' => $value->value]
                )->toArray(),
            ]
        )->toArray();
    }

    // --- QUẢN LÝ THUỘC TÍNH ---
    public function addOrUpdateAttribute(): void
    {
        $this->validate(['newAttributeName' => 'required|string|max:255', 'newAttributeValues' => 'required|string']);

        $values = array_filter(array_unique(array_map('trim', explode(',', $this->newAttributeValues))));
        if (empty($values)) {
            $this->addError('newAttributeValues', 'Cần có ít nhất một giá trị hợp lệ.');
            return;
        }

        $newName = trim($this->newAttributeName);

        // Kiểm tra trùng (trừ chính nó nếu đang sửa)
        foreach ($this->variantAttributes as $index => $attr) {
            if (strcasecmp($attr['name'], $newName) === 0 && $index !== $this->editingAttributeIndex) {
                $this->addError('newAttributeName', 'Thuộc tính này đã tồn tại.');
                return;
            }
        }

        $newAttribute = [
            'id' => $this->editingAttributeIndex !== null ? $this->variantAttributes[$this->editingAttributeIndex]['id'] : null,
            'name' => $newName,
            'values' => array_values($values),
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

    // --- TẠO BIẾN THỂ ---
    public function generateVariants(): void
    {
        if (empty($this->variantAttributes)) {
            $this->variants = [];
            return;
        }

        // Tạo một map các biến thể cũ để tra cứu
        $oldVariantsMap = collect($this->variants)->mapWithKeys(function ($variant) {
            $key = collect($variant['attribute_values'])
                ->sortBy('attribute')
                ->map(fn($a) => Str::slug($a['attribute']) . ':' . Str::slug($a['value']))
                ->implode(';');
            return [$key => $variant];
        });

        $attributes = array_map(fn($attr) => $attr['values'], $this->variantAttributes);
        $combinations = $this->getCartesianProduct($attributes);
        $newVariants = [];

        foreach ($combinations as $combination) {
            $attributeValues = [];
            foreach ($combination as $key => $value) {
                $attributeValues[] = ['attribute' => $this->variantAttributes[$key]['name'], 'value' => $value];
            }

            // Tìm biến thể cũ phù hợp nhất để kế thừa dữ liệu
            $foundData = $this->findBestMatchingVariant($attributeValues, $oldVariantsMap);

            $newVariants[] = [
                'id' => $foundData['id'] ?? null,
                'price' => $foundData['price'] ?? null,
                'quantity_available' => $foundData['quantity_available'] ?? null,
                'limit' => $foundData['limit'] ?? null,
                'status' => $foundData['status'] ?? 'available',
                'existing_image' => $foundData['existing_image'] ?? null,
                'image' => null,
                'attribute_values' => $attributeValues,
            ];
        }

        $this->variants = $newVariants;
        session()->flash('success_general', 'Đã tạo các biến thể! Vui lòng điền thông tin chi tiết.');
    }

    // Trong Livewire component Admin/Foods/EditFood.php

    protected function findBestMatchingVariant(array $newAttributeValues, $oldVariantsMap)
    {
        // Tạo key chuẩn hóa cho biến thể mới để tìm kiếm khớp hoàn hảo
        $newKey = collect($newAttributeValues)
            ->sortBy('attribute')
            ->map(fn($a) => Str::slug($a['attribute']) . ':' . Str::slug($a['value']))
            ->implode(';');

        // 1. Ưu tiên khớp hoàn hảo bằng key đã chuẩn hóa (nếu tập thuộc tính không thay đổi)
        if (isset($oldVariantsMap[$newKey])) {
            return $oldVariantsMap[$newKey];
        }

        // Nếu không có khớp hoàn hảo, tìm kiếm sự phù hợp một phần tốt nhất
        $bestMatch = null;
        $maxMatchCount = -1; // Số lượng thuộc tính trùng khớp tối đa
        $bestMatchTotalAttributes = -1; // Tổng số thuộc tính của biến thể cũ khớp tốt nhất

        $newPairs = collect($newAttributeValues)->mapWithKeys(fn($a) => [Str::slug($a['attribute']) => Str::slug($a['value'])]);
        $newAttributeCount = count($newPairs);

        foreach ($oldVariantsMap as $oldVariant) {
            $oldPairs = collect($oldVariant['attribute_values'])->mapWithKeys(fn($a) => [Str::slug($a['attribute']) => Str::slug($a['value'])]);

            $currentMatchCount = 0;
            foreach ($newPairs as $newAttrKey => $newAttrValue) {
                // Đếm số lượng thuộc tính khớp giữa biến thể mới và biến thể cũ
                if (isset($oldPairs[$newAttrKey]) && $oldPairs[$newAttrKey] === $newAttrValue) {
                    $currentMatchCount++;
                }
            }

            // Điều kiện để xem xét biến thể cũ này là một "best match"
            // - Số lượng khớp phải lớn hơn match hiện tại
            // - Hoặc, nếu số lượng khớp bằng nhau, ưu tiên biến thể cũ có ít thuộc tính hơn (gần với biến thể mới hơn)
            if ($currentMatchCount > $maxMatchCount) {
                $maxMatchCount = $currentMatchCount;
                $bestMatch = $oldVariant;
                $bestMatchTotalAttributes = count($oldPairs);
            } elseif ($currentMatchCount === $maxMatchCount && count($oldPairs) < $bestMatchTotalAttributes) {
                // Nếu có cùng số lượng khớp, chọn biến thể cũ có tổng số thuộc tính ít hơn (gần với biến thể mới hơn)
                $bestMatchTotalAttributes = count($oldPairs);
                $bestMatch = $oldVariant;
            }
        }

        // Chỉ trả về bestMatch nếu có ít nhất một thuộc tính khớp
        return $maxMatchCount > 0 ? $bestMatch : null;
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

    // --- LƯU TRỮ ---
    public function save()
    {
        $this->validate();
        DB::beginTransaction();
        try {
            $this->foodItem->update(['name' => $this->name, 'description' => $this->description, 'status' => $this->status]);
            if ($this->image instanceof UploadedFile) {
                if ($this->foodItem->image) Storage::disk('public')->delete($this->foodItem->image);
                $this->foodItem->image = $this->image->store('food-items', 'public');
                $this->foodItem->save();
            }

            $activeAttrIds = [];
            foreach ($this->variantAttributes as $attrData) {
                $attribute = FoodAttribute::updateOrCreate(['id' => $attrData['id']], ['food_item_id' => $this->foodItem->id, 'name' => $attrData['name']]);
                $activeAttrIds[] = $attribute->id;
                $activeValueIds = [];
                foreach ($attrData['values'] as $valueData) {
                    $value = FoodAttributeValue::updateOrCreate(['food_attribute_id' => $attribute->id, 'value' => $valueData]);
                    $activeValueIds[] = $value->id;
                }
                $attribute->values()->whereNotIn('id', $activeValueIds)->delete();
            }
            $this->foodItem->attributes()->whereNotIn('id', $activeAttrIds)->delete();

            $this->foodItem->load('attributes.values');
            $attrValueMap = $this->foodItem->attributes->flatMap(fn($attr) => $attr->values)->keyBy(fn($val) => Str::slug($val->attribute->name) . ':' . Str::slug($val->value));

            $activeVariantIds = [];
            foreach ($this->variants as $vData) {
                $variantData = ['price' => $vData['price'], 'quantity_available' => $vData['quantity_available'], 'limit' => $vData['limit'] ?: null, 'status' => $vData['status'], 'sku' => $this->generateSku($vData)];
                if (isset($vData['image']) && $vData['image'] instanceof UploadedFile) {
                    if (!empty($vData['existing_image'])) Storage::disk('public')->delete($vData['existing_image']);
                    $variantData['image'] = $vData['image']->store('food-variants', 'public');
                }
                $variant = $this->foodItem->variants()->updateOrCreate(['id' => $vData['id']], $variantData);
                $valueIdsToSync = [];
                foreach ($vData['attribute_values'] as $pair) {
                    $key = Str::slug($pair['attribute']) . ':' . Str::slug($pair['value']);
                    if (isset($attrValueMap[$key])) $valueIdsToSync[] = $attrValueMap[$key]->id;
                }
                $variant->attributeValues()->sync($valueIdsToSync);
                $activeVariantIds[] = $variant->id;
            }

            $this->foodItem->variants()->whereNotIn('id', $activeVariantIds)->get()->each(function ($variant) {
                if ($variant->image) {
                    Storage::disk('public')->delete($variant->image);
                }

                // Giải phóng SKU
                $variant->sku = $variant->sku . '-deleted-' . $variant->id;
                $variant->save();

                // XÓA BẢN GHI LIÊN KẾT pivot
                $variant->attributeValues()->detach();

                // Soft delete variant
                $variant->delete();
            });


            DB::commit();
            session()->flash('success', 'Cập nhật món ăn thành công!');
            return $this->redirect(route('admin.foods.index'), navigate: true);
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Đã xảy ra lỗi khi cập nhật: ' . $e->getMessage());
        }
    }

    protected function generateSku(array $variant): string
    {
        $parts = [Str::slug($this->name)];
        $sortedAttributes = collect($variant['attribute_values'])->sortBy('attribute')->all();
        foreach ($sortedAttributes as $pair) $parts[] = Str::slug($pair['value']);
        return implode('-', $parts);
    }

    public function render()
    {
        return view('livewire.admin.foods.food-edit');
    }
}
