<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

#[Layout('components.layouts.admin')]
class ProductManager extends Component
{
    use WithFileUploads;

    public $products;
    
    // Form fields
    public $productId;
    public $name;
    public $description;
    public $base_price;
    public $image;
    public $oldImage;
    
    // UI state
    public $isModalOpen = false;
    public $isEditMode = false;

    public function mount()
    {
        $this->loadProducts();
    }

    public function loadProducts()
    {
        $this->products = Product::all();
    }

    public function openModal()
    {
        $this->resetInputFields();
        $this->isModalOpen = true;
        $this->isEditMode = false;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->productId = null;
        $this->name = '';
        $this->description = '';
        $this->base_price = '';
        $this->image = null;
        $this->oldImage = null;
        $this->resetValidation();
    }

    public function save()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
        ];

        if (!$this->isEditMode || $this->image) {
            $rules['image'] = 'nullable|image|max:2048'; // 2MB Max
        }

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'base_price' => $this->base_price,
        ];

        if ($this->image) {
            $data['image'] = $this->image->store('products', 'public');
            
            // Delete old image if updating
            if ($this->isEditMode && $this->oldImage) {
                Storage::disk('public')->delete($this->oldImage);
            }
        }

        if ($this->isEditMode) {
            $product = Product::findOrFail($this->productId);
            $product->update($data);
        } else {
            Product::create($data);
        }

        $this->closeModal();
        $this->loadProducts();
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $this->productId = $id;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->base_price = $product->base_price;
        $this->oldImage = $product->image;
        
        $this->isModalOpen = true;
        $this->isEditMode = true;
    }

    public function delete($id)
    {
        $product = Product::findOrFail($id);
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        $this->loadProducts();
    }

    public function render()
    {
        return view('livewire.admin.product-manager');
    }
}
