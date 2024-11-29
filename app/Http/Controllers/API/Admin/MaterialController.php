<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\MaterialProduct;
use App\Models\Product;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return App\Models\Material
     */
    public function index()
    {
        return Material::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        Material::storeNewMaterial($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return App\Models\MaterialProduct
     */
    public function getProductMaterial($id)
    {
        return MaterialProduct::where('product_id', $id)
            ->with('material')
            ->get();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return App\Models\MaterialProduct
     */
    public function show($id)
    {
        return MaterialProduct::where('product_id', $id)->with('material')->get();
    }

    /**
     * getMaterialTypes
     *
     * @param  int  $site
     * @param  int  $id
     * @param  int  $memorialisationId
     *
     * @return Illuminate\Http\Response
     */
    public function getMaterialTypes($site, $id, $memorialisationId)
    {
        return Product::where('product_id', $id)
            ->where('site_id', $site)
            ->where('memorialisation_sites_id', $memorialisationId)
            ->with('materials')
            ->first();
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
        $requestMaterial = json_decode($request->editedMaterial);

        $material = Material::find($requestMaterial->id);
        $material->type = $requestMaterial->type;
        $material->price = $requestMaterial->price;
        $material->save();

        return response()->json(['message' => 'Material Updated Successfully'], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Illuminate\Http\Request  $request
     * @param  int  $id
     *
     * @return Illuminate\Http\Response
     */
    public function updateMaterialVisibility(Request $request, $id)
    {
        $material = Material::find($id);
        $material->visible = $request->visibility;
        $material->save();

        return response()->json(['message' => 'Material Updated Successfully'], 200);
    }
}
