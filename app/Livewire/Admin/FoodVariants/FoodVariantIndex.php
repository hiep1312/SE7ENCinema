<?php

namespace App\Livewire\Admin\FoodVariants;

use Livewire\Component;
use App\Models\FoodVariant;
use Illuminate\Support\Facades\Storage;
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

        $variant = FoodVariant::onlyTrashed()->find($variantId);

        // Kiểm tra image, nếu có thì xóa
        !Storage::disk('public')->exists($variant->image) ?: Storage::disk('public')->delete($variant->image);

        $variant->forceDelete();
        session()->flash('success', 'Xóa vĩnh viễn biến thể thành công!');
    }

    #[Title('Danh sách biến thể - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        $query = FoodVariant::query()
            ->with('foodItem')
            ->when($this->showDeleted, fn($query) => $query->onlyTrashed())
            ->when($this->search, function ($query) {
                $query->withTrashed();
                $query->where(function ($subQuery) {
                    $subQuery->where('name', 'like', '%' . $this->search . '%')
                        ->orWhereHas('foodItem', function ($query) {
                            $query->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            });

        !$this->statusFilter ?: $query->where('status', $this->statusFilter);

        $foodVariants = $query->orderBy($this->showDeleted ? 'deleted_at' : 'created_at', $this->sortDateFilter)->paginate(20);

        return view('livewire.admin.food-variants.food-variant-index', compact('foodVariants'));
    }
}
