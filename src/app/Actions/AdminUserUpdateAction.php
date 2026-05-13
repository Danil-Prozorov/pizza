<?php

namespace App\Actions;

use App\Contracts\AdminUserUpdateContract;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Exception;

class AdminUserUpdateAction implements AdminUserUpdateContract
{
    public function update($id, $userdata)
    {
        try{
            $user = User::whereId($id);

            if(isset($userdata['password'])){
                $userdata['password'] = Hash::make($userdata['password']);
            }

            $updated = $user->update($userdata);

            if(!$updated){
                throw new Exception('Impossible to update user');
            }

            if(Cache::has('user_'.$id)){
                Cache::forget('user_'.$id);
            }
        }catch(Exception $e){
            return response()->json(['status' => 'error' , 'message' => 'User not updated'], 202);
        }

        return response()->json(['status' => 'success','message' => 'User updated'], 201);
    }
}
