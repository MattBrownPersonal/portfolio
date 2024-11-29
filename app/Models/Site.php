<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'obitus_site_id',
        'account_type',
        'site_type',
        'operator_type',
    ];

    public static function cremOnlyRoute($slug)
    {
        return route('memorialsHome', ['code' => $slug]);
    }

    public function deceased()
    {
        return $this->hasMany(Deceased::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class);
    }

    public function sitestyle()
    {
        return $this->hasOne(SiteStyles::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function productSites()
    {
        return $this->hasMany(ProductSite::class);
    }

    public function articles()
    {
        return $this->belongsToMany(Article::class);
    }

    public function faqs()
    {
        return $this->hasMany(Faqs::class);
    }
}
