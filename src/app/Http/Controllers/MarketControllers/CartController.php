<?php

namespace App\Http\Controllers\MarketControllers;

use App\Http\Requests\Public\CartDeleteItemRequest;
use App\Http\Requests\Public\CartAddItemRequest;
use App\Http\Controllers\Controller;
use App\Actions\CartActions;
use App\Models\User;
use Exception;


class CartController extends Controller
{

    public function index()
    {
        $user = auth('api')->user();

        if(!empty($user)){
            $cart = User::find($user->id)->cart->toArray();
            $total = (new CartActions())->countTotal($cart);

            return response()->json(['cart' => $cart,'total_amount' => $total['total_amount'],'total_cost' => $total['total_cost']]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function store(CartAddItemRequest $request)
    {
        try{
            $item_data = $request->validated();

            $product = (new CartActions())->storeItems($item_data);
        }catch (\Exception $e){
            return response()->json(['status'=>'error','message' => 'Cannot add product'],401);
        }

        return response()->json(['status' => 'success','message'=>'Product added to cart','product' => $product],200);
    }

    public function destroy(CartDeleteItemRequest $request)
    {
        $request_data = $request->validated();

        try{
            $item = (new CartActions())->removeItem($request_data['product_id']);
            if(!$item){
                return response()->json(['status' => 'error','message' => 'Cannot delete product, product not found'],404);
            }
        }catch (Exception $e){
            return response()->json(['error'=>'Cannot remove product'],401);
        }

        return response()->json(['status'=>'success','message' => 'Product successfully removed'],200);
    }
}
