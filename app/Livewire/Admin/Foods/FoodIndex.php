<?php

namespace App\Livewire\Admin\Foods;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\FoodItem;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use SE7ENCinema\scAlert;
use Illuminate\Support\Str;

class FoodIndex extends Component
{
    use WithPagination, scAlert;

    public $search = '';
    public $showDeleted = false;
    public $statusFilter = '';
    public $sortDateFilter = 'desc';

    public function deleteFood(array $status, int $foodId, bool $statusDeleteVariants = false)
    {
        if(!$status['isConfirmed']) return;
        $food = FoodItem::find($foodId);

        // Kiểm tra xem có biến thể liên kết không
        if(!$statusDeleteVariants && $food->variants()->exists()) {
            $this->scConfirm("Phát hiện có biến thể đang liên kết!", "Bạn có chắc chắn vẫn muốn xóa không? Hành động này sẽ xóa tất cả các biến thể liên kết với món ăn, cùng với món ăn {$food->name} này!", 'warning', "deleteFood({$foodId}, {$status['isConfirmed']})");
            return;
        }

        // Soft delete món ăn và biến thể món ăn
        $food->variants()->delete();
        $food->delete();
        session()->flash('success', 'Xóa món ăn và biến thể của nó thành công!');
    }

    public function restoreFood(int $foodId)
    {
        $food = FoodItem::onlyTrashed()->find($foodId);

        $food->variants()->restore();
        $food->restore();
        session()->flash('success', 'Khôi phục món ăn và biến thể của nó thành công!');
    }

    public function forceDeleteFood(array $status, int $foodId, bool $statusDeleteVariants = false)
    {
        if(!$status['isConfirmed']) return;
        $food = FoodItem::onlyTrashed()->find($foodId);

        // Kiểm tra xem có biến thể liên kết không
        if(!$statusDeleteVariants && $food->variants()->onlyTrashed()->count() > 0) {
            $this->scConfirm("Phát hiện có biến thể đã bị xóa đang liên kết!", "Bạn có chắc chắn vẫn muốn xóa không? Hành động này sẽ xóa tất cả các biến thể đã bị xóa đang liên kết với món ăn, cùng với món ăn {$food->name} này! Và KHÔNG THỂ HOÀN TÁC!", 'warning', "deleteFood({$foodId}, {$status['isConfirmed']})");
            return;
        }

        // Xóa cứng món ăn và biến thể món ăn
        $food->variants()->onlyTrashed()->forceDelete();
        $food->forceDelete();
        session()->flash('success', 'Xóa vĩnh viễn món ăn và biến thể của nó thành công!');
    }

    public function resetFilters()
    {
        $this->reset(['search', 'statusFilter', 'sortDateFilter']);
        $this->resetPage();
    }

    #[Title('Danh sách món ăn - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        $query = FoodItem::query()->when($this->search, function($query) {
            $query->withTrashed();
            $query->where(function($query){
                $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        })->with(['variants' => function($query) {
            $this->search ? $query->withTrashed() : (!$this->showDeleted ?: $query->onlyTrashed());
        }]);

        if($this->showDeleted){
            $query->onlyTrashed()->orderBy('deleted_at', $this->sortDateFilter);
        }else{
            $query->orderBy('created_at', $this->sortDateFilter);
            !$this->statusFilter ?: $query->where('status', $this->statusFilter);
        }

        $foodItems = $query->paginate(20);

        return view('livewire.admin.foods.food-index', compact('foodItems'));
    }
}
