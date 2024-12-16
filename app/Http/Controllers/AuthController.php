<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


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
        'user_password' => bcrypt($validatedData['user_password']),
    ]);

    return response()->json(['message' => 'User registered successfully'], 201);
}


    public function login(Request $request)
    {
        $request->validate([
            'user_email' => 'required|string|user_email',
            'user_password' => 'required|string',
        ]);

        $user = User::where('user_email', $request->user_email)->first();

        if (!$user || !Hash::check($request->user_password, $user->user_password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}

