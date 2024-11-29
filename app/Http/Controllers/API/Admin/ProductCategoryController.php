<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Memorialisations;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return App\Models\Memorialisations
     */
    public function index()
    {
        return Memorialisations::orderBy('name')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Illuminate\Http\Request  $request
     *
     * @return App\Models\ProductCategory
     */
    public function store(Request $request)
    {
        return ProductCategory::createProductCategory($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return App\Models\Memorialisations
     */
    public function show($id)
    {
        return Memorialisations::where('id', $id)->with('products', 'products.site')->first();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Illuminate\Http\Request  $request
     * @param  int  $id
     *
     * @return App\Models\ProductCategory
     */
    public function update(Request $request, $id)
    {
        return ProductCategory::updateProductCategory($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return App\Models\ProductCategory
     */
    public function destroy($id)
    {
        return ProductCategory::deleteProductCategory($id);
    }
}
