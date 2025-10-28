<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsControllers;
use App\Http\Controllers\ComentsController;
use App\Models\User;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Careercontroller;


Route::post('/news/{id}/comments', [ComentsController::class, 'addComment']);
Route::get('/news/{id}/comments', [ComentsController::class, 'show']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/auth/logout', [AuthController::class, 'logout']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/news', [NewsControllers::class, 'store']);
    Route::put('/news/{id}', [NewsControllers::class, 'update']);
    Route::delete('/news/{id}', [NewsControllers::class, 'destroy']);
    Route::post ('/career', [Careercontroller::class, 'store']);
    Route::put ('/career/{id}', [Careercontroller::class, 'update']);
    Route::delete('/career/{id}', [Careercontroller::class, 'destroy']);
    
});


