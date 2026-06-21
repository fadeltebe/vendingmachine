<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Product;

#[Layout('components.layouts.admin')]
class ProductManager extends Component
{
    public $products;
    
    public function mount()
    {
        $this->products = Product::all();
    }

    public function render()
    {
        return view('livewire.admin.product-manager');
    }
}
