<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use App\Models\Deceased;
use App\Models\Product;
use App\Models\ProductTextColour;
use App\Models\Site;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return App\Models\Product
     */
    public function index()
    {
        return Product::all();
    }

    /**
     * Display a resource.
     *
     * @param  int  $id
     * @param  mixed $site
     *
     * @return App\Models\Product
     */
    public function show($id, $site)
    {
        if (is_numeric($site)) {
            $site_id = Deceased::where('code', $site)->pluck('site_id')->first();
        } else {
            $site_id = Site::where('name', str_replace('-', ' ', $site))->pluck('id')->first();
        }

        return Product::where('memorialisation_id', $id)->where('site_id', $site_id)->where('draft', 0)->get();
    }

    public function showAllProducts($site)
    {
        if (is_numeric($site)) {
            $site_id = Deceased::where('code', $site)->pluck('site_id')->first();
        } else {
            $site_id = Site::where('name', str_replace('-', ' ', $site))->pluck('id')->first();
        }

        return Product::where('site_id', $site_id)->where('draft', 0)->get();
    }

    /**
     * showFeatured.
     *
     * @param  string  $site
     *
     * @return App\Models\Product
     */
    public function showFeatured($site_code)
    {
        return Product::getFeaturedProducts($site_code);
    }

    /**
     * showProduct.
     *
     * @param  int  $id
     *
     * @return App\Models\Product
     */
    public function showProduct($id)
    {
        return Product::where('id', $id)->with('images', 'attributeTypes', 'attributeTypes.attributes', 'textColours', 'materials', 'customImages', 'customImages.additionalImage', 'site', 'supplier', 'fonts')->first();
    }

    /**
     * getOtherProducts.
     *
     * @param  mixed  $code
     *
     * @return App\Models\Product
     */
    public function getOtherProducts($code)
    {
        if (is_numeric($code)) {
            $siteId = Deceased::where('code', $code)->first()->site_id;
        } else {
            $siteId = Site::where('name', str_replace('-', ' ', $code))->first()->id;
        }

        return Product::where('site_id', $siteId)->where('draft', 0)->where('featured', 1)->with('images', 'attributeTypes', 'attributeTypes.attributes')->get();
    }

    /**
     * getColours.
     *
     * @param  int  $id
     *
     * @return App\Models\ProductTextColour
     */
    public function getColours($id)
    {
        return ProductTextColour::where('product_id', $id)->get();
    }
}
