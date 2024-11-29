<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Request;

class CustomImageController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  Illuminate\Http\Request  $request
     *
     * @return Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return Image::addTextToImage($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return App\Models\Image
     */
    public function show($id)
    {
        return Image::getCustomImage($id);
    }

    /**
     * getCustomImageDetails
     *
     * @param  int  $id
     *
     * @return App\Models\Image
     */
    public function getCustomImageDetails($id)
    {
        return Image::getCustomImageDetails($id);
    }
}
