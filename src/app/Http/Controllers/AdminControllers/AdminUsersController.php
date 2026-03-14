<?php

namespace App\Http\Controllers\AdminControllers;

use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;

class AdminUsersController extends Controller
{
    public function index()
    {
        $product = User::paginate(15);

        return response()->json($product);
    }

    public function create(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email'    => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:6',
            'is_admin' => 'required|boolean'
        ]);

        try{
            $userdata = $request->toArray();
            $userdata['password'] = Hash::make($userdata['password']);

            User::create($userdata);
        }catch (\Exception $e){
            return response()->json(['error' => 'Impossible to create user'], 404);
        }

        return response()->json(['status' => 'success','message' => 'User created'], 201);
    }

    public function show($id)
    {
        try{
            if(Cache::has('user_'.$id)){
                return response()->json(Cache::get('user_'.$id));
            }

            $user = User::findOrFail(['id' => $id]);
            Cache::put('user_'.$id, $user,1600);

            return response()->json($user);
        }catch (\Exception $e){
            return response()->json(['error' => 'User not found'], 404);
        }
    }

    public function update($id, Request $request)
    {
        try{
            $user = User::whereId($id);
            $userdata = $request->toArray();

            if(isset($userdata['password'])){
                $userdata['password'] = Hash::make($userdata['password']);
            }

            $user->update($userdata);

            if(Cache::has('user_'.$id)){
                Cache::forget('user_'.$id);
            }
        }catch(\Exception $e){
            return response()->json(['error' => 'Impossible to update user'], 404);
        }

        return response()->json(['status' => 'success','message' => 'User updated'], 201);
    }

    public function destroy($id)
    {
        try{
            $user = User::whereId($id);
            $user->delete();

            if(Cache::has('user_'.$id)){
                Cache::forget('user_'.$id);
            }
        }catch (\Exception $e){
            return response()->json(['error' => 'Impossible to delete user'], 404);
        }

        return response()->json(['status' => 'success','message' => 'User deleted'], 201);
    }
}
