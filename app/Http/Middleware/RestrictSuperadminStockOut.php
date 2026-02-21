<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RestrictSuperadminStockOut
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Allow superadmin to only view stock outs (index and show)
        if (Auth::check() && Auth::user()->role === 'Superadmin') {
            $action = $request->route()?->getActionMethod();
            
            // Only allow 'index' and 'show' methods for superadmin
            if (!in_array($action, ['index', 'show'])) {
                abort(403, 'Superadmin tidak dapat mengelola stock out. Hanya dapat memantau.');
            }
        }

        return $next($request);
    }
}
