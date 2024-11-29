<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    public static function storeNewMaterial($request)
    {
        if ($request->hasFile('image')) {
            $path = Image::storeMaterialImage($request);
            $material = new self;
            $material->type = $request->type;
            $material->imageurl = $path;
            $material->price = $request->price;
            $material->product_id = $request->product_id;
            $material->save();
        }

        return response()->json(['message' => 'Article Saved Successfully'], 200);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function cutomImages()
    {
        return $this->hasMany(CustomImage::class);
    }
}
