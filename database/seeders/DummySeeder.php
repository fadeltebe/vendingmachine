<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Machine;
use App\Models\Product;
use App\Models\MachineSlot;

class DummySeeder extends Seeder
{
    public function run(): void
    {
        $machine = Machine::create(['unique_code' => 'M01', 'name' => 'Mesin Utama', 'location' => 'Lobby', 'api_key' => '12345']);
        $p1 = Product::create(['name' => 'Coca Cola', 'base_price' => 8000]);
        $p2 = Product::create(['name' => 'Pocari Sweat', 'base_price' => 9500]);
        
        MachineSlot::create(['machine_id' => $machine->id, 'product_id' => $p1->id, 'slot_number' => '1', 'price' => 8000, 'stock' => 5]);
        MachineSlot::create(['machine_id' => $machine->id, 'product_id' => $p2->id, 'slot_number' => '2', 'price' => 9500, 'stock' => 3]);
    }
}
