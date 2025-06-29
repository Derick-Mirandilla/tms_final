<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect('/login'); // Or throw an UnauthorizedException
        }

        $user = Auth::user();

        // Check if the user has any of the required roles
        foreach ($roles as $role) {
            if ($user->hasRole($role)) { // Assuming you have a hasRole method on your User model
                return $next($request);
            }
        }

        // If user doesn't have any of the required roles
        // You can redirect, abort, or show an error
        abort(403, 'Unauthorized action.'); // Or redirect('/dashboard') with an error message
    }
}