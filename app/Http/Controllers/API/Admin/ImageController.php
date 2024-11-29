<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomImage;
use App\Models\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return App\Models\Image
     */
    public function index()
    {
        return Image::all();
    }

    /**
     * customImage
     *
     * @param Illuminate\Http\Request $request
     */
    public function customImage(Request $request)
    {
        Image::storeCustomImage($request);
    }

    /**
     * customImage
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Http\Response
     */
    public function getCustomProductImage($id, $productId)
    {
        $customImage = CustomImage::where('id', $id)->where('product_id', $productId)->with('customTextFields', 'customImagePerspectiveDetails', 'additionalImage', 'image')->first();

        return $customImage;
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
        $image = Image::find($id);
        $image->delete();

        return response()->json(['message' => 'Image Deleted Successfully'], 200);
    }
}
