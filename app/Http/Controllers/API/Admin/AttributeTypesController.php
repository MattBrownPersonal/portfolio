<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attributes;
use App\Models\AttributeTypes;
use Illuminate\Http\Request;

class AttributeTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Illuminate\Http\Response
     */
    public function index()
    {
        return AttributeTypes::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Illuminate\Http\Response  $request
     *
     * @return Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        foreach (json_decode($request->attributeTypes) as $requestAttributeType) {
            if ($requestAttributeType->id) {
                $attributeTypes = AttributeTypes::find($requestAttributeType->id);
                $attributeTypes->name = $requestAttributeType->name;
                $attributeTypes->product_id = $requestAttributeType->product_id;
                $attributeTypes->visible = $requestAttributeType->visible;
                $attributeTypes->save();
            } else {
                $attributeTypes = new AttributeTypes;
                $attributeTypes->name = $requestAttributeType->name;
                $attributeTypes->product_id = $requestAttributeType->product_id;
                $attributeTypes->visible = $requestAttributeType->visible;
                $attributeTypes->save();
            }

            foreach ($requestAttributeType->attributes as $requestAttribute) {
                if ($requestAttribute->id) {
                    $attribute = Attributes::find($requestAttribute->id);
                    $attribute->attribute_types_id = $attributeTypes->id;
                    $attribute->name = $requestAttribute->name;
                    $attribute->price = $requestAttribute->price;
                    $attribute->visible = $requestAttribute->visible;
                    $attribute->save();
                } else {
                    $attribute = new Attributes;
                    $attribute->attribute_types_id = $attributeTypes->id;
                    $attribute->name = $requestAttribute->name;
                    $attribute->price = $requestAttribute->price;
                    $attribute->visible = $requestAttribute->visible;
                    $attribute->save();
                }
            }
        }

        return response()->json(['message' => 'Attribute Type Added Successfully'], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  @param  Illuminate\Http\Response  $request
     * @param  int  $id
     *
     * @return Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        AttributeTypes::editAttributeType($request, $id);

        return response()->json(['message' => 'Attribute Type Updated Successfully'], 200);
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
        AttributeTypes::deleteAttributeType($id);

        return response()->json(['message' => 'Attribute Type Deleted Successfully'], 200);
    }
}
