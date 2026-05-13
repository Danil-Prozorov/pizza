<?php

namespace App\Actions;

use App\Contracts\AdminUserDestroyContract;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use Exception;

class AdminUserDestroyAction implements AdminUserDestroyContract
{
    public function destroy($id)
    {
        try{
            $user = User::findOrFail($id);
            $user->delete();

            if(Cache::has('user_'.$id)){
                Cache::forget('user_'.$id);
            }
        }catch (Exception $e){
            return response()->json(['status' => 'error' , 'message' => 'Impossible to delete user'], 404);
        }

        return response()->json(['status' => 'success','message' => 'User deleted'], 200);
    }
}
