<?php

namespace App\Actions;

use App\Contracts\AdminProductIndexContract;
use App\Models\Product;

class AdminProductIndexAction implements AdminProductIndexContract
{
    public function index()
    {
        $products = Product::paginate(15);

        return response()->json(['status'=>'success','products' => $products],200);
    }
}
