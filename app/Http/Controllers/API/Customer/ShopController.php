<?php

namespace App\Http\Controllers\API\Customer;

use App\Http\Controllers\Controller;
use App\Models\Deceased;
use App\Models\Order;
use App\Models\Site;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  Illuminate\Http\Request  $request
     *
     * @return Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $order = Order::saveOrder($request, Order::ORDER);
        $orderItem = Order::saveOrderItem($request, $order);

        try {
            Order::sendOrderEmail($request, $order, $orderItem);
        } catch(\Exception $e) {
            return response()->json(['message' => 'Error: Could not send email',
                'error' => $e->getMessage(),
                'order_number' => $order->order_number,
            ], 400);
        }

        return ['order_number' => $order->order_number];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $code
     *
     * @return App\Models\Deceased;
     */
    public function show($code)
    {
        if (is_numeric($code)) {
            $deceased = Deceased::where('code', $code)->with('site.sitestyle', 'site.sitestyle.image')->first();

            return ['isGenericSite' => false, 'deceased' => $deceased];
        }

        $siteStyle = Site::where('slug', $code)->with('sitestyle', 'sitestyle.image')->first();

        return ['isGenericSite' => true, 'siteStyle' => $siteStyle];
    }
}
