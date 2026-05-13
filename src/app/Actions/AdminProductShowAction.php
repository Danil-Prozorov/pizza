<?php

namespace App\Actions;

use App\Contracts\AdminProductShowContract;
use Illuminate\Support\Facades\Cache;
use App\Models\Product;
use Exception;

class AdminProductShowAction implements AdminProductShowContract
{
    public function show($id)
    {
        if(Cache::get('product_'.$id)){
            return response()->json(['status' => 'success','product' => Cache::get('product_'.$id)]);
        }

        try{
            $product = Product::findOrFail(['id' => $id]);

            Cache::put('product_'.$id,$product,800);

            return response()->json(['status' => 'success','product' => $product]);
        }catch (Exception $e){
            return response()->json(['status'=>'error','message'=>'Product not found'],404);
        }
    }
}
