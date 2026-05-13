<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Requests\Admin\AdminProductCreateRequest as CreateRequest;
use App\Contracts\AdminProductDestroyContract as ProductDestroy;
use App\Contracts\AdminProductCreateContract as ProductCreate;
use App\Contracts\AdminProductUpdateContract as ProductUpdate;
use App\Contracts\AdminProductIndexContract as ProductIndex;
use App\Contracts\AdminProductShowContract as ProductShow;
use App\Http\Requests\Admin\AdminProductUpdateRequest;
use App\Http\Controllers\Controller;

class AdminProductController extends Controller
{
    public function index(ProductIndex $products)
    {
        return $products->index();
    }

    public function create(CreateRequest $request, ProductCreate  $productCreate)
    {
        $data = $request->validated();

        return $productCreate->create($data,$request);
    }

    public function show($id, ProductShow $product)
    {
        return $product->show($id);
    }

    public function update($id,AdminProductUpdateRequest $request,ProductUpdate $product)
    {
        $params = $request->validated();

        return $product->update($id,$params);
    }

    public function destroy($id,ProductDestroy $product)
    {
        return $product->destroy($id);
    }
}
