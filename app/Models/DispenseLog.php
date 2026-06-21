<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DispenseLog extends Model
{
    use HasFactory;

    protected $fillable = ['order_item_id', 'is_successful', 'error_message'];

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
}
