<?php

namespace App\Http\Controllers;

use App\Models\Deceased;

class ShopController extends Controller
{
    /**
     * Display the shop homepage.
     *
     * @param  int  $code
     *
     * @return Illuminate\Contracts\Support\Renderable
     */
    public function show($code)
    {
        return view('applicant.home');
    }

    public function memorialsQr($code)
    {
        $deceased = Deceased::where('code', explode('-', $code)[0])->first();
        $deceased->times_qr_used += 1;
        $deceased->save();

        return redirect()->route('memorialsHome', ['code' => $code]);
    }
}
