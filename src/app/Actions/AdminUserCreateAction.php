<?php

namespace App\Actions;

use App\Contracts\AdminUserCreateContract;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Exception;

class AdminUserCreateAction implements AdminUserCreateContract
{
    public function create($userdata)
    {
        try{
            $userdata['password'] = Hash::make($userdata['password']);

            User::create($userdata);
        }catch (Exception $e){
            return response()->json(['status' =>'error','message' => 'Impossible to create user'], 400);
        }

        return response()->json(['status' => 'success','message' => 'User created'], 200);
    }
}
