<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\DriverController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\MainController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/company-balance/{id}', [MainController::class, 'companyBalance']);

Route::post('/user-register', [AuthController::class, 'register']);

Route::post('/user-login', [AuthController::class, 'login']);

Route::get('/driver-balance/{id}', [DriverController::class, 'balanceDriver']);

Route::post('/driver-register', [DriverController::class, 'registerDriver'])->middleware('auth:sanctum');

Route::put('/orders/{id}/status', [OrderController::class, 'updateOrderStatus']);

Route::put('/orders-complate/{id}', [OrderController::class, 'completeOrder']);
