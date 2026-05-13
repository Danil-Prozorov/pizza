<?php

namespace App\Http\Controllers\MarketControllers;

use App\Http\Requests\Public\OrderCreateRequest;
use App\Http\Controllers\Controller;
use App\Contracts\OrderContract;

class OrderController extends Controller
{

    private $order;

    public function __construct(OrderContract $order)
    {
        $this->order = $order;
    }

    public function index()
    {
        return $this->order->index();
    }

    public function create(OrderCreateRequest $request)
    {
        $params = $request->validated();

        return $this->order->create($params);
    }

    public function show($id)
    {
        return $this->order->show($id);
    }
    // Not actually delete, but change status to canceled
    public function delete($id)
    {
        return $this->order->destroy($id);
    }
}
