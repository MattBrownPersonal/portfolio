<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomImage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'product_id',
        'image_id',
        'custom_image_id',
        'material_id',
        'layout',
    ];

    public function image()
    {
        return $this->belongsTo(Image::class);
    }

    public function customImage()
    {
        return $this->belongsTo(Image::class, 'custom_image_id', 'id');
    }

    public function productSite()
    {
        return $this->belongsTo(ProductSite::class);
    }

    public function customTextFields()
    {
        return $this->hasMany(CustomTextField::class);
    }

    public function additionalImage()
    {
        return $this->hasOne(AdditionalImage::class);
    }

    public function customImagePerspectiveDetails()
    {
        return $this->hasOne(CustomTextPerspective::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
