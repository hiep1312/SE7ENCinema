<?php

namespace App\Livewire\Admin\Food;

use Livewire\Component;
use App\Models\FoodItem;
use Illuminate\Support\Facades\Storage;

class FoodDelete extends Component
{
    public $foodItem;


    public function render()
    {
        return view('livewire.admin.food.food-delete');
    }

    public function delete($id)
    {
        $this->foodItem = FoodItem::with('variants')->findOrFail($id);

        // Xoá ảnh của món ăn chính nếu có
        if ($this->foodItem->image && Storage::disk('public')->exists($this->foodItem->image)) {
            Storage::disk('public')->delete($this->foodItem->image);
        }

        // Xoá ảnh của từng biến thể (nếu có)
        foreach ($this->foodItem->variants as $variant) {
            if ($variant->image && Storage::disk('public')->exists($variant->image)) {
                Storage::disk('public')->delete($variant->image);
            }
            $variant->delete(); // Xoá bản ghi biến thể
        }

        // Cuối cùng xoá món ăn chính
        $this->foodItem->delete();

        session()->flash('success', 'Sản phẩm và các biến thể đã được xoá thành công.');
        return redirect()->route('admin.food.list');
    }
}
