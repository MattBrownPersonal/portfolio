<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Validator;

class ProductCategory extends Model
{
    use HasFactory;

    public static function validateInput($request)
    {
        return Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);
    }

    public static function createProductCategory($request)
    {
        $validator = self::validateInput($request);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->toJson()], 422);
        }

        $category = new self;
        $category->name = $request->name;
        $category->save();

        return response()->json(['message' => 'Category Saved Successfully'], 200);
    }

    public static function updateProductCategory($request, $id)
    {
        $validator = self::validateInput($request);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->toJson()], 422);
        }

        $category = self::findOrFail($id);
        $category->name = $request->name;
        $category->save();

        return response()->json(['message' => 'Category Updated Successfully'], 200);
    }

    public static function deleteProductCategory($id)
    {
        $category = self::findOrFail($id);
        $category->delete();

        return response()->json(['message' => 'Category Deleted Successfully'], 200);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
