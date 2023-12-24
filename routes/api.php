<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    Route::group(['middleware' => 'auth:sanctum'], function() { 
       
      Route::get('logout', [AuthController::class, 'logout']);
      Route::get('user', [AuthController::class, 'user']);
    });
});

// Requires authorization
Route::group(['middleware' => 'auth:sanctum'], function() {
  Route::get('user/getposts', [PostsController::class, 'retrieve']);
  Route::post('user/savepost', [PostsController::class, 'save']);
  Route::post('user/deletepost', [PostsController::class, 'delete']);
  Route::post('user/updatepost', [PostsController::class, 'update']);
});


Route::post('posts/filter', [PostsController::class, 'filter']);