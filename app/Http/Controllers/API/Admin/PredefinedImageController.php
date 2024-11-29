<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\PredefinedImage;
use Illuminate\Http\Request;

class PredefinedImageController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  Illuminate\Http\Request  $request
     *
     * @return App\Models\PredefinedImage
     */
    public function store(Request $request)
    {
        PredefinedImage::storeNewPredefinedImage($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  Illuminate\Http\Request  $predefinedImage
     *
     * @return App\Models\PredefinedImage
     */
    public function show($request)
    {
        return PredefinedImage::where('product_id', $request)->where('visible', 1)->get();
    }

    public function updatePredefinedImageVisibility(Request $request, $id)
    {
        $predefinedImage = PredefinedImage::find($id);
        $predefinedImage->visible = $request->visibility;
        $predefinedImage->save();

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
    public function update(Request $request, $id)
    {
        $image = PredefinedImage::findOrFail($id);
        $image->name = $request->name;
        $image->price = $request->price;
        $image->save();

        return response()->json(['message' => 'Image Details Updated Successfully'], 200);
    }
}
