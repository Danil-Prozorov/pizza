<?php

namespace App\Actions;

use App\Validators\CartValidator;
use App\Models\Product;
use App\Models\Cart;
use Exception;

class CartActions
{
    protected $user;

    public function __construct()
    {
        $this->user = auth('api')->user();
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
            $product = $this->store($existed,$item_data);
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

        foreach($cart as $item){
            $total_amount += $item['product_amount'];
            $total_cost   += Cart::find($item['id'])->products->price * $item['product_amount'];
        }

        return ['total_amount'=>$total_amount,'total_cost'=>$total_cost];
    }

    protected function store($existed,$request_data)
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
