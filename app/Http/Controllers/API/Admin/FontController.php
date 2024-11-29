<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Font;
use Illuminate\Http\Request;

class FontController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Font::getAllFonts();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeFonts(Request $request, $id)
    {
        return Font::storeFonts($request, $id);
    }

    public function deleteFont($productId, $fontId)
    {
        return Font::deleteFont($productId, $fontId);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Font  $font
     * @return \Illuminate\Http\Response
     */
    public function show(Font $font)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Font  $font
     * @return \Illuminate\Http\Response
     */
    public function edit(Font $font)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Font  $font
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Font $font)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Font  $font
     * @return \Illuminate\Http\Response
     */
    public function destroy(Font $font)
    {
        //
    }
}
