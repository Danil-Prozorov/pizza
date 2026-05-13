<?php

namespace App\Actions;

use App\Validators\CustomerOrderValidator as OrderValidator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Contracts\OrderContract;
use App\Models\OrderStatuses;
use App\Models\OrderProducts;
use App\Models\Product;
use App\Models\Order;
use App\Models\Cart;
use Exception;

class OrderActions implements OrderContract
{
    private $user;

    public function __construct()
    {
        $this->user = auth('api')->user();
    }

    public function index()
    {
        try{
            $orders         = $this->connectOrderStatuses($this->user->orders);
            $orderProducts = $this->getOrderProductList($this->user->orders);
        }catch (Exception $e){
            return response()->json([['status' => 'error', 'message' => "Cannot display an orders".$e->getMessage()], 500]);
        }

        return response()->json(['status' => 'success','orders_list' => $orders,'orders_products' => $orderProducts]);
    }

    public function show($id)
    {
        try{
            return response()->json(['status' => 'success','data' => $this->getOrderAndProducts($id)],200);
        }catch (Exception $e){
            return response()->json(['status' => 'error','message' => 'Can not get order №'.$id],404);
        }
    }

    public function create($params)
    {
        try{
            $this->createOrder($params);
        }catch (Exception $e){
            return response()->json(['status'=>'error','message' => 'Cannot create an order'], 400);
        }

        return response()->json(['status'=>'success','message'=>'Order created successfully'],200);
    }

    public function destroy($id)
    {
        try{
            $change = $this->cancelOrder($id);
            return response()->json(['status'=>'success','message'=>'Order deleted successfully','order_data' => $change],200);
        }catch (Exception $e){
            return response()->json(['status' => 'error','message' => $e->getMessage()], 401);
        }
    }

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

                $order_product_list = [];

                foreach ($products['products'] as $product) {
                    if(!(new OrderValidator())->validateAccessibleAmount($product['product']->id,$product['product_amount'])){
                        throw new Exception("Invalid product amount");
                    }

                    $order_product_list[] = $this->prepareItemInfoForStore($order,[
                        'product_data' => $product['product'],
                        'quantity'     => $product['product_amount']
                    ]);
                    // Не нашёл способа без цикла обновить значения товаров. whereIn + update позволяет указать только 1
                    // параметр для обновления для всех, а не отдельный для каждого :(
                    $current_product = Product::find($product['product']->id);
                    $current_product->update(['stock' => $current_product->stock - $product['product_amount']]);
                }


                foreach (array_chunk($order_product_list, 100) as $chunk) {
                    DB::table('orderProducts')->insert($chunk);
                }

                DB::table('carts')->whereIn('user_id',[$this->user->id])->delete();

            },attempts: 3);

        }catch (Exception $e){
            throw new Exception($e->getMessage());
        }

    }

    public function getOrderProductList($orders):array
    {
        $ids  = [];
        foreach ($orders as $order) {
            $ids[]  = $order->id;
        }

        $list = DB::table('orderProducts')->whereIn('order_id',$ids)->get()->toArray();
        return $list;
    }

    public function connectOrderStatuses($orders)
    {

        foreach ($orders as $order) {
            $order->status = OrderStatuses::find($order->status)->status_name;
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
                $info_list['product_data'] = $order->orderedProducts->toArray();
                break;
            }
        }

        return $info_list;
    }
}
