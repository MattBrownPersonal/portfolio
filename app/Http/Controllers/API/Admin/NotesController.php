<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\ItemOrder;
use App\Models\ItemOrderNote;
use App\Models\Order;
use App\Models\OrderHistory;
use Illuminate\Http\Request;

class NotesController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return App\Models\ItemOrderNote
     */
    public function show($id)
    {
        return ItemOrderNote::where('item_order_id', $id)->with('oldStatus', 'newStatus', 'user')->orderByDesc('created_at')->get();
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
        $itemOrder = ItemOrder::findOrFail($request->id);
        $order = Order::find($itemOrder->order->id);

        if ($request->source === 'status') {
            $itemOrder->status_id = $request->new_status;
            $itemOrder->save();
        } elseif ($request->source === 'order_type') {
            $order->type = $request->new_type;
            $order->save();
        }

        $note = new ItemOrderNote;
        $note->old_status_id = $request->old_status;
        $note->new_status_id = $request->new_status;
        $note->old_type = $request->old_type;
        $note->new_type = $request->new_type;
        $note->note = $request->newNote;
        $note->item_order_id = $request->id;
        $note->user_id = $request->user_id;
        $note->save();

        if ($request->source === 'status') {
            $statuses = [];
            $orderItemStatuses = $itemOrder->order->itemorders()->first()->status()->get();
            foreach ($orderItemStatuses as $status) {
                array_push($statuses, $status->id);
            }
            $orderStatusId = min($statuses);
            $order->status_id = $orderStatusId;
            $order->save();
        }

        $orderHistory = new OrderHistory();
        $orderHistory->order_id = $itemOrder->order->id;
        $orderHistory->status_id_from = $request->old_status;
        $orderHistory->status_id_to = $request->new_status;
        $orderHistory->type_from = $request->old_type;
        $orderHistory->type_to = $request->new_type;
        $orderHistory->save();

        return response()->json(['message' => 'Order Status Updated Successfully'], 200);
    }
}
