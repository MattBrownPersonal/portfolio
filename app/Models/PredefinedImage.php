<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PredefinedImage extends Model
{
    use HasFactory;

    public static function storeNewPredefinedImage($request)
    {
        if ($request->hasFile('image')) {
            $path = Image::storePredefinedImage($request);

            $preDefinedImage = new self;
            $preDefinedImage->name = $request->name;
            $preDefinedImage->imageurl = $path;
            $preDefinedImage->product_id = $request->product_id;
            $preDefinedImage->price = $request->price;
            $preDefinedImage->save();

            return response()->json(['message' => 'Image Saved Successfully'], 200);
        } else {
            return response()->json(['message' => 'Image not supplied'], 422);
        }
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
