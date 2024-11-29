<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    const UNFULFILLED = 1;

    const PROCESSING = 2;

    const FULFILLED = 3;

    const CANCELLED = 4;

    public function itemorder()
    {
        return $this->hasOne(ItemOrder::class);
    }

    public function itemordernote()
    {
        return $this->hasMany(ItemOrderNote::class);
    }

    public function order()
    {
        return $this->hasOne(Order::class);
    }
}
