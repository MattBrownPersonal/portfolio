<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deceased;
use App\Models\ItemOrder;
use App\Models\Order;
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
        $cartItems = json_decode($request->items, true);
        $order = new Order;
        $order->deceased_id = $request->id;
        $order->order_date = $request->order_date;
        $order->status = $request->status;
        $order->site_id = $request->site_id;
        $order->order_number = $this->generateOrderNumber();
        $order->notes = '';
        $order->save();

        foreach ($cartItems as $item) {
            $orderItem = new ItemOrder;
            $orderItem->product_id = $item['product']['id'];
            $orderItem->order_id = $order->id;
            $orderItem->status = 0;
            $orderItem->save();
        }

        return $request->items;
    }

    /**
     * generateOrderNumber
     *
     * @return int
     */
    public function generateOrderNumber()
    {
        $number = mt_rand(1000000, 9999999);

        if ($this->orderNumberExists($number)) {
            return $this->generateOrderNumber();
        }

        return $number;
    }

    /**
     * orderNumberExists
     *
     * @param int $number
     *
     * @return App\Models\Order
     */
    public function orderNumberExists($number)
    {
        return Order::where('order_number', $number)->exists();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $code
     *
     * @return App\Models\Deceased
     */
    public function show($code)
    {
        return Deceased::where('code', $code)->first()->site->sitestyle;
    }
}
