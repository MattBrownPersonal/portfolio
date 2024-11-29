<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeTypes extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'product_id',
        'visible',
    ];

    public static function addNewAttributeType($request)
    {
        $attributeType = new self;
        $attributeType->name = $request->name;
        $attributeType->save();
    }

    public static function editAttributeType($request, $id)
    {
        $attributeType = self::find($id);
        $attributeType->name = $request->name;
        $attributeType->save();
    }

    public static function deleteAttributeType($id)
    {
        $attributeType = self::findOrFail($id);
        $attributeType->delete();
    }

    public function attributes()
    {
        return $this->hasMany(Attributes::class);
    }

    public function allAttributes()
    {
        return $this->hasMany(Attributes::class);
    }

    public function productSite()
    {
        return $this->belongsToMany(ProductSite::class);
    }

    public function itemOrders()
    {
        return $this->belongsToMany(ItemOrder::class);
    }

    public function attributeProduct()
    {
        return $this->hasMany(AttributeProduct::class);
    }
}
