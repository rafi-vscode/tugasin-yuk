<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AnnouncementController;



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
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile-update', [ProfileController::class, 'update']);
    Route::post('/classes', [ClassController::class, 'create']);
    Route::get('/classes', [ClassController::class, 'index']);
    Route::post('/classes/join', [ClassController::class, 'join']);
    Route::post('/assignments', [AssignmentController::class, 'create']);
    Route::get('/assignments/{classroomId}', [AssignmentController::class, 'index']);
    Route::post('/assignments', [AssignmentController::class, 'store']);
    Route::post('/assignments/{assignmentId}/status', [AssignmentController::class, 'markStatus']);
    Route::post('/announcements', [AnnouncementController::class, 'store']);
    Route::get('/announcements/{classroomId}', [AnnouncementController::class, 'index']);
    Route::get('/assignments/status/{classroomId}', [AssignmentController::class, 'statusByUser']);
    Route::delete('/announcements/{id}', [AnnouncementController::class, 'destroy']);
    Route::delete('/assignments/{id}', [AssignmentController::class, 'destroy']);


});
