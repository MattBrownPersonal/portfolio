<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Validator;

class Settings extends Model
{
    use HasFactory;

    public static function getSetting($key)
    {
        return self::where('key', $key)->pluck('value')->first();
    }

    public static function getSiteSetting($key, $id)
    {
        return self::where('key', $key)->where('site_id', $id)->pluck('value')->first();
    }

    public static function getAllSettings($id)
    {
        $roles = Auth::user()->roles->pluck('id')->toArray();
        if (in_array(Role::VIVEDIA_ADMIN_ID, $roles)) {
            return self::all()->where('site_id', null);
        } else {
            return self::where('site_id', $id)->get();
        }
    }

    /**
     * Apply the validation rules to the request.
     *
     * @param  Illuminate\Http\Request  $request
     *
     * @return Validator
     */
    public static function validateInput($request)
    {
        return Validator::make($request->all(), [
            'name' => 'required',
        ]);
    }

    public static function updateSetting($request, $id)
    {
        $validator = self::validateInput($request);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->toJson()], 422);
        }

        $setting = self::findOrFail($id);
        $setting->value = $request->name;
        $setting->save();

        return response()->json(['message' => 'Setting Updated Successfully'], 200);
    }
}
