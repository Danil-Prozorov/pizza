<?php

namespace App\Actions;

use Illuminate\Support\Facades\DB;
use App\Validators\CartValidator;
use App\Contracts\CartContract;
use App\Models\Product;
use App\Models\Cart;
use Exception;

class CartActions implements CartContract
{
    protected $user;

    public function __construct()
    {
        $this->user = auth('api')->user();
    }

    public function index()
    {
        if(!empty($this->user)){
            $cart = $this->user->cart->toArray();
            $total = $this->countTotal($cart);

            return response()->json(['cart' => $cart,'total_amount' => $total['total_amount'],'total_cost' => $total['total_cost']]);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function store($item_data)
    {
        try{
            $product = (new CartActions())->storeItems($item_data);
        }catch (\Exception $e){
            return response()->json(['status'=>'error','message' => 'Cannot add product'],401);
        }

        return response()->json(['status' => 'success','message'=>'Product added to cart','product' => $product],200);
    }

    public function destroy($request_data)
    {
        try{
            $item = $this->removeItem($request_data['product_id']);
            if(!$item){
                return response()->json(['status' => 'error','message' => 'Cannot delete product, product not found'],404);
            }
        }catch (Exception $e){
            return response()->json(['error'=>'Cannot remove product'],401);
        }

        return response()->json(['status'=>'success','message' => 'Product successfully removed'],200);
    }

    public function storeItems($item_data)
    {

        $item_data['user_id'] = $this->user->id;
        $existed = Cart::where('user_id',$this->user->id)->where('product_id',$item_data['product_id'])->first();

        // Checking available amount of items
        if($this->checkRequestedAmount($item_data['product_id'],$item_data['product_amount'])){
            throw new Exception('Requesting more items than available');
        }
        // Checking status of items, to not allow to add inactive products to cart
        if(!(new CartValidator())->validate($item_data['product_id'])){
            throw new Exception('Product unavailable');
        }

        try{
            $product = $this->save($existed,$item_data);
        }catch (\Exception $e){
            throw new Exception("Cannot add item to cart");
        }

        return $product;
    }

    public function removeItem($id){
        try{
            return Cart::where('user_id',$this->user->id)->where('product_id',$id)->delete();
        }catch (Exception $e){
            throw new Exception("Cannot remove item from cart");
        }
    }

    public function countTotal($cart)
    {
        $total_amount = 0;
        $total_cost   = 0;
        $id_list      = [];
        $prod_amount  = [];

        foreach($cart as $item){
            $total_amount += $item['product_amount'];
            $id_list[] = $item['product_id'];
            $prod_amount[] = $item['product_amount'];
        }

        $product_list = DB::table('products')->whereIn('id', $id_list)->get();

        for($i = 0; $i <= count($product_list)-1; $i++){

            $total_cost += $product_list[$i]->price * $prod_amount[$i];
        }

        return ['total_amount'=>$total_amount,'total_cost'=>$total_cost];
    }

    protected function save($existed,$request_data)
    {
        if($existed && $request_data['product_amount'] > 0){
            return $existed->update($request_data);
        }

        return Cart::create($request_data);
    }

    protected function checkRequestedAmount($product_id,$amount)
    {
        $available_amount = Product::find($product_id)->stock;

        if($available_amount >= $amount){
            return false;
        }

        return true;
    }
}
