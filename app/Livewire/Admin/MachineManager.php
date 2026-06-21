<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Machine;

#[Layout('components.layouts.admin')]
class MachineManager extends Component
{
    public $machines;
    
    public function mount()
    {
        $this->machines = Machine::all();
    }

    public function render()
    {
        return view('livewire.admin.machine-manager');
    }
}
