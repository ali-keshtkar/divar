<?php

use Illuminate\Support\Facades\Route;
use Modules\Otp\Http\Controllers\AuthenticateController;

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

Route::get('login',[AuthenticateController::class,'loginPage'])
    ->name('otp.authenticate.page.login.get.web');

Route::post('login', [AuthenticateController::class, 'requestOtp'])
    ->name('otp.authenticate.request-otp.post.web');

Route::get('confirm',[AuthenticateController::class,'confirmPage'])
    ->name('otp.authenticate.page.confirm.get.web');

Route::post('confirm',[AuthenticateController::class,'confirmOtp'])
    ->name('otp.authenticate.confirm-otp.post.web');
