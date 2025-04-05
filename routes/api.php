<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NoteController;
use App\Models\User;
use App\Models\Note;
use App\Http\Middleware\TenancyMiddleware as Tenancy;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);



Route::middleware(['auth:sanctum', Tenancy::class])->group(function () {
    Route::get('/notes', function(){
        return Note::all();
    });

});





