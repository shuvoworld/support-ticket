<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Only allow super admins and admins to access the admin panel
        if (!$user || !$user->hasRole(['super_admin', 'admin'])) {
            // If user is an agent, redirect to tickets page
            if ($user && $user->hasRole(['agent'])) {
                return redirect()->route('tickets.index');
            }

            // For all other users, abort with 403
            abort(403, 'Access denied. Admin privileges required.');
        }

        return $next($request);
    }
}
