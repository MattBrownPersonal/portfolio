<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['settings' => Settings::getAllSettings(Auth::user()->id)]);
    }

    /**
     * Update the setting in the DB.
     *
     * @param  Illuminate\Http\Request  $request
     * @param  int  $id
     *
     * @return App\Models\ProductCategory
     */
    public function update(Request $request, $id)
    {
        return Settings::updateSetting($request, $id);
    }
}
