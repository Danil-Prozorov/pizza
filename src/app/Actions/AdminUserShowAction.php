<?php

namespace App\Actions;

use App\Contracts\AdminUserShowContract;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use Exception;

class AdminUserShowAction implements AdminUserShowContract
{
    public function show($id)
    {
        try{
            if(Cache::has('user_'.$id)){
                return response()->json(Cache::get('user_'.$id));
            }

            $user = User::findOrFail(['id' => $id]);
            Cache::put('user_'.$id, $user,1600);

            return response()->json(['status' => 'success','user_data' => $user],200);
        }catch (Exception $e){
            return response()->json(['status' => 'error','message' => 'User not found'], 404);
        }
    }
}
