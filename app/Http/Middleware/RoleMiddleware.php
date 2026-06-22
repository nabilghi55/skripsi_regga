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
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->role !== $role) {
            if ($user->role === 'pengurus') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'khatib') {
                return redirect()->route('khatib.dashboard');
            } elseif ($user->role === 'takmir') {
                return redirect()->route('takmir.dashboard');
            }
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
