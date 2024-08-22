<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        
        $validator = Validator::make($request->all(),[
            'email'     => 'required|string|max:255',
            'password'  => 'required|string'
        ]);
        $credentials    =   $request->only('email', 'password');
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        if (! Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'User not found'
            ], 401);
        }
        $user= User::where('email', $request->email)->firstOrFail();
        $token  = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'       => 'Login success',
            'access_token'  => $token,
            'token_type'    => 'Bearer'
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|max:255|unique:users',
            'password'  => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password)
        ]);
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'data'          => $user,
            'access_token'  => $token,
            'token_type'    => 'Bearer'
        ]);
    }
    public function logout()
    {
        Auth::user()->tokens()->delete();
        return response()->json([
            'message'=>'Logout successful'
        ]);
    }
}
