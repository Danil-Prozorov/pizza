<?php

namespace App\Actions;

use App\Contracts\AdminOrderUpdateProductContract;
use Illuminate\Support\Facades\DB;
use App\Models\OrderProducts;
use Exception;

class AdminOrderUpdateProductAction implements AdminOrderUpdateProductContract
{
    public function update($params)
    {
        try {
            $product = OrderProducts::where('order_id', $params['order_id'])->where('product_id', $params['product_id'])->get();

            DB::transaction(function () use ($product, $params) {
                $product[0]->update($params);
            }, attempts: 3);
        }catch (Exception $e){
            return response()->json(['status' => 'error', 'message' => 'Cannot update products.'],404);
        }

        return response()->json(['status' => 'success', 'message' => 'Product updated successfully.'],200);
    }
}
