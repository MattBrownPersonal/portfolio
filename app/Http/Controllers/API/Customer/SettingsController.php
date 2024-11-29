<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use App\Models\Settings;

class SettingsController extends Controller
{
    /**
     * getSiteSettings
     *
     * @param int $id
     *
     * @return Illuminate\Http\Response
     */
    public function getSiteSettings($id)
    {
        return response()->json([
            'buy_now_text' => Settings::getSiteSetting('buy_now_text', $id),
            'share_text' => Settings::getSiteSetting('share_text', $id),
            'next_steps' => Settings::getSiteSetting('next_steps', $id),
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Illuminate\Http\Response
     */
    public function fetchSettings()
    {
        return Settings::all()->keyBy('key');
    }
}
