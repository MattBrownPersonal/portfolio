<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LineTypes extends Model
{
    use HasFactory;

    public function ProductSite()
    {
        return $this->belongsToMany(ProductSite::class);
    }

    public function customProductText()
    {
        return $this->belongsTo(CustomProductText::class);
    }
}
