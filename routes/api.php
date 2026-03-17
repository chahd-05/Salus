<?php

use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\SymptomController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function(){
Route::apiResource('symptoms', SymptomController::class);
Route::apiResource('appointments', AppointmentController::class);
});

Route::get('/doctors', [DoctorController::class, 'index']);
Route::get('/doctors/{id}', [DoctorController::class, 'show']);
Route::get('/doctors/search', [DoctorController::class, 'search']);