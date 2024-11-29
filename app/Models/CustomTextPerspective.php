<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomTextPerspective extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'custom_image_id',
        'ax',
        'ay',
        'bx',
        'by',
        'cx',
        'cy',
        'dy',
        'dx',
    ];

    public function customImage()
    {
        return $this->belongsTo(CustomImage::class);
    }
}
