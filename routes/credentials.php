<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordResetController;


Route::post('/registro', [AuthController::class, 'register']);


Route::post('password/email', [PasswordResetController::class, 'sendEmail']);
Route::post('password/reset', [PasswordResetController::class, 'reset']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
