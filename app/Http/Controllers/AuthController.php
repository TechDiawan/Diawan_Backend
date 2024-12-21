<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    
    public function register(Request $request)
    {

        $validatedData = $request->validate([
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|email|unique:users,user_email',
            'user_password' => 'required|string|confirmed|min:8',
        ]);
    
        $user = User::create([
            'user_name' => $validatedData['user_name'],
            'user_email' => $validatedData['user_email'],
            'user_password' => Hash::make($validatedData['user_password']),
        ]);
    
        $token = JWTAuth::fromUser($user);
    
        return response()->json([
            'message' => 'User registered successfully',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    public function login(Request $request)
{
    $request->validate([
        'user_email' => 'required|email',
        'user_password' => 'required',
    ]);

    // Retrieve the user by email
    $user = User::where('user_email', $request->user_email)->first();

    // Validate the password
    if (!$user || !Hash::check($request->user_password, $user->user_password)) {
        return response()->json(['error' => 'Invalid credentials'], 401);
    }

    // Generate a JWT token
    $token = auth()->login($user);

    return response()->json([
        'access_token' => $token,
        'token_type' => 'Bearer',
    ]);
}


    public function getUser()
    {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'User not found'], 404);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Invalid token'], 400);
        }

        return response()->json(compact('user'));
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function me()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            return response()->json($user);
        } catch (JWTException $e) {
            return response()->json(['message' => 'Failed to fetch user'], 500);
        }
    }
}
