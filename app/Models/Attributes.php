<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Attributes extends Model
{
    use HasFactory;

    /**
     * Apply the validation rules to the request.
     *
     * @param  Illuminate\Http\Request  $request
     *
     * @return Validator
     */
    public static function validateInput($request)
    {
        return Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required',
        ]);
    }

    public static function addNewAttribute($request)
    {
        $validator = self::validateInput($request);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->toJson()], 422);
        }

        $attribute = new self;
        $attribute->name = $request->name;
        $attribute->price = $request->price;
        $attribute->attribute_types_id = $request->id;
        $attribute->save();
    }

    public static function updateAttribute($request, $id)
    {
        $validator = self::validateInput($request);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->toJson()], 422);
        }

        $attribute = self::find($id);
        $attribute->name = $request->name;
        $attribute->price = $request->price;
        $attribute->save();
    }

    public static function deleteAttribute($id)
    {
        $attribute = self::findOrFail($id);
        $attribute->delete();
    }

    public function attributeTypes()
    {
        return $this->belongsTo(AttributeTypes::class);
    }

    public function itemOrders()
    {
        return $this->belongsToMany(ItemOrder::class);
    }
}
