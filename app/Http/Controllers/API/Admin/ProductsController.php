<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomImage;
use App\Models\MemorialisationSite;
use App\Models\Product;
use App\Models\ProductTextColour;
use App\Models\Site;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return App\Models\Product
     */
    public function index()
    {
        return Product::with('memorialisation', 'supplier')->get();
    }

    /**
     * singleSiteproduct
     *
     * @param  int  $id
     * @param  int  $site
     *
     * @return App\Models\Product
     */
    public function singleSiteproduct($id, $site)
    {
        return Product::where('id', $id)->where('site_id', $site)->with('site', 'supplier', 'memorialisation', 'images', 'customImages', 'customImages.material', 'customImages.image', 'attributeTypes', 'attributeTypes.attributes', 'materials', 'predefinedImages', 'customImages.customImage', 'customProductTexts', 'customImages.customTextFields', 'fonts')->first();
    }

    /**
     * productsBySite
     *
     * @param  int  $id
     *
     * @return App\Models\Product
     */
    public function productsBySite($id)
    {
        return Product::where('product_id', $id)->with('site')->get();
    }

    /**
     * getSavedProducts
     *
     * @param  int  $id
     *
     * @return App\Models\Product
     */
    public function getSavedProducts($id)
    {
        return Product::where('saved', 1)->with('site', 'images', 'customImages', 'customImages.material')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Illuminate\Http\Request  $request
     *
     * @return App\Models\Product
     */
    public function store(Request $request)
    {
        return Product::storeNewProduct($request);
    }

    /**
     * storeSiteProduct
     *
     * @param  Illuminate\Http\Request  $request
     *
     * @return App\Models\Product
     */
    public function storeSiteProduct(Request $request)
    {
        return Product::storeNewSiteProduct($request);
    }

    /**
     * storeSiteMemorialisationProduct
     *
     * @param  Illuminate\Http\Request  $request
     *
     * @return App\Models\Product
     */
    public function storeSiteMemorialisationProduct(Request $request)
    {
        return Product::storeSiteMemorialisationProduct($request);
    }

    /**
     * saveProduct
     *
     * @param  int  $id
     * @param  int  $site
     */
    public function saveProduct($id, $site)
    {
        $product = Product::where('id', $id)->where('site_id', $site)->first();
        $product->saved = 1;
        $product->save();
    }

    /**
     * setDateFormat
     *
     * @param  Illuminate\Http\Request  $request
     */
    public function setDateFormat(Request $request)
    {
        $product = Product::where('id', $request->id)->first();
        $product->date_layout = $request->format;
        $product->save();
    }

    /**
     * saveProductAsDraft
     *
     * @param  int  $id
     * @param  int  $site
     * @param  Illuminate\Http\Request  $request
     */
    public function saveProductAsDraft($id, $site, Request $request)
    {
        $product = Product::where('id', $id)->where('site_id', $site)->first();
        $product->draft = $request->flag;
        $product->save();
    }

    /**
     * copyProduct
     *
     * @param  Illuminate\Http\Request  $request
     *
     * @return App\Models\Product
     */
    public function copyProduct(Request $request)
    {
        return Product::copyProduct($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return App\Models\Product
     */
    public function show($id)
    {
        return Product::getAllProducts($id);
    }

    /**
     * siteProducts
     *
     * @param  int  $site
     * @param  int  $id
     *
     * @return App\Models\Product
     */
    public function siteProducts($site, $id)
    {
        return Product::where('site_id', $id)->where('memorialisation_id', $site)->with('site')->get();
    }

    /**
     * allSiteProducts
     *
     * @param  int  $site
     *
     * @return App\Models\Product
     */
    public function allSiteProducts($site)
    {
        return Product::where('site_id', $site)->orderBy('memorialisation_id')->get();
    }

    /**
     * allSiteProducts
     *
     * @param  int  $site
     *
     * @return App\Models\Product
     */
    public function allProductsForOrder($id, $site)
    {
        return Product::where('site_id', $site)->where('memorialisation_id', $id)->get();
    }

    /**
     * siteMemorialisations
     *
     * @param  int  $id
     *
     * @return App\Models\MemorialisationSite
     */
    public function siteMemorialisations($id)
    {
        return MemorialisationSite::where('site_id', $id)->with('memorialisation')->get();
    }

    /**
     * storeColour.
     *
     * @param  Illuminate\Http\Request  $request
     *
     * @return Illuminate\Http\Response
     */
    public function storeColour(Request $request)
    {
        $validator = ProductTextColour::validateInput($request);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 422);
        }

        $colour = new ProductTextColour;
        $colour->colour = json_decode($request->colour);
        $colour->name = $request->name;
        $colour->product_id = $request->product_id;
        $colour->save();

        return response()->json(['message' => 'Colour Stored Successfully'], 200);
    }

    public function editColours(Request $request, $id)
    {
        $validator = ProductTextColour::validateInput($request);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 422);
        }

        $selectedColour = ProductTextColour::find($id);
        $selectedColour->colour = json_decode($request->colour);
        $selectedColour->name = $request->name;
        $selectedColour->product_id = $request->product_id;
        $selectedColour->save();

        return response()->json(['message' => 'Colour Stored Successfully'], 200);
    }

    public function checkCategories()
    {
        return Product::with('site')->whereNull('memorialisation_id')->whereHas('site', function ($q) {
            $q->where('uses_categories', 1);
        })->get()->count();
    }

    public function checkSiteCategories($id)
    {
        $site = Site::find($id);
        if ($site->uses_categories === 1) {
            return Product::where('site_id', $id)->whereNull('memorialisation_id')->get()->count();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Illuminate\Http\Request  $request
     * @param  int  $id
     *
     * @return Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return Product::updateProduct($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Product Deleted Successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return Illuminate\Http\Response
     */
    public function deleteColour($id)
    {
        $productTextColour = ProductTextColour::findOrFail($id);
        $productTextColour->delete();

        return response()->json(['message' => 'Colour Deleted Successfully'], 200);
    }

    /**
     * deleteProductImage
     *
     * @param  int  $id
     *
     * @return Illuminate\Http\Response
     */
    public function deleteProductImage($id)
    {
        $customImage = CustomImage::find($id);
        $customImage->additionalImage()->delete();
        $customImage->customImagePerspectiveDetails()->delete();
        foreach ($customImage->customTextFields()->get() as $customTextField) {
            $customTextField->delete();
        }
        $customImage->delete();

        return response()->json(['message' => 'Image Deleted Successfully'], 200);
    }
}
