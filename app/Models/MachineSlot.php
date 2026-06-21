<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MachineSlot extends Model
{
    use HasFactory;

    protected $fillable = ['machine_id', 'product_id', 'slot_number', 'price', 'stock', 'capacity', 'is_active'];

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
