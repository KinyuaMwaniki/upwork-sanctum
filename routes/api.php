<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;

Route::group(['prefix' => '/v1', 'middleware' => ['auth:sanctum']] , function() {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user/info', [AuthController::class, 'getUserInfo']);
    Route::get('users', function() {
        $users = User::all();
        return response()->json([
            'users' => $users
        ], 200);
    });
});

Route::group(['prefix' => '/v1'], function() {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});
