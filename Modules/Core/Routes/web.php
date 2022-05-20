<?php

use Illuminate\Support\Facades\Route;
use Modules\Core\Http\Controllers\BaseWebController;
use Spatie\Health\Http\Controllers\HealthCheckResultsController;

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

Route::get('health', [HealthCheckResultsController::class,'__invoke']);
Route::get('web/health', [BaseWebController::class, 'health'])->name('core.base-web.health.get.web');
