<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NoteController;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Middleware\TenancyMiddleware;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


// foreach (config('tenancy.central_domains') as $domain) {
foreach (
    \App\Models\Tenant::all()->map(function ($tenant) {
        return $tenant->domains->first()->domain;
    })->toArray() as $domain
) {
    Route::domain($domain)->group(function () {
        Route::middleware(
            ['auth:sanctum', TenancyMiddleware::class]

        )->group(function () {

            Route::get('/notes', [NoteController::class, 'index']);
            Route::post('/notes', [NoteController::class, 'store']);
        });
    })
    ->middleware([

        // InitializeTenancyByDomain::class,
        // PreventAccessFromCentralDomains::class,

    ]);
}
