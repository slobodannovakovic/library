<?php

use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BooksController;
use App\Http\Controllers\BooksBorrowController;

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
    Route::post('books', [BooksController::class, 'store'])
        ->can('create', Book::class);

    Route::post('books/borrow', [BooksBorrowController::class, 'borrow']);
    Route::patch('books/return', [BooksBorrowController::class, 'return']);
    Route::get('books/borrow/list', [BooksBorrowController::class, 'list']);
    Route::get('books/borrow/admin-list', [BooksBorrowController::class, 'adminList'])
        ->can('viewAny', Book::class);

    Route::post('admins', [AdminController::class, 'store'])
        ->can('create', User::class);

    Route::post('logout', [AuthController::class, 'logout']);
});