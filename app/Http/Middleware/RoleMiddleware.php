<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!in_array(auth()->user()->role, $roles)) {
            // Redirect ke portal yang sesuai
            return match(auth()->user()->role) {
                'siswa' => redirect()->route('siswa.dashboard'),
                'coach' => redirect()->route('coach.dashboard'),
                default => redirect()->route('admin.dashboard'),
            };
        }

        return $next($request);
    }
}
