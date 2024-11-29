<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use App\Mail\CustomerContact;
use App\Models\Memorialisations;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MemorialisationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Illuminate\Http\Response
     */
    public function index()
    {
        return Memorialisations::all();
    }

    /**
     * sendContactMessage.
     *
     * @param  Illuminate\Http\Request  $request
     *
     * @return Illuminate\Http\Response
     */
    public function sendContactMessage(Request $request)
    {
        $fullNumber = str_replace(' ', '', $request->contactNumber);
        if (substr($fullNumber, 0, 1) != '0') {
            return response()->json(['error' => 'Phone Number Invalid'], 422);
        } elseif (strlen($fullNumber) < 10 || strlen($fullNumber) > 11) {
            return response()->json(['error' => 'Phone Number Incorect Length'], 422);
        }
        if ($request->origin === 'contactUs') {
            $order = Order::saveOrder($request, Order::ENQUIRYFROMCONTACTUS);
        } else {
            $order = Order::saveOrder($request, Order::ENQUIRYFROMSHARE);
        }
        $orderItem = Order::saveOrderItemFromContact($request, $order);
        $bccAddress = env('MAIL_BCC_ADDRESS', 'ENV_SECRET_NOT_SETUP');
        try {
            Mail::to($request->email)->send(new CustomerContact($request->name, $request->contactNumber, $request->customerEmail, $request->message, $bccAddress));
        } catch(\Exception $e) {
            return response()->json(['message' => 'Error: Could not send email',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * getMemorialisation.
     *
     * @param  int  $id
     *
     * @return App\Models\Memorialisations
     */
    public function getMemorialisation($id)
    {
        return Memorialisations::find($id);
    }
}
