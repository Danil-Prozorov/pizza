<?php

namespace App\Http\Controllers\MarketControllers;

use App\Http\Requests\Public\OrderCreateRequest;
use App\Http\Controllers\Controller;
use App\Actions\OrderActions;
use Exception;

class OrderController extends Controller
{
    public function index()
    {
        try{
            $user           = auth('api')->user();
            $orders         = (new OrderActions())->connectOrderStatuses($user->orders);
            $order_products = (new OrderActions())->getOrderProductList($user->orders);
        }catch (Exception $e){
            return response()->json([['status' => 'error', 'message' => "Cannot display an orders"], 500]);
        }

        return response()->json(['status' => 'success','orders_list' => $orders,'orders_products' => $order_products]);
    }

    public function create(OrderCreateRequest $request)
    {
        try{
            $params = $request->validated();

            (new OrderActions())->createOrder($params);
        }catch (Exception $e){
            return response()->json(['status'=>'error','message' => 'Cannot create an order'], 400);
        }

        return response()->json(['status'=>'success','message'=>'Order created successfully'],200);
    }

    public function show($id)
    {
        try{
            return response()->json(['status' => 'success','data' => (new OrderActions())->getOrderAndProducts($id)],200);
        }catch (\Exception $e){
            return response()->json(['status' => 'error','message' => 'Can not get order №'.$id],404);
        }
    }
    // Not actually delete, but change status to canceled
    public function delete($id)
    {
        try{
            $change = (new OrderActions())->cancelOrder($id);
            return response()->json(['status'=>'success','message'=>'Order deleted successfully','order_data' => $change],200);
        }catch (Exception $e){
            return response()->json(['status' => 'error','message' => $e->getMessage()], 401);
        }
    }
}
