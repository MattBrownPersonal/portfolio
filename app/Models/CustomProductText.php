<?php

namespace App\Models;

use App\Scopes\HasNonRemovedDataScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomProductText extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'product_id',
        'line_types',
        'custom_index',
        'is_custom',
        'custom_message_text',
        'removed',
        'order_index',
    ];

    public function productSite()
    {
        return $this->belongsTo(ProductSite::class);
    }

    public function lineType()
    {
        return $this->hasMany(LineTypes::class);
    }

    public function lineText()
    {
        return $this->belongsTo(LineText::class);
    }

    public function customTextField()
    {
        return $this->hasMany(CustomTextField::class);
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
        static::addGlobalScope(new HasNonRemovedDataScope);
    }
}
