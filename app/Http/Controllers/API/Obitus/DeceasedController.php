<?php

namespace App\Http\Controllers\API\Obitus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DeceasedController extends Controller
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
        return Validator::make($request->all(), [
            'name' => 'string|max:255',
            'date_of_service' => 'date',
            'obitus_site_id' => 'integer',
        ]);
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

        $deceased = new Deceased;
        $deceased->name = $request->name;
        $deceased->date_of_service = $request->date_of_service;
        $deceased->obitus_site_id = $request->obitus_site_id;
        $deceased->link_emailed = 0;
        $deceased->link_printed = 0;
        $deceased->code = rand(10000000, 99999999);
        $deceased->save();

        return response()->json(['message' => 'Deceased Saved Successfully'], 200);
    }
}
