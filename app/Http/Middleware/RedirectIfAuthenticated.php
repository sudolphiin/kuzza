<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            // If the user is already authenticated and tries to access a guest-only page (like /login),
            // send them through the standard post-login flow so they land on the correct dashboard
            // for their role (admin, teacher, student, parent, etc.).
            return redirect('/after-login');
        }

        return $next($request);
    }
}
