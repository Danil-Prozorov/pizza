<?php

namespace App\Http\Controllers\MarketControllers;

use App\Http\Requests\Public\CartDeleteItemRequest;
use App\Http\Requests\Public\CartAddItemRequest;
use App\Http\Controllers\Controller;
use App\Contracts\CartContract;

class CartController extends Controller
{

    private $cart;

    public function __construct(CartContract $cart)
    {
        $this->cart = $cart;
    }

    public function index()
    {
        return $this->cart->index();
    }

    public function store(CartAddItemRequest $request)
    {
        $item_data = $request->validated();

        return $this->cart->store($item_data);
    }

    public function destroy(CartDeleteItemRequest $request)
    {
        $request_data = $request->validated();

        return $this->cart->destroy($request_data);
    }
}
