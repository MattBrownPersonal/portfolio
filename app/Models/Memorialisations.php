<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Validator;

class Memorialisations extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'image',
        'description',
    ];

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
        ]);
    }

    /**
     * Get the Memorialisation's products.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the Memorialisation's MemorialisationSites.
     */
    public function memorialisationSites()
    {
        return $this->hasMany(MemorialisationSite::class);
    }
}
