<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attributes;
use App\Models\AttributeTypes;
use Illuminate\Http\Request;

class AttributesController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  Illuminate\Http\Request  $request
     *
     * @return Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Attributes::addNewAttribute($request);

        return response()->json(['message' => 'Attribute Added Successfully'], 200);
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
        $attributes = Attributes::where('attribute_types_id', $id)->with('attributeTypes')->get();
        $attributeType = AttributeTypes::find($id);

        return ['attributes' => $attributes, 'attributeType' => $attributeType];
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
        Attributes::updateAttribute($request, $id);

        return response()->json(['message' => 'Attribute Updated Successfully'], 200);
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
        Attributes::deleteAttribute($id);

        return response()->json(['message' => 'Attribute Deleted Successfully'], 200);
    }
}
