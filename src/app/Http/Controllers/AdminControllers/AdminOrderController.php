<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Requests\Admin\AdminOrderUpdateProductsRequest as ProductRequest;
use App\Http\Requests\Admin\AdminOrderCreateRequest as CreateRequest;
use App\Http\Requests\Admin\AdminOrderUpdateRequest as UpdateRequest;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use App\Actions\AdminOrderCreation;
use Illuminate\Support\Facades\DB;
use App\Models\Order_products;
use App\Models\Order;


class AdminOrderController extends Controller
{

    public function index()
    {
        if (Cache::has('admin_order_list')) {
            return response()->json(['status' => 'success', 'data' => Cache::get('admin_order_list')]);
        }

        $orders = Order::paginate(15);

        Cache::put('admin_order_list', $orders);
        return response()->json(['status' => 'success', 'data' => $orders]);
    }

    public function create(CreateRequest $request)
    {
        try{
            $arguments = $request->validated();

            (new AdminOrderCreation())->handle($arguments);

        }catch (\Exception $e){
            return response()->json(['status' => 'error', 'message' => 'Cannot create order.'],404);
        }

        return response()->json(['status' => 'success', 'message' => 'Order created successfully.'],200);
    }

    public function show($id)
    {
        try{
            $order = Order::findOrFail(['id' => $id]);

            return response()->json(['status' => 'success', 'data' => $order],200);
        }catch (\Exception $e){
            return response()->json(['status' => 'error', 'message' => 'Cannot show order.'],404);
        }
    }

    public function update($id, UpdateRequest $request)
    {
        try {
            $params = $request->validated();
            $order  = Order::findOrFail(['id' => $id]);

            DB::transaction(function () use ($order, $params) {
                $order[0]->update($params);
            }, attempts: 3);

        }catch (\Exception $e){
            return response()->json(['status' => 'error', 'message' => 'Cannot update order.'],404);
        }

        return response()->json(['status' => 'success', 'message' => 'Order updated successfully.'],200);
    }

    public function updateProducts(ProductRequest $request)
    {
        try {
            $params = $request->validated();
            $product = Order_products::where('order_id', $params['order_id'])->where('product_id', $params['product_id'])->get();

            DB::transaction(function () use ($product, $params) {
                $product[0]->update($params);
            }, attempts: 3);
        }catch (\Exception $e){
            return response()->json(['status' => 'error', 'message' => 'Cannot update products.'],404);
        }

        return response()->json(['status' => 'success', 'message' => 'Product updated successfully.'],200);
    }

    public function destroy($id)
    {
        try{
            DB::transaction(function () use ($id) {
                Order::findOrFail(['id'=>$id])[0]->delete();
                Order_products::where(['order_id'=> $id])->delete();
            },attempts: 3);
        }catch (\Exception $e){
            return response()->json(['status' => 'error', 'message' => 'Cannot delete order.'],404);
        }

        return response()->json(['status' => 'success', 'message' => 'Order and related products deleted successfully.'],200);
    }
}
