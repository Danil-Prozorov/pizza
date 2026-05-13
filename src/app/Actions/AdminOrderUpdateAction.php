<?php

namespace App\Actions;

use App\Contracts\AdminOrderUpdateContract;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use Exception;

class AdminOrderUpdateAction implements AdminOrderUpdateContract
{
    public function update($id,$params)
    {
        try {
            $order  = Order::findOrFail(['id' => $id]);

            DB::transaction(function () use ($order, $params) {
                $order[0]->update($params);
            }, attempts: 3);

        }catch (Exception $e){
            return response()->json(['status' => 'error', 'message' => 'Cannot update order.'],404);
        }

        return response()->json(['status' => 'success', 'message' => 'Order updated successfully.'],200);
    }
}
