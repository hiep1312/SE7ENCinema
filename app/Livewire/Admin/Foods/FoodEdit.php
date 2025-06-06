<?php

namespace App\Livewire\Admin\Foods;

use Livewire\Component;
use App\Models\FoodItem;
use App\Models\FoodVariant;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class FoodEdit extends Component
{
    use WithFileUploads;

    public $foodItem;
    public $name = '';
    public $description = null;
    public $image = null;
    public $status = 'activate';
    public $quantityVariants = 0;
    public $variants = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'image' => 'nullable|image|max:20480',
        'status' => 'required|in:activate,discontinued',

        'variants.*.name' => 'required|string|max:255',
        'variants.*.price' => 'required|numeric|min:0',
        'variants.*.image' => 'nullable|image|max:20480',
        'variants.*.quantity' => 'required|integer|min:0',
        'variants.*.limit' => 'nullable|integer|min:0',
        'variants.*.status' => 'required|in:available,out_of_stock,hidden',
    ];

    protected $messages = [
        'name.required' => 'Tên món ăn là bắt buộc.',
        'name.max' => 'Tên món ăn không được vượt quá 255 ký tự.',
        'image.image' => 'Ảnh món ăn phải là một tệp hình ảnh hợp lệ.',
        'image.max' => 'Ảnh món ăn không được vượt quá 20MB.',
        'status.required' => 'Trạng thái món ăn là bắt buộc.',
        'status.in' => 'Trạng thái món ăn không hợp lệ.',

        'variants.*.name.required' => 'Tên biến thể là bắt buộc.',
        'variants.*.name.max' => 'Tên biến thể không được vượt quá 255 ký tự.',
        'variants.*.price.required' => 'Giá biến thể là bắt buộc.',
        'variants.*.price.numeric' => 'Giá biến thể phải là số.',
        'variants.*.price.min' => 'Giá biến thể không được nhỏ hơn 0.',
        'variants.*.image.image' => 'Ảnh biến thể phải là một tệp hình ảnh hợp lệ.',
        'variants.*.image.max' => 'Ảnh biến thể không được vượt quá 20MB.',
        'variants.*.quantity.required' => 'Số lượng là bắt buộc.',
        'variants.*.quantity.integer' => 'Số lượng phải là số nguyên.',
        'variants.*.quantity.min' => 'Số lượng không được nhỏ hơn 0.',
        'variants.*.limit.integer' => 'Giới hạn số lượng nhập phải là số nguyên.',
        'variants.*.limit.min' => 'Giới hạn số lượng nhập không được nhỏ hơn 0.',
        'variants.*.status.required' => 'Trạng thái biến thể là bắt buộc.',
        'variants.*.status.in' => 'Trạng thái biến thể không hợp lệ.',
    ];

    public function mount(FoodItem $food)
    {
        $this->foodItem = $food;
        $this->fill($food->only('name', 'description', 'status'));
        $this->getVariantsCurrent($food);
    }

    public function getVariantsCurrent(FoodItem $food){
        $this->variants = $food->variants->map(function ($variant) {
            return [
                'id' => $variant->id,
                'name' => $variant->name,
                'price' => $variant->price,
                'quantity' => $variant->quantity_available,
                'limit' => $variant->limit,
                'status' => $variant->status,
                'image' => null,
            ];
        })->toArray();
        $this->quantityVariants = count($this->variants);
    }

    public function generateVariants()
    {
        if(count($this->variants) < $this->quantityVariants) $this->variants = array_pad($this->variants, $this->quantityVariants, ['name' => '', 'price' => null, 'quantity' => null, 'limit' => null, 'status' => 'available', 'image' => null]);
        elseif(count($this->variants) > $this->quantityVariants) $this->variants = array_slice($this->variants, 0, $this->quantityVariants);
    }

    #[Computed]
    public function listVariants(){
        $this->generateVariants();

        return $this->variants;
    }

    public function removeVariant(int $index)
    {
        $this->variants = array_values(array_filter($this->variants, fn($i) => $i !== $index, ARRAY_FILTER_USE_KEY));
        $this->quantityVariants = count($this->variants);
    }

    public function updateFood()
    {
        $this->validate();

        $imagePath = $this->foodItem->image;
        if($this->image && $this->image instanceof UploadedFile):
            !Storage::disk('public')->exists($imagePath) ?: Storage::disk('public')->delete($imagePath);
            $imagePath = $this->image->store('foods', 'public');
        endif;

        $currentVariants = $this->foodItem->variants->pluck('id')->toArray();

        $variantsDeleted = array_diff($currentVariants, array_column($this->variants, 'id'));

        $this->foodItem->variants()->whereIn('id', $variantsDeleted)->delete();

        foreach ($this->variants as $index => $variant) {
            !$variant['limit'] ?: $this->validate([
                "variants.{$index}.quantity" => "integer|max:{$variant['limit']}"
            ], [
                "variants.{$index}.quantity.max" => "Số lượng đã vượt quá giới hạn số lượng nhập"
            ]);

            if(isset($variant['id']) && in_array($variant['id'], $currentVariants)) {
                $foodVariant = $this->foodItem->variants()->find($variant['id']);

                $imagePathVariant = $foodVariant->image;
                if($variant['image'] && $variant['image'] instanceof UploadedFile):
                    !Storage::disk('public')->exists($imagePathVariant) ?: Storage::disk('public')->delete($imagePathVariant);
                    $imagePathVariant = $variant['image']->store('food_variants', 'public');
                endif;

                $foodVariant->update([
                    'name' => $variant['name'],
                    'price' => $variant['price'],
                    'image' => $imagePathVariant,
                    'quantity_available' => $variant['quantity'],
                    'limit' => $variant['limit'],
                    'status' => $variant['status'],
                ]);
            }else{
                $imagePathVariant = null;
                !($variant['image'] && $variant['image'] instanceof UploadedFile) ?: $imagePathVariant = $variant['image']->store('food_variants', 'public');

                $foodVariant = FoodVariant::create([
                    'food_item_id' => $this->foodItem->id,
                    'name' => $variant['name'],
                    'price' => $variant['price'],
                    'image' => $imagePathVariant,
                    'quantity_available' => $variant['quantity'],
                    'limit' => $variant['limit'],
                    'status' => $variant['status'],
                ]);
            }
        }

        $this->foodItem->update([
            'name' => $this->name,
            'description' => $this->description,
            'image' => $imagePath,
            'status' => $this->status,
        ]);

        return redirect()->route('admin.foods.index')->with('success', 'Cập nhật món ăn thành công!');
    }

    #[Title('Chỉnh sửa món ăn - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        return view('livewire.admin.foods.food-edit');
    }
}
