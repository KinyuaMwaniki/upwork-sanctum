<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request) {
        // User::truncate();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return response()->json([
                $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;
        
        return response()->json([
            'user' => $user,
            'token' => $token
        ],201);
    }

    public function login(Request $request) {
        $rules = [
            'email' => ['required'],
            'password' => ['required'],
        ];
        
        $validator = Validator::make($request->all(), $rules);
        
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
        
        $userdata = array(
            'email'     => $request->email, 
            'password'  => $request->password 
        );
        
        
        if (Auth::attempt($userdata)) { 
            $token = Auth::user()->createToken('myapptoken')->plainTextToken;
    
            return response()->json([
                'token' => $token,
            ],201);
        } 
        
        return response()->json([
            'message' => 'Invalid credentitals'
        ],401);           
    }

    public function getUserInfo(Request $request) {
        return response()->json([
            'user' => $request->user()
        ]);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();;
        return response()->json('Logged out successfully', 200);
    }
}
