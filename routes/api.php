<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SessionRequestFormController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
Route::get('/me', [AuthController::class, 'me'])->middleware('auth:api');
Route::put('/user/update', [AuthController::class, 'update'])->middleware('auth:api');
Route::delete('/admin/student/{id}', [AuthController::class, 'deleteStudent'])->middleware('auth:api');


Route::group(['middleware' => ['auth:api', 'student']], function () {
    Route::get('session-requests/student', [SessionRequestFormController::class, 'index']);
    Route::apiResource('session-requests', SessionRequestFormController::class)->except(['approve', 'reject']);
});


Route::group(['middleware' => ['auth:api', 'administrator']], function () {
    Route::get('session-requests', [SessionRequestFormController::class, 'index']);
    Route::post('session-requests/{id}/approve', [SessionRequestFormController::class, 'approve']);
    Route::post('session-requests/{id}/reject', [SessionRequestFormController::class, 'reject']);
});


 Route::apiResource('sessions', SessionsController::class)->middleware('auth:api');

 Route::post('reports', [ReportController::class, 'store']);

 Route::middleware('auth:api')->group(function () {
    Route::apiResource('reports', ReportController::class)->only(['index', 'show']);
 });

 /*
 Route::middleware('auth:api')->group(function () {
    Route::post('/reports', [ReportController::class, 'store']); // Create a report
    Route::get('/reports', [ReportController::class, 'index']);  // Get all reports
    Route::get('/reports/{id}', [ReportController::class, 'show']); // Get a specific report
});
*/
