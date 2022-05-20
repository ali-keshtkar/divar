<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Core\Http\Controllers\Api\BaseApiController;

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

Route::get('check-version', [BaseApiController::class, 'checkVersion'])->name('core.base-api.check-version.get.api');
Route::get('health', [BaseApiController::class, 'health'])->name('core.base-api.health.get.api');
