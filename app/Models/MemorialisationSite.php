<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Validator;

class MemorialisationSite extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'image',
        'memorialisation_id',
        'site_id',
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
            'memorialisation_id' => 'required|exists:memorialisations,id',
            'site_id' => 'required|exists:sites,id',
            'image' => 'required|image',
        ]);
    }

    /**
     * Get the MemorialisationSite's memorialisation.
     */
    public function memorialisation()
    {
        return $this->belongsTo(Memorialisations::class);
    }

    /**
     * Get the MemorialisationSite's site.
     */
    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}
