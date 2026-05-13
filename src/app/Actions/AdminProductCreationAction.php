<?php

namespace App\Actions;

use App\Contracts\AdminProductCreateContract;
use App\Models\Product;
use Exception;

class AdminProductCreationAction implements AdminProductCreateContract
{
    public function create($data,$request)
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
}
