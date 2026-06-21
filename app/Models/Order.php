<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['machine_id', 'midtrans_order_id', 'status', 'total_price', 'snap_token'];

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
