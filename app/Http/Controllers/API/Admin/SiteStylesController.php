<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Site;
use App\Models\SiteStyles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Validator;

class SiteStylesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return App\Models\SiteStyles
     */
    public function index()
    {
        return SiteStyles::all();
    }

    /**
     * Apply the validation rules to the request.
     *
     * @param  Illuminate\Http\Request  $request
     *
     * @return Validator
     */
    public function validateInput($request)
    {
        $validator = Validator::make($request->all(), [
            'logo_file' => 'image',
            'primary_colour' => 'required|string|max:30',
            'secondary_colour' => 'required|string|max:30',
            'primary_font_colour' => 'required|string|max:30',
            'secondary_font_colour' => 'required|string|max:30',
        ]);

        return $validator;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Illuminate\Http\Request  $request
     *
     * @return Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $this->validateInput($request);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->toJson()], 422);
        }

        $style = SiteStyles:: storeSiteStyle($request);

        return response()->json(['message' => 'Site Style Saved Successfully'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $siteId
     *
     * @return App\Models\SiteStyles
     */
    public function show($siteId)
    {
        return SiteStyles::where('site_id', $siteId)->with('image')->first();
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
        $validator = $this->validateInput($request);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->toJson()], 422);
        }

        if ($request->logo_file) {
            $image_id = Image::storeSiteLogo($request);
        } else {
            $image_id = $request->image_id;
        }

        $style = SiteStyles::updateOrCreate([
            'site_id' => $request->site_id,
        ], [
            'site_id' => $request->site_id,
            'image_id' => $image_id,
            'primary_colour' => $request->primary_colour,
            'secondary_colour' => $request->secondary_colour,
            'primary_font_colour' => $request->primary_font_colour,
            'secondary_font_colour' => $request->secondary_font_colour,
        ]);

        $site = Site::find($request->site_id);
        $site->name = $request->site_name;
        $site->uses_categories = $request->uses_categories;
        $site->save();

        return response()->json(['message' => 'Site Style Updated Successfully'], 200);
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
        $site = SiteStyles::findOrFail($id);
        $site->delete();

        return response()->json(['message' => 'Site Deleted Successfully'], 200);
    }
}
