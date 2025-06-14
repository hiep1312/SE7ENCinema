<?php

namespace App\Livewire\Admin\FoodVariants;

use Livewire\Component;
use App\Models\FoodVariant;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use SE7ENCinema\scAlert;
use Livewire\WithPagination;

class FoodVariantIndex extends Component
{
    use WithPagination, scAlert;

    public $search = '';
    public $showDeleted = false;
    public $statusFilter = '';
    public $sortDateFilter = 'desc';

    protected $paginationTheme = 'bootstrap';


    public function resetFilters()
    {
        $this->reset(['search', 'statusFilter', 'sortDateFilter']);
        $this->resetPage();
    }

    public function deleteVariant(array $status, int $variantId)
    {
        if (!$status['isConfirmed']) return;

        $variant = FoodVariant::find($variantId);
        if (!$variant) {
            session()->flash('error', 'Biến thể không tồn tại.');
            return;
        }

        $variant->delete();
        session()->flash('success', 'Xóa biến thể thành công!');
    }

    public function restoreVariant(int $variantId)
    {
        $variant = FoodVariant::onlyTrashed()
            ->with('foodItem')
            ->find($variantId);

        if (!$variant) {
            session()->flash('error', 'Biến thể không tồn tại.');
            return;
        }

        if (!$variant->foodItem || $variant->foodItem->trashed()) {
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
        if (!$variant) {
            session()->flash('error', 'Biến thể không tồn tại.');
            return;
        }

        $variant->forceDelete();
        session()->flash('success', 'Xóa vĩnh viễn biến thể thành công!');
    }


    #[Title('Danh sách Biến thể - SE7ENCinema')]
    #[Layout('components.layouts.admin')]

    public function render()
    {
        $query = FoodVariant::query()
            ->with(['foodItem' => function ($q) {
                $q->withTrashed();
            }])
            ->when($this->showDeleted, function ($q) {
                $q->onlyTrashed();
            })
            ->when($this->search, function ($q) {
                $q->where(function ($subQuery) {
                    $subQuery->where('name', 'like', '%' . $this->search . '%')
                        ->orWhereHas('foodItem', function ($foodQuery) {
                            $foodQuery->withTrashed()->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->statusFilter, function ($q) {
                $q->where('status', $this->statusFilter);
            })
            ->when($this->showDeleted, function ($q) {
                $q->orderBy('deleted_at', 'desc');
            }, function ($q) {
                $q->orderBy('created_at', $this->sortDateFilter);
            });


        $foodVariants = $query->paginate(10);

        return view('livewire.admin.food-variants.food-variant-index', compact('foodVariants'));
    }
}
