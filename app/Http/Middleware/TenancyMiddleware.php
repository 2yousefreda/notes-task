<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Stancl\Tenancy\Tenancy;
class TenancyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function __construct(
        protected Tenancy $tenancy
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            abort(401, 'Unauthenticated');
        }

        if (! $tenant = $request->user()->tenant) {
            abort(404, 'Tenant not found');
        }

        $this->tenancy->initialize($tenant);

        return $next($request);
    }
}
