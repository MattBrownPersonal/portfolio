<?php

namespace App\Http\Controllers\API\Obitus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SiteController extends Controller
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
            'name' => 'required|string|max:255',
        ]);

        return $validator;
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
        $site->name = $request->name;
        $site->saveOrFail();

        return response()->json(['message' => 'Site Renamed Successfully'], 200);
    }
}
