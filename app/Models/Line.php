<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Line extends Model
{
    use HasFactory;

    public function itemOrders()
    {
        return $this->belongsTo(ItemOrder::class);
    }
}
