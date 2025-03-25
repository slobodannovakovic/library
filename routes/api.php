<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);

Route::post('register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('users', function (Request $request) {
        return App\Models\User::all();
    });

    Route::post('logout', [AuthController::class, 'logout']);
});