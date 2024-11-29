<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Site;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;

class SiteStaffController extends Controller
{
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
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|max:255',
        ]);

        return $validator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Illuminate\Http\Response
     */
    public function index()
    {
        $siteStaff = User::getSiteStaff();

        return ['siteStaff' => $siteStaff];
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
        $existingUser = User::where('email', $request->email)->first();

        if ($existingUser !== null) {
            return response()->json(['message' => 'Email Already Exists'], 422);
        }

        return User::createNewUser($request, true);
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
        $staff = Site::find($id);

        return response()->json(['staff' => $staff->users, 'sitename' => $staff->name]);
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

        $roles = [3, 4];

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->toJson()], 422);
        }

        $existingUser = User::where('email', $request->email)->first();

        if ($existingUser !== null) {
            return response()->json(['message' => 'Email Already Exists'], 422);
        }

        $user = User::findOrFail($id);
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->saveOrFail();
        $user->roles()->sync($roles);
        $user->sites()->sync($request->selectedSites);

        return response()->json(['message' => 'User Updated Successfully', 'editedUser' => User::getSingleSiteStaff($user->id)], 200);
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
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }
}
