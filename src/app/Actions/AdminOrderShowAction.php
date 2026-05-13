<?php

namespace App\Actions;

use App\Contracts\AdminOrderShowContract;
use App\Models\Order;
use Exception;

class AdminOrderShowAction implements AdminOrderShowContract
{
    public function show($id)
    {
        try{
            $order = Order::findOrFail(['id' => $id]);

            return response()->json(['status' => 'success', 'data' => $order],200);
        }catch (Exception $e){
            return response()->json(['status' => 'error', 'message' => 'Cannot show order.'],404);
        }
    }
}
