<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BooksBorrowController;
use App\Http\Controllers\BooksController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('reset-password', [AuthController::class, 'resetPassword'])
    ->middleware('signed')
    ->name('password.reset');

Route::get('books', [BooksController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('users', function (Request $request) {
        return App\Models\User::all();
    });

    Route::get('books/{book}', [BooksController::class, 'show']);
    Route::post('books/borrow', [BooksBorrowController::class, 'borrow']);
    Route::patch('books/return', [BooksBorrowController::class, 'return']);
    Route::get('books/borrow/list', [BooksBorrowController::class, 'list']);

    Route::post('logout', [AuthController::class, 'logout']);
});