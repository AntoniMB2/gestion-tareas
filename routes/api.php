<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\TaskController;

// Rutas que no requieren autenticación
Route::post('/registro', [AuthController::class, 'register']);
// En routes/api.php
Route::post('password/email', [PasswordResetController::class, 'sendEmail']);
Route::post('password/reset', [PasswordResetController::class, 'reset']);
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::group(['middleware' => 'auth:api'], function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Rutas de usuarios
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);


    // Rutas de tareas
    Route::post('/tasks', [TaskController::class, 'store']);

    // Rutas de reportes
    Route::post('/generate-report', [ReportController::class, 'generate']);
});
