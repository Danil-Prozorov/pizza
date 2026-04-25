<?php

namespace App\Actions;

use App\Validators\CustomerOrderValidator as OrderValidator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\Order_statuses;
use App\Models\Order_products;
use App\Models\Product;
use App\Models\Order;
use Exception;

class OrderActions
{
    public function createOrder($params)
    {
        try {
            $user = auth('api')->user();
            $products = $user->cart->toArray();

            if (empty($products)) {
                throw new Exception("No products in cart");
            }

            $products = $this->prepareProducts($products);

            DB::transaction(function () use ($products, $user, $params) {
                $order = Order::create($this->prepareOrderParams([
                    'user'            => $user,
                    'products_amount' => $products['total_amount'],
                    'address'         => $params['address'],
                ]));

                foreach ($products['products'] as $product) {
                    if(!(new OrderValidator())->validateAccessibleAmount($product['product']->id,$product['product_amount'])){
                        throw new Exception("Invalid product amount");
                    }

                    Order_products::create($this->prepareItemInfoForStore($order,[
                        'product_data' => $product['product'],
                        'quantity'     => $product['product_amount']
                    ]));

                    $current_product = Product::find($product['product']->id);
                    $current_product->update(['stock' =>$current_product->stock - $product['product_amount']]);

                    (new CartActions())->removeItem($product['product']->id);
                }

            },attempts: 3);

        }catch (Exception $e){
            throw new Exception($e->getMessage());
        }

    }

    public function getOrderProductList($orders):array
    {
        $list = [];

        foreach ($orders as $order) {
            $list[] = ['order_'.$order->id.'_products' => Order_products::where('order_id',$order->id)->get()->toArray()];
        }

        return $list;
    }

    public function connectOrderStatuses($orders)
    {

        foreach ($orders as $order) {
            $order->status = Order_statuses::find($order->status)->status_name;
        }

        return $orders;
    }

    public function getOrderAndProducts($order_id):array
    {
        if(Cache::has('order_'.$order_id)) {
            return Cache::get('order_'.$order_id);
        }

        $user   = auth('api')->user();
        $orders = $this->connectOrderStatuses($user->orders);
        $list   = $this->getDetailedOrderData($order_id, $orders);

        if(empty($list['order_data']) && empty($list['product_data'])){
            throw new Exception("Order is empty");
        }

        Cache::put('order_'.$order_id, $list, 120);
        return  $list;
    }

    public function cancelOrder($id)
    {
        try{
            $order    = Order::findOrFail(['id' => $id]);
            $user  = auth('api')->user();

            if($order[0]->user_id != $user->id){
                throw new Exception("Invalid order");
            }

            $order[0]->update(['status'=>5]);
        }catch (Exception $e){
            throw new Exception('Cannot cancel order');
        }

        return $order;
    }

    protected function prepareProducts($products):array
    {
        $output     = [];
        $prod_count = 0;

        foreach ($products as $product) {
            $item = Product::find($product['product_id']);

            if (!empty($item)) {
                $output[] = ['product' => $item, 'product_amount' => $product['product_amount']];
                $prod_count += $product['product_amount'];
            }
        }

        return ['products' => $output, 'total_amount' => $prod_count];
    }

    protected function prepareOrderParams($params):array
    {
        return [
            'user_id'  => $params['user']->id,
            'status'   => 1,
            'products' => $params['products_amount'],
            'address'  => $params['address'],
        ];
    }

    protected function prepareItemInfoForStore($order,$params):array
    {
        return [
            'order_id'          => $order->id,
            'product_id'        => $params['product_data']->id,
            'name'              => $params['product_data']->name,
            'image'             => $params['product_data']->image,
            'price'             => $params['product_data']->price,
            'product_amount'    => $params['quantity'],
            'short_description' => $params['product_data']->short_desc,
        ];
    }

    protected function getDetailedOrderData($id,$orders)
    {
        $info_list   = [
            'order_data' => [],
            'product_data' => []
        ];

        foreach($orders as $order){
            if($order['id'] == $id){
                $info_list['order_data'] = $order->toArray();
                $info_list['product_data'] = $order->ordered_products->toArray();
                break;
            }
        }

        return $info_list;
    }
}
