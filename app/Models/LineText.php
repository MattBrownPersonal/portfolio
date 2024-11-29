<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LineText extends Model
{
    use HasFactory;

    public function customProductText()
    {
        return $this->hasMany(CustomProductText::class);
    }
}
