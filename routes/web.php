<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get(uri: '', action: 'UserController@index');

Route::get(uri: 'login', action: 'LoginController@index');

Route::get(uri:'login/auth', action:'LoginController@auth');

Route::get(uri: '/{id}', action: 'UserController@index');