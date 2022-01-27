<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::view('/','components.auth.login')->name('home');

// User pages
Route::group(['prefix' => 'user', 'middleware' => 'auth'], function () {
    Route::get('mypage', [UserController::class, 'index'])->name('user.index');
    Route::post('update', [UserController::class, 'update'])->name('user.update');
});

// Auth
Route::post('login', [AuthController::class, 'login'])->name('auth.login');
Route::get('registration', [AuthController::class, 'registration'])->name('auth.registerForm');
Route::post('register', [AuthController::class, 'register'])->name('auth.register');
Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');
