<?php

namespace App\Http\Controllers\AdminControllers;

use App\Contracts\AdminOrderUpdateProductContract as IAdminOrderUpdateProducts;
use App\Http\Requests\Admin\AdminOrderUpdateProductsRequest as ProductRequest;
use App\Http\Requests\Admin\AdminOrderCreateRequest as CreateRequest;
use App\Http\Requests\Admin\AdminOrderUpdateRequest as UpdateRequest;
use App\Contracts\AdminOrderCreationContract as IAdminOrderCreation;
use App\Contracts\AdminOrderDestroyContract as IAdminOrderDestroy;
use App\Contracts\AdminOrderUpdateContract as IAdminOrderUpdate;
use App\Contracts\AdminOrderIndexContract as IAdminOrderIndex;
use App\Contracts\AdminOrderShowContract as IAdminOrderShow;
use App\Http\Controllers\Controller;

class AdminOrderController extends Controller
{

    public function index(IAdminOrderIndex $index)
    {
        return $index->handle();
    }

    public function create(CreateRequest $request, IAdminOrderCreation $creator)
    {
        $arguments = $request->validated();

        return $creator->handle($arguments);
    }

    public function show($id,IAdminOrderShow $show)
    {
        return $show->show($id);
    }

    public function update($id, UpdateRequest $request, IAdminOrderUpdate $order)
    {
        $params = $request->validated();

        return $order->update($id, $params);
    }

    public function updateProducts(ProductRequest $request, IAdminOrderUpdateProducts $order_products)
    {
        $params = $request->validated();

        return $order_products->update($params);
    }

    public function destroy($id, IAdminOrderDestroy $order)
    {
        return $order->destroy($id);
    }
}
