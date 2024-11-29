<?php

namespace App\Models;

use App\Scopes\HasActiveDataScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomTextField extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'type',
        'custom_image_id',
        'lineType',
        'left',
        'top',
        'fontSize',
        'angle',
        'radius',
        'centerRotation',
        'arc',
        'letterCount',
        'rectangleX',
        'rectangleY',
        'rectangleWidth',
        'rectangleHeight',
        'rectangleTextScale',
        'line_index',
        'custom_product_text_id',
    ];

    public function customImage()
    {
        return $this->belongsTo(CustomImage::class);
    }

    public function customProductText()
    {
        return $this->belongsTo(CustomProductText::class);
    }

    /**
     * The "boot" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        // using seperate scope class
        static::addGlobalScope(new HasActiveDataScope);
    }
}
