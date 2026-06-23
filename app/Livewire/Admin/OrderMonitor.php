<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Order;

#[Layout('components.layouts.admin')]
class OrderMonitor extends Component
{
    public function render()
    {
        $orders = Order::with('machine')->latest()->limit(50)->get();
        return view('livewire.admin.order-monitor', compact('orders'));
    }
}
