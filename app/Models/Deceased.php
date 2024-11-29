<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Validator;

class Deceased extends Model
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
        'firstname',
        'lastname',
        'middlename',
        'date_of_birth',
        'date_of_death',
        'date_of_service',
        'code',
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
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
        ]);
    }

    public static function shopRoute($code, $firstname, $lastname)
    {
        $identifier = $code.'-'.strtolower($firstname).'-'.strtolower($lastname);

        return route('memorialsHome', ['code' => $identifier]);
    }

    public static function shopQrRoute($code, $firstname, $lastname)
    {
        $identifier = $code.'-'.strtolower($firstname).'-'.strtolower($lastname);

        return route('memorialsQr', ['code' => $identifier]);
    }

    public static function storeDeceased($request)
    {
        $validator = self::validateInput($request);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->toJson()], 422);
        }

        $deceased = new self;
        $deceased->firstname = $request->firstname;
        $deceased->middlename = $request->middlename;
        $deceased->lastname = $request->lastname;
        $deceased->site_id = $request->site_id;
        $deceased->date_of_birth = $request->date_of_birth;
        $deceased->date_of_death = $request->date_of_death;
        $deceased->date_of_service = $request->date_of_service;
        $deceased->site_id = $request->selectedSite;
        $deceased->date_of_cremation = $request->date_of_cremation;
        $deceased->funeral_director = $request->funeralDirector;
        $deceased->cremation_number = $request->cremNumber;
        $deceased->link_emailed = 0;
        $deceased->link_printed = 0;
        $deceased->times_qr_used = 0;
        $deceased->contact_firstname = $request->contact_firstname;
        $deceased->contact_lastname = $request->contact_lastname;
        $deceased->contact_email = $request->contact_email;
        $deceased->landing_code = self::getUniqueCode($deceased->lastname);
        $deceased->code = rand(10000000, 99999999);
        $deceased->save();

        return response()->json(['message' => 'Deceased Saved Successfully'], 200);
    }

    public static function generateCode()
    {
        $string = 'abcdefghjkmnpqrstuvwxyz23456789';
        $randomCode = substr(str_shuffle($string), 0, 8);
        $code = $randomCode;

        return $code;
    }

    /**
     * checks database for code and reruns if one exists else returns code to use.
     *
     * @var name
     * @return code
     */
    public static function getUniqueCode()
    {
        $code = self::generateCode();

        $existingCode = self::where('landing_code', $code)->get();

        if ($existingCode->isEmpty()) {
            return $code;
        } else {
            return self::getUniqueCode();
        }
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}
