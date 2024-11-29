<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'price',
        'image_url',
        'status_id',
        'item_type',
        'product_name',
        'supplier_name',
        'attribute_type',
        'attribute_name',
        'product_id',
    ];

    public static function amendOrderStatus($statuses)
    {
        return min($statuses);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function image()
    {
        return $this->hasOne(Image::class);
    }

    public function attributeTypes()
    {
        return $this->belongsToMany(AttributeTypes::class);
    }

    public function attributes()
    {
        return $this->belongsToMany(Attributes::class);
    }

    public function lines()
    {
        return $this->hasMany(Line::class);
    }
}
