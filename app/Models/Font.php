<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Font extends Model
{
    use HasFactory;

    public static function getAllFonts()
    {
        return self::all();
    }

    public static function storeFonts($request, $id)
    {
        $fonts = json_decode($request->fonts);

        foreach ($fonts as $font) {
            $fontModel = self::where('id', $font->id)->first();
            $fontModel->products()->attach($id);
        }

        return response()->json(['message' => 'Font Saved Successfully'], 200);
    }

    public static function deleteFont($productId, $fontId)
    {
        Product::find($productId)->fonts()->detach($fontId);

        return response()->json(['message' => 'Font Removed Successfully'], 200);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
