<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Machine;
use App\Models\MachineSlot;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;

#[Layout('components.layouts.store')]
class Storefront extends Component
{
    public $machine;
    public $machineSlots;
    public $cart = []; // Array of slot_id => qty
    public $isCartOpen = false;

    public function mount($unique_code)
    {
        $this->machine = Machine::where('unique_code', $unique_code)->where('is_active', true)->firstOrFail();
        
        // Get active slots with stock > 0
        $this->machineSlots = MachineSlot::with('product')
            ->where('machine_id', $this->machine->id)
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->get();
    }

    public function addToCart($slotId)
    {
        $slot = $this->machineSlots->firstWhere('id', $slotId);
        if (!$slot) return;

        $currentQty = $this->cart[$slotId] ?? 0;
        
        if ($currentQty < $slot->stock) {
            $this->cart[$slotId] = $currentQty + 1;
        }
    }

    public function removeFromCart($slotId)
    {
        $currentQty = $this->cart[$slotId] ?? 0;
        
        if ($currentQty > 1) {
            $this->cart[$slotId] = $currentQty - 1;
        } else {
            unset($this->cart[$slotId]);
        }
        
        if (empty($this->cart)) {
            $this->isCartOpen = false;
        }
    }

    public function toggleCart()
    {
        if (count($this->cart) > 0) {
            $this->isCartOpen = !$this->isCartOpen;
        }
    }

    public function getTotalPriceProperty()
    {
        $total = 0;
        foreach ($this->cart as $slotId => $qty) {
            $slot = $this->machineSlots->firstWhere('id', $slotId);
            if ($slot) {
                $total += $slot->price * $qty;
            }
        }
        return $total;
    }

    public function checkout()
    {
        if (empty($this->cart)) return;

        DB::beginTransaction();
        try {
            $totalPrice = 0;
            $orderIdStr = 'ORD-' . $this->machine->unique_code . '-' . time() . '-' . rand(100, 999);
            
            $order = Order::create([
                'machine_id' => $this->machine->id,
                'midtrans_order_id' => $orderIdStr,
                'status' => 'pending',
                'total_price' => 0,
            ]);

            foreach ($this->cart as $slotId => $qty) {
                $slot = MachineSlot::lockForUpdate()->find($slotId);
                if (!$slot || $slot->stock < $qty) {
                    throw new \Exception("Stok tidak mencukupi untuk slot {$slot->slot_number}");
                }

                // Reserve stock
                $slot->decrement('stock', $qty);

                $order->items()->create([
                    'machine_slot_id' => $slot->id,
                    'qty' => $qty,
                    'price' => $slot->price,
                ]);

                $totalPrice += ($slot->price * $qty);
            }

            $order->update(['total_price' => $totalPrice]);

            DB::commit();

            // Set your Merchant Server Key
            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production', false);
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id' => $orderIdStr,
                    'gross_amount' => $totalPrice,
                ],
                'customer_details' => [
                    'first_name' => 'Pelanggan',
                    'last_name' => 'Mesin ' . $this->machine->unique_code,
                ]
            ];

            $snapToken = Snap::getSnapToken($params);

            // Clear cart
            $this->cart = [];
            $this->isCartOpen = false;
            
            // Refresh slots to show updated stock
            $this->mount($this->machine->unique_code);
            
            $this->dispatch('snap-pay', token: $snapToken);
            
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', $e->getMessage());
        }
    }

    public function getTotalItemsProperty()
    {
        return array_sum($this->cart);
    }

    public function render()
    {
        return view('livewire.storefront');
    }
}
