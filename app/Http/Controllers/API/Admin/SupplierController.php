<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return App\Models\Supplier
     */
    public function index()
    {
        return Supplier::with('sites')->orderBy('name')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Illuminate\Http\Request  $request
     *
     * @return App\Models\Supplier
     */
    public function store(Request $request)
    {
        return Supplier::createSupplier($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return App\Models\Supplier
     */
    public function show($id)
    {
        return Supplier::where('id', $id)->with('sites')->first();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Illuminate\Http\Request  $request
     * @param  int  $id
     *
     * @return App\Models\Supplier
     */
    public function update(Request $request, $id)
    {
        return Supplier::updateSupplier($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return App\Models\Supplier
     */
    public function destroy($id)
    {
        return Supplier::deleteSupplier($id);
    }
}
