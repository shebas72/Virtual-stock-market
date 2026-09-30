<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TenantOwnerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless($request->user()?->isTenantOwner(), 403, 'Workspace owner access required.');

        return $next($request);
    }
}
