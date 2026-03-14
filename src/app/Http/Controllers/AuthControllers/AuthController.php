<?php

namespace App\Http\Controllers\AuthControllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        try {
            $token = JWTAuth::fromUser($user);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'],500);
        }

        return response()->json([
            'token' => $token,
            'user' => $user,
        ],201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try{
            if(!$token = JWTAuth::attempt($credentials)){
               return response()->json(['error' => 'Invalid credentials'], 401);
            }
        }catch (JWTException $e){
            return response()->json(['error' => 'Could not create token'],500);
        }

        return response()->json([
            'token' => $token,
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ]);
    }

    public function loginView()
    {
        return view('login');
    }

    public function registerView()
    {
        return view('register');
    }

    public function logout(Request $request)
    {
        try{
            JWTAuth::invalidate(JWTAuth::getTocken());
        }catch (JWTException $e){
            return response()->json(['error' => 'Failed to logout']);
        }
    }
    /* Move below to UserController */
    public function getUser()
    {
        try{
            $user = auth('api')->user();
            if(!$user){
                return response()->json(['error' => 'User not found'],404);
            }
            return response()->json($user);
        }catch (JWTException $e){
            return response()->json(['error' => 'Failed to fetch user profile'],500);
        }
    }

    public function updateUser(Request $request)
    {
        try{
            $user = auth('api')->user();
            $user->update($request->only(['username', 'email']));
            return response()->json($user);
        }catch (JWTException $e){
            return response()->json(['error' => 'Failed to update user'],500);
        }
    }
}
