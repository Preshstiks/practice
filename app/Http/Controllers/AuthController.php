<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {
    public function register(Request $request){
        try {
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6'
            ]);
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'status' => 'success',
                'message' => 'User Created Successfully',
                'user' => $user,
                'token' => $token
            ], 201);
        } catch (Exception $e) {
           return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
           ], 422);
        }
    }
    public function login(Request $request){
       try {
         $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        $user = User::where('email', $request->email)->first();
        if(!$user || !Hash::check($request->password, $user->password)){
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid Credentials'
            ], 401);
        }
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'status' => 'success',
            'message' => 'Login Successful!',
            'user' => $user,
            'token' => $token
        ]);
       } catch (Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
           ], 422);
       }
    }
}
