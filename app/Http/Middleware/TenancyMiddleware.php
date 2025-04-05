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
        protected Tenancy $Tenancy
    ) {}

    public function handle(Request $Request, Closure $Next): Response
    {
        $Tenant = Tenant::find($Request->user()->tenant_id);
        if (! $Request->user()) {
            abort(401, 'Unauthenticated');
        }

        if (! $Tenant) {
            abort(404, 'Tenant not found');
        }

        if (explode(":", $_SERVER['HTTP_HOST'])[0] != $Tenant->domains->first()->domain) {
            abort(401, 'Unauthenticated');
        }

        $this->Tenancy->initialize($Tenant);

        return $Next($Request);
    }
}
