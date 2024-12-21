<?php

// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\AuthController;
// use App\Http\Middleware\JwtMiddleware;

// // Route::post('/register', [AuthController::class, 'register']);
// // Route::post('/login', [AuthController::class, 'login']);

// Route::post('/register', [AuthController::class, 'register']);
// Route::post('/login', [AuthController::class, 'login']);

// Route::middleware([JwtMiddleware::class])->group(function () {
//     Route::get('user', [AuthController::class, 'getUser']);
//     Route::post('logout', [AuthController::class, 'logout']);
// });

// // Route::middleware('auth:api')->group(function () {
// //     Route::post('/logout', [AuthController::class, 'logout']);
// //     Route::get('/me', [AuthController::class, 'me']);
// // });

// Route::get('/test', function () {
//     return response()->json(['message' => 'API is working'], 200);
// });

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('/register', [AuthController::class, 'register']); // Public
Route::post('/login', [AuthController::class, 'login']);       // Public

Route::middleware([App\Http\Middleware\JwtMiddleware::class])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']); // Protected
    Route::get('/user', [AuthController::class, 'getUser']);  // Protected
});

