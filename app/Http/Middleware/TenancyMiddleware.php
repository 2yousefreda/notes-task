<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Stancl\Tenancy\Tenancy;
use App\Models\Tenant;

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
        $tenant = Tenant::find($request->user()->tenant_id);
        // 1. get current user
        // 2. get current tenant
        // 3. check both of them if equal each other
        if (! $request->user()) {
            abort(401, 'Unauthenticated');
        }

        if (! $tenant) {
            abort(404, 'Tenant not found');
        }

        if (explode(":", $_SERVER['HTTP_HOST'])[0] != $tenant->domains->first()->domain) {
            abort(401, 'Unauthenticated');
        }

        $this->tenancy->initialize($tenant);

        return $next($request);
    }
}
