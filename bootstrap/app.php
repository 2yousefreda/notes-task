<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;



return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then:function()  { // Pass $domain to the closure
            foreach (config('tenancy.central_domains') as $domain) {
                
                Route::middleware('web')
                    ->domain($domain)
                    
                    ->group(base_path('routes/web.php'));
            }
        }
    )
    
    ->withMiddleware(function (Middleware $middleware) {
        
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
