<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
  Route::get('posts', [PostController::class, 'index']);
  Route::post('posts', [PostController::class, 'store']);
  Route::get('posts/{id}', [PostController::class, 'show']);
});
