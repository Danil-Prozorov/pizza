<?php

namespace App\Actions;

use Illuminate\Support\Facades\DB;
use App\Models\Order_products;
use App\Models\Product;
use App\Models\Order;


class AdminOrderCreation
{
    public function handle($arguments): void
    {
        $totalAmount = $this->countTotalProductAmount($arguments['quantity']);
        $products    = $this->getListFromIdArray($arguments['products_id']);

        DB::transaction(function() use ($arguments, $totalAmount, $products) {
            $order = Order::create([
                'user_id'  => $arguments['user_id'],
                'status'   => 1,
                'products' => $totalAmount,
                'address'  => $arguments['address'],
            ]);

            $prod_count = 0;
            foreach ($products as $product) {
                Order_products::create([
                    'order_id'          => $order->id,
                    'product_id'        => $product->id,
                    'name'              => $product->name,
                    'image'             => $product->image,
                    'price'             => $product->price,
                    'product_amount'    => $arguments['quantity'][$prod_count],
                    'short_description' => $product->short_desc,
                ]);

                $prod_count++;
            }
        }, attempts: 3);

    }

    protected function countTotalProductAmount($products)
    {
        $count = 0;
        foreach ($products as $product) {
            $count += $product;
        }

        return $count;
    }

    protected function getListFromIdArray($list)
    {
        $products_list = [];

        foreach($list as $id){
            $products_list[] = Product::findOrFail($id);
        }

        return $products_list;
    }
}
