<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Accepts one or more roles: role:admin or role:admin,guru
     *
     * @param  mixed  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $userRole = Auth::user()->role;

        if (empty($roles) || in_array($userRole, $roles)) {
            return $next($request);
        }

        // Unauthorized for this role
        return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
    }
}
