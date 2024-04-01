<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttachmentController;

    Route::get('/attachments', [AttachmentController::class, 'index']);
    Route::get('/attachments/{id}', [AttachmentController::class, 'show']);
    Route::post('/attachments', [AttachmentController::class, 'store']);
    Route::delete('/attachments/{id}', [AttachmentController::class, 'destroy']);

