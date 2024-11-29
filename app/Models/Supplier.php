<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Validator;

class Supplier extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'site_id',
        'name',
        'address',
        'phone_number',
        'email',
    ];

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
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phonenumber' => 'required',
            'email' => 'required|string|max:255',
        ]);
    }

    /**
     * @param Request $request
     */
    public static function createSupplier($request)
    {
        $validator = self::validateInput($request);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->toJson()], 422);
        }

        $allSites = $request->sites;
        $sites = explode(',', $allSites);

        $supplier = new self;
        $supplier->name = $request->name;
        $supplier->phone_number = $request->phonenumber;
        $supplier->email = $request->email;
        $supplier->address = $request->address;
        $supplier->save();

        foreach ($sites as $site) {
            $supplier->sites()->attach($site);
        }

        return response()->json(['message' => 'Supplier Saved Successfully'], 200);
    }

    /**
     * @param Request $request
     */
    public static function updateSupplier($request, $id)
    {
        $validator = self::validateInput($request);
        $sites = explode(',', $request->sites);
        $response = response()->json(['message' => 'Supplier Updated Successfully'], 200);

        if ($request->requestType == 'sites') {
            $supplier = self::findOrFail($id);
            $supplier->sites()->sync($sites);

            return $response;
        }

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->toJson()], 422);
        }

        $supplier = self::findOrFail($id);
        $supplier->name = $request->name;
        $supplier->phone_number = $request->phonenumber;
        $supplier->email = $request->email;
        $supplier->address = $request->address;
        $supplier->save();

        return $response;
    }

    /**
     * @param Request $request
     */
    public static function deleteSupplier($id)
    {
        $supplier = self::findOrFail($id);
        $supplier->delete();

        return response()->json(['message' => 'Supplier Deleted Successfully'], 200);
    }

    public function products()
    {
        return $this->hasMany(Products::class);
    }

    public function sites()
    {
        return $this->belongsToMany(Site::class);
    }
}
