<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use App\Models\Deceased;
use App\Models\Faqs;
use App\Models\Site;

class FaqController extends Controller
{
    public function index()
    {
        return Faqs::all();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return App\Models\Faqs
     */
    public function show($id)
    {
        if (is_numeric($id)) {
            $siteId = Deceased::where('code', $id)->first()->site_id;
        } else {
            $siteId = Site::where('name', str_replace('-', ' ', $id))->first()->id;
        }

        return Faqs::where('hidden', 0)->where('site_id', $siteId)->orWhereNull('site_id')->orderBy('site_id', 'asc')->get();
    }
}
