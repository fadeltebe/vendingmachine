<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Machine;
use App\Models\MachineSlot;
use App\Models\Product;

#[Layout('components.layouts.admin')]
class MachineSlotManager extends Component
{
    public Machine $machine;
    public $machineSlots;
    public $products;

    public $slotId;
    public $product_id;
    public $slot_number;
    public $price;
    public $stock;
    public $capacity;
    public $is_active = true;

    public $isModalOpen = false;
    public $isEditMode = false;

    public function mount(Machine $machine)
    {
        $this->machine = $machine;
        $this->products = Product::all();
        $this->loadSlots();
    }

    public function loadSlots()
    {
        $this->machineSlots = $this->machine->slots()->with('product')->orderBy('slot_number')->get();
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
        $this->slotId = null;
        $this->product_id = '';
        $this->slot_number = '';
        $this->price = '';
        $this->stock = 0;
        $this->capacity = 10;
        $this->is_active = true;
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate([
            'product_id' => 'required|exists:products,id',
            'slot_number' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0|lte:capacity',
            'capacity' => 'required|numeric|min:1',
            'is_active' => 'boolean',
        ]);

        $data = [
            'machine_id' => $this->machine->id,
            'product_id' => $this->product_id,
            'slot_number' => $this->slot_number,
            'price' => $this->price,
            'stock' => $this->stock,
            'capacity' => $this->capacity,
            'is_active' => $this->is_active,
        ];

        if ($this->isEditMode) {
            $slot = MachineSlot::findOrFail($this->slotId);
            $slot->update($data);
        } else {
            MachineSlot::create($data);
        }

        $this->closeModal();
        $this->loadSlots();
    }

    public function edit($id)
    {
        $slot = MachineSlot::findOrFail($id);
        $this->slotId = $id;
        $this->product_id = $slot->product_id;
        $this->slot_number = $slot->slot_number;
        $this->price = $slot->price;
        $this->stock = $slot->stock;
        $this->capacity = $slot->capacity;
        $this->is_active = (bool) $slot->is_active;

        $this->isModalOpen = true;
        $this->isEditMode = true;
    }

    public function delete($id)
    {
        $slot = MachineSlot::findOrFail($id);
        $slot->delete();
        $this->loadSlots();
    }

    public function render()
    {
        return view('livewire.admin.machine-slot-manager');
    }
}
