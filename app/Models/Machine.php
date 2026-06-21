<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    use HasFactory;

    protected $fillable = ['unique_code', 'name', 'location', 'api_key', 'is_active', 'last_polling_at'];

    public function slots()
    {
        return $this->hasMany(MachineSlot::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
