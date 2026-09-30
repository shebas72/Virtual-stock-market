<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless($request->user()?->is_active, 403, 'Your account is suspended. Contact your workspace owner.');

        $tenant = $request->user()?->tenant;
        abort_unless($tenant?->is_active, 403, 'This workspace is suspended. Contact support for assistance.');
        if (! $tenant->hasValidSubscription()) {
            return redirect()->route('subscription.show')->with('error', 'Your workspace subscription has expired. Choose a plan to restore access.');
        }

        return $next($request);
    }
}
