<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\SymptomController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\HealthAdviceController;

use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::get('/doctors/search', [DoctorController::class, 'search']);
    Route::get('/doctors', [DoctorController::class, 'index']);
    Route::get('/doctors/{doctor}', [DoctorController::class, 'show']);

    Route::apiResource('symptoms', SymptomController::class);
    Route::apiResource('appointments', AppointmentController::class);

    Route::post('/ai/health-advice', [HealthAdviceController::class, 'generate']);
    Route::get('/ai/health-advice', [HealthAdviceController::class, 'index']);
});
