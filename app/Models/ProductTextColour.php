<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Validator;

class ProductTextColour extends Model
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
            'product_id' => 'required|exists:products,id',
        ]);
    }

    /**
     * Get the ProductSite for the ProductTextColour.
     */
    public function productSite()
    {
        return $this->belongsTo(ProductSite::class);
    }
}
