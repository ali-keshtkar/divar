<?php

use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Modules\Otp\Http\Controllers\Api\V1\ApiAuthenticateController;

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

Route::prefix('v1')->group(function (Router $router){
    $router->post('login', [ApiAuthenticateController::class, 'requestOtp'])
        ->name('otp.api-authenticate.request-otp.post.api');
    $router->post('confirm', [ApiAuthenticateController::class, 'confirmOtp'])
        ->name('otp.api-authenticate.confirm-otp.post.api');
});
