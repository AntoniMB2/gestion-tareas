<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;

Route::group(['middleware' => 'auth:api'], function () {


    Route::get('/tasks/{taskId}/comments', [CommentController::class, 'getCommentsByTask']);
    Route::post('/tasks/{taskId}/comments', [CommentController::class, 'store']);
    Route::put('/comments/{id}', [CommentController::class, 'update']);
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']);
});
