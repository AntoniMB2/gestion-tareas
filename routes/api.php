<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AttachmentController;

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
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::get('/tasks/{id}', [TaskController::class, 'show']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::put('/tasks/{id}', [TaskController::class, 'update']);
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);

    // Rutas de comentarios
    Route::get('/tasks/{taskId}/comments', [CommentController::class, 'getCommentsByTask']);
    Route::post('/tasks/{taskId}/comments', [CommentController::class, 'store']);
    Route::put('/comments/{id}', [CommentController::class, 'update']);
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']);
    Route::post('/generate-report', [ReportController::class, 'generate']);

    // Rutas de archivos adjuntos
    Route::get('/attachments', [AttachmentController::class, 'index']);
    Route::get('/attachments/{id}', [AttachmentController::class, 'show']);
    Route::post('/attachments', [AttachmentController::class, 'store']);
    Route::delete('/attachments/{id}', [AttachmentController::class, 'destroy']);

    // ruta de reportes
    Route::post('/generate-report', [ReportController::class, 'generate']);

});
