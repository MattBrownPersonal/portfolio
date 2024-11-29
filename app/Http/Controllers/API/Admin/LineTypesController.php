<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomProductText;
use App\Models\CustomTextField;
use App\Models\LineTypes;
use Illuminate\Http\Request;

class LineTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return App\Models\LineTypes
     */
    public function index()
    {
        return LineTypes::all();
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
        $existingLineUsed = false;

        foreach (json_decode($request->lines) as $line) {
            if (isset($line->id)) {
                $textFields = CustomTextField::where('custom_product_text_id', $line->id)->get();
            }

            if ($line->removed === 1) {
                if (isset($line->id)) {
                    $oldCustomText = CustomProductText::find($line->id);
                    $oldCustomText->removed = 1;
                    $oldCustomText->save();
                }

                if ($textFields) {
                    foreach ($textFields as $textField) {
                        $existingLineUsed = true;
                        $textField->removed = 1;
                        $textField->line_index = null;
                        $textField->save();
                    }
                }
            } else {
                if (isset($line->id)) {
                    $customLines = CustomProductText::updateOrCreate([
                        'id' => $line->id,
                    ], [
                        'product_id' => $request->product_id,
                        'line_types' => $line->line_types,
                        'custom_index' => $line->custom_index,
                        'is_custom' => $line->is_custom,
                        'custom_message_text' => $line->custom_message_text,
                        'order_index' => $line->order_index,
                        'removed' => 0,
                    ]);
                } else {
                    $customLines = new CustomProductText;
                    $customLines->product_id = $request->product_id;
                    $customLines->line_types = $line->line_types;
                    $customLines->custom_index = $line->custom_index;
                    $customLines->is_custom = $line->is_custom;
                    $customLines->custom_message_text = $line->custom_message_text;
                    $customLines->order_index = $line->order_index;
                    $customLines->removed = 0;
                    $customLines->save();
                }
            }
        }

        if ($existingLineUsed === true) {
            return response()->json(['message' => 'One or more product images include these lines. You will have to edit these images in order for them to be used'], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return App\Models\CustomProductText
     */
    public function show($id)
    {
        return CustomProductText::where('product_id', $id)->where('removed', 0)->with('customTextField')->get();
    }
}
