<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;

// Rutas que no requieren autenticación
Route::post('/registro', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Mover todas las rutas que requieren autenticación aquí
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);
    Route::post('/generate-report', [ReportController::class, 'generate']);
});