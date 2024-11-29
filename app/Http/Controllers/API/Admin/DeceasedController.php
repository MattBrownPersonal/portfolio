<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\DeceasedResource;
use App\Mail\ApplicantCodeEmail;
use App\Models\Deceased;
use App\Models\LinkLogs;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use QrCode;
use Validator;

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
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'date_of_death' => 'required|date',
            'selectedSite' => 'required',
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Illuminate\Http\Response
     */
    public function index()
    {
        $canCreate = Gate::inspect('create', Deceased::class)->allowed();

        if (Auth::user()->isVivediaAdmin()) {
            $deceadeds = Deceased::with('site')->orderBy('firstname')->get();
        } else {
            $siteIds = Auth::user()->sites()->pluck('sites.id');
            $deceadeds = Deceased::wherein('site_id', $siteIds)->with('site')->orderBy('firstname')->get();
        }

        return response()->json(['deceased' => $deceadeds, 'canCreate' => $canCreate]);
    }

    /**
     * Send mail to applicant
     *
     * @param  Illuminate\Http\Request  $request
     *
     * @return Illuminate\Http\Response
     */
    public function sendMail(Request $request)
    {
        $deceasedName = $request->deceased['firstname'].' '.$request->deceased['lastname'];
        $applicantName = $request->deceased['contact_firstname'].' '.$request->deceased['contact_lastname'];
        $bccAddress = env('MAIL_BCC_ADDRESS', 'ENV_SECRET_NOT_SETUP');
        try {
            Mail::to($request->deceased['contact_email'])->send(new ApplicantCodeEmail($deceasedName, $request->deceased['site']['name'], $applicantName, Deceased::shopRoute($request->deceased['code'], $request->deceased['firstname'], $request->deceased['lastname']), $bccAddress));
        } catch(\Exception $e) {
            return response()->json(['message' => 'Error: Could not send email',
                'error' => $e->getMessage(),
            ], 400);
        }
        $deceased = Deceased::findOrFail($request->deceased['id']);
        $deceased->link_emailed = 1;
        $deceased->save();

        return response()->json(['message' => 'Email sent to applicant successfully'], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        return Deceased::storeDeceased($request);
    }

    /**
     * Get a QR code.
     *
     * @param  Illuminate\Http\Request  $request
     *
     * @param QrCode
     */
    public function getQrCode(Request $request)
    {
        return QrCode::size(110)->generate(Deceased::shopQrRoute($request->code, $request->firstname, $request->lastname));
    }

    /**
     * Log that a QR code was printed.
     *
     * @param  Illuminate\Http\Request  $request
     */
    public function qrCodeprinted(Request $request)
    {
        $deceased = Deceased::findOrFail($request->id);
        $deceased->link_printed = 1;
        $deceased->save();
    }

    /**
     * linkAccessed
     *
     * @param  Illuminate\Http\Request  $request
     */
    public function linkAccessed(Request $request)
    {
        $linkOpen = new LinkLogs;
        $linkOpen->deceased_id = $request->id;
        $linkOpen->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return App\Http\Resources\DeceasedResource
     */
    public function show($id)
    {
        $deceasedPerson = Deceased::with('site')->find($id);

        return new DeceasedResource($deceasedPerson);
    }

    public function checkLandingCode($code)
    {
        $deceased = Deceased::where('landing_code', $code)->first();
        if ($deceased) {
            return $deceased;
        }

        return response()->json(['error' => 'Sorry, there are no results for '.$code], 422);
    }

    /**
     * fetchDeceased
     *
     * @param  int  $code
     *
     * @return App\Models\Deceased
     */
    public function fetchDeceased($code)
    {
        return Deceased::where('code', $code)->get()->first();
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

        $deceased = Deceased::findOrFail($id);
        $deceased->firstname = $request->firstname;
        $deceased->middlename = $request->middlename;
        $deceased->lastname = $request->lastname;
        $deceased->date_of_birth = $request->date_of_birth;
        $deceased->date_of_death = $request->date_of_death;
        $deceased->date_of_service = $request->date_of_service;
        $deceased->date_of_cremation = $request->date_of_cremation;
        $deceased->funeral_director = $request->funeralDirector;
        $deceased->cremation_number = $request->cremNumber;
        $deceased->contact_firstname = $request->contact_firstname;
        $deceased->contact_lastname = $request->contact_lastname;
        $deceased->contact_email = $request->contact_email;
        $deceased->site_id = $request->selectedSite;
        $deceased->save();

        return response()->json(['message' => 'Deceased Updated Successfully'], 200);
    }
}
