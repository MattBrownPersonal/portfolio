<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deceased;
use App\Models\Image;
use App\Models\Memorialisations;
use App\Models\MemorialisationSite;
use App\Models\Product;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class MemorialisationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Illuminate\Http\Response
     */
    public function index()
    {
        return Memorialisations::orderBy('name')->get();
    }

    public function checkSite($id)
    {
        if (is_numeric($id)) {
            $site = Deceased::where('code', $id)->first()->site;

            return ['isGenericSite' => false, 'site' => $site->uses_categories];
        }
        $site = Site::where('slug', $id)->first();

        return ['isGenericSite' => true, 'site' => $site->uses_categories];
    }

    /**
     * getSiteCategories
     *
     * @param  int $id
     *
     * @return App\Models\MemorialisationSite
     */
    public function getSiteCategories($id)
    {
        $otherCategory = Memorialisations::where('name', 'Other')->first();
        if (is_numeric($id)) {
            $siteId = Deceased::where('code', $id)->first()->site_id;
        } else {
            $siteId = Site::where('name', str_replace('-', ' ', $id))->first()->id;
        }
        if ($otherCategory) {
            $siteCategories = MemorialisationSite::where('site_id', $siteId)->with('memorialisation')->where('memorialisation_id', '!=', $otherCategory->id)->take(20)->get();
        } else {
            $siteCategories = MemorialisationSite::where('site_id', $siteId)->with('memorialisation')->take(20)->get();
        }

        return $siteCategories;
    }

    /**
     * getSiteCategories
     *
     * @param  int $id
     *
     * @return App\Models\MemorialisationSite
     */
    public function getSiteCategoriesForOrder($id)
    {
        return MemorialisationSite::where('site_id', $id)->with('memorialisation')->take(20)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Illuminate\Http\Request  $request
     *
     * @return Illuminate\Http\Response
     */
    public function storeSiteMemorialisation(Request $request)
    {
        // Validate the user input.
        $validator = MemorialisationSite::validateInput($request);
        $memorialisationSite = null;
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 422);
        }

        // Check if a MemorialisationSite already exists for this Memorialisation.
        $existingMemorialsiation = MemorialisationSite::where('memorialisation_id', $request->memorialisation_id)->where('site_id', $request->site_id)->exists();
        if ($existingMemorialsiation) {
            return response()->json(['errors' => ['Memorialisation Already Exists For This Site']], 422);
        }

        DB::transaction(function () use ($request) {
            $path = Image::storeMemorialisationSiteImage($request);

            $memorialisationSite = new MemorialisationSite;
            $memorialisationSite->memorialisation_id = $request->memorialisation_id;
            $memorialisationSite->site_id = $request->site_id;
            $memorialisationSite->image = $path;
            $memorialisationSite->description = $request->description;
            $memorialisationSite->save();
        });

        return $memorialisationSite;
    }

    public function updateSiteMemorialisation(Request $request)
    {
        DB::transaction(function () use ($request) {
            if ($request->image) {
                $path = Image::storeMemorialisationSiteImage($request);
            } else {
                $path = $request->path;
            }

            $memorialisationSite = MemorialisationSite::where('memorialisation_id', $request->memorialisation_id)->where('site_id', $request->site_id)->first();
            $memorialisationSite->memorialisation_id = $request->memorialisation_id;
            $memorialisationSite->site_id = $request->site_id;
            $memorialisationSite->image = $path;
            $memorialisationSite->description = $request->description;
            $memorialisationSite->save();
        });

        return response()->json(['message' => 'Memorialisation Saved Successfully'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  Illuminate\Http\Request  $request
     *
     * @return Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the user input.
        $validator = Memorialisations::validateInput($request);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 422);
        }

        // Check if a model of that name already exists.
        if (Memorialisations::where('name', $request->name)->exists()) {
            return response()->json(['errors' => ['A Category called '.$request->name.' already exists']], 422);
        }

        // Else we can go ahead and create the record.
        $memorialisation = new Memorialisations;
        $memorialisation->name = $request->name;
        $memorialisation->save();

        return $memorialisation;
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
        return MemorialisationSite::where('site_id', $id)->with('memorialisation')->get();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return App\Models\Memorialisations
     */
    public function fetchmemorialisation($site, $id)
    {
        return MemorialisationSite::where('site_id', $site)->where('memorialisation_id', $id)->with('memorialisation')->first();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Illuminate\Http\Request  $request
     * @param  App\Models\Memorialisations  $memorialisations
     *
     * @return Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Memorialisations::validateInput($request);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->toJson()], 422);
        }
        $memorialisation = Memorialisations::FindOrFail($id);
        $memorialisation->name = $request->name;
        $memorialisation->save();

        return response()->json(['message' => 'Category Updated Successfully'], 200);
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
        DB::beginTransaction();

        $memorialisation = Memorialisations::FindOrFail($id);

        $memoraisationWithSite = MemorialisationSite::where('memorialisation_id', $id)->first();

        if ($memoraisationWithSite) {
            return Response::json(['message' => 'This category is assigned to one or more sites and cannot be deleted. Category needs to be removed from site(s) before it can be deleted'], 403);
        } else {
            $products = Product::where('memorialisation_id', $id)->get();
            foreach ($products as $product) {
                $product->memorialisation_id = null;
                $product->save();
            }

            try {
                $memorialisation->delete();
                DB::commit();
            } catch(\Exception $e) {
                DB::rollback();

                return Response::json(['message' => 'Cannot delete this category'], 403);
            }

            return Response::json(['message' => 'Category deleted successfully'], 200);
        }

        MemorialisationSite::where('memorialisation_id', $id)->delete();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return Illuminate\Http\Response
     */
    public function deleteSiteMemorialisation($id)
    {
        DB::beginTransaction();
        $memoraisationWithSite = MemorialisationSite::where('id', $id)->first();
        $productsWithSite = Product::where('site_id', $memoraisationWithSite->site_id)->where('memorialisation_id', $memoraisationWithSite->memorialisation_id)->first();

        if ($productsWithSite) {
            return Response::json(['message' => 'This category has products assigned. Please re-assign these products to another category to continue.'], 403);
        } else {
            try {
                $memoraisationWithSite->delete();
                DB::commit();
            } catch(\Exception $e) {
                DB::rollback();

                return Response::json(['message' => 'Cannot delete this category'], 403);
            }
        }

        return Response::json(['message' => 'Category deleted successfully'], 200);
    }
}
