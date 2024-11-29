<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return App\Models\Order
     */
    public function index()
    {
        $roles = Auth::user()->roles->pluck('id')->toArray();
        $sites = Auth::user()->sites->pluck('id')->toArray();

        if (in_array(Role::SITE_STAFF_ID, $roles) || in_array(Role::SITE_ADMIN_ID, $roles)) {
            return Order::whereIn('site_id', $sites)->with('site', 'status', 'deceased')->orderBy('created_at', 'DESC')->get();
        }

        return Order::with('site', 'status', 'deceased')->orderBy('created_at', 'DESC')->get();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return Illuminate\Http\Response
     */
    public function show($id)
    {
        $orders = Order::with('site', 'itemorders.product', 'itemorders.status', 'itemorders.product.supplier', 'itemorders.lines', 'deceased', 'status')->find($id);

        return response()->json(['orders' => $orders, 'id' => Auth::user()->id]);
    }

    public function fetchProductAttributes($product, $site)
    {
        return Order::fetchProductAttributes($product, $site);
    }

    public function updateCustomerDetails(Request $request, $id)
    {
        switch ($request->type) {
            case 'editCustomer':
                return Order::updateCustomerDetails($request, $id);
                break;
            case 'editDeceased':
                return Order::updateDeceasedDetails($request);
                break;
            case 'editProduct':
                return Order::updateProductDetails($request, $id);
                break;
            case 'editItemOrders':
                return Order::updateItemOrders($request, $id);
                break;
        }
    }
}
