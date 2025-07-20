<?php

namespace App\Livewire\Admin\FoodVariants;

use Livewire\Component;
use App\Models\FoodVariant;
use App\Models\FoodAttributeValue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;


class FoodVariantIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $showDeleted = false;
    public $statusFilter = '';
    public $sortDateFilter = 'desc';

    public function resetFilters()
    {
        $this->reset(['search', 'statusFilter', 'sortDateFilter']);
        $this->resetPage();
    }

    public function deleteVariant(array $status, int $variantId)
    {
        if (!$status['isConfirmed']) return;

        $variant = FoodVariant::find($variantId);

        $variant->delete();
        session()->flash('success', 'Xóa biến thể thành công!');
    }

    public function restoreVariant(int $variantId)
    {
        $variant = FoodVariant::onlyTrashed()->find($variantId);

        if (!$variant->foodItem->exists() || $variant->foodItem->trashed()) {
            session()->flash('error', 'Không thể khôi phục biến thể vì món ăn liên kết đã bị xóa hoặc không tồn tại.');
            return;
        }

        $variant->restore();
        session()->flash('success', 'Khôi phục biến thể thành công!');
    }


    public function forceDeleteVariant(array $status, int $variantId)
    {
        if (!$status['isConfirmed']) return;

        $variant = FoodVariant::onlyTrashed()
            ->with(['attributeValues.attribute', 'foodItem.variants', 'foodItem.attributes'])
            ->find($variantId);

        if (!$variant) {
            session()->flash('error', 'Không tìm thấy biến thể.');
            return;
        }

        $foodItem = $variant->foodItem;

        // Nếu chỉ còn 1 biến thể thì KHÔNG CHO XOÁ
        $remainingVariants = $foodItem->variants()->count();
        if ($remainingVariants <= 1) {
            session()->flash('error', 'Không thể xoá biến thể cuối cùng. Vui lòng xoá sản phẩm nếu muốn xoá hết.');
            return;
        }

        // Xoá file ảnh nếu có
        if ($variant->image && Storage::disk('public')->exists($variant->image)) {
            Storage::disk('public')->delete($variant->image);
        }

        // Lưu các attributeValueId liên quan
        $attributeValueIds = $variant->attributeValues->pluck('id')->toArray();

        // Xoá bản ghi pivot trước
        DB::table('food_variant_attribute_values')
            ->where('food_variant_id', $variant->id)
            ->delete();

        // Xoá cứng biến thể
        $variant->forceDelete();

        // Xoá các giá trị attributeValue nếu không còn được dùng
        foreach ($attributeValueIds as $attrValueId) {
            $isUsed = DB::table('food_variant_attribute_values')
                ->where('food_attribute_value_id', $attrValueId)
                ->exists();

            if (!$isUsed) {
                $attrValue = FoodAttributeValue::find($attrValueId);
                if ($attrValue) {
                    $attrValue->delete();
                }
            }
        }

        session()->flash('success', 'Xoá vĩnh viễn biến thể & dữ liệu liên quan thành công!');
    }




    #[Title('Danh sách biến thể - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        $query = FoodVariant::query()
            ->with(['foodItem', 'attributeValues.attribute'])
            ->when($this->showDeleted, fn($query) => $query->onlyTrashed())
            ->when($this->search, function ($query) {
                $query->withTrashed();
                $query->where(function ($subQuery) {
                    $subQuery->where('sku', 'like', '%' . $this->search . '%')
                        ->orWhereHas('foodItem', function ($query) {
                            $query->where('name', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('attributeValues', function ($query) {
                            $query->where('value', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('attributeValues', function ($query) {
                            $query->where('value', 'like', '%' . $this->search . '%')
                                ->orWhereHas('attribute', function ($q) {
                                    $q->where('name', 'like', '%' . $this->search . '%');
                                });
                        });
                });
            });


        !$this->statusFilter ?: $query->where('status', $this->statusFilter);

        $foodVariants = $query->orderBy($this->showDeleted ? 'deleted_at' : 'created_at', $this->sortDateFilter)->paginate(20);

        return view('livewire.admin.food-variants.food-variant-index', compact('foodVariants'));
    }
}
