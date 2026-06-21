<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'machine_slot_id', 'qty', 'price', 'is_dispensed', 'dispense_attempted_at', 'dispensed_at'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function slot()
    {
        return $this->belongsTo(MachineSlot::class, 'machine_slot_id');
    }
}
