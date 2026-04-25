<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminProductCreateRequest;
use App\Http\Requests\Admin\AdminProductUpdateRequest;
use Illuminate\Support\Facades\Cache;
use App\Models\Product;
use Exception;

class AdminProductController extends Controller
{
    public function index()
    {
        $products = Product::paginate(15);

        return response()->json(['status'=>'success','products' => $products],200);
    }

    public function create(AdminProductCreateRequest $request)
    {

        try{
            $data = $request->validated();

            if($request->hasFile('image')) {
                $path = $request->file('image')->store('uploads','public');
                $data['image'] = 'storage/'.$path;
            }

            Product::create($data);
        }catch (Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json(['status' => 'success', 'message' => 'Product created'],200);
    }

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

    public function update($id,AdminProductUpdateRequest $request)
    {
        try{
            $params = $request->validated();
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
