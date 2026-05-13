<?php

namespace App\Actions;

use App\Contracts\AdminOrderCreationContract;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\User;
use Exception;

class AdminOrderCreationAction implements AdminOrderCreationContract
{
    public function handle($arguments)
    {
        try {
            $totalAmount = $this->countTotalProductAmount($arguments['quantity']);
            $products = $this->getListFromIdArray($arguments['products_id']);

            User::findOrFail(["id" => $arguments['user_id']]);

            DB::transaction(function () use ($arguments, $totalAmount, $products) {
                $order = Order::create([
                    'user_id' => $arguments['user_id'],
                    'status' => 1,
                    'products' => $totalAmount,
                    'address' => $arguments['address'],
                ]);

                $params_list = ['order' => $order, 'arguments' => $arguments];
                $counter = 0;
                $products_list = [];

                foreach ($products as $product) {
                    $params_list['product'] = $product;
                    $products_list[] = $this->getProductParamsList($params_list, $counter);
                    $counter++;
                }

                foreach (array_chunk($products_list, 100) as $chunk) {
                    DB::table('orderProducts')->insert($chunk);
                }
            }, attempts: 3);

        }catch (Exception $e){
            return response()->json(['status' => 'error', 'message' => 'Cannot create order.'],404);
        }

        return response()->json(['status' => 'success', 'message' => 'Order created successfully.'],200);
    }

    protected function countTotalProductAmount($products)
    {
        $count = 0;
        foreach ($products as $product) {
            $count += $product;
        }

        return $count;
    }

    protected function getProductParamsList($params,$counter)
    {
        return [
            'order_id'          => $params['order']->id,
            'product_id'        => $params['product']->id,
            'name'              => $params['product']->name,
            'image'             => $params['product']->image,
            'price'             => $params['product']->price,
            'product_amount'    => $params['arguments']['quantity'][$counter],
            'short_description' => $params['product']->short_desc,
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s'),
        ];
    }

    protected function getListFromIdArray($list)
    {
        return DB::table('products')->whereIn('id',$list)->get();
    }
}
