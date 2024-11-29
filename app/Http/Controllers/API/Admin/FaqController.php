<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faqs;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        return Faqs::with('site')->get();
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
        $faq = new Faqs;
        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->site_id = $request->site;
        $faq->save();

        return response()->json(['message' => 'Faq Saved Successfully'], 200);
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
        return Faqs::where('site_id', $id)->orWhere('site_id', null)->get();
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
        $faq = Faqs::findOrFail($id);

        if ($request->question) {
            $faq->question = $request->question;
        } elseif ($request->answer) {
            $faq->answer = $request->answer;
        }

        $faq->save();

        return response()->json(['message' => 'Faq Updated Successfully'], 200);
    }

    public function editFaqVisibility(Request $request, $id)
    {
        $faq = Faqs::find($id);
        $faq->hidden = $request->visibility;
        $faq->save();

        return response()->json(['message' => 'FAQ Updated Successfully'], 200);
    }
}
