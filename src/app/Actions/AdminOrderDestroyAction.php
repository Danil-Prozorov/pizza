<?php

namespace App\Actions;

use App\Contracts\AdminOrderDestroyContract;
use Illuminate\Support\Facades\DB;
use App\Models\OrderProducts;
use App\Models\Order;
use Exception;

class AdminOrderDestroyAction implements AdminOrderDestroyContract
{
    public function destroy($id)
    {
        try{
            DB::transaction(function () use ($id) {
                Order::findOrFail(['id'=>$id])[0]->delete();
                OrderProducts::where(['order_id'=> $id])->delete();
            },attempts: 3);
        }catch (Exception $e){
            return response()->json(['status' => 'error', 'message' => 'Cannot delete order.'],404);
        }

        return response()->json(['status' => 'success', 'message' => 'Order and related products deleted successfully.'],200);
    }
}
