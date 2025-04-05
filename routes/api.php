<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NoteController;

Route::post('/register', [AuthController::class, 'register']);

Route::middleware(['tenant'])->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
   
});
Route::middleware('auth:sanctum')->group(function () {
});

