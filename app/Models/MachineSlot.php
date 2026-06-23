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

    protected static function booted()
    {
        static::saved(function ($slot) {
            if ($slot->isDirty('product_id') && $slot->getOriginal('product_id')) {
                $oldProduct = Product::find($slot->getOriginal('product_id'));
                if ($oldProduct) {
                    $oldProduct->update(['stock' => $oldProduct->slots()->sum('stock')]);
                }
            }
            if ($slot->product_id && $slot->product) {
                $slot->product->update(['stock' => $slot->product->slots()->sum('stock')]);
            }
        });

        static::deleted(function ($slot) {
            if ($slot->product_id && $slot->product) {
                $slot->product->update(['stock' => $slot->product->slots()->sum('stock')]);
            }
        });
    }
}
