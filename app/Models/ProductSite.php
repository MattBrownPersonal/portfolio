<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSite extends Model
{
    use HasFactory;

    public function attributeProductSite()
    {
        return $this->hasMany(AttributeProductSite::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function images()
    {
        return $this->belongsToMany(Image::class);
    }

    public function lineTypes()
    {
        return $this->hasMany(LineTypes::class);
    }
}
