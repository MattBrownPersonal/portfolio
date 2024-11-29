<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class VivediaStaffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return mixed
     */
    public function index()
    {
        $canCreate = Gate::inspect('create', User::class)->allowed();

        if (User::isVivedia()) {
            return ['users' => User::with('roles')->orderBy('firstname')->get(), 'canCreate' => $canCreate];
        } elseif (User::isSiteAdmin()) {
            return ['users' => User::siteUsers(User::authUserSites()), 'canCreate' => $canCreate];
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Illuminate\Http\Request  $request
     *
     * @return App\Models\User
     */
    public function store(Request $request)
    {
        $existingUser = User::where('email', $request->email)->first();
        if ($existingUser !== null) {
            return response()->json(['message' => 'Email Already Exists'], 422);
        }

        return User::createNewUser($request, false);
    }

    /**
     * storeUserSites
     *
     * @param  Illuminate\Http\Request  $request
     */
    public function storeUserSites(Request $request, $id)
    {
        $user = User::find($id);
        $user->sites()->sync($request->sites);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return App\Models\User
     */
    public function show($id)
    {
        return User::with('sites', 'roles')->find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Illuminate\Http\Request  $request
     * @param  int  $id
     *
     * @return App\Models\User
     */
    public function update(Request $request, $id)
    {
        if ($request->requestType == 'roles') {
            return User::updateUserRoles($request, $id);
        } elseif ($request->requestType == 'sites') {
            return User::updateUserSites($request, $id);
        }

        return User::updateUserDetails($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return App\Models\User
     */
    public function destroy($id)
    {
        return User::deleteUser($id);
    }
}
