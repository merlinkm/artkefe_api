<?php

use App\Http\Controllers\Api\Admin\AdminController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\Seller\SellerController;
use App\Http\Controllers\Api\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [LoginController::class, 'login']);
Route::post('user/register', [RegisterController::class, 'userRegister']);
Route::post('seller/register', [RegisterController::class, 'sellerRegister']);

Route::post("/seller/email_verification",[RegisterController::class, 'sellerEmailVerify'])->name('sellerEmailVerify');

Route::post('/logout', [LoginController::class, 'logout']);
Route::post('/refresh', [LoginController::class, 'refresh']);
Route::get('/getAllRoles', [HomeController::class, 'getAllRoles']); 


// Admin
Route::group(['middleware' => ['jwt.role:1'], 'prefix' => 'admin'], function ($router) {
    Route::get('/dashboard', [AdminController::class, 'dashboard']);

    Route::resource('user_management', AdminController::class)->except(['update', 'destroy']);
    Route::post("user_management/{id}/", [AdminController::class, 'updated'])->name('user_management.updated');
    Route::get("user_management/{id}/", [AdminController::class, 'destroy']);
    Route::post("user_management/statusUpdate/", [AdminController::class, 'statusUpdate']);
});

// client or seller
Route::group(['middleware' => ['jwt.role:2'], 'prefix' => 'seller'], function ($router) {
    Route::get('/dashboard', [SellerController::class, 'dashboard']);
});

// user
Route::group(['middleware' => ['jwt.role:3'], 'prefix' => 'user'], function ($router) {
    Route::get('/dashboard', [UserController::class, 'dashboard']);
});
