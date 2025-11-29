<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRoomOwner
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->role === 'guru' && $user->room_id) {
            return $next($request);
        }

        abort(403, 'Hanya guru penanggung jawab ruangan yang dapat mengakses.');
    }
}
