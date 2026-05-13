<?php

namespace App\Actions;

use App\Contracts\AdminOrderIndexContract;
use Illuminate\Support\Facades\Cache;
use App\Models\Order;

class AdminOrderIndexAction implements AdminOrderIndexContract
{
    public function handle()
    {
        if (Cache::has('admin_order_list')) {
            return response()->json(['status' => 'success', 'data' => Cache::get('admin_order_list')]);
        }

        $orders = Order::paginate(15);

        Cache::put('admin_order_list', $orders);
        return response()->json(['status' => 'success', 'data' => $orders]);
    }
}
