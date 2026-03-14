<?php

namespace App\Http\Controllers\AdminControllers;

use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class AdminProductController extends Controller
{
    public function index()
    {
        if(Cache::has('main_products')) {
            return response()->json(Cache::get('main_products'));
        }

        $products = Product::paginate(15);
        Cache::put('main_products',$products,30);

        return response()->json($products);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'mimes:jpeg,png,jpg,gif,svg|max:2048',
            'price' => 'required|integer',
            'description' => 'string|nullable',
            'recipe' => 'string|nullable',
            'short_desc' => 'string|nullable|max:255',
            'category' => 'required|integer',
            'stock' => 'integer',
            'active' => 'integer',
            'status' => 'integer|exists:product_statuses,id',
        ]);

        try{
            $data = $request->toArray();

            if($request->hasFile('image')) {
                $path = $request->file('image')->store('uploads','public');
                $data['image'] = 'storage/'.$path;
            }

            Product::create($data);
        }catch (\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json(['status' => 'success', 'message' => 'Product created'],200);
    }

    public function show($id)
    {
        if(Cache::get('product_'.$id)){
            return response()->json(Cache::get('product_'.$id));
        }

        try{
            $product = Product::findOrFail(['id' => $id]);

            Cache::put('product_'.$id,$product,800);

            return response()->json($product);
        }catch (\Exception $e){
            return response()->json(['error'=>'Product not found'],404);
        }
    }

    public function update($id,Request $request)
    {
        try{
            Product::whereId($id)->update($request->all());

            if(Cache::get('product_'.$id)){
                Cache::put('product_'.$id,Product::find($id),800);
            }
        }catch (\Exception $e){
            return response()->json(['error'=>'Product not found'],404);
        }

        return response()->json(['status' => 'success', 'message' => 'Product updated'],200);
    }

    public function destroy($id)
    {
        try{
            Product::whereId($id)->delete();

            if(Cache::get('product_'.$id)){
                Cache::forget('product_'.$id);
            }
        }catch(\Exception $e){
            return response()->json(['error' => 'Product not found'],404);
        }

        return response()->json(['status' => 'success', 'message' => 'Product deleted'],200);
    }
}
