<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;





include('credentials.php');

Route::group(['middleware' => 'auth:api'], function () {
    include('attachments.php');
    include('comments.php');
    include('tasks.php');
    include('users.php');

    Route::post('/generate-report', [ReportController::class, 'generate'])->name('report.generate');

});
