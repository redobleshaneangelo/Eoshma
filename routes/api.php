<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PrebiddingController;
use App\Http\Controllers\MobilizationController;
use App\Http\Controllers\RecruitmentController;

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Public Resource Routes
|--------------------------------------------------------------------------
*/
Route::get('/clients', [ClientController::class, 'index']); 
Route::post('/clients', [ClientController::class, 'store']); 
Route::get('/clients/{client}', [ClientController::class, 'show']);

Route::post('/users', [UserController::class, 'store']);

Route::get('/prebiddings', [PrebiddingController::class, 'index']); 
Route::post('/prebiddings', [PrebiddingController::class, 'store']);

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
