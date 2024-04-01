<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;


Route::group(['middleware' => 'auth:api'], function () {

    Route::get('/tasks', [TaskController::class, 'index']);
    Route::get('/tasks/{id}', [TaskController::class, 'show']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::put('/tasks/{id}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);

});
