<?php

namespace App\Http\Controllers\MarketControllers;

use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    public function show($id)
    {
        try{
            if(Cache::get('product_id_'.$id)){
                return response()->json(['status' => 'success','product' => Cache::get('product_id_'.$id)],200);
            }

            $product = Product::findOrFail(['id' => $id]);
            return response()->json(['status' => 'success','product' => $product],200);
        }catch (\Exception $e){
            return response()->json(['status'=>'error','message' => 'Product not found'],404);
        }
    }
}
