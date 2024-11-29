<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deceased;
use App\Models\Site;
use App\Models\SiteStyles;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Illuminate\Http\Response
     */
    public function index()
    {
        if (User::isVivedia()) {
            return Site::with('products')->orderBy('name')->get();
        } elseif (User::isSiteAdmin()) {
            return Site::whereIn('id', User::authUsersites())->with('products')->orderBy('name')->get();
        }
    }

    /**
     * getContactDetails.
     *
     * @param  string  $code
     *
     * @return App\Models\Site
     */
    public function getContactDetails($code)
    {
        $siteId = Deceased::where('code', $code)->pluck('site_id')->first();

        return Site::find($siteId);
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
            'siteName' => 'required|string|max:255',
        ]);

        return $validator;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return App\Models\Site
     */
    public function siteName($id)
    {
        return Site::find($id);
    }

    public function getSiteUrl()
    {
        return config('app.app_url');
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

        $site = new Site;
        $site->obitus_site_id = $request->siteId;
        $site->name = $request->siteName;
        $site->account_type = $request->accountType;
        $site->site_type = $request->siteType;
        $site->operator_type = $request->operatorType;
        $site->parent_account = $request->parentAccount;
        $site->primary_contact_name = $request->contactName;
        $site->primary_contact_email = $request->contactEmail;
        $site->phone = $request->phoneNumber;
        $site->street = $request->street;
        $site->city = $request->city;
        $site->county = $request->county;
        $site->country = $request->country;
        $site->postcode = $request->postcode;
        $site->region = $request->region;
        $site->uses_categories = $request->usesCategories;
        $site->slug = $request->siteUrl;
        $site->save();

        SiteStyles::storeSiteStyle($site->id);

        return response()->json(['message' => 'Site Saved Successfully'], 200);
    }

    /**
     * storeContactDetails
     *
     * @param  int  $id
     * @param  Illuminate\Http\Request  $request
     *
     * @return Illuminate\Http\Response
     */
    public function storeContactDetails($id, Request $request)
    {
        $site = Site::find($id);
        $site->primary_contact_name = $request->name;
        $site->primary_contact_email = $request->email;
        $site->phone = $request->phoneNumber;
        $site->street = $request->street;
        $site->city = $request->city;
        $site->county = $request->county;
        $site->country = $request->country;
        $site->postcode = $request->postcode;
        $site->save();

        return response()->json(['message' => 'Contact Details Saved Successfully'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return Illuminate\Http\Response
     */
    public function show($id)
    {
        $site = Site::find($id);

        return response()->json(['site' => $site]);
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

        $site = Site::findOrFail($id);
        $site->obitus_site_id = $request->siteId;
        $site->name = $request->siteName;
        $site->account_type = $request->accountType;
        $site->site_type = $request->siteType;
        $site->operator_type = $request->operatorType;
        $site->parent_account = $request->parentAccount;
        $site->primary_contact_name = $request->contactName;
        $site->primary_contact_email = $request->contactEmail;
        $site->phone = $request->phoneNumber;
        $site->street = $request->street;
        $site->city = $request->city;
        $site->county = $request->county;
        $site->country = $request->country;
        $site->postcode = $request->postcode;
        $site->region = $request->region;
        $site->saveOrFail();

        return response()->json(['message' => 'Site Renamed Successfully'], 200);
    }

    public function updateSiteDetails(Request $request, $id)
    {
        $site = Site::findOrFail($id);
        $site->obitus_site_id = $request->obitus_site_id;
        $site->slug = $request->slug;
        $site->parent_account = $request->parent_account;
        $site->site_type = $request->site_type;
        $site->saveOrFail();

        return response()->json(['message' => 'Site Details Updated Successfully'], 200);
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
        $site = Site::findOrFail($id);
        $site->delete();

        return response()->json(['message' => 'Site Deleted Successfully'], 200);
    }
}
