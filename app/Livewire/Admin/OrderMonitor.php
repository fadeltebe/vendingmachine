<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Order;

#[Layout('components.layouts.admin')]
class OrderMonitor extends Component
{
    public $orders;
    
    public function mount()
    {
        $this->orders = Order::with('machine')->latest()->limit(50)->get();
    }

    public function render()
    {
        return view('livewire.admin.order-monitor');
    }
}
