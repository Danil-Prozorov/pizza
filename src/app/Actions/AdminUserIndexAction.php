<?php

namespace App\Actions;

use App\Contracts\AdminUserIndexContract;
use App\Models\User;

class AdminUserIndexAction implements AdminUserIndexContract
{
    public function index()
    {
        $product = User::paginate(15);

        return response()->json(['status' => 'success','users_list' => $product]);
    }
}
