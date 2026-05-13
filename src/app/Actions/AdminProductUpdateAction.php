<?php

namespace App\Actions;

use App\Contracts\AdminProductUpdateContract;
use Illuminate\Support\Facades\Cache;
use App\Models\Product;
use Exception;

class AdminProductUpdateAction implements AdminProductUpdateContract
{
    public function update($id,$params)
    {
        try{
            $product = Product::whereId($id)->update($params);

            if(!$product){
                throw new Exception("Product not found");
            }

            if(Cache::get('product_'.$id)){
                Cache::put('product_'.$id,Product::find($id),800);
            }
        }catch (Exception $e){
            return response()->json(['status'=>'error','message'=>'Product not found'],404);
        }

        return response()->json(['status' => 'success', 'message' => 'Product updated'],200);
    }
}
