<?php

namespace App\Actions;

use App\Contracts\AdminProductDestroyContract;
use Illuminate\Support\Facades\Cache;
use App\Models\Product;
use Exception;

class AdminProductDestroyAction implements AdminProductDestroyContract
{
    public function destroy($id)
    {
        try{
            $product = Product::whereId($id)->delete();

            if(!$product){
                throw new Exception("Product not found");
            }

            if(Cache::get('product_'.$id)){
                Cache::forget('product_'.$id);
            }
        }catch(Exception $e){
            return response()->json(['status'=>'error','message'=> 'Product not found'],404);
        }

        return response()->json(['status' => 'success', 'message' => 'Product deleted'],200);
    }
}
