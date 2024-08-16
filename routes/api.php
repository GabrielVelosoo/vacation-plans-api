<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HolidayPlanController;
use App\Http\Controllers\AuthController;

    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function(){

        Route::apiResource('vacation-plans', HolidayPlanController::class);

        Route::get('vacation-plans/{id}/pdf', [HolidayPlanController::class, 'generatePdf']);

        Route::get('/user', [AuthController::class, 'getUser']);

        Route::post('logout', [AuthController::class, 'logout']);

    });