<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NoteController;
use App\Models\User;
use App\Models\Note;
use App\Http\Middleware\TenancyMiddleware;
use Mockery\Matcher\Not;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
// function()  { // Pass $domain to the closure
//     foreach (config('tenancy.central_domains') as $domain) {
        
//     }
// };
Route::middleware(['auth:sanctum',TenancyMiddleware::class])->group(function () {
    
    Route::get('/notes',[NoteController::class, 'index']);
});





