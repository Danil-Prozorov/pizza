<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Requests\Admin\AdminUserUpdateRequest;
use App\Http\Requests\Admin\AdminUserCreateRequest;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Exception;

class AdminUsersController extends Controller
{
    public function index()
    {
        $product = User::paginate(15);

        return response()->json(['status' => 'success','users_list' => $product]);
    }

    public function create(AdminUserCreateRequest $request)
    {
        try{
            $userdata = $request->validated();
            $userdata['password'] = Hash::make($userdata['password']);

            User::create($userdata);
        }catch (Exception $e){
            return response()->json(['status' =>'error','message' => 'Impossible to create user'], 400);
        }

        return response()->json(['status' => 'success','message' => 'User created'], 200);
    }

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

    public function update($id, AdminUserUpdateRequest $request)
    {
        try{
            $user = User::whereId($id);
            $userdata = $request->validated();

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
