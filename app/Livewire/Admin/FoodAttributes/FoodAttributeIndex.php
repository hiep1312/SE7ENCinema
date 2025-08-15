<?php

namespace App\Livewire\Admin\FoodAttributes;

use App\Models\FoodAttribute;
use App\Models\FoodAttributeValue;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

class FoodAttributeIndex extends Component
{
    public $search = '';

    public $expandedAttributeId = null;
    public $editingAttributeId = null;
    public $editingAttributeName = '';
    public $editingAttributeDescription = '';
    public $editingValueId = null;
    public $editingValue = '';

    public $newName = '';
    public $newDescription = '';
    public $newValues = '';

    public $showCreateForm = false;

    protected $rules = [
        'newName' => 'required|string|max:255',
        'newDescription' => 'nullable|string',
        'newValues' => 'nullable|string',
    ];

    protected $messages = [
        'newName.required' => 'Tên thuộc tính là bắt buộc.',
        'newName.string' => 'Tên thuộc tính phải là chuỗi ký tự.',
        'newName.max' => 'Tên thuộc tính không được vượt quá 255 ký tự.',
        'newDescription.string' => 'Mô tả phải là chuỗi ký tự.',
        'newValues.string' => 'Danh sách giá trị phải là chuỗi ký tự.',
    ];

    public function create()
    {
        $this->validate();

        if (FoodAttribute::where('name', $this->newName)->exists()) {
            $this->addError('newName', 'Tên thuộc tính đã tồn tại.');
            return;
        }

        $attribute = FoodAttribute::create([
            'name' => $this->newName,
            'description' => $this->newDescription,
        ]);

        if ($this->newValues) {
            $values = array_filter(array_map('trim', explode(',', $this->newValues)));
            foreach ($values as $val) {
                if (FoodAttributeValue::where('food_attribute_id', $attribute->id)->where('value', $val)->exists()) continue;

                FoodAttributeValue::create([
                    'food_attribute_id' => $attribute->id,
                    'value' => $val,
                ]);
            }
        }

        session()->flash('success', 'Thêm thuộc tính thành công!');
        $this->reset(['newName', 'newDescription', 'newValues']);
    }

    public function toggleExpand($id)
    {
        $this->expandedAttributeId = $this->expandedAttributeId === $id ? null : $id;
        $this->resetValueEdit();
    }

    public function toggleCreateForm()
    {
        $this->showCreateForm = !$this->showCreateForm;
        $this->reset(['newName', 'newDescription', 'newValues']);
    }

    public function editAttribute($id)
    {
        $attribute = FoodAttribute::findOrFail($id);
        $this->editingAttributeId = $id;
        $this->editingAttributeName = $attribute->name;
        $this->editingAttributeDescription = $attribute->description;
    }

    public function saveAttribute()
    {
        $this->validate(['editingAttributeName' => 'required|string|max:255'], [
            'editingAttributeName.required' => 'Tên thuộc tính không được để trống.',
            'editingAttributeName.string'   => 'Tên thuộc tính phải là chuỗi ký tự.',
            'editingAttributeName.max'      => 'Tên thuộc tính không được vượt quá 255 ký tự.'
        ]);

        if (FoodAttribute::where('name', $this->editingAttributeName)
            ->where('id', '!=', $this->editingAttributeId)
            ->exists()) {
            $this->addError('editingAttributeName', 'Tên thuộc tính đã tồn tại.');
            return;
        }

        $attribute = FoodAttribute::findOrFail($this->editingAttributeId);
        $attribute->update([
            'name' => $this->editingAttributeName,
            'description' => $this->editingAttributeDescription,
        ]);

        session()->flash('success', 'Đã cập nhật thuộc tính.');
        $this->resetAttributeEdit();
    }

    public function deleteAttribute($id)
    {
        FoodAttribute::findOrFail($id)->delete();
        session()->flash('success', 'Đã xoá thuộc tính.');
    }

    public function saveValue($attributeId)
    {
        $this->validate(['editingValue' => 'required|string|max:255'], [
            'editingValue.required' => 'Giá trị thuộc tính không được để trống.',
            'editingValue.string'   => 'Giá trị thuộc tính phải là chuỗi ký tự.',
            'editingValue.max'      => 'Giá trị thuộc tính không được vượt quá 255 ký tự.'
        ]);

        $valueInput = trim($this->editingValue);

        $query = FoodAttributeValue::where('food_attribute_id', $attributeId)
            ->where('value', $valueInput);

        if ($this->editingValueId) {
            $query->where('id', '!=', $this->editingValueId);
        }
        if ($query->exists()) {
            $this->addError('editingValue', 'Giá trị này đã tồn tại.');
            return;
        }

        if ($this->editingValueId) {
            $value = FoodAttributeValue::findOrFail($this->editingValueId);
            $value->value = $valueInput;
            $value->save();
            session()->flash('success', 'Đã cập nhật giá trị.');
        } else {
            FoodAttributeValue::create([
                'food_attribute_id' => $attributeId,
                'value' => $valueInput,
            ]);
            session()->flash('success', 'Đã thêm giá trị.');
        }

        $this->resetValueEdit();
    }

    public function editValue($id)
    {
        $value = FoodAttributeValue::findOrFail($id);
        $this->editingValueId = $id;
        $this->editingValue = $value->value;
    }

    public function deleteValue($id)
    {
        FoodAttributeValue::findOrFail($id)->delete();
        session()->flash('success', 'Đã xoá giá trị.');
    }

    public function resetAttributeEdit()
    {
        $this->editingAttributeId = null;
        $this->editingAttributeName = '';
        $this->editingAttributeDescription = '';
    }

    public function resetValueEdit()
    {
        $this->editingValueId = null;
        $this->editingValue = '';
    }

    #[Title('Quản lý thuộc tính - SE7ENCinema')]
    #[Layout('components.layouts.admin')]
    public function render()
    {
        $query = FoodAttribute::query()
            ->with('values')
            ->whereNull('food_item_id')
            ->latest();

        if (trim($this->search)) {
            $searchTerm = '%' . trim($this->search) . '%';

            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                    ->orWhere('description', 'like', $searchTerm);
            });
        }

        $attributes = $query->get();

        return view('livewire.admin.food-attributes.food-attribute-index', compact('attributes'));
    }
}
